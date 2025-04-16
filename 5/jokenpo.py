import random

opcoes = ["pedra", "papel", "tesoura"]

print("Bem-vindo ao Jokenpô!")

while True:
    jogador = input("Escolha: pedra, papel ou tesoura? ").lower()

    if jogador not in opcoes:
        print("Escolha inválida. Tente novamente.")
        continue

    computador = random.choice(opcoes)

    print(f"Você jogou: {jogador}")
    print(f"Computador jogou: {computador}")

    if jogador == computador:
        print("Empate!")
    elif (jogador == "pedra" and computador == "tesoura") or \
         (jogador == "papel" and computador == "pedra") or \
         (jogador == "tesoura" and computador == "papel"):
        print("Você venceu!")
    else:
        print("Você perdeu!")

    jogar_novamente = input("Quer jogar de novo? (s/n): ").lower()
    if jogar_novamente != "s":
        print("Valeu por jogar!")
        break
