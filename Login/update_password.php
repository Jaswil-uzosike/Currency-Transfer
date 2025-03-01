<?php
//the following code is used to display the form for user password update and also validate and update the user password
session_start();

$res;
$allFields = "yes";
$errorpw = "";

if (isset($_POST['submit'])) {

    if ("" === $_POST['pw']) {
        $errorpw = "password cannot be null";
        $allFields = "no";
    }

    if ($_POST['pw2'] !== $_POST['pw']) {
        $errorpw = "passwords do not match";
        $allFields = "no";
    }

  include('db_conn2.php');

    try {
        
        $email =  $_SESSION['mail_recipient'];
        $sql = 'SELECT * FROM useracc WHERE email = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
        if ($user) {
            $res =  $user['user_id'];
            
        } else {
            $errorem = "No user found with email " . $email;
            $allFields = "no";
        }
    
        
    } catch(PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }


    if ($allFields == "yes") {
       
        
        
        include_once('db_conn.php');

        $stmt2 = $mysqli->prepare("UPDATE useracc SET passwords = ? WHERE user_id = $res");
        $stmt2->bind_param('s', $update);
        $update = $_POST["pw"];

        $stmt2->execute();
        //the logic
        if ($stmt2) {
            session_destroy();
            echo "<script>alert('Password Update Successful'); window.location.href='../index.php';</script>";
            exit; 
        }
    }


}
?>


<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">
    <title>Login Page</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</head>

<form class="form" method="post">
    <fieldset>
        <legend>RESET PASSWORD</legend>
        <div>
           
            <div class="form-item">
                <input placeholder="New Password" type="password" name="pw">
                <span class="danger">
                    <?php echo $errorpw; ?>
                </span>
            </div>
            <div class="form-item">
                <input placeholder="Repeat Password" type="password" name="pw2">
            </div>
            <div>
                <input class="form-button" type="submit" value="Reset" name="submit">
            </div>
        </div>
    </fieldset>
</form>