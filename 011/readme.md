# System Monitoring

A simple python system monitoring tool that shows the CPU and memory usage of your system. Uptime, Disk usage, and more. For my local server monitoring.

## Features

- Monitor CPU usage
- Monitor memory usage
- Monitor disk usage
- Monitor system uptime
- Monitor system load average

## What I Learn

- How to use venv to create a virtual environment in python
- How to use python to monitor system resources
- How to create a service and run it in the background on ubuntu

## Technology

- **Python**: The project is implemented using Python with flask.
- **Flask**: A lightweight WSGI web application framework in Python.
- **psutil**: A cross-platform library for retrieving information on running processes and system utilization (CPU, memory, disks, network, sensors) in Python.

## How to Run

1. **Clone the repository**:
    ```bash
    git clone https://github.com/your-username/100daysOfCode.git
    cd 100daysOfCode/011
    ```
2. **Run Python Server**

    ```bash
    python3 app.py
    ```

3. **Open your browser** and go to `http://localhost:5000` to see the app in action.


**OR run as a service**

1. **Create a environment**
    ```bash
    cd /projectpath/011/
    python3 -m venv venv
    source venv/bin/activate
    pip install flask psutil
    ```
2. **Create a service**
    ```bash
    sudo nano /etc/systemd/system/monitor_server.service
    ```

3. **Edit and add the `monitor_server.service` content to the file**


4. **Start the service**
    ```bash
    sudo systemctl daemon-reload

    sudo systemctl start monitor_server
    ```


5. **Check the status of the service**
    ```bash
    sudo systemctl status monitor_server
    ```

6. **Check the logs of the service**
    ```bash
    sudo journalctl -u monitor_server
    ```

7. **Access the service on browser**
    ```bash
    http://localhost:5000
    ```

8. **Stop the service**
    ```bash
    sudo systemctl stop monitor_server
    ```

