<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Forum</title>
<link rel="icon" href="icons/favicon.ico">
<script>
function functionName() {

}
</script>
</head>
<body>

<?php
$username = $_POST['username'];
$password = $_POST['password'];
$passwordRepeat = $_POST['passwordRepeat'];

try {
  $conn = new PDO("mysql:host=localhost;dbname=myForum", "alex", "svetly");
  $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  $sql = "INSERT INTO Users (Username, Password, RegistrationDate)
          VALUES ('$username', '$password', CURDATE())";
  $stmt = $conn->prepare($sql);
  $stmt->execute();
  echo "You have been registered successfully";
}
catch(PDOException $e) {
  echo "Something went wrong";
  error_log($e->getMessage(), 0);
}
$conn = null;
?>

</body>
</html>
