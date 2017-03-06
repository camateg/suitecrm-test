<meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">

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
   
    $case_name = $ge_result->entry_list[0]->name_value_list->name->value;
    $case_number = $ge_result->entry_list[0]->name_value_list->case_number->value;
    $case_description = $ge_result->entry_list[0]->name_value_list->description->value;
    
    $cid = $_GET['case_id'];

    $case_info = array("case_name" => $case_name, "case_number" => $case_number, "case_description" => $case_description);

?>
<head>
<script
  src="//code.jquery.com/jquery-3.1.1.min.js"
  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
  crossorigin="anonymous"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script>
$(document).ready(function() {
  refreshNotes();
  $('#add_note').on('submit', function(e) {
    e.preventDefault();
    payload = {
        'case_id': "<?php echo $cid ?>",
        'name': $('#note_content').val()
    };
    $.post('add_note.php',
      payload).done(
      function(data,status) {
        refreshNotes();
        $('#note_content').val('');
      }
    );      
  });
});

function refreshNotes() {
  var case_id = "<?php echo $cid ?>";

  $('#notes').html('');

  $.getJSON('get_notes.php?case_id=' + case_id, function(ret) {
    ret.forEach(function(note) {
     console.log(note['portal']);
     var portal_style = 'color: red;';
     if (note['portal'] == 1) {
        portal_style = 'color: black;';
     } 
     var prettyDate = new Date(note['date']);
     $('#notes').append('<div style="margin-bottom: 10px; width: 100%" class="well">' + prettyDate.toString() + '<hr /><div style="' + portal_style + '">' + note['name'] + '</div></div><br />');
    }); 
  });
  $.getJSON('get_documents.php?case_id=' + case_id, function(ret) {
    $('#documents').html('');
    ret.forEach(function(doc) {
     var btn_class = 'btn btn-default';
     $('#documents').append('<div style="margin-bottom: 10px; width: 100%" class="' + btn_class + '"><a href="download.php?document_id=' + doc['id'] + '">' + doc['document_name'] + '</a></div><br />');
    });
  });

};

</script>

</head>
<body>
    <a class="btn glyphicon glyphicon-home btn-primary" href="show_cases.php"></a>
    <a class="btn btn-primary" href="logout.php">Logout</a>
    <br />
    <h3 class="well">#<?php echo $case_number ?> - <?php echo $case_name ?><br /><br />
    <?php echo $case_description ?></h3>
    <br />
    <div class="well">
    <div id="notes"></div>
    <form action="" method="POST" id="add_note">
      <div class="form-group">
      <label for="note_content">Note Text</label>
        <textarea id="note_content" class="form-control" placeholder="Type your note here." rows="3"></textarea>
      <br /><input class="btn btn-success" type="submit" value="submit note"></input>
      </div>
  </form>
  <div id="documents"></div>
</body>
