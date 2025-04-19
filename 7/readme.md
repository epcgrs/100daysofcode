# WP SMTP Mailer by Mzero

[English Version](#wp-smtp-mailer-by-mzero-1)

WP SMTP Mailer by Mzero é um plugin para WordPress que permite configurar o envio de e-mails através de um servidor SMTP.

## Funcionalidades

- Configuração de servidor SMTP para envio de e-mails.
- Suporte a autenticação SMTP com usuário e senha.
- Opções de criptografia (SSL/TLS).
- Teste de envio de e-mails diretamente do painel de administração.
- Integração com a API do WordPress para envio de e-mails.

## O que aprendi com o desenvolvimento deste plugin
- Aprendi a trabalhar com a API do WordPress para enviar e-mails de forma segura.
- Aprendi a implementar autenticação SMTP e criptografia para proteger as credenciais de envio.
- Aprendi a criar uma interface de configuração amigável para o usuário, permitindo fácil configuração do servidor SMTP.
- Aprendi a lidar com erros e exceções durante o envio de e-mails, garantindo que o usuário receba feedback adequado.
- Aprendi a armazenar e recuperar configurações de forma segura, utilizando a API do WordPress.
- Aprendi a criar um teste de envio de e-mail para verificar se as configurações estão corretas.
- Aprendi a criar um plugin WordPress do zero, seguindo as melhores práticas de desenvolvimento.

## Instalação

1. Faça o upload do plugin para o diretório `/wp-content/plugins/` ou instale diretamente pelo painel do WordPress.
2. Ative o plugin no menu "Plugins" do WordPress.
3. Acesse o menu de configurações do plugin para configurar os detalhes do servidor SMTP.

## Observações Importantes

- Durante a ativação do plugin, é necessário criar a constante `SMTP_MAILER_ENCRYPTION_KEY` no arquivo `wp-config.php`. Essa chave é usada para criptografar as credenciais de autenticação SMTP.
  
  Exemplo de configuração no `wp-config.php`:
  ```php
  define('SMTP_MAILER_ENCRYPTION_KEY', 'sua-chave-secreta-aqui');
  ```

- Caso a constante não seja criada antes da ativação, o plugin exibirá um aviso e não funcionará corretamente.

## Configuração

1. Após ativar o plugin, vá até o menu **Configurações > WP SMTP Mailer**.
2. Preencha os campos necessários:
   - Servidor SMTP
   - Porta
   - E-mail do remetente
   - Nome do remetente
   - Usuário e senha SMTP
   - Tipo de criptografia (SSL/TLS)
3. Salve as configurações.

## Teste de Envio

- Utilize a funcionalidade de teste de envio para verificar se as configurações estão corretas.
- Insira um endereço de e-mail de destino e clique em "Enviar Teste".

## Suporte

Se você encontrar problemas ou tiver dúvidas, entre em contato com o desenvolvedor ou consulte a documentação oficial.

## Licença

Este plugin é distribuído sob a licença [GPLv2 ou superior](https://www.gnu.org/licenses/gpl-2.0.html).

# WP SMTP Mailer by Mzero

WP SMTP Mailer by Mzero is a WordPress plugin that allows you to configure email sending through an SMTP server.

## Features

- SMTP server configuration for email sending.
- Support for SMTP authentication with username and password.
- Encryption options (SSL/TLS).
- Email sending test directly from the admin panel.
- Integration with the WordPress API for email sending.

## Installation

1. Upload the plugin to the `/wp-content/plugins/` directory or install it directly from the WordPress admin panel.
2. Activate the plugin in the "Plugins" menu in WordPress.
3. Go to the plugin settings menu to configure the SMTP server details.

## Important Notes

- During plugin activation, you must create the `SMTP_MAILER_ENCRYPTION_KEY` constant in the `wp-config.php` file. This key is used to encrypt the SMTP authentication credentials.
  
  Example configuration in `wp-config.php`:
  ```php
  define('SMTP_MAILER_ENCRYPTION_KEY', 'your-secret-key-here');
  ```

- If the constant is not created before activation, the plugin will display a warning and will not function correctly.

## Configuration

1. After activating the plugin, go to the **Settings > WP SMTP Mailer** menu.
2. Fill in the required fields:
   - SMTP Server
   - Port
   - Sender Email
   - Sender Name
   - SMTP Username and Password
   - Encryption Type (SSL/TLS)
3. Save the settings.

## Sending Test

- Use the sending test feature to verify if the settings are correct.
- Enter a destination email address and click "Send Test".

## Support

If you encounter issues or have questions, contact the developer or consult the official documentation.

## License

This plugin is distributed under the [GPLv2 or later](https://www.gnu.org/licenses/gpl-2.0.html) license.