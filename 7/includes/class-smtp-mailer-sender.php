<?php

require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';

class SMTP_mailer_Sender {
    public function configure_mailer($phpmailer) {
        $options = get_option('smtp_mailer_settings');

        $phpmailer->isSMTP();
        $phpmailer->Host       = $options['host'] ?? 'smtp.example.com';
        $phpmailer->SMTPAuth   = true;
        $phpmailer->Port       = $options['port'] ?? 587;
        $phpmailer->SMTPAutoTLS = false;
        $phpmailer->SMTPDebug  = 2; // Set to 0 for production, 2 for debugging
        $phpmailer->Username   = $options['username'] ?? '';
        $phpmailer->Password   = $options['password'] ?? '';
        $phpmailer->SMTPSecure = $options['encryption'] ?? 'tls';
        $phpmailer->From       = $options['from_email'] ?? 'you@example.com';
        $phpmailer->FromName   = $options['from_name'] ?? 'Mailer';
        
    }

    public function intercept_wp_mail($args) {
        // Inclui os arquivos do PHPMailer manualmente
        require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';
        require_once ABSPATH . WPINC . '/PHPMailer/SMTP.php';
        require_once ABSPATH . WPINC . '/PHPMailer/Exception.php';
    
        $options = get_option('smtp_mailer_settings');
    
        // Configurações do PHPMailer
        $phpmailer = new \PHPMailer\PHPMailer\PHPMailer(true);

        $encrypted_password = $options['password'] ?? '';
        $password = openssl_decrypt(
            $encrypted_password,
            'AES-256-CBC',
            SMTP_MAILER_ENCRYPTION_KEY,
            0,
            substr(SMTP_MAILER_ENCRYPTION_KEY, 0, 16) // IV (16 bytes)
        );

    
        try {
            $phpmailer->isSMTP();
            $phpmailer->CharSet = 'UTF-8';
            $phpmailer->Host       = $options['host'] ?? 'smtp.example.com';
            $phpmailer->SMTPAuth   = true;
            $phpmailer->Username   = $options['username'] ?? '';
            $phpmailer->Password   = $password;
            $phpmailer->SMTPSecure = $options['encryption'] ?? 'tls'; // tls ou ssl
            $phpmailer->Port       = $options['port'] ?? 587;
    

            $phpmailer->setFrom($options['from_email'] ?? 'no-reply@example.com', $options['from_name'] ?? 'SMTP Mailer');
    
            if (!empty($args['to'])) {
                foreach ((array) $args['to'] as $recipient) {
                    $phpmailer->addAddress($recipient);
                }
            }
    
            $phpmailer->isHTML(true);
            $phpmailer->Subject = $args['subject'] ?? '(Sem Assunto)';
            $phpmailer->Body    = $args['message'] ?? '';
            $phpmailer->AltBody = strip_tags($args['message'] ?? '');
    
            if (!empty($args['headers'])) {
                foreach ((array) $args['headers'] as $header) {
                    $phpmailer->addCustomHeader($header);
                }
            }
    
            if (!empty($args['attachments'])) {
                foreach ((array) $args['attachments'] as $attachment) {
                    $phpmailer->addAttachment($attachment);
                }
            }
    
            $phpmailer->send();
    
            return true;
        } catch (\PHPMailer\PHPMailer\Exception $e) {
            error_log('Erro ao enviar e-mail: ' . $e->getMessage());
    
            return false;
        }
    }
}
