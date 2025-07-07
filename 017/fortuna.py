#!/usr/bin/env python3
import random

def carregar_frases(arquivo):
    with open(arquivo, 'r', encoding='utf-8') as f:
        conteudo = f.read()
    return [frase.strip() for frase in conteudo.split('%%') if frase.strip()]

def main():
    frases = carregar_frases('frases.txt')
    print(random.choice(frases))

if __name__ == '__main__':
    main()
