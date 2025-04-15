<?php 
    require_once './_request.php';

    function _isset_keys($array, $keys) {
        foreach ($keys as $key) {
            if (!isset($array[$key])) {
                return false;
            }
        }
        return true;
    }

    function _isset_key($array, $key) {
        return isset($array[$key]);
    }

    function _create_filters($array, $exceptions = []) {
        $filters = [];

        foreach ($array as $key => $value) {
            if (!in_array($key, $exceptions)) {
                $filters[$key] = htmlspecialchars($value);
            }
        }

        return $filters;
    }

    switch ($_SERVER['REQUEST_METHOD']) {
        case 'GET':
            if (!_isset_key($_GET, 'action')) {
                echo json_encode(['error' => 'Missing action parameter']);
                break;
            }

            switch ($_GET['action']) {
                case 'search_agence':
                    $filters = _create_filters($_GET, ['action']);
                    $item = new Agence();
                    $result = $item->search($filters);
                    echo json_encode($result);
                    break;
                
                case 'search_horraire':
                    $filters = _create_filters($_GET, ['action']);
                    $item = new Horraire();
                    $result = $item->search($filters);
                    echo json_encode($result);
                    break;
                
                case 'search_exception':
                    $filters = _create_filters($_GET, ['action']);
                    $item = new ExceptionService();
                    $result = $item->search($filters);
                    echo json_encode($result);
                    break;

                default:
                    echo json_encode(['error' => 'Unknown action']);
            }


            break;
        
        case 'POST':
            break;

        default:
            echo json_encode(['error' => 'Invalid request method']);
    }
?>