# Bitcoin Taproot address generator

A generator for Bitcoin Taproot addresses. It generates a new address every time you refresh the page.
Generate the private and public keys, and the address.

## Warning
⚠️ I dont know if this is secure, I just made it for fun and to learn. Do not use it for real transactions. Use at your own risk.
⚠️ This is not a production-ready application. The code may contain bugs and security vulnerabilities. Use at your own risk.
⚠️ This is not a wallet. It does not store your private keys. It only generates them and shows them to you. Do not use it for real transactions. Use at your own risk.

## Features

- Generate a new Bitcoin Taproot address
- Generate a new private key
- Generate a new public key
- Verify address

## What I Learn

- How to generate a Bitcoin Taproot address
- BECH32 encoding
- How to generate a private key
- How to generate a public key
- How to verify a Bitcoin address

## Technology

- **PHP**: The project is implemented using pure PHP without any frameworks.
- libs: 
  - composer: simplito/elliptic-php
  - php: php-gmp


## How to Run

1. **Clone the repository**:
    ```bash
    git clone https://github.com/your-username/100daysOfCode.git
    cd 100daysOfCode/10
    ```
2. **Run PHP Server**

    ```bash
    php -s localhost:8000
    ```

3. **Open your browser** and go to `http://localhost:8000` to see the app in action.

4. **Generate a new address** by refreshing the page.

5. **Verify the address** by clicking the "Verify" button.
