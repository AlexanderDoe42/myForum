<?php
  include 'includes/autoloader.inc.php';
?>
<!DOCTYPE html>
<html>
<head>

</head>
<body>

<?php
$username = Dbh::test_input($_GET['q']);
$myForumDB = new MyDB();
$myForumDB->searchForUser($username);
?>

</body>
</html>
