<?php 
    session_start();
    if (!isset($_SESSION["username"])){
        echo "None";
    }
    else {
        echo $_SESSION["username"];
    }
?>