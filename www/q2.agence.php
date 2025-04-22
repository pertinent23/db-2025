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
    <title>Recherche Agence</title>
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
                            <span>Url</span>
                        </th>
                        <th>
                            <span>Fuseau Horraire</span>
                        </th>
                        <th>
                            <span>Téléphone</span>
                        </th>
                        <th>
                            <span>Siège</span>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="text" name="NOM" placeholder="Az:" class="seach-field">
                        </td>
                        <td>
                            <input type="text" name="URL" placeholder="Az:" class="seach-field">
                        </td>
                        <td>
                            <input type="text" name="FUSEAU_HORAIRE" placeholder="Az:" class="seach-field">
                        </td>
                        <td>
                            <input type="text" name="TELEPHONE" placeholder="Az:" class="seach-field">
                        </td>
                        <td>
                            <input type="text" name="SIEGE" placeholder="Az:" class="seach-field">
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
                        <span>Nom</span>
                    </th>
                    <th>
                        <span>Url</span>
                    </th>
                    <th>
                        <span>Fuseau Horraire</span>
                    </th>
                    <th>
                        <span>Téléphone</span>
                    </th>
                    <th>
                        <span>Siège</span>
                    </th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $filters = _create_filters($_GET, ['action']);
                    $item = new Agence();
                    $result = $item->search($filters);

                    foreach ($result as $item) {
                        if($item instanceof Agence) {
                            ?>
                                <tr>
                                    <td> <?= $item->getID() ?> </td>
                                    <td> <?= $item->getNom() ?> </td>
                                    <td> <?= $item->getUrl() ?> </td>
                                    <td> <?= $item->getFuseau() ?> </td>
                                    <td> <?= $item->getTelephone() ?> </td>
                                    <td> <?= $item->getSiege() ?> </td>
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