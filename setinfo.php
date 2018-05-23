<?php
header("Access-Control-Allow-Origin: *");
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['uuid']) && isset($_POST['action']) && isset($_POST['val'])) {
        $uuid   = str_replace('\'', '\\\'', $_POST['uuid']);
        $action = $_POST['action'];
        $val = str_replace('\'','\\\'',$_POST['val']);
        if ($uuid == '' || $action == '' || $val=='') {
            die('{"status":"error","message":"Invalid parameter(s)"}');
        }
        include "db.php";
        $sql    = "SELECT * FROM `users` WHERE uuid='$uuid'";
        $result = mysqli_query($db, $sql);
        if ($result == false) {
            die('{"status":"error","message":"Error, please try again"}');
        } else if (mysqli_num_rows($result) != 1) {
            die('{"status":"error","message":"User not found"}');
        }
        if ($action === "profilePic") {
            $sql    = "UPDATE `users` SET `profilepic='$val' WHERE `uuid`='$uuid'";
            $result = mysqli_query($db, $sql);
            if ($result == false) {
                die('{"status":"error","message":"Error, please try again"}');
            } else {
                $row = mysqli_fetch_row($result);
                $msg = '{"status":"success","message":"Updated"}';
                die($msg);
            }
        } else if ($action == "updateOnline") {
            $sql    = "UPDATE `users` SET `lastonline`='" . (time() * 1000) . "' WHERE `uuid`=" . $uuid;
            $result = mysqli_query($db, $sql);
            if ($result == false) {
                die('{"status":"error","message":"Error, please try again"}');
            } else {
                die('{"status":"success","message":"updated"}');
            }
        } else {
            die('{"status":"error","message":"Action not found"}');
        }
    }
} else {
    die('{"status":"error","message":"Invalid arguments"}');
}