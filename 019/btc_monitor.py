import requests
import time
import os

def get_btc_price(currency):
    url = f"https://api.coingecko.com/api/v3/simple/price?ids=bitcoin&vs_currencies={currency}"
    try:
        response = requests.get(url, timeout=10)
        response.raise_for_status()
        data = response.json()
        return data['bitcoin'][currency]
    except requests.exceptions.RequestException as e:
        print(f"Error fetching data from API: {e}")
        return None
    except KeyError:
        print(f"Currency conversion from BTC to {currency} is not available.")
        return None

def monitor_btc_price(currency, interval=60):
    print(f"Monitoring BTC price in {currency.upper()} every {interval} seconds...")
    while True:
        price = get_btc_price(currency)
        if price is not None:
            os.system('clear' if os.name == 'posix' else 'cls')

        print("===================================")
        print("         BITCOIN PRICE MONITOR     ")
        print("===================================")
        print(f"Currency: {currency.upper()}")
        print(f"Price: {price:.2f}")
        print("===================================")
        time.sleep(interval)

if __name__ == "__main__":
    currency = input("Enter the fiat currency you want to monitor (e.g., 'usd', 'eur', 'brl'): ").lower()
    interval = input("Enter the monitoring interval in seconds (default is 60): ")
    
    if not interval.isdigit():
        interval = 60
    else:
        interval = int(interval)
    
    monitor_btc_price(currency, interval)