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
if (isset($_GET["AuthorID"])) {
  $authorID = Dbh::test_input($_GET["AuthorID"]);
  $myForumDB->getPosts(NULL, $authorID);
} elseif (isset($_GET["SubjectID"])) {
  $subjectID = Dbh::test_input($_GET["SubjectID"]);
  $myForumDB->getPosts($subjectID, NULL);
} else {
  $myForumDB->getPosts(NULL, NULL);
}

?>
</body>
</html>
