<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Frase Estoica do Dia</title>
    <style>
        body {
            font-family: 'Courier New', monospace;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            text-align: center;
            color: #333;
        }
        .container {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            max-width: 600px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏛️ Frase do Dia</h1>
        <p>
            "<span id="frase"></span>"
            <br>
            <em><span id="autor"></span></em>
        </p>
        <small>Atualize a página para uma nova frase,<br> ou clique nos botões abaixo.</small>
        <div class="buttons" style="margin-top: 20px;">
            <button onclick="fetchFrase('estoica')"
            style="margin-right: 10px; background-color: #4CAF50; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold;"
            >
                Frase Estoica
            </button>
            <button onclick="fetchFrase('cristã')"
            style="background-color: #2196F3; color: white; border: none; padding: 10px 20px; border-radius: 5px; font-weight: bold;"
            >
                Frase Cristã
            </button>
        </div>
    </div>

    <script>
        async function fetchFrase(type = null) {
            try {
                const response = await fetch(`api.php?type=${type}`);
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const data = await response.json();
                document.getElementById('frase').innerText = data.frase;
                document.getElementById('autor').innerText = data.autor;
            } catch (error) {
                console.error('Error fetching the quote:', error);
                document.getElementById('frase').innerText = 'Erro ao carregar a frase.';
            }
        }

        // Chama a função para buscar a frase ao carregar a página
        window.onload = () => fetchFrase();
    </script>
</body>
</html>
