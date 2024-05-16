<?php
    // Global variable for base url. Will be changed when uploaded to DigitalOcean
    $GLOBALS['BASEURL'] = "localhost/website";
    // Checks if all keys in $keys are present in the associative array $array
    function all_keys_exist($keys, $array){
        $all_exist = 1;
        $len = count($keys);
        for ($i = 0; $i < $len; $i++){
            $all_exist = $all_exist && array_key_exists($keys[$i], $array);
        }
        return $all_exist ? true : false;
    }

    // Checks if required keys are present in the decoded json $data
    // Exit if any required values are missing
    function check_required_values($keys, $data){
        if (!all_keys_exist($keys, $data)){
            echo "One or more required values is not present.";
            http_response_code(400);
            exit(1);
        }
    }
?>