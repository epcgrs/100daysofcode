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
            max-width: 800px;
        }
        .container-table {
            background: white;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        .container-table table {
            width: 100%;
            border-collapse: collapse;
        }
        .container-table th, .container-table td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        .container-table th {
            background-color: #f2f2f2;
            text-align: left;
        }
        .container-table tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .container-table tr:hover {
            background-color: #f1f1f1;
        }
        .container-table table td button {
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
        }
        .container-table table td.phrase {
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .container-table table td.autor {
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .container-table table td .actions {
            display: flex;
            justify-content: space-between;
        }
        .container-table table td .actions button {
            margin: 0 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>🏛️ Gerenciar </h1>

        <button id="addFraseButton">
            Adicionar Frase
        </button>
       
        <div class="container-table" style="margin-top: 20px; overflow-x: auto; max-width: 100%; height: 400px;">
            <table> 
                <tr>
                    <th>Frase</th>
                    <th>Autor</th>
                    <th>Ações</th>
                </tr>
            </table>
        </div>
    </div>
    <script>
        async function createModal(id = null) {
            
            const modal = document.createElement('div');
            modal.style.position = 'fixed';
            modal.style.top = '50%';
            modal.style.left = '50%';
            modal.style.transform = 'translate(-50%, -50%)';
            modal.style.backgroundColor = '#fff';
            modal.style.padding = '20px';
            modal.style.boxShadow = '0 0 10px rgba(0, 0, 0, 0.5)';
            modal.innerHTML = `
                <h2>Adicionar Frase</h2>
                <input type="text" id="fraseInput" placeholder="Frase" required>
                <input type="text" id="autorInput" placeholder="Autor" required>
                <select id="typeInput">
                    <option value="estoica">Estoica</option>
                    <option value="cristã">Cristã</option>
                </select>
                <button onclick="saveFrase(${id})" style='background-color: #4CAF50; color: white;'>Salvar</button>
                <button onclick="this.parentElement.remove()">Fechar</button>
            `;
            document.body.appendChild(modal);
        }

        async function editFrase(id) {
         
            const response = await fetch(`api.php?action=get&id=${id}`);
            const data = await response.json();
            createModal(id);

            document.getElementById('fraseInput').value = data.frase;
            document.getElementById('autorInput').value = data.autor;
            document.getElementById('typeInput').value = data.type;
            document.querySelector('h2').innerText = 'Editar Frase';
            document.querySelector('button[onclick^="saveFrase"]').innerText = 'Salvar Alterações';
            document.querySelector('button[onclick^="saveFrase"]').setAttribute('onclick', `saveFrase(${id})`);
            document.querySelector('button[onclick^="saveFrase"]').style.backgroundColor = '#4CAF50';
            document.querySelector('button[onclick^="saveFrase"]').style.color = 'white';
            document.querySelector('button[onclick^="saveFrase"]').style.marginRight = '10px';
            document.querySelector('button[onclick^="saveFrase"]').style.marginLeft = '10px';
            document.querySelector('button[onclick^="saveFrase"]').style.backgroundColor = '#4CAF50';
            document.querySelector('button[onclick^="saveFrase"]').style.color = 'white';
            document.querySelector('button[onclick^="saveFrase"]').style.marginRight = '10px';
            document.querySelector('button[onclick^="saveFrase"]').style.marginLeft = '10px';
        }

        async function deleteFrase(id) {
            if (confirm('Você tem certeza que deseja deletar esta frase?')) {
                try {
                    const response = await fetch(`api.php?action=delete&id=${id}`, {
                        method: 'DELETE'
                    });
                    const data = await response.json();
                    if (data.error) {
                        throw new Error(data.error);
                    }
                    alert('Frase deletada com sucesso!');
                    fetchAll();
                } catch (error) {
                    console.error('Error deleting the quote:', error);
                }
            }
            
        }
        async function fetchAll() {
            try {
                const response = await fetch(`api.php?action=getAll`);
                
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                const data = await response.json();
                if (data.error) {
                    throw new Error(data.error);
                }
                const table = document.querySelector('table');
                data.forEach(item => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class='phrase'>${item.frase}</td>
                        <td class='autor'>${item.autor}</td>
                        <td class=''>
                            <div class='actions'>
                                <button onclick="editFrase(${item.id})" style="margin-right: 10px; background-color: #4CAF50; color: white;">Editar</button>
                                <button onclick="deleteFrase(${item.id})" style="margin-left: 10px; background-color: #f44336; color: white;">Deletar</button>
                            </div>
                        </td>
                    `;
                    table.appendChild(row);
                });
            } catch (error) {
                console.error('Error fetching the quote:', error);
            }
        }

        async function saveFrase(id = null) {
            const frase = document.getElementById('fraseInput').value;
            const autor = document.getElementById('autorInput').value;
            const type = document.getElementById('typeInput').value;

            if (!frase || !autor || !type) {
                alert('Por favor, preencha todos os campos.');
                return;
            }

            try {
                const response = await fetch(`api.php?action=${id ? 'update' : 'create'}`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ id, frase, autor, type })
                });
                const data = await response.json();
                if (data.error) {
                    throw new Error(data.error);
                }
                alert('Frase salva com sucesso!');
                document.body.removeChild(document.querySelector('div[style*="position: fixed"]'));
                fetchAll();
            } catch (error) {
                console.error('Error saving the quote:', error);
            }
        }

        // Chama a função para buscar todas as frases ao carregar a página
        window.onload = () => fetchAll();


        document.getElementById('addFraseButton').addEventListener('click', () => createModal());
    </script>
</body>
</html>