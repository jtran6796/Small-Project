<?php
    include("../config/database.php");
    include("../config/utils.php");
    session_start();

    // Connect to the database and acquire necessary data
    $conn = connect();
    $data = json_decode(file_get_contents("php://input"), true);
    $keys = ["firstname", "lastname", "username", "password"];

    // Check if the required data is present
    check_required_values($keys, $data);
    // Create local variable copies
    $firstname = $data["firstname"];
    $lastname = $data["lastname"];
    $username = $data["username"];
    $password = $data["password"];

    // Query to see if the username is already taken
    $q = "SELECT * FROM users WHERE username = '{$username}'";
    $res = mysqli_query($conn, $q);
    // Deny the request if the username is taken
    if (mysqli_num_rows($res) !== 0){
        echo "The requested username is not available";
        http_response_code(400);
        exit(1);
    }

    // Insert the new registered user into the database
    $q = "INSERT INTO users (firstname, lastname, username, password)
    VALUES ('{$firstname}', '{$lastname}', '{$username}', '{$password}')";
    mysqli_query($conn, $q);
    mysqli_close($conn);

    echo "Registration successful";
    http_response_code(200);
?>