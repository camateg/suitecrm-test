<?php
    session_start();

    class SuiteCRM
    {
	    public $url = "http://www.matt.ridgeleap.com/suite/service/v4_1/rest.php";
	    public $portal_user = "133264aa-702c-c906-f6e0-58be0bbe4237";
	    public $session = '';

	    private $username = "admin";
	    private $password = "SOMEPASS";

	    //function to make cURL request
	    function call($method, $parameters)
	    {
		ob_start();
		$curl_request = curl_init();

		curl_setopt($curl_request, CURLOPT_URL, $this->url);
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

	function kick() {
		        header( 'Location: ./?error=Session%20Expired' );
	}

	function login() {
	    $login_parameters = array(
		 "user_auth" => array(
		      "user_name" => $this->username,
		      "password" => md5($this->password),
		      "version" => "1"
		 ),
		 "application_name" => "mCasePortal",
		 "name_value_list" => array(),
	    );

	    $login_result = $this->call("login", $login_parameters);
	    $this->session = $login_result->id;
	    return $login_result->id;
	}
	function ensure_portal() {
		return $this->portal_login($_SESSION['user_name'], $_SESSION['user_pass']);
	}
	function portal_login($user, $password) {
	    $this->login();
	    $gel_parameters = array(
		 "session" => $this->session,
		 "module_name" => "Contacts",
  		 "query" => " contacts_cstm.portal_user_c = '" . $user . "' ",
		 "order_by" => "",
		 "offset" => 0,
		 "select_fields" => array(),
		 "link_name_to_fields_array" => array("portal_user_c", "portal_md5_c"),
		 "max_results" => 1,
		 "deleted" => 0,
		 "favorites" => false,
	    );
	    $gel_results = $this->call("get_entry_list", $gel_parameters);

	    $entry = $gel_results->entry_list[0];
   	      $id = $entry->name_value_list->id->value;
	      $name = $entry->name_value_list->name->value;
	      $portal_md5 = $entry->name_value_list->portal_md5_c->value;
	      $portal_user = $entry->name_value_list->portal_user_c->value;
	    if ($portal_md5 == md5($password)) {
		return $id; 
	    } else {
	      return -1;
	    }
	}

	function get_accounts($user_id) {
	    if ($this->ensure_portal() != -1) {
		    $this->login();
			$ger_params = array(
			 'session' => $this->session,
			 'module_name' => 'Contacts',
			 'module_id' => $user_id,
			 'link_field_name' => 'accounts',
			 'related_module_query' => '',
			 'related_fields' => array(
			    'id',
			    'name',
			 ),
			 'related_module_link_name_to_fields_array' => array(
			 ),
			 'deleted'=> '0',
			 'order_by' => '',
			 'offset' => 0,
			 'limit' => 5,
		    );

		    $ger_result = $this->call("get_relationships", $ger_params);
		    $accounts = [];

		    foreach($ger_result->entry_list as $entry) {
		    	$id = $entry->name_value_list->id->value;
	      		$name = $entry->name_value_list->name->value;
	      		$accounts[]= array("id" => $id, "name" => $name);
	    	}
	    	return $accounts;
	    } else {
	      $this->kick();
	    }
	}

	function cases_by_account($account_id) {
	    $this->login();
		if ($this->ensure_portal() != -1) {
			$gel_parameters = array(
				"session" => $this->session,
				"module_name" => "Cases",
				"query" => " cases.account_id = '" . $account_id . "' ",
				"order_by" => " cases.case_number DESC ",
				"offset" => 0,
				"select_fields" => array(),
				"link_name_to_fields_array" => array(),
				"max_results" => 100,
				"deleted" => 0,
				"favorites" => false,
		    );

		    $gel_results = $this->call("get_entry_list", $gel_parameters);

		    $cases = [];

		    foreach($gel_results->entry_list as $entry) {
		      $id = $entry->name_value_list->id->value;
		      $name = $entry->name_value_list->name->value;
		      $number = $entry->name_value_list->case_number->value;
		      $cases[]= array("id" => $id, "name" => $name, "number" => $number);
		    }

		    return $cases;
		} else {
			$this->kick();	
		}
	}

	function case_detail($case_id) {
		if ($this->ensure_portal() != -1) {
			$this->login();
			    $ge_parameters = array(
					"session" => $this->session,
					"module_name" => "Cases",
					"id" => $case_id,
					"select_fields" => array(),
					'link_name_to_fields_array' => [],
			    );

		    $ge_result = $this->call("get_entry", $ge_parameters);

		    $case_name = $ge_result->entry_list[0]->name_value_list->name->value;
		    $case_number = $ge_result->entry_list[0]->name_value_list->case_number->value;
		    $case_description = $ge_result->entry_list[0]->name_value_list->description->value;

		    $cid = $case_id;

		    $case_info = array("case_id" => $cid, "case_name" => $case_name, "case_number" => $case_number, "case_description" => $case_description);
				return $case_info;
			} else {
				$this->kick();
			}
		}
	function get_documents($case_id) {
		if ($this->ensure_portal() != -1) {
			$this->login();
			$gr_parameters = array(
				"session" => $this->session,
				"module_name" => "Cases",
				"module_id" => $case_id,
				"link_field_name" => "documents",
				"related_module_query" => "",
				"related_fields" => array('id', 'name'),
				"related_module_link_name_to_fields_array" => array(),
				"deleted" => 0,
				"order_by" => "",
				"offset" => 0,
				"limit" => 10,
		     );

	    	$grr = $this->call("get_relationships", $gr_parameters);

			$documents = [];

			foreach($grr->entry_list as $entry) {
				$id = $entry->id;
				$document_name = $entry->name_value_list->name->value;
	       		$documents[]= array("id" => $id, "document_name" => $document_name);
		    } 
			   return $documents;
			} else {
				return -1;
			}
	}

	function get_notes($case_id) {
 
		if ($this->ensure_portal() != -1) {
			$this->login();
			    $gel_parameters = array(
			      "session" => $this->session,
			      "module_name" => "AOP_Case_Updates",
			      "query" => " (aop_case_updates.internal = 0 OR aop_case_updates.internal IS NULL) AND aop_case_updates.case_id = '" . $case_id . "' ",
			      "order_by" => "aop_case_updates.date_entered DESC",
			      "offset" => "",
			      "select_fields" => array(),
			      "link_name_to_fields_array" => [],
			      "max_results" => 40,
			      "deleted" => 0,
			      "favorites" => false,
			    );

			    $gel_results = $this->call("get_entry_list", $gel_parameters);

			    $notes = [];

			    foreach($gel_results->entry_list as $entry) {
					$id = $entry->name_value_list->id->value;
					$name = $entry->name_value_list->name->value;
		   			$desc = $entry->name_value_list->description->value;
		      		$date_entered = $entry->name_value_list->date_entered->value;
		      		$assigned_user_id = $entry->name_value_list->assigned_user_id->value;
				if ($assigned_user_id == $this->portal_user) {
			 		$portal = 1;
		    	} else {
			 		$portal = 0;
		      	}
		      	$notes[] = array("id" => $id, "name" => $name, "description" => $desc, "date" => $date_entered, "portal" => $portal);
		    };
				return $notes;
			} else {
				return -1;
			}
	}
	function add_case($title, $body, $account) {
		$this->login();
		if ($this->ensure_portal() != -1) {
		    $set_entry_parameters = array(
			 "session" => $this->session,
			 "module_name" => "Cases",
			 "name_value_list" => array(
			      array("name" => "name", "value" => $title),
			      array("name" => "description", "value" => $body),
			      array("name" => "account_id", "value" => $account),
			 ),
		    );

		    $set_entry_result = $this->call("set_entry", $set_entry_parameters);

		    $cid = $set_entry_result->id;

		    $get_entry_parameters = array(
				"session" => $this->session,
				"module_name" => "Cases",
				"id" => $cid,
				"select_fields" => array(
				    "id",
				    "name",
				    "case_number",
				),
					'link_name_to_fields_array' => [],
			);

		    $get_entry_result = $this->call("get_entry", $get_entry_parameters);
		    $case_name = $get_entry_result->entry_list[0]->name_value_list->name->value;
		    $case_number = $get_entry_result->entry_list[0]->name_value_list->case_number->value;

		    $cases = array('id' => $cid, 'name' => $case_name, 'number' => $case_number);
		    return $cases;
		} else {
			return -1;
		}
	}

	function add_note($name, $case_id) {
		$this->login();
		if ($this->ensure_portal() != -1) {
	    	$set_entry_parameters = array(
		 		"session" => $this->session,
		 		"module_name" => "AOP_Case_Updates",
				"name_value_list" => array(
		      		array("name" => "name", "value" => $name),
		      		array("name" => "description", "value" => $name),
		      		array("name" => "case_id", "value" => $case_id),
		      		array("name" => "assigned_user_id", "value" => $this->portal_user),
		 		),
	    	);

	    	$set_entry_result = $this->call("set_entry", $set_entry_parameters);
	    	return $set_entry_result->id;
				} else {
			return -1;
			}
	}

	function upload($case_id, $file_name, $tmp_name) {
		$this->login();
			if ($this->ensure_portal() != -1) {
				  $se_params = array(
				    "session" => $this->session,
				    "module" => "Documents",
		    		"name_value_list" => array(
		      			array("name" => "document_name", "value" => $file_name),
		    		),
		  	);

		  $se_results = $this->call("set_entry", $se_params);

		  $did = $se_results->id;

		  $sdr_params = array(
		    "session" => $this->session,
		    "document_revision" => array(
		      "id" => $did,
		      "revision" => "1",
		      "filename" => $file_name,
		      "file" => base64_encode(file_get_contents($tmp_name)),
		    ),
		  );

		  $sdr_result = $this->call("set_document_revision", $sdr_params);

		  $rid = $sdr_result->id;

		  $sr_params = array(
		    "session" => $this->session,
		    "module_name" => "Documents",
		    "module_id" => $did,
		    "link_field_name" => "cases",
		    "related_ids" => array($case_id),
		    "name_value_list" => array(),
		    "delete" => 0,
		  );

		$sr_result = $this->call("set_relationship", $sr_params);
			return $sr_result;
		} else {
			return -1;
		}
	}

        function download($doc_id) {
			$this->login();
			if ($this->ensure_portal() != -1) {
			     $ge_parameters = array(
					"session" => $this->session,
					"module_name" => "Documents",
					"id" => $doc_id,
					"select_fields" => array("document_revision_id"),
					'link_name_to_fields_array' => [],
	    		);

			    $get_entry_results = $this->call("get_entry", $ge_parameters);

			    $rev_id = $get_entry_results->entry_list[0]->name_value_list->document_revision_id->value;

	    		$get_doc_parameters = array(
					"session" => $this->session,
					"id" => $rev_id,
			    );

			    $get_doc_result = $this->call("get_document_revision", $get_doc_parameters);

			    $file_name = $get_doc_result->document_revision->filename;
			    $file = $get_doc_result->document_revision->file;

	   			return Array('file' => $file, 'file_name' => $file_name);
			} else {
				$this->kick();
			}
	}
    }
?>
