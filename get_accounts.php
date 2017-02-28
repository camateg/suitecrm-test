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

    $gel_parameters = array(
         "session" => $session_id,
         "module_name" => "Accounts",
         "query" => "",
         "order_by" => "",
         "offset" => 0,
         "select_fields" => array(),
         "link_names_to_field_array" => array(),
         "max_results" => 10,
         "deleted" => 0,
         "favorites" => false,
    );

    $gel_results = call("get_entry_list", $gel_parameters, $url);

    $accounts = [];

    foreach($gel_results->entry_list as $entry) {
      $id = $entry->name_value_list->id->value;
      $name = $entry->name_value_list->name->value;
      $accounts[]= array("id" => $id, "name" => $name);
    }

    header('Content-type: application/json');
    echo json_encode($accounts);
