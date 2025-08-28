#include <iostream>
#include <fstream>
#include <map>
#include <string>
#include <sstream>
#include <cctype>
using namespace std;

string limparPontuacao(const string& palavra) {
    string limpa;
    for (char c : palavra) {
        if (isalnum(static_cast<unsigned char>(c))) {
            limpa += tolower(c);
        }
    }
    return limpa;
}

int main() {
    ifstream arquivo("texto.txt");
    if (!arquivo) {
        cerr << "Erro ao abrir arquivo!\n";
        return 1;
    }

    map<string, int> contador;
    string linha;
    int totalLinhas = 0, totalPalavras = 0;

    while (getline(arquivo, linha)) {
        totalLinhas++;
        stringstream ss(linha);
        string palavra;

        while (ss >> palavra) {
            palavra = limparPontuacao(palavra);
            if (!palavra.empty()) {
                contador[palavra]++;
                totalPalavras++;
            }
        }
    }

    string maisFrequente;
    int maiorFreq = 0;
    for (const auto& [palavra, freq] : contador) {
        if (freq > maiorFreq) {
            maisFrequente = palavra;
            maiorFreq = freq;
        }
    }

    cout << "Linhas: " << totalLinhas << "\n";
    cout << "Palavras: " << totalPalavras << "\n";
    cout << "Palavra mais frequente: '" << maisFrequente << "' (" << maiorFreq << " vezes)\n";
}
