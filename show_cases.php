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
  $.getJSON('get_accounts.php', function(res) {
    $.each(res, function(v,t) {
      $('#accounts').append(new Option(t['name'],t['id']));
    });
  });

  function refreshHistory() {
    $('#case_history').html('');
    $.getJSON('cases_by_account.php?account_id=' + $('#accounts').val(), function(ret) {
       ret.forEach(function(ele) {
         id = ele['id'] || '';
         name = ele['name'] || '';
         href = "case_detail.php?case_id=" +  id;
         $('#case_history').append('<a style="margin-bottom: 10px;" width="300px" class="btn btn-default case_link" id="' + id + '" href="' + href + '">' + name + '</a><br />');
       });
    });

  };

  $('#accounts').on('change', function(e) {
    refreshHistory();
  });
     

  $('#case_form').on('submit', function(e) {
    e.preventDefault();

    $('#case_number').fadeOut(300); 

    payload = {
        'account': $('#accounts').val(),
        'title': $('#title').val(),
        'body': $('#text').val()
    };

    $.post('case.php',
      payload).done(
      function(data,status) {
        $('#case_number').html(data['name'] + " was submitted as <a href=\"/suite/index.php?action=ajaxui#ajaxUILoc=index.php%3Fmodule%3DCases%26offset%3D1%26stamp%3D1488256673068343400%26return_module%3DCases%26action%3DDetailView%26record%3D" + data['id'] + '">#' + data['number'] + '</a>').fadeIn(1000);
        refreshHistory();
      }
    );
  });
});       
</script>
</head>
<body>
<a class="btn btn-primary" href="logout.php">Logout</a>
<div class="well">
<form class="form" id="case_form" method="POST" action="/case.php">
  <div class="form-group">
    <label for="accounts">Account</label>
      <select name="account" id="accounts">
        <option value="">Choose Account</option>
      </select>
    <br />
    <label for="title">Title</label>
    <input id="title" name="title" class="form-control"></input>
    <label for="body">Body</label>
    <textarea id="text" name="body" class="form-control" rows="3"></textarea>
    <br />
    <button class="btn btn-success" id="submit" value="go">Submit a new case...</button>
    </div>
</form>
</div>
<div class="well" id="case_history"></div>

</body>
