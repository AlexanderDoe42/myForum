<!DOCTYPE html>
<html>
<head>
</head>
<body>

<?php
$title = $_POST['title'];
$content = $_POST['content'];
$usrID = $_COOKIE['usrID'];

try {
  $conn = new PDO("mysql:host=localhost;dbname=myForum", "alex", "svetly");
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "INSERT INTO Subjects (AuthorID, Created, Title, Content)
          VALUES ('$usrID', NOW(), '$title', '$content')";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $sql = "UPDATE Users
          SET NumberOfSubjects = NumberOfSubjects + 1
          WHERE UserID = '$usrID'";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
}
catch(PDOException $e) {
  error_log($e->getMessage(), 0);
}
$conn = null;
?>
<script> document.location = 'index.html'; </script>

</body>
</html>
