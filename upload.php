<meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<?php
  require_once('functions.php');
  $suite = new SuiteCRM();
  $upload = $suite->upload($_POST['case_id'], $_FILES['file']['name'], $_FILES['file']['tmp_name']);
?>
  <a class="btn btn-default" href="case_detail.php?case_id=<?php echo $_POST['case_id'] ?>">Document submitted as <?php echo $_FILES['file']['name']; ?></a>
