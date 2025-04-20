<link rel="stylesheet" href="/e-learning-role-final/public/style/admin-form.css">

<h2>Modifier le cours</h2>
<form method="POST">
    <input name="nom" value="<?= $cours['nom'] ?>" required><br>
    <input name="professeur" value="<?= $cours['professeur'] ?>" required><br>
    <input name="niveau" value="<?= $cours['niveau'] ?>" required><br>
    <input name="duree" value="<?= $cours['duree'] ?>" required><br>
    <input name="image" value="<?= $cours['image'] ?>" required><br>
    <textarea name="contenu" required><?= $cours['contenu'] ?></textarea><br>
    <button type="submit">Modifier</button>
</form>