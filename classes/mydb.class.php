<?php

class MyDB extends Dbh {
  function __construct() {
    if (isset($_COOKIE['usrID']) && $_COOKIE['usrID'] != 'wrong') {
      $usrID = $_COOKIE['usrID'];
      $conn = $this->connect();
      $sql = "UPDATE Users
              SET LastSeen = CURRENT_TIMESTAMP
              WHERE UserID = " . $conn->quote($usrID);
      $stmt = $conn->prepare($sql);
      $stmt->execute();
    }
  }
  private function drawSubject($subject) {
    $author = $this->getUser($subject['AuthorID']);
    if ($subject['LastPostID'] == 0) {
      $lastPostAuthor = $author;
      $lastPost = $subject;
    } else {
      $lastPost = $this->getPost($subject['LastPostID']);
      $lastPostAuthorID = $lastPost['AuthorID'];
      $lastPostAuthor = $this->getUser($lastPostAuthorID);
    }
    static $bg = "";
    $bg = ($bg == "bg1") ? "bg2" : "bg1";
    echo '
      <div class="post ' . $bg . '">
        <div class="inner">
          <div class="columns">
            <div class="postbody">
              <a class="title" href="subject.php?id=' . $subject['SubjectID'] . '">
                ' . $subject['Title'] . '
              </a>
              <div class="subjectinfo">
                by <a href="#">' . $author['Username'] . '</a>
                <span class="datetime"> >> ' . $subject['Created'] . '</span>
              </div>
            </div>
            <div class="rightcolumn">
              <div>' . $subject['NumberOfPosts'] . ' posts</div>
              <div>
                last post by
                <a href="#">' . $lastPostAuthor['Username'] . '</a>
              </div>
              <div class="datetime">' . $lastPost['Created'] . '</div>
            </div>
          </div>
          <div class="back2top">
            <a class="top" href="#top">
              <img src="icons/angle-circle-arrow-up.png" style="width:18px;height:18px;">
            </a>
          </div>
        </div>
      </div>
    ';
  }
  private function drawPost($post) {
    $subject = $this->getSubject($post['SubjectID']);
    $title = $subject['Title'];
    $author = $this->getUser($post['AuthorID']);
    $lastSeen = DateTime::createFromFormat('Y-m-d H:i:s', $author['LastSeen']);
    if (time() - $author['LastSeenTimestamp'] < 240) {
      $status = '<div class="online">Online</div>';
    } else {
      $status = 'last seen <div class="datetime">' . $lastSeen->format('F d, Y H:i') . '</div>';
    }
    $profilePicture = '';
    if ($author['HasAProfilePicture']) {
      $profilePicture = '<img src="images/id' . $post['AuthorID'] . '.JPG">';
    }
    static $bg = "";
    $bg = ($bg == "bg1") ? "bg2" : "bg1";
    echo '
      <div id="post' . $post['PostID'] . '" class="post ' . $bg . '">
        <div class="inner">
          <div class="columns">
            <div class="postbody">
              <div class="posthead">
                <a href="#post' . $post['PostID'] . '" class="retitle">Re: ' . $title . '</a>
                <div class="postinfo">
                  by <a href="#" id="author_post' . $post['PostID'] . '">' . $author['Username'] . '</a>
                  <span class="datetime"> >> ' . $post['Created'] . '</span>
                </div>
              </div>
              <button class="quote-button" onclick="replyButtonClickEvent(' . $post['PostID'] . ')">
                <img src="/icons/double_quotation_mark.png">
              </button>
              <div id="post_content' . $post['PostID'] . '" class="post_content">' . $post['PostContent'] . '</div>
            </div>
            <div class="rightcolumn">
              ' . $profilePicture . '
              <div>
                <a href="#">' . $author['Username'] . '</a>
              </div>
              ' . $status . '
            </div>
          </div>
          <div class="back2top"><a class="top" href="#top"><img src="icons/angle-circle-arrow-up.png" style="width:18px;height:18px;"></a></div>
        </div>
      </div>
    ';
  }
  private function drawDescription($subject) {
    $author = $this->getUser($subject['AuthorID']);
    $status = "";
    $lastSeen = DateTime::createFromFormat('Y-m-d H:i:s', $author['LastSeen']);
    if (time() - $author['LastSeenTimestamp'] < 240) {
      $status = '<div class="online">Online</div>';
    } else {
      $status = 'last seen <div class="datetime">' . $lastSeen->format('F d, Y H:i') . '</div>';
    }
    $profilePicture = '';
    if ($author['HasAProfilePicture']) {
      $profilePicture = '<img src="images/id' . $subject['AuthorID'] . '.JPG">';
    }
    $bg = "bg2";
    echo '
      <div id="post0" class="post ' . $bg . '">
        <div class="inner">
          <div class="columns">
            <div class="postbody">
              <div class="posthead">
                <a href="#post0" class="title">' . $subject['Title'] . '</a>
                <div class="postinfo">
                  by <a href="#" id="author_post0">' . $author['Username'] . '</a>
                  <span class="datetime"> >> ' . $subject['Created'] . '</span>
                </div>
              </div>
              <button class="quote-button" onclick="replyButtonClickEvent(0)"><img src="/icons/double_quotation_mark.png"></button>
              <div id="post_content0" class="post_content">' . $subject['Content'] . '</div>
            </div>
            <div class="rightcolumn">
              ' . $profilePicture . '
              <div>
                <a href="#">' . $author['Username'] . '</a>
                ' . $status . '
              </div>
            </div>
          </div>
          <div class="back2top"><a class="top" href="#top"><img src="icons/angle-circle-arrow-up.png" style="width:18px;height:18px;"></a></div>
        </div>
      </div>
    ';
  }

