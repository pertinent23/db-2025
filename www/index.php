<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link rel="stylesheet" href="./assets/main.css">
</head>
<body>
    <b>Bienvenue Au debut du projet 2025</b>
    <?php 
        #Test de la connexion à la base de données
        require_once './_request.php';

        $item = new Arret();

        var_dump($item->search('midi'));
    ?>
</body>
</html>