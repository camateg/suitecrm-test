<meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">

<?php
  require_once('functions.php');
 
  $cid = $_POST['case_id'];
 
  $se_params = array(
    "session" => $session_id,
    "module" => "Documents",
    "name_value_list" => array(
      array("name" => "document_name", "value" => $_FILES['file']['name']),
    ),
  );

  $se_results = call("set_entry", $se_params, $url);

  $did = $se_results->id;

  $sdr_params = array(
    "session" => $session_id,
    "document_revision" => array(
      "id" => $did,
      "revision" => "1",
      "filename" => $_FILES['file']['name'],
      "file" => base64_encode(file_get_contents($_FILES['file']['tmp_name'])),
    ),
  );

  $sdr_result = call("set_document_revision", $sdr_params, $url);

  $rid = $sdr_result->id;

  $sr_params = array(
    "session" => $session_id,
    "module_name" => "Documents",
    "module_id" => $did,
    "link_field_name" => "cases",
    "related_ids" => array($cid),
    "name_value_list" => array(),
    "delete" => 0,
  );

  $sr_result = call("set_relationship", $sr_params, $url);
?>
  <a href="case_detail.php?case_id=<?php echo $cid ?>">Document submitted as <?php echo $_FILES['file']['name']; ?></a>
