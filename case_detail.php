<?php require_once('config.php'); ?>

<title><?php echo TITLE; ?></title>

<meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">

<?php

    require_once('functions.php');

    $suite = new SuiteCRM();
    $case_info = $suite->case_detail($_GET['case_id']);

?>
<head>
<script
  src="//code.jquery.com/jquery-3.1.1.min.js"
  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
  crossorigin="anonymous"></script>

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script>
$(document).ready(function() {
  refreshNotes();
  $('#add_note').on('submit', function(e) {
    e.preventDefault();
    payload = {
        'case_id': "<?php echo $case_info['case_id'] ?>",
        'name': $('#note_content').val()
    };
    $.post('note.php',
      payload).done(
      function(data,status) {
        refreshNotes();
        $('#note_content').val('');
      }
    );      
  });
});

function refreshNotes() {
  var case_id = "<?php echo $case_info['case_id'] ?>";

  $('#notes').html('');

  $.getJSON('service.php?action=get_notes&param=' + case_id, function(ret) {
    ret.forEach(function(note) {
     console.log(note['portal']);
     var portal_style = 'color: red;';
     if (note['portal'] == 1) {
        portal_style = 'color: <?php echo NOTE_LIST_FG; ?>';
     } 
     var prettyDate = new Date(note['date']);
     $('#notes').append('<div class="btn btn-default" style="background-color: <?php echo NOTE_LIST_BG ?>; width: 100%; margin-bottom: 2px;"><div style="' + portal_style + '"><p class="glyphicon glyphicon-comment"></p>  ' + prettyDate.toDateString() + '</div><p style="white-space: normal; ' + portal_style + '">  ' + note['name'] + '</p></div><br />');
    }); 
  });
  $.getJSON('service.php?action=get_documents&param=' + case_id, function(ret) {
    $('#documents').html('');
    ret.forEach(function(doc) {
     var btn_class = 'btn btn-default';
     $('#documents').append('<div style="color: <?php echo NOTE_LIST_FG ?>; background-color: <?php echo NOTE_LIST_BG ?>; text-align: left; margin-bottom: 10px; width: 100%" class="' + btn_class + '"><p class="glyphicon glyphicon-download-alt"></p>  <a style="color: <?php echo NOTE_LIST_FG ?>; href="download.php?document_id=' + doc['id'] + '">' + doc['document_name'] + '</a></div><br />');
    });
  });
};

</script>

</head>
<body>
    <div><a style="margin-left: 2px; margin-top: 2px;" class="btn glyphicon glyphicon-home btn-primary" href="show_cases.php"></a>
    <a style="margin-top: 4px;" class="btn btn-primary" href="logout.php">Logout</a>
    </div>
    <h3 class="well" style="background-color: <?php echo LOGIN_BG; ?>"><p class="glyphicon glyphicon-briefcase"></p> #<?php echo $case_info['case_number'] ?> - <?php echo $case_info['case_name'] ?><br /><br />
    <?php echo $case_info['case_description'] ?></h3>
    <br />
    <div class="well" style="background-color: <?php echo LOGIN_BG; ?>">
    <div id="notes"></div>
    <br />
    <form action="" method="POST" id="add_note">
      <div class="form-group">
      <p class="glyphicon glyphicon-pencil"></p>
      <label for="note_content">Write Note</label>
        <textarea id="note_content" class="form-control" placeholder="Type your note here." rows="3"></textarea>
      <br /><input style="width: 100%; background-color: <?php echo SUBMIT_BG; ?>" class="btn btn-success" type="submit" value="submit note"></input>
      </div>
  </form>
    <form action="upload.php" method="POST" id="add_upload" enctype="multipart/form-data">
       <div class="form-group">
       <p class="glyphicon glyphicon-cloud-upload"></p>
       <label for="upload_file">Upload File</label>
          <input style="width: 100%" class="btn btn-primary" type="file" name="file" id="file">
          <br />
          <input type="hidden" id="case_id" name="case_id" value="<?php echo $case_info['case_id'] ?>">
          <br />
          <input style="width: 100%; background-color: <?php echo SUBMIT_BG; ?>" type="submit" value="submit file" class="btn btn-success" name="submit">
       </div>
    </form>
    <div id="upload_success"></div>
  <div id="documents"></div>
</body>
