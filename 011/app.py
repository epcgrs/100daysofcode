from flask import Flask, render_template
import psutil
import socket
import json

app = Flask(__name__)

@app.route('/')
def home():
    # Coletando dados do sistema
    cpu_percent = psutil.cpu_percent(interval=1)
    memory = psutil.virtual_memory()
    disk = psutil.disk_usage('/')
    load_avg = psutil.getloadavg()
    temps = psutil.sensors_temperatures()

    tempsList = {}
    for name, entries in temps.items():
        tempsList[name] = [{"label": e.label, "temp": e.current} for e in entries]
    
    
    # IP da máquina
    ip = socket.gethostbyname(socket.gethostname())
    
    # Coletando uptime
    uptime_seconds = psutil.boot_time()
    
    # Montando os dados para a página
    system_info = {
        "cpu_percent": cpu_percent,
        "disk": {
            "free": disk.free,
            "percent": disk.percent,
            "total": disk.total,
            "used": disk.used
        },
        "hostname": socket.gethostname(),
        "ip": ip,
        "load_avg": load_avg,
        "memory": {
            "active": memory.active,
            "available": memory.available,
            "buffers": memory.buffers,
            "cached": memory.cached,
            "free": memory.free,
            "inactive": memory.inactive,
            "percent": memory.percent,
            "shared": memory.shared,
            "slab": memory.slab,
            "total": memory.total,
            "used": memory.used
        },
        "temps": tempsList,
        "uptime": uptime_seconds
    }

    return render_template('index.html', system_info=system_info)

if __name__ == '__main__':
    app.run(host='0.0.0.0', port=5000)
