<?php
    include("../config/database.php");
    include("../config/utils.php");
    session_start();

    header("Content-Type: application/json");

    function compose_response($updated, $msg, $rescode){
        $res = [
            "contactUpdated" => $updated,
            "message" => $msg,
            "responseCode" => $rescode
        ];
        return $res;
    }

    $data = json_decode(file_get_contents("php://input"), true);
    $keys = ["contactId"];

    // Check that the contactId is specified
    if (!all_keys_exist($keys, $data)){
        echo json_encode(compose_response(
            false, "The required field contactId was not provided", 400
        ));
        http_response_code(400);
        exit(1);
    }

    $contactid = $data["contactId"];

    // Find first name, last name, email, and phone if applicable
    $firstname = check_key_and_return("firstName", $data);
    $lastname = check_key_and_return("lastName", $data);
    $email = check_key_and_return("email", $data);
    $phone = check_key_and_return("phone", $data);

    // Flag will be 0 if no columns are updated. At least one column
    // must be updated for the row to be changed.
    $update_flag = 0;

    // Build query
    $q = "UPDATE Contacts SET ";
    if ($firstname != NULL){
        $q = $q."firstName = '{$firstname}',";
        $update_flag++;
    }
    if ($lastname != NULL){
        $q = $q."lastName = '{$lastname}',";
        $update_flag++;
    }
    if ($email != NULL){
        $q = $q."email = '{$email}',";
        $update_flag++;
    }
    if ($phone != NULL){
        $q = $q."phone = '{$phone}',";
        $update_flag++;
    }
    $q = rtrim($q, ",");
    $q = $q." WHERE contactId = '{$contactid}';";

    // Exit if no valid update could be performed
    if (!$update_flag){
        echo json_encode(compose_response(
            false, "Update not performed because no valid values were sent", 400
        ));
        http_response_code(400);
        exit(1);
    }

    // Open the connection
    $failResponse = compose_response(
        false, "There was a server-side issue with your request", 500);
    $conn = connect($failResponse);
    mysqli_query($conn, $q);
    mysqli_close($conn);

    echo json_encode(compose_response(
        true, "Contact updated successfully", 200
    ));
    http_response_code(200);
?>