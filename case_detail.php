<?php

    require_once('functions.php');

    $ge_parameters = array(
        "session" => $session_id,
        "module_name" => "Cases",
        "id" => $_GET['case_id'],
        "select_fields" => array(),
        'link_name_to_fields_array' => [],
    );

    $ge_result = call("get_entry", $ge_parameters, $url);
   
    //print_r($ge_result);
    print_r($get_entry_result->entry_list[0]); 
    $case_name = $ge_result->entry_list[0]->name_value_list->name->value;
    $case_number = $ge_result->entry_list[0]->name_value_list->case_number->value;
    $case_description = $ge_result->entry_list[0]->name_value_list->description->value;

    $gel_parameters = array(
      "session" => $session_id,
      "module_name" => "AOP_Case_Updates",
      "query" => " aop_case_updates.case_id = '" . $_GET['case_id'] . "' ",
      "order_by" => "",
      "offset" => "",
      "select_fields" => array(),
      "link_name_to_fields_array" => [],
      "max_results" => 10,
      "deleted" => 0,
      "favorites" => false,
    );

    $gel_results = call("get_entry_list", $gel_parameters, $url);

    $notes = [];

    foreach($gel_results->entry_list as $entry) {
      $id = $entry->name_value_list->id->value;
      $name = $entry->name_value_list->name->value;
      $desc = $entry->name_value_list->description->value;
      $notes[] = array("id" => $id, "name" => $name, "description" => $desc);
    };

    $cid = $_GET['case_id'];

    $case_info = array("case_name" => $case_name, "case_number" => $case_number, "case_description" => $case_description, "notes" => $notes);

?>
<head>
<script
  src="https://code.jquery.com/jquery-3.1.1.min.js"
  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
  crossorigin="anonymous"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script>
$(document).ready(function() {
  $('#add_note').on('submit', function(e) {
    e.preventDefault();
    payload = {
        'case_id': "<?php echo $cid ?>",
        'name': $('#note_content').val()
    };
    $.post('add_note.php',
      payload).done(
      function(data,status) {
        console.log(data);
      }
    );      
  });
});


</script>

</head>
<body>
    <h2><?php echo $case_name ?> #<?php echo $case_number ?></h2>
    <h3><?php echo $case_description ?></h3>
    <!-- header('Content-type: application/json');
    echo json_encode($case_info); -->
    <?php
      foreach($notes as $note) {
        echo '<div class="well">' . $note['description'] . '</div>';
      };
    ?>
    <form method="POST" id="add_note">
      <input id="note_content"></input>
      <input type="submit" value="submit note"></input>
    </form> 
</body>
