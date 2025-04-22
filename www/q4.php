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
    <title>Services Avec Exceptions</title>
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
                        <span>Service ID</span>
                    </th>
                    <th>
                        <span>Nom</span>
                    </th>
                    <th>
                        <span>Date</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $item = new Service();
                    $result = $item->findDateService();

                    foreach ($result as $item) {
                        ?>
                            <tr>
                                <td> <?= $item->getServiceId() ?> </td>
                                <td> <?= $item->getNom() ?> </td>
                                <td> <?= $item->getDate() ?> </td>
                            </tr>
                        <?php
                    }
                ?>
            </tbody>
        </table>
    </main>
</body>
</html>