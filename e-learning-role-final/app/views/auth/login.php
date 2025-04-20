<html><head><title>Connexion</title>
<link rel="stylesheet" href="/e-learning-role-final/public/style/login.css"></head><body>
<h2>Connexion</h2>
<?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
<form method="POST" action="/e-learning-role-final/public/login">
<input type="email" name="email" placeholder="Email" required><br>
<input type="password" name="password" placeholder="Mot de passe" required><br>
<button type="submit">Se connecter</button>
</form>
<p>Pas encore inscrit ? <a href="/e-learning-role-final/public/register">Créer un compte</a></p>
</body></html>
