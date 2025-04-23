<?php 
include __DIR__ .'/../partials/header.php'; ?>
    <section class="Dashboard">
        <div class="container">
           
            <h2>Dashboard</h2>
            <p>Bem-vindo ao seu painel de controle.</p>

            <p>Gerencie suas URLs encurtadas, visualize estatísticas e muito mais.</p>

        </div>
        <div class="container">
            <h2>Crie um link encurtado</h2>
            <form action="/dashboard/shorten" method="post" id="shorten-form">
                <label for="original-url">URL Original:</label>
                <input type="text" id="original-url" name="url" required>
                
                <label for="ip-limit">Limitar por IP: <small>(opcional)</small></label>
                <input type="text" id="ip-limit" name="ip_limit" placeholder="Opcional">

                <label for="expiration">Data de Expiração: <small>(opcional)</small></label>

                <input type="date" id="expiration" name="expiration" placeholder="Opcional">

                <?php if (isset($_SESSION['error'])): ?>
                    <div class="error-message">
                        <?php echo $_SESSION['error']; ?>
                    </div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>

                <?php if (isset($_SESSION['success'])): ?>
                    <div class="success-message">
                        <?php echo $_SESSION['success']; ?>
                    </div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
                <button type="submit" class="primary-button">Encurtar</button>
            </form>
        </div>
        <div class="container">
            <h2>Links Encurtados</h2>
            <p>Veja abaixo a lista de seus links encurtados:</p>
            
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>URL Original</th>
                        <th>URL Encurtada</th>
                        <th>Data de Criação</th>
                        <th>Data de Expiração</th>
                        <th>Limitação de IP</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
    <?php
        if (empty($userLinks)) {
            echo "<tr><td colspan='7' style='text-align: center;'>Nenhum link encurtado encontrado.</td></tr>";
        }
        foreach ($userLinks as $link) {
            $createdAt = (new DateTime($link['created_at']))->format('d/m/Y'); // Formata a data de criação
            $expiration = $link['expiration'] ? (new DateTime($link['expiration']))->format('d/m/Y') : 'Sem Expiração'; // Formata a data de expiração ou exibe "Sem Expiração"
            echo "<tr>
                        <td>{$link['id']}</td>
                        <td>{$link['url']}</td>
                        <td>http://{$_SERVER['HTTP_HOST']}/{$link['short_url']}</td>
                        <td>{$createdAt}</td>
                        <td>{$expiration}</td>
                        <td>{$link['ip_limit']}</td>
                        <td>
                            <form action='/dashboard/delete/{$link['id']}' method='post' id='delete-form'>
                                <input type='hidden' name='id' value='{$link['id']}'>
                                <button type='submit' onclick='return confirm(\"Tem certeza que deseja excluir este link?\");'>Excluir</button>
                            </form>
                        </td>
                    </tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <div class="container">
            <h2>Cliques nos seus links</h2>
            <p>Aqui você pode ver o número de cliques em cada link encurtado.</p> <!-- Added description for clicks -->
                    
            <table>
                <thead>
                    <tr>
                        <th>URL Encurtada</th>
                        <th>Número de Cliques</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (empty($userClicks)) {
                        echo "<tr><td colspan='2' style='text-align: center;'>Nenhum clique encontrado.</td></tr>";
                    }
                    foreach ($userClicks as $counter): ?>
                        <tr>
                            <td><?php echo $counter['short_url']; ?></td>
                            <td><?php echo $counter['clicks']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>
<?php include __DIR__ .'/../partials/footer.php'; ?>