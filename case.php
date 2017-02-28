<?php

    $url = "http://localhost:8888/suite/service/v4_1/rest.php";
    $username = "admin";
    $password = "admin";

    //function to make cURL request
    function call($method, $parameters, $url)
    {
        ob_start();
        $curl_request = curl_init();

        curl_setopt($curl_request, CURLOPT_URL, $url);
        curl_setopt($curl_request, CURLOPT_POST, 1);
        curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl_request, CURLOPT_HEADER, 1);
        curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);

        $jsonEncodedData = json_encode($parameters);

        $post = array(
             "method" => $method,
             "input_type" => "JSON",
             "response_type" => "JSON",
             "rest_data" => $jsonEncodedData
        );

        curl_setopt($curl_request, CURLOPT_POSTFIELDS, $post);
        $result = curl_exec($curl_request);
        curl_close($curl_request);

        $result = explode("\r\n\r\n", $result, 2);
        $response = json_decode($result[1]);
        ob_end_flush();

        return $response;
    }

    //login --------------------------------------------- 

    $login_parameters = array(
         "user_auth" => array(
              "user_name" => $username,
              "password" => md5($password),
              "version" => "1"
         ),
         "application_name" => "mCasePortal",
         "name_value_list" => array(),
    );

    $login_result = call("login", $login_parameters, $url);

    $session_id = $login_result->id;

    $set_entry_parameters = array(
         "session" => $session_id,
         "module_name" => "Cases",
         "name_value_list" => array(
              array("name" => "name", "value" => $_POST['title']),
              array("name" => "description", "value" => $_POST['body']),
              array("name" => "account_id", "value" => $_POST['account']),
         ),
    );

    $set_entry_result = call("set_entry", $set_entry_parameters, $url);

    $cid = $set_entry_result->id;

    $get_entry_parameters = array(
        "session" => $session_id,
        "module_name" => "Cases",
        "id" => $cid,
        "select_fields" => array(
	    "id",
            "name",
            "case_number",
        ),
        'link_name_to_fields_array' => [],
    );

    $get_entry_result = call("get_entry", $get_entry_parameters, $url);
    
    $case_name = $get_entry_result->entry_list[0]->name_value_list->name->value;
    $case_number = $get_entry_result->entry_list[0]->name_value_list->case_number->value;

    $cases = array('name' => $case_name, 'number' => $case_number);

    header('Content-type: application/json');
    echo json_encode($cases);
