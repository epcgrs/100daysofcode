<?php

class SMTP_mailer {
    public function init() {
        require_once plugin_dir_path(__FILE__) . 'class-smtp-mailer-admin.php';
        require_once plugin_dir_path(__FILE__) . 'class-smtp-mailer-sender.php';
    
        if (is_admin()) {
            $admin = new SMTP_mailer_Admin();
            $admin->init();
        }
    
        $sender = new SMTP_mailer_Sender();
        add_action('phpmailer_init', [$sender, 'configure_mailer']);
        add_filter('wp_mail', [$sender, 'intercept_wp_mail']); // Intercepta o wp_mail
    }
}

