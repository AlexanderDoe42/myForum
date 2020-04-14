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
  function drawPost($postID, $content, $author, $title) {
    static $bg = "";
    $bg = ($bg == "bg1") ? "bg2" : "bg1";
    echo '
      <div id="post' . $postID . '" class="post ' . $bg . '">
        <div class="inner">
          <div class="columns">
            <div class="postbody">
              <div class="posthead">
                <div>' . $title . '</div>
                <div>by <span id="author_post' . $postID . '">' . $author . '</span></div>
              </div>
              <button class="quote-button" onclick="showBox(' . $postID . ')"><img src="/icons/double_quotation_mark.png"></button>
              <div id="post_content' . $postID . '" class="post_content">' . $content . '</div>
            </div>
            <div class="postprofile">
              <div>' . $author . '</div>
              <div>Posts: 242</div>
            </div>
          </div>
          <div class="back2top"><a class="top" href="#top"><img src="icons/angle-circle-arrow-up.png" style="width:18px;height:18px;"></a></div>
        </div>
      </div>
    ';
  }
  function loadDescription() {
    $subjectID = $_GET['id'];
    try {
      $conn = new PDO("mysql:host=localhost;dbname=myForum", "alex", "svetly");
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql="SELECT * FROM Subjects WHERE SubjectID = '$subjectID'";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetch();
      $authorID = $result['AuthorID'];
      $sql="SELECT Username FROM Users WHERE UserID = '$authorID'";
      $stmt2 = $conn->prepare($sql);
      $stmt2->execute();
      $result2 = $stmt2->fetch();
      $author = $result2['Username'];
      drawPost(0, $result['Content'], $author, $result['Title']);
      $result = $stmt->fetch();
    }
    catch(PDOException $e) {
      error_log($e->getMessage(), 0);
    }
    $conn = null;
  }
  function getTitle() {
    $subjectID = $_GET['id'];
    try {
      $conn = new PDO("mysql:host=localhost;dbname=myForum", "alex", "svetly");
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql="SELECT * FROM Subjects WHERE SubjectID = '$subjectID'";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetch();
      return $result['Title'];
    }
    catch(PDOException $e) {
      error_log($e->getMessage(), 0);
    }
    $conn = null;
  }
  function loadPosts() {
    $subjectID = $_GET['id'];
    try {
      $conn = new PDO("mysql:host=localhost;dbname=myForum", "alex", "svetly");
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql="SELECT * FROM Posts WHERE SubjectID = '$subjectID'";
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetch();
      while ($result) {
        $authorID = $result['AuthorID'];
        $sql="SELECT Username FROM Users WHERE UserID = '$authorID'";
        $stmt2 = $conn->prepare($sql);
        $stmt2->execute();
        $result2 = $stmt2->fetch();
        $author = $result2['Username'];
        drawPost($result['PostID'], $result['PostContent'], $author, "Re: " . getTitle());
        $result = $stmt->fetch();
      }
    }
    catch(PDOException $e) {
      error_log($e->getMessage(), 0);
    }
    $conn = null;
  }
?>

<div class="main-container">
  <div id="forumhead"></div>
  <div id="user-info"></div>
  <div id="posts">
    <?php
      loadDescription();
      loadPosts();
    ?>
  </div>
  <button onclick="showBox()">Reply</button>
</div>
<div id="new-something-body">
  <div id="new-something-box">
    <div id="close-button" class="cursor-pointer" onclick="closeBox()">
      <div></div>
    </div>
    <form id="newsomething-form" action="newpost.php" method="post">
      <textarea id="message" name="message" placeholder="type your message here"></textarea>
      <input class="cursor-pointer" type="submit" name="send-button" value="Send">
    </form>
  </div>
</div>

<script>
  $("#forumhead").load("/html/forumhead.html");
  $("#user-info").load("/html/user-info.html", function(responseTxt, statusTxt, xhr){
    if(statusTxt == "success") {
      userCondition();
    }
  });
  var curURL = window.location.href;
  var pos = curURL.indexOf("?id=");
  var subjectID = curURL.slice(pos);
  $("#newsomething-form").attr("action", "newpost.php" + subjectID);
</script>
<script src="/js/main.js"></script>
<script> loadPosts() </script>

</body>
</html>
