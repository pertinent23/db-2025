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
    <title>Recherche Exception</title>
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
                            <span>Service ID</span>
                        </th>
                        <th>
                            <span>Date</span>
                        </th>
                        <th>
                            <span>Code</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="number" name="SERVICE_ID" placeholder="Az:" class="seach-field">
                        </td>
                        <td>
                            <input type="date" name="DATE" placeholder="Az:" class="seach-field">
                        </td>
                        <td>
                            <input type="number" name="CODE" placeholder="Az:" class="seach-field">
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
                        <span>Service ID</span>
                    </th>
                    <th>
                        <span>Date</span>
                    </th>
                    <th>
                        <span>Code</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $filters = _create_filters($_GET, ['action']);
                    $item = new ExceptionService();
                    $result = $item->search($filters);

                    foreach ($result as $item) {
                        if($item instanceof ExceptionService) {
                            ?>
                                <tr>
                                    <td> <?= $item->getServiceId() ?> </td>
                                    <td> <?= $item->getDate() ?> </td>
                                    <td> <?= $item->getserviceCode() ?> </td>
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