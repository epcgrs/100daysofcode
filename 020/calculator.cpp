#include <iostream>
using namespace std;

int main() {
    double num1, num2;
    char operation;

    cout << "Welcome to the Calculator!" << endl;
    cout << "Enter the first number: ";
    cin >> num1;
    cout << "Enter the operation (+, -, *, /): ";
    cin >> operation;
    cout << "Enter the second number: ";
    cin >> num2;


    switch (operation) {
        case '+':
            cout << "Result: " << num1 + num2 << endl;
            break;
        case '-':
            cout << "Result: " << num1 - num2 << endl;
            break;
        case '*':
            cout << "Result: " << num1 * num2 << endl;
            break;
        case '/':
            if (num2 != 0) {
                cout << "Result: " << num1 / num2 << endl;
            } else {
                cout << "Error: Division by zero!" << endl;
            }
            break;
        default:
            cout << "Error: Invalid operation!" << endl;
    }

    return 0;
}