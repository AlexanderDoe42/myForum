<!DOCTYPE html>
<html>
<head>

</head>
<body>

<?php
$username = $_GET['q'];
try {
  $conn = new PDO("mysql:host=localhost;dbname=myForum", "alex", "svetly");
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT Username FROM Users WHERE Username = '$username'";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  $result = $stmt->fetch();
  if ($result) {
    echo "taken";
  }
}
catch(PDOException $e) {
  error_log($e->getMessage(), 0);
}
$conn = null;
?>

</body>
</html>
