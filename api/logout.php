<?php
    include('../config/utils.php');
    // Access session
    session_start();
    header("Content-Type: application/json");
    // Delete session information
    session_destroy();

    // Start the session to see if session variables have been deleted
    session_start();
    // Session deleted successfully
    if (empty($_SESSION)){
        $res = [
            "message" => "Logged out successfully.",
            "responseCode" => 200,
        ];
        echo json_encode($res);
        http_response_code(200);
    }
    // Session deletion failed
    else {
        $res = [
            "message" => "Logout failed due to an internal server error. Refresh this page to try again.",
            "responseCode" => 500,
        ];
        echo json_encode($res);
        http_response_code(500);
    }
?>