  public function getSubjects($authorID) {
    $conn = $this->connect();
    if ($authorID) {
      $sql="SELECT * FROM Subjects WHERE AuthorID = " . $conn->quote($authorID);
    } else {
      $sql="SELECT * FROM Subjects";
    }
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
      $this->drawSubject($row);
    }
  }
  private function getPost($postID) {
    $conn = $this->connect();
    $sql="SELECT * FROM Posts WHERE PostID = " . $conn->quote($postID);
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetch();
  }
  private function getSubject($subjectID) {
    $conn = $this->connect();
    $sql="SELECT * FROM Subjects WHERE SubjectID = " . $conn->quote($subjectID);
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    return $stmt->fetch();
  }
  public function getDescription($subjectID) {
    $conn = $this->connect();
    $sql="SELECT * FROM Subjects WHERE SubjectID = " . $conn->quote($subjectID);
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    $this->drawDescription($result);
  }
  public function getPosts($subjectID, $authorID) {
    $conn = $this->connect();
    $sql = "SELECT * FROM Posts";
    $needAND = false;
    if ($subjectID || $authorID) {
      $sql = $sql . " WHERE ";
    }
    if ($subjectID) {
      $sql = $sql . "SubjectID = " . $conn->quote($subjectID);
      $needAND = true;
    }
    if ($authorID) {
      if ($needAND) {
        $sql = $sql . " AND ";
      }
      $sql = $sql . "AuthorID = " . $conn->quote($authorID);
      $needAND = true;
    }
    error_log($sql, 0);
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    while ($row = $stmt->fetch()) {
      $this->drawPost($row);
    }
  }
  public function getUser($usrID) {
    $conn = $this->connect();
    $sql = "SELECT Username,
                   RegistrationDate,
                   NumberOfPosts,
                   NumberOfSubjects,
                   LastSeen,
                   UNIX_TIMESTAMP(LastSeen) AS LastSeenTimestamp,
                   HasAProfilePicture
            FROM Users
            WHERE UserID = " . $conn->quote($usrID);
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->fetch();
    if ($result) {
      return $result;
    }
    return NULL;
  }
  public function echoUsername($usrID) {
    $user = $this->getUser($usrID);
    $username = $user["Username"];
    if ($username) {
      echo $username;
    } else {
      setcookie("usrID", "", time() - 3600);
    }
  }
  public function login($username, $password) {
    $username = $this->test_input($username);
    $password = $this->test_input($password);

    $conn = $this->connect();
    $sql = "SELECT UserID, Username, Password
            FROM Users
            WHERE Username = :username AND
                  Password = :password";
    $stmt = $conn->prepare($sql);
    $stmt->bindValue(":username", $username, PDO::PARAM_STR);
    $stmt->bindValue(":password", $password, PDO::PARAM_STR);
    $stmt->execute();
    $result = $stmt->fetch();
    if (!$result) {
      setcookie("usrID", "wrong", time() + (86400 * 365), "/");
    } else {
      setcookie("usrID", $result['UserID'], time() + (86400 * 365), "/");
    }
  }
  public function signup($username, $password) {
    try {
      $conn = $this->connect();
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $sql = "INSERT INTO Users (Username, Password)
              VALUES (:username, :password)";
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
  }
  public function newSubject($title, $content, $usrID) {
    try {
      $conn = $this->connect();
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "INSERT INTO Subjects (AuthorID, Title, Content)
              VALUES (:usrID, :title, :content)";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue("usrID", $usrID, PDO::PARAM_STR);
      $stmt->bindValue("title", $title, PDO::PARAM_STR);
      $stmt->bindValue("content", $content, PDO::PARAM_STR);
      $stmt->execute();
      $sql = "UPDATE Users
              SET NumberOfSubjects = NumberOfSubjects + 1
              WHERE UserID = " . $conn->quote($usrID);
      $stmt = $conn->prepare($sql);
      $stmt->execute();
    }
    catch(PDOException $e) {
      error_log($e->getMessage(), 0);
    }
    $conn = null;
  }
  public function newPost($msg, $usrID, $subjectID) {
    try {
      $conn = $this->connect();
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "INSERT INTO Posts (AuthorID, PostContent, SubjectID)
              VALUES (:usrID, :msg, :subjectID)";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":usrID", $usrID, PDO::PARAM_STR);
      $stmt->bindValue(":msg", $msg, PDO::PARAM_STR);
      $stmt->bindValue(":subjectID", $subjectID, PDO::PARAM_STR);
      $stmt->execute();

      $sql = "UPDATE Users
              SET NumberOfPosts = NumberOfPosts + 1
              WHERE UserID = " . $conn->quote($usrID);
      $stmt = $conn->prepare($sql);
      $stmt->execute();

      $sql = "SELECT MAX(PostID) FROM Posts WHERE SubjectID = " . $conn->quote($subjectID);
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $lastPostID = $stmt->fetch()[0];

      $sql = "UPDATE Subjects
              SET NumberOfPosts = NumberOfPosts + 1,
                  LastPostID = :lastPostID
              WHERE SubjectID = :subjectID";
      $stmt = $conn->prepare($sql);
      $stmt->bindValue(":lastPostID", $lastPostID, PDO::PARAM_STR);
      $stmt->bindValue(":subjectID", $subjectID, PDO::PARAM_STR);
      $stmt->execute();
    }
    catch(PDOException $e) {
      error_log($e->getMessage(), 0);
    }
    $conn = null;
  }
  public function getRegistrationDate() {
    $usrID = $_COOKIE['usrID'];
    try {
      $conn = $this->connect();
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT RegistrationDate FROM Users WHERE UserID = " . $conn->quote($usrID);
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetch();
      echo $result['RegistrationDate'];
    }
    catch(PDOException $e) {
      error_log($e->getMessage(), 0);
    }
    $conn = null;
  }
  public function getNumberOfSubjects() {
    $usrID = $_COOKIE['usrID'];
    try {
      $conn = $this->connect();
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT NumberOfSubjects FROM Users WHERE UserID = " . $conn->quote($usrID);
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetch();
      echo $result['NumberOfSubjects'];
    }
    catch(PDOException $e) {
      error_log($e->getMessage(), 0);
    }
    $conn = null;
  }
  public function getNumberOfPosts() {
    $usrID = $_COOKIE['usrID'];
    try {
      $conn = $this->connect();
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      $sql = "SELECT NumberOfPosts FROM Users WHERE UserID = " . $conn->quote($usrID);
      $stmt = $conn->prepare($sql);
      $stmt->execute();
      $result = $stmt->fetch();
      echo $result['NumberOfPosts'];
    }
    catch(PDOException $e) {
      error_log($e->getMessage(), 0);
    }
    $conn = null;
  }
  public function searchForUser($username) {
    try {
      $conn = $this->connect();
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
  }
  public function userProvidedAPicture($userID) {
    $conn = $this->connect();
    $sql = "UPDATE Users
            SET HasAProfilePicture = true
            WHERE UserID = " . $conn->quote($userID);
    $stmt = $conn->prepare($sql);
    $stmt->execute();
  }
}

?>
