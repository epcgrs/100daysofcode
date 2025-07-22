import requests

def convert_currency(to_currency, amount):

    try:
        amount = float(amount)
        print(f"Converting {amount} BTC to {to_currency.upper()}")
    except ValueError:
        print("Invalid amount. Please enter a numeric value.")
        return

    url = f"https://api.coingecko.com/api/v3/simple/price?ids=bitcoin&vs_currencies={to_currency}"
    print(f"Fetching conversion rate from API: {url}")

    try:
        response = requests.get(url)
        if response.status_code != 200:
            print("Error fetching data from API")
            return "status_code: " + str(response.status_code)

        data = response.json()
        print("Received response from API:", data)

        try:
            tax = data['bitcoin'][to_currency]
            conversion =  float(amount) * tax
            print(f"{amount} BTC is equal to {conversion:.2f} {to_currency.upper()}")
        except KeyError:
            print(f"Currency conversion from BTC to {to_currency} is not available.")
    
    except requests.exceptions.RequestException as e:
        print(f"Error fetching data from API: {e}")


print("Welcome to the Currency Converter!")
to_currency = input("Enter the fiat currency you want to convert to (e.g., 'usd', 'eur', 'brl'): ").lower()
sats_or_btc = input("Do you want to convert from 'sats' or 'btc'? ").lower()
amount = input(f"Enter the amount in {sats_or_btc.upper()}: ")

if sats_or_btc == "sats":
    amount = float(amount) / 100000000
elif sats_or_btc == "btc":
    amount = float(amount)

convert_currency(to_currency, amount)
