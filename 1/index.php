<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Frase Estoica do Dia - Uma citação inspiradora para o seu dia.">
    <meta name="keywords" content="frase, estoica, citação, inspiração">
    <meta name="author" content="Emmanuelpcg">
    <meta name="theme-color" content="#4CAF50">
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
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            max-width: 600px;
        }
        h1 {
            font-size: 1.8rem;
            margin-bottom: 1rem;
        }
        p {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }
        em {
            font-size: 1.2rem;
            color: #555;
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
        <!-- github icon -->
        <a href="https://github.com/epcgrs/100daysofcode" target="_blank" style="display: inline-block; margin-top: 20px;">
            <img src="https://cdn-icons-png.flaticon.com/512/25/25231.png" alt="GitHub" style="width: 30px; height: 30px;">
        </a>
        <!-- by -->

        <p>Desenvolvido por Emmanuelpcg</p>      
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
