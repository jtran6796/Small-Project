<?php
    define("SERVER", "localhost");
    define("USER", "TheBeast");
    define("PASS", "cop4331iscool");
    define("DB", "db");

    function connect($server=SERVER, $user=USER, $pass=PASS, $db=DB){
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
            echo "There was a server-side issue with your request.";
            http_response_code(500);
            exit(1);
        }
        return $conn;
    }
?>