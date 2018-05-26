<?php
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
  if (isset($_POST['username']) && isset($_POST['password'])) {
    $user = str_replace('\'', '\\\'', $_POST['username']);
    $pw = str_replace('\'', '\\\'', $_POST['password']);
    $pw = hash('md5', '_!_' . $pw . '_!_');
    include "db.php";

    $sql = "SELECT username, name, password, uuid, lastonline, profilepic, description FROM `users` WHERE `username`='" . $user . "' AND `password`= BINARY '" . $pw . "'";
    $result = mysqli_query($db, $sql);
    if ($result === false) {
      die('{"status":"error","message":"Error, please try again"}'); // '.mysqli_error($db).'"});
    }
    else
    if (mysqli_num_rows($result) != 1) {
      die('{"status":"error","message":"Wrong username or password"}');
    }
    else {
      $row = mysqli_fetch_row($result);
      $msg = '{"status":"success","name":"' . $row['1'] . '","uuid":"' . $row['3'] . '","profilePic":"' . $row['5'] . '","description":"'.$row['6'].'"}';
      $sql = "UPDATE `users` SET `lastonline`='" . (time() * 1000) . "' WHERE `uuid`=" . $row['3'];
      die($msg);
    }
  }
  else {
    die('{"status":"error","message":"Enter all data"}');
  }
}
else {
  die('{"status":"error","message":"Invalid arguments"}');
}