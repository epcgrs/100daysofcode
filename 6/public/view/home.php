<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <title>Pague por uma Piada</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Pague por uma piada com Bitcoin Lightning.">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Unkempt:wght@400;700&display=swap" rel="stylesheet">
  <style>
    body { font-family: "Unkempt", sans-serif; letter-spacing: 1px; text-align: center; padding: 50px; background-color: #f0f0f0; color: #333; font-weight: 600;}
    button { padding: 10px 20px; font-size: 1.2em; font-family: "Unkempt", sans-serif; border: none; border-radius: 5px; background-color: #4CAF50; color: white; cursor: pointer; font-weight: 900;}
    button:hover { background-color: #45a049; }
    label { display: block; margin-bottom: 20px; font-size: 1.2em; font-family: "Unkempt", sans-serif; }
    #paymentForm { display: flex; flex-direction: column; align-items: center; margin-top: 60px; }
    .amount { padding: 10px; font-size: 1.2em; font-family: "Unkempt", sans-serif; border: 1px solid #ccc; border-radius: 5px; margin-bottom: 20px; }
    .piada { display: none; margin-top: 20px; font-weight: bold; }
    .payment { display: none; margin-top: 20px; padding: 30px; max-width: 640px; border: 1px solid #ccc; border-radius: 10px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); margin: auto;}
    .payment .qrcode { text-align: center; }
    .payment .qrcode img { max-width: 100%; height: auto; }
    .payment .lninvoice { margin-top: 20px; font-size: 1.2em; word-break: break-all; font-family: sans-serif; }
    .error { color: red; margin-top: 20px; }
    .loading { display: none; text-align: center; margin-top: 50px; margin-bottom: 50px; }
    .success { display: none; text-align: center; margin-top: 50px; margin-bottom: 50px; }
    .success p { font-size: 1.5em; font-family: "Unkempt", sans-serif; }
  </style>
</head>
<body>
  <h1>😂 Pague por uma Piada</h1>
  <form method="POST" id="paymentForm" action="#">
    <label for="amount">Valor em Satoshis:
    <input type="number" name="amount" value="10" class="amount" placeholder="Valor em Satoshis" required min="10" max="50000">
    </label>
    <button type="submit">Quero uma piada</button>
   
  </form>
  <div class="payment">
    <h2>Pagamento</h2>
    <p>Escaneie o QR Code ou copie o código da invoice lightning ⚡ abaixo:</p>
    <div class="qrcode"></div>
    <p class="lninvoice"></p>
  </div>
  <div class="loading">
    <img src="https://media.giphy.com/media/Yj2nHhbGsNQSrGyvI7/giphy.gif" alt="Loading..." style="width: 150px; height: 150px;">
    <p>Carregando...</p>
  </div>
  <div class="success">
    <p>Pagamento realizado com sucesso! </p>
  </div>
  <div class="piada" style="display: none;"></div>
  <script src="/js/app.js"></script>
</body>
</html>
