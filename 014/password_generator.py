import string
import random

def ask_yes_no(question):
    while True:
        response = input(f"{question} (s/n): ").strip().lower()
        if response in ['s', 'n']:
            return response == 's'
        print("Responda com 's' para sim ou 'n' para não.")

def ask_length():
    while True:
        try:
            length = int(input("Quantos caracteres deseja? (mínimo 4, máximo 128): "))
            if 4 <= length <= 128:
                return length
            print("Erro - Valor fora do intervalo permitido.")
        except ValueError:
            print("Erro - Digite um número válido.")

def gerar_senha(length, usar_letras, usar_maiusculas, usar_simbolos):
    pool = ''
    if usar_letras:
        pool += string.ascii_lowercase
    if usar_maiusculas:
        pool += string.ascii_uppercase
    if usar_simbolos:
        pool += string.punctuation
    pool += string.digits

    if not pool:
        print("Erro - Nenhum tipo de caractere selecionado.")
        return ''

    return ''.join(random.choice(pool) for _ in range(length))

def main():
    print("Bem-vindo ao Gerador de Senhas Seguras")
    length = ask_length()
    usar_letras = ask_yes_no("Deseja inserir letras minúsculas?")
    usar_maiusculas = ask_yes_no("Deseja inserir letras maiúsculas?")
    usar_simbolos = ask_yes_no("Deseja inserir símbolos?")
    
    senha = gerar_senha(length, usar_letras, usar_maiusculas, usar_simbolos)
    
    if senha:
        print(f"\nSenha gerada com sucesso:\n{senha}\n")

if __name__ == "__main__":
    main()
