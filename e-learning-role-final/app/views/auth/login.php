
<link rel="stylesheet" href="/e-learning-role-final/public/style/login.css"></head><body>

<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST" action="/e-learning-role-final/public/login">

<div class="top-form">
    <div style="font-size:30px;font-weight:bold;">Connexion</div>
    <a class="back-btn" href="/e-learning-role-final/public/">Accueil</a>    
</div>
<hr>

<input type="email" name="email" placeholder="Email" required><br>
<input type="password" name="password" placeholder="Mot de passe" required><br>
<button type="submit">Se connecter</button>
<p>Pas encore inscrit ? <a href="/e-learning-role-final/public/register">Créer un compte</a></p>
</form>
</body></html>
