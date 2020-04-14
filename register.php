<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Forum</title>
<link rel="icon" href="icons/favicon.ico">
<link rel="stylesheet" type="text/css" href="/font/flaticon.css">
<link rel="stylesheet" type="text/css" href="/style/main.css">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>

</head>
<body>

<?php
$username = $password = $passwordRepeat = $checked = "";
$usernameErr = $passwordErr = $passwordRepeatErr = $termsErr = "";
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST['username'])) {
    $usernameErr = "required field";
  } else {
    $username = test_input($_POST['username']);
  }
  if (empty($_POST['password'])) {
    $passwordErr = "required field";
  } else {
    $password = test_input($_POST['password']);
  }
  if (empty($_POST['passwordRepeat'])) {
    $passwordRepeatErr = "required field";
  } else {
    $passwordRepeat = test_input($_POST['passwordRepeat']);
  }
  if (!isset($_POST['terms'])) {
    $termsErr = "must be accepted";
  } else {
    $checked = "Checked";
  }
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
}
function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}
?>

<div class="main-container">
  <div id="forumhead"></div>
  <form class="register-form" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
    <h1>Registration</h1>
    <div>
      <div class="username-and-pwd">
        <div class="input-field">
          <input type="text" name="username" placeholder="username" value="<?php echo $username ?>">
          <div class="error"><?php echo $usernameErr ?></div>
        </div>
        <div class="input-field">
          <input type="password" name="password" placeholder="password">
          <div class="error"><?php echo $passwordErr ?></div>
        </div>
        <div class="input-field">
          <input type="password" name="passwordRepeat" placeholder="repeat password">
          <div class="error"><?php echo $passwordRepeatErr ?></div>
        </div>
      </div>
      <div class="terms">
        <input type="checkbox" name="terms" id="terms" <?php echo $checked ?>>
        <label for="terms">I agree to the <span class="dashed">"myForum" Terms</span></label>
        <div class="error"><?php echo $termsErr ?></div>
      </div>
    </div>
    <input type="submit" value="Submit">
  </form>
</div>

<script>
  $("#forumhead").load("/html/forumhead.html");
</script>

</body>
</html>
