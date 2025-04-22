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
    <title>Temps Moyen d'arrêt</title>
    <link rel="stylesheet" href="./assets/q2.css">
    <?php 
        include_once './tools/_head.php';
    ?>
</head>
<body>
    <form class="header" method="GET"></form>
    <main>
        <table>
            <thead>
                <tr>
                    <th>
                        <span>Itinéraire ID</span>
                    </th>
                    <th>
                        <span>Trajet ID</span>
                    </th>
                    <th>
                        <span>Temps Moyen d'arrêt</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $item = new Trajet();
                    $result = $item->findAvgTime();

                    foreach ($result as $item) {
                        ?>
                            <tr>
                                <td> <?= $item->getItineraireId() ?> </td>
                                <td> <?= $item->getTrajetId() ?> </td>
                                <td> <?= $item->getAvgStopTime() ?> </td>
                            </tr>
                        <?php
                    }
                ?>
            </tbody>
        </table>
    </main>
</body>
</html>