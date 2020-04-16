<!DOCTYPE html>
<html>
<head>

</head>
<body>

<?php
$username = test_input($_GET['q']);
try {
  $dsn = "mysql:host=localhost;dbname=myForum;charset=utf8";
  $conn = new PDO($dsn, "alex", "svetly");
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
  $sql = "SELECT Username FROM Users WHERE Username = " . $conn->quote($username);
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

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

</body>
</html>
