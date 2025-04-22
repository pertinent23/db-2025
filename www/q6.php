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
    <title> Nombre de train </title>
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
                            <span>Nom</span>
                        </th>
                        <th>
                            <span>Numéro</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="text" name="NOM" placeholder="Az:" class="seach-field">
                        </td>
                        <td>
                            <input type="number" name="NUMERO" placeholder="Az:" class="seach-field">
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
                        <span>Arrêt ID</span>
                    </th>
                    <th>
                        <span>Nom</span>
                    </th>
                    <th>
                        <span>Service</span>
                    </th>
                    <th>
                        <span>Total arrivées</span>
                    </th>
                    <th>
                        <span>Total départs</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $nom = _isset_key($_GET, 'NOM') ? $_GET['NOM'] : '';
                    $numero = _isset_key($_GET, 'NUMERO') && trim($_GET['NUMERO']) ? intval($_GET['NUMERO']) : 0;
                    $item = new Arret();
                    $result = $item->search($nom, $numero);

                    foreach ($result as $item) {
                        ?>
                            <tr>
                                <td> <?= $item->getArretId() ?> </td>
                                <td> <?= $item->getArretNom() ?> </td>
                                <td> <?= $item->getServiceId() ?> </td>
                                <td> <?= $item->getTotalArrivees() ?> </td>
                                <td> <?= $item->getTotalDeparts() ?> </td>
                            </tr>
                        <?php
                    }
                ?>
            </tbody>
        </table>
    </main>
</body>
</html>