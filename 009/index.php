<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerador de QR Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            padding: 20px;
        }
        input[type="text"] {
            padding: 10px;
            width: 300px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        button {
            padding: 10px 20px;
            font-size: 1em;
            color: #fff;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        img {
            margin-top: 20px;
            max-width: 100%;
        }
    </style>
</head>
<body>
    <h1>Gerador de QR Code</h1>
    <form id="qrForm">
        <input type="text" id="textInput" placeholder="Digite o texto para o QR Code" required>
        <button type="submit">Gerar QR Code</button>
    </form>
    <div id="qrCodeContainer">
        <img id="qrCodeImage" src="" alt="QR Code" style="display: none; margin: 0 auto;">
    </div>

    <script>
        document.getElementById('qrForm').addEventListener('submit', function (e) {
            e.preventDefault();

            const text = document.getElementById('textInput').value;
            const qrCodeImage = document.getElementById('qrCodeImage');
            const qrCodeContainer = document.getElementById('qrCodeContainer');

            // Enviar o texto para o backend e exibir o QR Code
            fetch('generate_qr.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'text=' + encodeURIComponent(text),
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro ao gerar QR Code');
                }
                return response.blob();
            })
            .then(blob => {
                const url = URL.createObjectURL(blob);
                qrCodeImage.src = url;
                qrCodeImage.style.display = 'block';
            })
            .catch(error => {
                alert(error.message);
            });
        });
    </script>
</body>
</html>