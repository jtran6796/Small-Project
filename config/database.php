<?php
    define("SERVER", "localhost");
    define("USER", "TheBeast");
    define("PASS", "cop4331iscool");
    define("DB", "db");

    // $failResponse is a JSON object that's sent as a response if the database connection fails
    // It is assumed that $failResponse holds the http response code using the key "response_code"
    // The other parameters should not need to be modified from their defaults
    function connect($failResponse, $server=SERVER, $user=USER, $pass=PASS, $db=DB){
        $conn = "";
        try{
            $conn = mysqli_connect(
                $server,
                $user,
                $pass,
                $db
            );
        }
        catch(mysqli_sql_exception){
            echo json_encode($failResponse);
            http_response_code($failResponse["responseCode"]);
            exit(1);
        }
        return $conn;
    }
?>