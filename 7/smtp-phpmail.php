<?php
/*
Plugin Name: SMTP mailer Sender - by Mzero
Description: Envia e-mails via SMTP usando mailer, com boas práticas.
Version: 1.0
Author: Mzero
*/

if (!defined('ABSPATH')) exit;

require_once plugin_dir_path(__FILE__) . 'includes/class-smtp-mailer.php';

function smtp_mailer_init() {
    $plugin = new SMTP_mailer();
    $plugin->init();
}
add_action('plugins_loaded', 'smtp_mailer_init');


function smtp_mailer_mzero_deactivate() {
    delete_option('smtp_mailer_mzero_version');
}
register_deactivation_hook(__FILE__, 'smtp_mailer_mzero_deactivate');


function smtp_mailer_mzero_uninstall() {
    delete_option('smtp_mailer_mzero_version');
}
register_uninstall_hook(__FILE__, 'smtp_mailer_mzero_uninstall');


function smtp_mailer_mzero_update() {
    $current_version = get_option('smtp_mailer_mzero_version');
    if ($current_version !== '1.0.0') {
        update_option('smtp_mailer_mzero_version', '1.0.0');
    }
}
add_action('plugins_loaded', 'smtp_mailer_mzero_update');


register_activation_hook(__FILE__, 'smtp_mailer_mzero_activate');

function smtp_mailer_mzero_activate() {
    if (!defined('SMTP_MAILER_ENCRYPTION_KEY')) {
        $key = bin2hex(random_bytes(32)); // Gera uma chave de 256 bits (32 bytes)

        $wp_config_path = ABSPATH . 'wp-config.php';

        // Verifica se o arquivo wp-config.php é gravável
        if (is_writable($wp_config_path)) {
            // Adiciona a constante ao wp-config.php
            $config_content = file_get_contents($wp_config_path);
            $key_definition = "\ndefine('SMTP_MAILER_ENCRYPTION_KEY', '$key');\n";

            // Insere a chave antes da linha "/* That's all, stop editing! */"
            if (strpos($config_content, "/* That's all, stop editing! */") !== false) {
                $config_content = str_replace(
                    "/* That's all, stop editing! */",
                    $key_definition . "/* That's all, stop editing! */",
                    $config_content
                );

                // Salva o arquivo atualizado
                file_put_contents($wp_config_path, $config_content);

                // Força o recarregamento do WordPress
                wp_die(__('O plugin foi ativado e a chave de criptografia foi adicionada ao wp-config.php. Por favor, recarregue esta página.'), 'Plugin ativado', ['response' => 200]);
            }
        } else {
            // Lança um aviso se o arquivo wp-config.php não for gravável
            wp_die(__('O arquivo wp-config.php não é gravável. Por favor, defina manualmente a constante SMTP_MAILER_ENCRYPTION_KEY.'));
        }
    }

    // Atualiza a versão do plugin no banco de dados
    update_option('smtp_mailer_mzero_version', '1.0.0');
}