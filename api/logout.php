<?php
    include('../config/utils.php');
    // Access session
    session_start();
    header("Content-Type: application/json");
    // Delete session information
    session_destroy();
    // Check the session through session.php
    $url = $GLOBALS['BASEURL'].'/api/session.php';

    // cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_URL, $url);
    $sessionActive = json_decode(curl_exec($ch), true)["sessionActive"];

    $res = [
        "message" => "Logged out successfully.",
        "response_code" => 200,
    ];

    // Session deleted successfully
    if (!$sessionActive){
        echo json_encode($res);
        http_response_code(200);
    }
    // Session deletion failed
    else {
        $res["message"] = "Logout failed due to an internal server error. 
        Refresh this page to try again.";
        $res["response_code"] = 500;
        echo json_encode($res);
        http_response_code(500);
    }
?>