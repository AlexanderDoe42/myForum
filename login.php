<?php
  include 'includes/autoloader.inc.php';
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>

<?php
$username = $_POST['username'];
$password = $_POST['password'];
$myForumDB = new MyDB();
$myForumDB->login($username, $password);
?>
<script>
  var curURL = window.location.href;
  var pos = curURL.indexOf("?id=");
  if (pos == -1) {
    document.location = "index.html";
  } else {
    var subjectID = curURL.slice(pos);
    document.location = "subject.php" + subjectID;
  }
</script>

</body>
</html>
