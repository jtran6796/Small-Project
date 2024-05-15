<?php
    include("../config/database.php");
    include("../config/utils.php");
    session_start();

    // Connect to the database and acquire necessary data
    $conn = connect();
    $data = json_decode(file_get_contents("php://input"), true);
    $keys = ["username", "password"];

    // Check for username and password
    check_required_values($keys, $data);
    // Save username and password as locals
    $username = $data["username"];
    $password = $data["password"];

    // Check if the provided username exists
    $q = "SELECT * FROM users WHERE username = '{$username}'";
    $res = mysqli_query($conn, $q);
    mysqli_close($conn);
    
    // Respond if the username is not found
    if (mysqli_num_rows($res) === 0){
        echo "No account with the username {$username} exists.";
        http_response_code(401);
        exit(1);
    }

    $row = mysqli_fetch_assoc($res);
    
    // Check if the correct password matches the provided one
    if ($row["Password"] != $password){
        echo "The given username and password combination was incorrect.";
        http_response_code(401);
        exit(1);
    }

    echo "Login successful";
    http_response_code(200);

    // Set session variables
    $_SESSION["firstname"] = $row["FirstName"];
    $_SESSION["lastname"] = $row["LastName"];
    $_SESSION["username"] = $row["Username"];
    $_SESSION["userid"] = $row["UserId"];
?>