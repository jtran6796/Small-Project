<?php
    include("../config/database.php");
    include("../config/utils.php");
    session_start();

    header("Content-Type: application/json");

    // Response function for deleteContact
    function compose_response($conDeleted, $message, $rescode){
        $res = [
            "contactDeleted" => $conDeleted,
            "message" => $message,
            "responseCode" => $rescode
        ];
        return $res;
    }

    // Connect to the database
    $failResponse = compose_response(false, "There was a server-side issue with your request", 500);
    $conn = connect($failResponse);

    // Check if the json has a contactId
    $data = json_decode(file_get_contents("php://input"), true);
    $keys = ["contactId"];
    check_required_values($keys, $data);

    // Check that the contactId is an integer
    $contactid = $data["contactId"];
    if (gettype($contactid) !== "integer"){
        echo json_encode(compose_response(
            false, "The contactId field must be an integer", 400
        ));
        http_response_code(400);
        exit(1);
    };

    // Send the query to delete from contacts
    $q = "DELETE FROM Contacts where contactId = '{$contactid}'";
    mysqli_query($conn, $q);
    mysqli_close($conn);

    // Emit successful response
    echo json_encode(compose_response(
        true, "Contact deleted successfully", 200 
    ));
    http_response_code(200);
?>