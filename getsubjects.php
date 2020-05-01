<?php
  include 'includes/autoloader.inc.php';
?>
<!DOCTYPE html>
<html>
<head>
</head>
<body>

<?php
//error_log($_SERVER['REMOTE_ADDR'], 0);

$myForumDB = new MyDB();
$myForumDB->getSubjects();

?>
</body>
</html>
