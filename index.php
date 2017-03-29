<?php require_once('config.php'); ?>

<title><?php echo TITLE; ?></title>

<script
  src="//code.jquery.com/jquery-3.1.1.min.js"
  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
  crossorigin="anonymous"></script>

<meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<div class="well" style="margin-top: 40px;">
<div style="text-align: center;">
<img src="suite_icon.png">

<h2><?php echo TITLE; ?></h2>
</div>
<form action="login.php" method="POST">
  <div class="form-group">
    <label for="userName">Username</label>
    <input name="user" type="text" class="form-control" id="userName" placeholder="Username">
  </div>
  <div class="form-group">
    <label for="userPassword">Password</label>
    <input name="password" type="password" class="form-control" id="userPassword" placeholder="Password">
  </div>
  <button type="submit" class="btn btn-default">Submit</button>
  <div style="color: red;" class="error"><?php echo $_GET['error']; ?>
</form>
</div>
