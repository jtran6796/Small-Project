<?php 
    session_start();

    if (!isset($_SESSION["username"])){
        echo "";
    }
    else{
        echo "Logged in as {$_SESSION["username"]}";
    }
?>