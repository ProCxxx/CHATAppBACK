<?php
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] === "POST") {
  if (isset($_POST['username']) && isset($_POST['name']) && isset($_POST['password']) && isset($_POST['password2'])) {
    $user = str_replace('\'', '\\\'', $_POST['username']);
    $name = str_replace('\'', '\\\'', $_POST['name']);
    $pw1 = str_replace('\'', '\\\'', $_POST['password']);
    $pw2 = str_replace('\'', '\\\'', $_POST['password2']);
    if ($pw1 !== $pw2) {
      die('{"status":"error","message":"Match passwords"}');
    }
    else if ($user == '' || $name == '' || $pw1 == '') {
      die('{"status":"error","message":"Enter all data"}');
    }

    include "db.php";

    $sql = "SELECT * FROM `users` WHERE `username`='" . $user . "'";
    $result = mysqli_query($db, $sql);
    if ($result === false) {
      die('{"status":"error","message":"Error, please try again"}');
    }
    else if (mysqli_num_rows($result) !== 0) {
      die('{"status":"error","message":"Username is alredy in use"}');
    }

    $passwd = hash('md5', '_!_' . $pw1 . '_!_');
    $uuid = hash('md5', hash('md5',rand(1000).$user.rand(1000)) . hash('md5', time() * rand(1234, 12345)));
    $lastOnline = time();
    $pp = '';
    $sql = "INSERT INTO `users`(`username`, `name`, `password`, `uuid`, `lastonline`, `profilepic`) VALUES ('$user','$name','$passwd','$uuid','$lastOnline','')";
    $result = mysqli_query($db, $sql);
    if ($result === false) {
      die('{"status":"error","message":"Error, please try again"}');
    } else {
        setcookie("uuid", $uuid, time() + 3600, '/');
        die('{"status":"success","uuid":"' . $uuid . '"}');
    }
  } else {
      die('{"status":"error","message":"Enter all data"}');
  }
} else {
    die('{"status":"error","message":"Invalid arguments"}');
}