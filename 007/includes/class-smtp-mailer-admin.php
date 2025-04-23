<?php

class SMTP_mailer_Admin {
    public function init() {
        add_action('admin_menu', [$this, 'add_menu']);
        add_action('admin_init', [$this, 'register_settings']);
        add_action('admin_post_smtp_mailer_test_email', [$this, 'handle_test_email']);
    }

    public function add_menu() {
        add_menu_page('SMTP mailer', 'SMTP mailer', 'manage_options', 'smtp-mailer', [$this, 'settings_page']);
        add_submenu_page('smtp-mailer', 'Teste de E-mail', 'Teste de E-mail', 'manage_options', 'smtp-mailer-test', [$this, 'test_email_page']);
    }

    public function register_settings() {
        register_setting('smtp_mailer_options', 'smtp_mailer_settings', [
            'sanitize_callback' => function ($input) {
                if (isset($input['password'])) {
                    // Criptografa a senha antes de salvar
                    $input['password'] = openssl_encrypt(
                        $input['password'],
                        'AES-256-CBC',
                        SMTP_MAILER_ENCRYPTION_KEY,
                        0,
                        substr(SMTP_MAILER_ENCRYPTION_KEY, 0, 16) // IV (16 bytes)
                    );
                }
                return $input;
            }
        ]);
    
        add_settings_section('smtp_mailer_section', 'Configurações SMTP', null, 'smtp-mailer');
    
        $fields = [
            'host' => 'Host SMTP',
            'port' => 'Porta SMTP',
            'encryption' => 'Criptografia',
            'username' => 'Usuário mailer',
            'password' => 'Senha mailer (não é exibido depois de salvar)',
            'from_email' => 'E-mail Remetente',
            'from_name' => 'Nome do Remetente'
        ];
    
        foreach ($fields as $id => $label) {
            add_settings_field(
                $id,
                $label,
                function () use ($id) {
                    $options = get_option('smtp_mailer_settings');
                    $value = $options[$id] ?? '';
                    if ($id === 'password' && !empty($value)) {
                        // Exibe um campo vazio para a senha por segurança
                        $value = '';
                    }
                    $type = $id === 'password' ? 'password' : 'text';
                    echo "<input type='$type' name='smtp_mailer_settings[$id]' value='" . esc_attr($value) . "' style='width: 300px;'>";
                },
                'smtp-mailer',
                'smtp_mailer_section'
            );
        }
    }

    public function settings_page() {
        ?>
        <div class="wrap">
            <h1>Configurações SMTP (mailer)</h1>
            <form method="post" action="options.php">
                <?php
                settings_fields('smtp_mailer_options');
                do_settings_sections('smtp-mailer');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    public function test_email_page() {
        ?>
        <div class="wrap">
            <h1>Teste de E-mail</h1>
            <?php
                if (isset($_GET['message'])) {
                    if ($_GET['message'] === 'success') {
                        echo '<div class="notice notice-success"><p>E-mail enviado com sucesso!</p></div>';
                    } elseif ($_GET['message'] === 'error') {
                        echo '<div class="notice notice-error"><p>Falha ao enviar o e-mail.</p></div>';
                    }
                }
            ?>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="smtp_mailer_test_email">
                <table class="form-table">
                    <tr>
                        <th scope="row"><label for="test_email">E-mail de Destino</label></th>
                        <td>
                            <input type="email" name="test_email" id="test_email" required style="width: 300px;">
                        </td>
                    </tr>
                </table>
                <?php submit_button('Enviar E-mail de Teste'); ?>
            </form>
        </div>
        <?php
    }

    public function handle_test_email() {
        // Inclui os arquivos do PHPMailer manualmente
        require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
        require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
        require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';

        // Verifica permissões
        if (!current_user_can('manage_options')) {
            wp_die(__('Você não tem permissão para acessar esta página.'));
        }

        // Valida o e-mail de teste
        if (!isset($_POST['test_email']) || !is_email($_POST['test_email'])) {
            wp_die(__('E-mail inválido.'));
        }

        $test_email = sanitize_email($_POST['test_email']);
        $options = get_option('smtp_mailer_settings');

        $encrypted_password = $options['password'] ?? '';
        $password = openssl_decrypt(
            $encrypted_password,
            'AES-256-CBC',
            SMTP_MAILER_ENCRYPTION_KEY,
            0,
            substr(SMTP_MAILER_ENCRYPTION_KEY, 0, 16) // IV (16 bytes)
        );

        // Configurações do PHPMailer
        $phpmailer = new \PHPMailer\PHPMailer\PHPMailer(true);

        try {
            $phpmailer->isSMTP();
            $phpmailer->CharSet = 'UTF-8';
            $phpmailer->Host       = $options['host'] ?? 'smtp.example.com';
            $phpmailer->SMTPAuth   = true;
            $phpmailer->Username   = $options['username'] ?? '';
            $phpmailer->Password   = $password;
            $phpmailer->SMTPSecure = $options['encryption'] ?? 'tls'; // tls ou ssl
            $phpmailer->Port       = $options['port'] ?? 587;
            $phpmailer->SMTPDebug  = 2; // Habilita o debug SMTP (0 = desativado, 1 = erros e mensagens, 2 = mensagens detalhadas)
            $phpmailer->Debugoutput = function($str, $level) {
                error_log("SMTP Debug [$level]: $str");
            };




            // Configurações do remetente
            $phpmailer->setFrom($options['from_email'] ?? 'no-reply@example.com', $options['from_name'] ?? 'SMTP Mailer');
            $phpmailer->addAddress($test_email); // Destinatário

            // Conteúdo do e-mail
            $phpmailer->isHTML(true);
            $phpmailer->Subject = 'Teste de E-mail SMTP';
            $phpmailer->Body    = '<p>Este é um e-mail de teste enviado pelo plugin SMTP Mailer.</p>';
            $phpmailer->AltBody = 'Este é um e-mail de teste enviado pelo plugin SMTP Mailer.';

            // Enviar o e-mail
            $phpmailer->send();

            // Redirecionar com mensagem de sucesso
            wp_redirect(add_query_arg('message', 'success', wp_get_referer()));
            exit; // Encerra o script após o redirecionamento
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            // Registrar erro no log do WordPress
            error_log('Erro ao enviar e-mail: ' . $e->getMessage());

            // Redirecionar com mensagem de erro
            wp_redirect(add_query_arg('message', 'error', wp_get_referer()));
            exit; // Encerra o script após o redirecionamento
        }
    }

}
