<?php
  include 'includes/autoloader.inc.php';
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>

<?php
if (!isset($_COOKIE['usrID']) || empty($_POST['message']) || !isset($_GET['id'])) {
  echo "<p style='font-size:10em;'>ERROR 401</p>";
  exit;
}
$msg = Dbh::test_input($_POST['message']);
$usrID = $_COOKIE['usrID'];
$subjectID = $_GET['id'];
$myForumDB = new MyDb();
$myForumDB->newPost($msg, $usrID, $subjectID);
?>
<script>
  var curURL = window.location.href;
  var pos = curURL.indexOf("?id=");
  var subjectID = curURL.slice(pos);
  document.location = "subject.php" + subjectID;
</script>

</body>
</html>
