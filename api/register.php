<?php
    include("../config/database.php");
    include("../config/utils.php");
    session_start();

    header("Content-Type: application/json");

    // Function for forming registration response
    function compose_response($ac, $msg, $rescode){
        $res = [
            "accountCreated" => $ac, // boolean
            "message" => $msg, // string containing success or reason for failure
            "response_code" => $rescode // http response code
        ];
        return $res;
    }

    // Pass the response/code to emit if the connection fails
    $failResponse = compose_response(false, "There was a server-side issue with your request", 500);
    $conn = connect($failResponse);

    // Acquire necessary data
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
    $q = "SELECT * FROM Users WHERE username = '{$username}'";
    $res = mysqli_query($conn, $q);
    // Deny the request if the username is taken
    if (mysqli_num_rows($res) !== 0){
        echo json_encode(compose_response(
            false,
            "The requested username is not available",
            400
        ));
        http_response_code(400);
        exit(1);
    }

    // Insert the new registered user into the database
    $q = "INSERT INTO Users (firstname, lastname, username, password)
    VALUES ('{$firstname}', '{$lastname}', '{$username}', '{$password}')";
    mysqli_query($conn, $q);
    mysqli_close($conn);

    echo json_encode(compose_response(
        true,
        "Registration successful",
        200
    ));
    http_response_code(200);
?>