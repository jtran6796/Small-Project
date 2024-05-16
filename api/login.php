<?php
    include("../config/database.php");
    include("../config/utils.php");
    session_start();

    header("Content-Type: application/json");

    // Function for composing JSON responses
    function compose_response($success, $msg, $rescode){
        $res = [
            "loginSuccess" => $success,
            "message" => $msg,
            "response_code" => $rescode
        ];
        return $res;
    }

    // Pass the response/code to emit if the connection fails
    $failResponse = compose_response(false, "There was a server-side issue with your request", 500);
    $conn = connect($failResponse);

    // Acquire data and set required keys
    $data = json_decode(file_get_contents("php://input"), true);
    $keys = ["username", "password"];

    // Check for username and password
    check_required_values($keys, $data);
    // Save username and password as locals
    $username = $data["username"];
    $password = $data["password"];

    // Check if the provided username exists
    $q = "SELECT * FROM Users WHERE username = '{$username}'";
    $res = mysqli_query($conn, $q);
    mysqli_close($conn);
    
    // Respond if the username is not found
    if (mysqli_num_rows($res) === 0){
        echo json_encode(compose_response(
            false,
            "No account with the username {$username} exists",
            401
        ));
        http_response_code(401);
        exit(1);
    }

    $row = mysqli_fetch_assoc($res);
    
    // Check if the correct password matches the provided one
    if ($row["Password"] != $password){
        echo json_encode(compose_response(
            false,
            "The given username and password was incorrect",
            401
        ));
        http_response_code(401);
        exit(1);
    }

    // Login was successful, emit response and set session variables
    echo json_encode(compose_response(
        true,
        "Login successful",
        200
    ));
    http_response_code(200);

    // Set session variables
    $_SESSION["firstname"] = $row["FirstName"];
    $_SESSION["lastname"] = $row["LastName"];
    $_SESSION["username"] = $row["Username"];
    $_SESSION["userid"] = $row["UserId"];
?>