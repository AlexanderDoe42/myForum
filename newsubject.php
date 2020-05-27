<?php
  include 'includes/autoloader.inc.php';
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>

<?php
if (!isset($_COOKIE['usrID']) || empty($_POST['title']) || empty($_POST['content'])) {
  echo "<p style='font-size:10em;'>ERROR 401</p>";
  exit;
}
$title = Dbh::test_input($_POST['title']);
$content = Dbh::test_input($_POST['content']);
$usrID = $_COOKIE['usrID'];
$myForumDB = new MyDB();
$myForumDB->newSubject($title, $content, $usrID);
?>
<script> document.location = 'index.html'; </script>

</body>
</html>
