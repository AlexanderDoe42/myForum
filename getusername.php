<?php
  include 'includes/autoloader.inc.php';
?>
<!DOCTYPE html>
<html>
<head>

</head>
<body>

<?php
$usrID = $_COOKIE['usrID'];
$myForumDB = new MyDB();
$myForumDB->getUsername($usrID);
?>

</body>
</html>
