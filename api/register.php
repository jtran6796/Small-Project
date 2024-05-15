<?php
    include("../config/database.php");
    include("../config/utils.php");
    session_start();

    // Checks if the provided username is available
    function check_username_availability($conn, $username){
        $q = "SELECT * FROM users WHERE username = '{$username}'";
        $res = mysqli_query($conn, $q);
        return mysqli_num_rows($res) ? false : true;
    }

    // Open the connection
    $conn = connect();
    // Receive data as an associative array
    $data = json_decode(file_get_contents('php://input'), true);
    // Hardcoded array of required values for registration
    $keys = ["firstname", "lastname", "username", "password"];

    // Exit if any required values are missing
    check_required_values($keys, $data);

    // All required fields were provided, save them as variables
    $firstname = $data["firstname"];
    $lastname = $data["lastname"];
    $username = $data["username"];
    $password = $data["password"];

    // Exit if the requested username is taken
    if (!check_username_availability($conn, $username)){
        echo "The provided username is already taken. Please try again.";
        http_response_code(400);
        exit(1);
    }

    // Yay! We passed all of the checks! Register the user
    $q = "INSERT INTO Users (firstname, lastname, username, password) 
    VALUES ('{$data["firstname"]}', '{$data["lastname"]}', 
    '{$data["username"]}', '{$data["password"]}');";
    mysqli_query($conn, $q);

    // Get all data about the new user
    $q = "SELECT * FROM users WHERE username = '{$username}'";
    $row = mysqli_fetch_assoc(mysqli_query($conn, $q));

    mysqli_close($conn);

    // Reset session and set variables
    $_SESSION["username"] = $row["Username"];
    $_SESSION["userid"] = $row["UserId"];
    echo "Registered as {$_SESSION["username"]}";
?>