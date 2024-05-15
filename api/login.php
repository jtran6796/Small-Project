<?php
    include("../config/database.php");
    include("../config/utils.php");
    session_start();

    $conn = connect();
    // Receive data as an associative array
    $data = json_decode(file_get_contents("php://input"), true);
    // Hardcoded array of required values for login
    $keys = ["username", "password"];

    // Exit if any required values are missing
    check_required_values($keys, $data);

    // Retrieve fields from data
    $username = $data["username"];
    $password = $data["password"];

    // Find the requested account
    $q = "SELECT * FROM users WHERE username = '{$username}'";
    $res = mysqli_query($conn, $q);
    mysqli_close($conn);

    // Check if the user exists
    if (mysqli_num_rows($res) == 0){
        echo "The requested user does not exist.";
        http_response_code(401);
        exit(1);
    }

    // Fetch the row
    $row = mysqli_fetch_assoc($res);
    
    // Check that the password matches
    $correct_pw = $row["Password"];
    // Incorrect password case
    if ($correct_pw != $password){
        echo "The provided password was incorrect. Please try again.";
        http_response_code(401);
        exit(1);
    }

    // Correct password case
    echo "Logged in successfully.";
    http_response_code(200);

    // Change session variables to the logged in user
    $_SESSION["username"] = $row["Username"];
    $_SESSION["userid"] = $row["UserId"];
?>