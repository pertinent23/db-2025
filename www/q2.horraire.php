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
    <title>Recherche Horraire</title>
    <link rel="stylesheet" href="./assets/q2.css">
    <?php 
        include_once './tools/_head.php';
    ?>
</head>
<body>
    <form class="header" method="GET">
        <div class="container">
            <table>
                <thead>
                    <tr>
                        <th>
                            <span>Trajet ID</span>
                        </th>
                        <th>
                            <span>Arret ID</span>
                        </th>
                        <th>
                            <span>Heure D'arrivée</span>
                        </th>
                        <th>
                            <span>Heure de depart</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="number" name="ITINERAIRE_ID" placeholder="Az:" class="seach-field">
                        </td>
                        <td>
                            <input type="number" name="ARRET_ID" placeholder="Az:" class="seach-field">
                        </td>
                        <td>
                            <input type="time" name="HEURE_ARRIVEE" placeholder="Az:" class="seach-field">
                        </td>
                        <td>
                            <input type="time" name="HEURE_DEPART" placeholder="Az:" class="seach-field">
                        </td>
                    </tr>
                </tbody>
            </table>
            <div class="button-container">
                <button type="submit">Rechercher</button>
            </div>
        </div>
    </form>
    <main>
        <table>
            <thead>
                <tr>
                    <th>
                        <span>Id</span>
                    </th>
                    <th>
                        <span>Itinéraire ID</span>
                    </th>
                    <th>
                        <span>Arret Id</span>
                    </th>
                    <th>
                        <span>Heure d'arrivée</span>
                    </th>
                    <th>
                        <span>Heure de départ</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $filters = _create_filters($_GET, ['action']);
                    $item = new Horraire();
                    $result = $item->search($filters);

                    foreach ($result as $item) {
                        if($item instanceof Horraire) {
                            ?>
                                <tr>
                                    <td> <?= $item->getTrajetId() ?> </td>
                                    <td> <?= $item->getItineraireId() ?> </td>
                                    <td> <?= $item->getArretId() ?> </td>
                                    <td> <?= $item->getHeureArrivee() ?> </td>
                                    <td> <?= $item->getHeureDepart() ?> </td>
                                </tr>
                            <?php
                        }
                    }
                ?>
            </tbody>
        </table>
    </main>
</body>
</html>