<?php 
header("Access-Control-Allow-Origin: *");
if($_SERVER['REQUEST_METHOD']==='POST'){
    if(isset($_POST['uuid']) && $_POST['uuid']!==''){
        $uuid = str_replace('\'','\\\'',$_POST['uuid']);
        include "db.php";
        $sql = "SELECT * FROM `users` WHERE uuid='$uuid'";
        $result = mysqli_query($db,$sql);
        if($result===false){
            die('{"status":"error","message":"Error, please try again"}'); // '.mysqli_error($db).'"});
        }else if(mysqli_num_rows($result)!=1){
            die('{"status":"error","message":"Invalid credentials, please log in"}');
        }else{
            $row = mysqli_fetch_row($result);
            $msg = '{"status":"success","name":"'.$row['2'].'","uuid":"'.$row['4'].'","profilePic":"'.$row['6'].'"}';
            $sql = "UPDATE `users` SET `lastonline`='".(time()*1000)."' WHERE `uuid`=".$row['uuid'];
            die($msg);
        }

    }
}else{
    die('{"status":"error","message":"Invalid arguments"}');
}