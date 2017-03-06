<head>
<script
  src="//code.jquery.com/jquery-3.1.1.min.js"
  integrity="sha256-hVVnYaiADRTO2PzUGmuLJr8BLUSjGIZsDYGmIJLv2b8="
  crossorigin="anonymous"></script>

<meta name="viewport" content="width=device-width, initial-scale=1.0 user-scalable=no">

<!-- Latest compiled and minified CSS -->
<link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<script>

$(document).ready(function() {
  $('#account_name').html('Loading account...');
  $.getJSON('get_accounts.php', function(res) {
    $.each(res, function(v,t) {
      $('#account_name').html(t['name']);
      $('#account_id').html(t['id']);

      refreshHistory();
    });
  });

  function refreshHistory() {
    $('#case_history').html('');
    $.getJSON('cases_by_account.php?account_id=' + $('#account_id').html(), function(ret) {
       ret.forEach(function(ele) {
         id = ele['id'] || '';
         name = ele['name'] || '';
         number = ele['number'] || '';
         href = "case_detail.php?case_id=" +  id;
         $('#case_history').append('<a style="width: 100%; margin-bottom: 10px;" width="300px" class="btn btn-default case_link" id="' + id + '" href="' + href + '">' + "#" + number + ' - ' + name + '</a><br />');
       });
    });

  };


  $('#case_form').on('submit', function(e) {
    e.preventDefault();

    $('#case_number').fadeOut(300); 

    payload = {
        'account': $('#account_id').html(),
        'title': $('#title').val(),
        'body': $('#text').val()
    };

    $.post('case.php',
      payload).done(
      function(data,status) {
        refreshHistory();
	$('#case_number').html(data['name'] + " was submitted as #" + data['number']).fadeIn(1000).fadeOut(5000);
        $('#title').val('');
        $('#text').val('');
      }
    );
  });
});       
</script>
</head>
<body>
<a class="btn btn-primary" href="logout.php">Logout</a>
<div class="well">
<form class="form" id="case_form" method="POST" action="case.php">
  <div class="form-group">
    <label for="accounts">Account</label>
      <div id="account_name"></div>
      <div style="display: none;" id="account_id"></div>
    <br />
    <label for="title">Title</label>
    <input placeholder="Case title." id="title" name="title" class="form-control"></input>
    <label for="body">Body</label>
    <textarea id="text" name="body" placeholder="A description of the case." class="form-control" rows="3"></textarea>
    <br />
    <button class="btn btn-success" id="submit" value="go">Submit a new case...</button>
    <div id="case_number"></div>
    </div>
</form>
</div>
<div class="well" id="case_history"></div>

</body>
