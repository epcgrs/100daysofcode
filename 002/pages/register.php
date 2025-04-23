<?php 
session_start(); 
include __DIR__ .'/../partials/header.php'; ?>
    <section class="register">
        <div class="container">
            
            <h2>Cadastre-se</h2>
            <p>Preencha o formulário abaixo para criar sua conta.</p>
            <form action="/register" method="post" id="register-form">
                <label for="username">Nome de usuário:</label>
                <input type="text" id="username" name="username" required>
                
                <label for="password">Senha:</label>
                <input type="password" id="password" name="password" required>
                
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
                <button type="submit" class="primary-button">Registrar</button>
            </form>
        </div>
    </section>
<?php include __DIR__ .'/../partials/footer.php'; ?>