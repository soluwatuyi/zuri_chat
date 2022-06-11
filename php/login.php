<?php
session_start();
if(isset($_SESSION["name"])) {
    $msg = "Session active";
} elseif(isset($_POST["submit"])){
    $email = (string) $_POST["email"];
    $password = (string) $_POST["password"];
    $email = strtolower($email);
    if(empty($email) || empty($password)) {
        $msg = "All fields are required";
    } elseif(($handle = fopen("../storage/users.csv", "r")) !== FALSE) {
        while(($data = fgetcsv($handle)) !== FALSE) {
            if($data[1] == $email || $data[3] == $email) {
                if($data[2] == md5($password)) {
                    setcookie(session_name(), session_id(), time()+1800);
                    $_SESSION["name"] = $data[0];
                    $_SESSION["username"] = $data[3];
                    $_SESSION["lastID"] = filesize("../storage/chats.csv");
                    $msg = "Login successful";
                } else {
                    $msg = "Incorrect password";
                }
                break;
            }
        }
        $msg = isset($msg) ? $msg : "User not registered";
        fclose($handle);
    } else {
        $msg = "Internal error";
    }
} else {
    $msg = "Access denied";
}
$_SESSION["msg"] = $msg;
header("Location: ../index.php");
?>
