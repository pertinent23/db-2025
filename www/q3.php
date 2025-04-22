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
    <title>Ajout de Service</title>
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
            <input type="hidden" name="action" value="ajout_service">
            <span class="title"> Ajout d'un Service </span>
            <div class="form-item">
                <label for="nom">Nom: </label>
                <input type="text" name="NOM" id="nom" placeholder="Nom du service" required>
            </div>
            <div class="form-item">
                <label for="date-debut">Date de début:</label>
                <input type="date" name="DATE_DEBUT" id="date-debut" required>
            </div>
            <div class="form-item">
                <label for="date-fin">Date de fin:</label>
                <input type="date" name="DATE_FIN" id="date-fin" required>
            </div>
            <div class="form-item">
                <label for="lundi">Lundi:</label>
                <input type="hidden" name="LUNDI" value="false">
                <input type="checkbox" name="LUNDI" id="lundi" value="1">
            </div>
            <div class="form-item">
                <label for="mardi">Mardi:</label>
                <input type="hidden" name="MARDI" value="false">
                <input type="checkbox" name="MARDI" id="mardi" value="1">
            </div>
            <div class="form-item">
                <label for="mercredi">Mercredi:</label>
                <input type="hidden" name="MERCREDI" value="false">
                <input type="checkbox" name="MERCREDI" id="mercredi" value="1">
            </div>
            <div class="form-item">
                <label for="jeudi">Jeudi:</label>
                <input type="hidden" name="JEUDI" value="false">
                <input type="checkbox" name="JEUDI" id="jeudi" value="1">
            </div>
            <div class="form-item">
                <label for="vendredi">Vendredi:</label>
                <input type="hidden" name="VENDREDI" value="false">
                <input type="checkbox" name="VENDREDI" id="vendredi" value="1">
            </div>
            <div class="form-item">
                <label for="samedi">Samedi:</label>
                <input type="hidden" name="SAMEDI" value="false">
                <input type="checkbox" name="SAMEDI" id="samedi" value="1">
            </div>
            <div class="form-item">
                <label for="dimanche">Dimanche:</label>
                <input type="hidden" name="DIMANCHE" value="false">
                <input type="checkbox" name="DIMANCHE" id="dimanche" value="1">
            </div>
            <div class="form-item">
                <label for="exception">Exception:</label>
                <textarea name="EXCEPTIONS" id="exception" placeholder="Ex: 2025-04-20 INCLUS"></textarea>
            </div>
            <div class="error">
                <span>
                    <?php 
                        if (_isset_key($_GET, 'error')) {
                            switch ($_GET['error']) {
                                case 'failed_service_creation':
                                    echo "Erreur lors de la création du service.";
                                    break;
                                case 'failed_exception_creation':
                                    echo "Erreur lors de la création de l'exception.";
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