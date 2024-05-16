<?php 
    session_start();
    
    header("Content-Type: application/json");

    function compose_response($sessionActive, $fname, $lname, $user, $uid, $rescode){
        $res = [
            "sessionActive" => $sessionActive,
            "firstname" => $fname,
            "lastname" => $lname,
            "username" => $user,
            "userid" => $uid,
            "response_code" => $rescode
        ];
        return $res;
    }

    // If a user is logged in this field will be set
    if (!isset($_SESSION["username"])){
        echo json_encode(compose_response(
            false, NULL, NULL, NULL, NULL, 404
        ));
        http_response_code(404);
    }
    else{
        echo json_encode(compose_response(
            true, $_SESSION["firstname"], $_SESSION["lastname"], $_SESSION["username"], 
            $_SESSION["userid"], 200
        ));
        http_response_code(200);
    }
?>