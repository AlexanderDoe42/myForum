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
if (isset($_GET['id'])) {
  $subjectID = Dbh::test_input($_GET['id']);
  $location = "Location: subject.php?id=" . $subjectID;
} else {
  $location = "Location: index.html";
}
header($location, TRUE, 307);
?>

</body>
</html>
