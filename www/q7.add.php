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
    <title>Ajouter des Horraires </title>
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
            <input type="hidden" name="action" value="ajout_horaire">
            <span class="title"> Creation d'un trajet </span>
            <div class="form-item">
                <label for="itineraire_id">Itinéraire: </label>
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
            <div class="form-item">
                <label for="direction">Direction:</label>
                <select name="DIRECTION" id="direction">
                    <option value="0">Aller</option>
                    <option value="1">Retour</option>
                </select>
            </div>
            <div class="form-item">
                <label for="service_id">Service: </label>
                <select name="SERVICE_ID" id="service_id">
                    <?php 
                        $service = new Service();
                        $serviceList = $service->findAll();
                        foreach ($serviceList as $ser) {
                            ?>
                                <option value="<?= $ser->getID(); ?>">
                                    <?php echo $ser->getID() . " - " . $ser->getNom(); ?>
                                </option>
                            <?php
                        }
                    ?>
                </select>
            </div>
            <div class="form-item">
                <label for="horaire">Horraires:</label>
                <textarea name="HORAIRE" id="horaire" placeholder="Ex: STOP_ID,harrivé,hdepart"></textarea>
            </div>
            <div class="error">
                <span>
                    <?php 
                        if (_isset_key($_GET, 'error')) {
                            switch ($_GET['error']) {
                                case 'failed_trajet_creation"':
                                    echo "Erreur lors de la création du trajet.";
                                    break;
                                case 'failed_horraire_creation':
                                    echo "Erreur lors de la création de l'horaire.";
                                    break;
                                case 'invalid_horaire':
                                    echo "Erreur: L'horaire d'arrivée doit être inférieur à l'horaire de départ.";
                                    break;
                                case 'invalid_horaire_format':
                                    echo "Erreur: Le format de l'horaire est invalide.";
                                    break;
                                default:
                                    echo "Erreur inconnue.";
                            }
                        }
                    ?>
                </span>
            </div>
            <button type="submit">Ajouter</button>
        </form>
    </main>
</body>
</html>