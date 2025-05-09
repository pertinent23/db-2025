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
    <title>Supprimer un itinéraire</title>
    <?php 
        include_once './tools/_head.php';
    ?>
    <link rel="stylesheet" href="./assets/q2.css">
    <link rel="stylesheet" href="./assets/q3.css">
</head>
<body>
    <form class="header" method="GET"></form>
    <main>
        <form  action="./_server.php" method="POST">
            <input type="hidden" name="action" value="supprimer_itineraire">
            <span class="title"> Supprimer un itinéraire </span>
            <div class="form-item">
                <label for="itineraire_id">Choisir: </label>
                <select name="INTINERAIRE_ID" id="itineraire_id">
                    <?php 
                        $itineraire = new Itineraire();
                        $itineraireList = $itineraire->findAll();
                        foreach ($itineraireList as $itineraire) {
                            ?>
                                <option value="<?= $itineraire->getID(); ?>">
                                    <?php echo $itineraire->getID() . " - " . $itineraire->getNom(); ?>
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
                                case 'failed_itineraire_deletion"':
                                    echo "Erreur lors de la suppréssion.";
                                    break;
                                default:
                                    echo "Erreur inconnue.";
                            }
                        }
                    ?>
                </span>
            </div>
            <button type="submit">Supprimer</button>
        </form>
    </main>
</body>
</html>