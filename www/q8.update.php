<?php 
    session_start();
    require_once './_request.php';
    require_once './tools/_tools.php';

    if (!_isset_key($_GET, 'ARRET_ID')) {
        header("Location: ./index.php");
        exit();
    }

    $arret = new Arret();
    
    try {
        $arret = $arret->findByID($_GET['ARRET_ID']);
    } catch (Exception $e) {
        header("Location: ./index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Arret</title>
    <?php 
        include_once './tools/_head.php';
    ?>
    <link rel="stylesheet" href="./assets/q2.css">
    <link rel="stylesheet" href="./assets/q3.css">
</head>
<body>
    <form class="header" method="GET"></form>
    <main>
        <?php 
            $arret = new Arret();
            $arret = $arret->findByID($_GET['ARRET_ID']);
        ?>
        <form  action="./_server.php" method="POST">
            <input type="hidden" name="action" value="modifier_arret">
            <span class="title"> Modifier Arrêt </span>
            <div class="form-item">
                <label for="arret_id">Id: </label>
                <input type="number" name="ID" id="arret_id" value="<?= $arret->getID(); ?>" required>
            </div>
            <div class="form-item">
                <label for="nom">Nom: </label>
                <input type="text" name="NOM" id="nom" value="<?= $arret->getNom(); ?>" required>
            </div>
            <div class="form-item">
                <label for="latitude">Latitude:</label>
                <input type="number" name="LATITUDE" id="latitude" value="<?= $arret->getLatitude(); ?>" required>
            </div>
            <div class="form-item">
                <label for="longitude">Longitude:</label>
                <input type="number" name="LONGITUDE" id="longitude" value="<?= $arret->getLongitude(); ?>" required>
            </div>
            <div class="error">
                <span>
                    <?php 
                        if (_isset_key($_GET, 'error')) {
                            switch ($_GET['error']) {
                                case 'invalid_boudary':
                                    echo "Erreur: Les coordonnées sont invalides (doivent être en belgique).";
                                    break;
                                case 'failed_arret_update':
                                    echo "Erreur, Id déjà utilisé.";
                                    break;
                                default:
                                    echo "Erreur inconnue.";
                            }
                        }
                    ?>
                </span>
            </div>
            <button type="submit">Enrégistrer</button>
        </form>
    </main>
</body>
</html>