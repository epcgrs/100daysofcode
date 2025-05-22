import secrets
import string
import bcrypt

def ask_yes_no(question):
    while True:
        resp = input(f"{question} (s/n): ").strip().lower()
        if resp in ['s', 'n']:
            return resp == 's'
        print("Responda com 's' ou 'n'.")

def ask_length():
    while True:
        try:
            n = int(input("Quantos caracteres? (mínimo 8, máximo 128): "))
            if 8 <= n <= 128:
                return n
            print("Erro - Valor fora do intervalo.")
        except ValueError:
            print("Erro -  Digite um número válido.")

def montar_pool(letras, maiusculas, numeros, simbolos):
    pool = ''
    if letras:
        pool += string.ascii_lowercase
    if maiusculas:
        pool += string.ascii_uppercase
    if numeros:
        pool += string.digits
    if simbolos:
        pool += string.punctuation
    return pool

def gerar_senha(pool, tamanho):
    return ''.join(secrets.choice(pool) for _ in range(tamanho))

def gerar_hash_bcrypt(senha):
    return bcrypt.hashpw(senha.encode(), bcrypt.gensalt()).decode()

def main():
    print("Bem-vindo ao Gerador de Senhas com Alta Entropia + Bcrypt")
    tamanho = ask_length()
    usar_letras = ask_yes_no("Incluir letras minúsculas?")
    usar_maiusculas = ask_yes_no("Incluir letras maiúsculas?")
    usar_numeros = ask_yes_no("Incluir números?")
    usar_simbolos = ask_yes_no("Incluir símbolos?")

    pool = montar_pool(usar_letras, usar_maiusculas, usar_numeros, usar_simbolos)
    if not pool:
        print("Erro - Nenhum tipo de caractere foi selecionado.")
        return

    senha = gerar_senha(pool, tamanho)
    senha_hash = gerar_hash_bcrypt(senha)

    print("\n Sua senha segura gerada:")
    print(senha)
    print("\n Hash Bcrypt (apenas para visualizar, impossível reverter):")
    print(senha_hash)

if __name__ == "__main__":
    main()
