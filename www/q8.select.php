<?php 
    session_start();
    require_once './_request.php';
    require_once './tools/_tools.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Selectionner un arrêt</title>
    <?php 
        include_once './tools/_head.php';
    ?>
    <link rel="stylesheet" href="./assets/q2.css">
    <link rel="stylesheet" href="./assets/q3.css">
</head>
<body>
    <form class="header" method="GET"></form>
    <main>
        <form  action="./q8.update.php" method="GET">
            <input type="hidden" name="action" value="selectionner_arret">
            <span class="title"> Selectionner un arrêt </span>
            <div class="form-item">
                <label for="arret_id">Choisir: </label>
                <select name="ARRET_ID" id="arret_id">
                    <?php 
                        $arret = new Arret();
                        $arretList = $arret->findAll();
                        foreach ($arretList as $arret_item) {
                            ?>
                                <option value="<?= $arret_item->getID(); ?>">
                                    <?php echo $arret_item->getID() . " - " . $arret_item->getNom(); ?>
                                </option>
                            <?php
                        }
                    ?>
                </select>
            </div>
            <div class="error">
                <span>
                    <?php 
                        if (_isset_key($_GET, 'error')) {
                            switch ($_GET['error']) {
                                default:
                                    echo "Erreur inconnue.";
                            }
                        }
                    ?>
                </span>
            </div>
            <button type="submit">Selectionner</button>
        </form>
    </main>
</body>
</html>