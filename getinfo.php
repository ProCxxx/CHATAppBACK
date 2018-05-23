<?php
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  if (isset($_POST['uuid']) && isset($_POST['action'])) {
    $uuid = str_replace('\'', '\\\'', $_POST['uuid']);
    $action = $_POST['action'];
    if ($uuid == '' || $action == '') {
      die('{"status":"error","message":"Invalid parameter(s)"}');
    }

    include "db.php";

    $sql = "SELECT * FROM `users` WHERE uuid='$uuid'";
    $result = mysqli_query($db, $sql);
    if ($result == false) {
      die('{"status":"error","message":"Error, please try again"}');
    }
    else
    if (mysqli_num_rows($result) != 1) {
      die('{"status":"error","message":"User not found"}');
    }

    if ($action === "profile") {
      $sql = "SELECT username, name, profilepic, uuid FROM `users` WHERE `uuid`='$uuid'";
      $result = mysqli_query($db, $sql);
      if ($result == false) {
        die('{"status":"error","message":"Error, please try again"}');
      }
      else
      if (mysqli_num_rows($result) != 1) {
        die('{"status":"error","message":"User not found"}');
      }
      else {
        $row = mysqli_fetch_row($result);
        $msg = '{"status":"success","uuid":"' . $uuid . '","profilepic":"' . $row['2'] . '","name":"' . $row['1'] . '","username":"' . $row['0'] . '"}';
        die($msg);
      }
    }
    else {
      die('{"status":"error","message":"Action not found"}');
    }
  }
}
else {
  die('{"status":"error","message":"Invalid arguments"}');
}