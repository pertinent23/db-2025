<?php 
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
                $val = trim(htmlspecialchars($value));
                if ($val)
                    $filters[$key] = $val;
            }
        }

        return $filters;
    }
?>