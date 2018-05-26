<?php
header("Access-Control-Allow-Origin: *");

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    if (isset($_POST['uuid']) && isset($_POST['action'])) {
        $uuid   = str_replace('\'', '\\\'', $_POST['uuid']);
        $action = $_POST['action'];
        if ($uuid == '' || $action == '') {
            die('{"status":"error","message":"Invalid parameter(s)"}');
        }
        
        include "db.php";
        
        $sql    = "SELECT * FROM `users` WHERE uuid='$uuid'";
        $result = mysqli_query($db, $sql);
        if ($result == false) {
            die('{"status":"error","message":"Error, please try again"}');
        } else if (mysqli_num_rows($result) == 0) {
            die('{"status":"error","message":"User not found"}');
        }
        if ($action === "conversation") {
            $sql    = "SELECT * FROM `conversation` WHERE oneside='$uuid' OR twoside='$uuid'";
            $row = mysqli_fetch_row($result);
            $name = $row[2];
            $pp = $row[6];
            $result = mysqli_query($db, $sql);
            $count  = mysqli_num_rows($result);
            if ($result == false) {
                die('{"status":"error","message":"Error, please try again"}');
            } else if ($count == 0) {
                die('{"status":"error","message":"No conversation(s) found"}');
            } else {
                $msg = '{"status":"success","message":"Conversation(s) found","name":"'.$name.'","profilepic":"'.$pp.'","convs":[';
                for ($i = 0; $i < $count; $i++) {
                    $row = mysqli_fetch_row($result);
                    $uuid2=($row[2]===$uuid)?$row[3]:$row[2];
                    $sql2 = "SELECT username,name,uuid, profilepic, lastonline FROM `users` WHERE uuid='$uuid2'";
                    $result2 = mysqli_query($db,$sql2);
                    $row2 = mysqli_fetch_row($result2);
                    $un2 = $row2[0];
                    $name2 = $row2[1];
                    $pp2 = $row2[3];
                    $lo = $row2[4];
                    if ($i > 0) {
                        $msg .= ',';
                    }
                    $msg .= "{\"name\":\"$name2\",\"username\":\"$un2\",\"profilepic\":\"$pp2\",\"lastonline\":\"$lo\",";
                    $msg .= "\"convID\":\"" . $row[1] . "\",\"oneside\":\"" . $row[2] . "\",\"twoside\":\"" . $row[3] . "\",";
                    $msg .= "\"lastmsg\":\"" . $row[4] . "\",\"lastmsgtime\":\"" . $row[5] . "\"}";
                }
                $msg .= "],\"now\":\"".time()."\"}";
                die($msg);
            }
        } else if ($action === "profile") {
            $sql    = "SELECT username, name, profilepic, uuid, description FROM `users` WHERE `uuid`='$uuid'";
            $result = mysqli_query($db, $sql);
            if ($result == false) {
                die('{"status":"error","message":"Error, please try again"}');
            } else if (mysqli_num_rows($result) == 0) {
                die('{"status":"error","message":"User not found"}');
            } else {
                $row = mysqli_fetch_row($result);
                $msg = '{"status":"success","uuid":"' . $uuid . '","profilepic":"' . $row['2'] . '","name":"' . $row['1'] . '","username":"' . $row['0'] . '","description":"'.$row['4'].'"}';
                die($msg);
            }
        } else {
            die('{"status":"error","message":"Action not found"}');
        }
    }
} else {
    die('{"status":"error","message":"Invalid arguments"}');
}