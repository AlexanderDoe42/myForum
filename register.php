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
$allGood = true;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (empty($_POST['username'])) {
    $usernameErr = "required field";
    $allGood = false;
  } else {
    $username = test_input($_POST['username']);
    if (strlen($username) < 3) {
      $allGood = false;
    }
  }
  if (empty($_POST['password'])) {
    $passwordErr = "required field";
    $allGood = false;
  } else {
    $password = test_input($_POST['password']);
    if (strlen($password) < 3) {
      $allGood = false;
    }
  }
  if (empty($_POST['passwordRepeat'])) {
    $passwordRepeatErr = "required field";
    $allGood = false;
  } else {
    $passwordRepeat = test_input($_POST['passwordRepeat']);
    if ($password != $passwordRepeat) {
      $allGood = false;
    }
  }
  if (!isset($_POST['terms'])) {
    $termsErr = "must be accepted";
    $allGood = false;
  } else {
    $checked = "Checked";
  }
  if ($allGood) {
    try {
      $dsn = "mysql:host=localhost;dbname=myForum;charset=utf8";
      $conn = new PDO($dsn, "alex", "svetly");
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $sql = "INSERT INTO Users (Username, Password, RegistrationDate)
              VALUES (:username, :password, CURDATE())";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":username", $username, PDO::PARAM_STR);
      $stmt->bindValue(":password", $password, PDO::PARAM_STR);
      $stmt->execute();
      echo "<script>document.body.onload = function() { regSuccess(); }</script>";
    }
    catch(PDOException $e) {
      echo "<script>document.body.onload = function() { regFailure(); }</script>";
      error_log($e->getMessage(), 0);
    }
    $conn = null;
  } else {
    echo "<script>document.body.onload = function() { regFailure(); }</script>";
  }
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
  <div id="registration">
    <div id="reg-success">
      <h3>You have been registered successfully!</h3>
      go to the <a href="/">main page</a>
    </div>
    <form class="register-form" name="register" onsubmit="return checkRegForm()" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
      <h1>Registration</h1>
      <div onkeyup="enableSubmit()">
        <div class="username-and-pwd">
          <div class="input-field">
            <input type="text" name="username" autocomplete="off" placeholder="username" value="<?php echo $username ?>" onkeyup="showHint(this.value)">
            <div class="error" id="error-username"><?php echo $usernameErr ?></div>
          </div>
          <div class="input-field">
            <input type="password" name="password" placeholder="password" onkeyup="cleanError(this.name)" autocomplete="new-password">
            <div class="error" id="error-password"><?php echo $passwordErr ?></div>
          </div>
          <div class="input-field">
            <input type="password" name="passwordRepeat" placeholder="repeat password" onkeyup="cleanError(this.name)" autocomplete="new-password">
            <div class="error" id="error-passwordRepeat"><?php echo $passwordRepeatErr ?></div>
          </div>
        </div>
        <div class="terms">
          <input type="checkbox" name="terms" id="terms" <?php echo $checked ?> onchange="cleanError(this.name)">
          <label for="terms">I agree to the <span style="display:none">"myForum" Terms</span></label><span onclick="showBox('terms')">"myForum" Terms</span>
          <div class="error" id="error-terms"><?php echo $termsErr ?></div>
        </div>
      </div>
      <input type="submit" name="submit" value="Submit" disabled>
    </form>
    <div id="reg-fault">
      Nope!<br>
      Check all the fields and try again
    </div>
  </div>
</div>
<div id="new-something-body">
  <div id="new-something-box">
    <div id="close-button" class="cursor-pointer" onclick="closeBox()">
      <div></div>
    </div>
    <h1 class="h1-terms">"myForum" Terms</h1>
    <p class="p-terms">
    When using the services in this site you agree to the terms and conditions
    described herein.<br><br>

By submitting this form you will create an account as registered user of this
site. That account can be used to identify yourself in this site.<br><br>

You must ensure that you read, understand and agree with the specific rules
 before using it. You understand and agree that "myForum" assumes no
  responsibility for the
 timeliness, deletion, unavailability or failure to store or deliver any user
  message, content or setting, and in no case will "myForum" be responsible
  for any damage to your system or loss of data that may result of using our
  site.<br><br>

You warrant that you will not post or transmit through the means provided
through this site any material or text that may be found obscene, hateful,
threatening, defamatory, violative of copyright laws or otherwise unlawful, as
well as you will not use these services or the data provided through them for
unauthorized solicitation or advertising.<br><br>

Any content you provide is to be free of charge and royalties for "myForum"
 and with permission to be published, moved and copied in this site and in
 accordance with the copyright notices at the bottom of our pages.<br><br>

You agree to be responsible for keeping the confidentiality of the credentials
used to log into our systems, and to be fully responsible for all activities
 that occur under this account. In no case will "myForum" be liable for
  user-provided content or messages. You agree to indemnify, defend, and hold
   harmless "myForum" from all third party claims, liability, damages and
   costs arising from your use of our services.<br><br>

The administrators of this site have the right (although not the obligation)
in their sole discretion to refuse, move, modify or delete any content that may
 be found objectionable, as well as the right to suspend or terminate any
  account and refuse any future use of its services to any user without
  notification.<br><br>

These terms may be amended or changed by "myForum" at any time in its sole
 discretion by posting them in our website. Your continued use of this website
  afterwards shall signify your acceptance of such changes.<br><br>

By checking the box below you declare that you agree with these terms of
service.<br><br>
    </p>
  </div>
</div>

<script>
  $("#forumhead").load("/html/forumhead.html");
</script>
<script src="/js/main.js"></script>

</body>
</html>
