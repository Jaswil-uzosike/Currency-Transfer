<?php
//The following code is like a login page but its a verification page.
//It asks the user for their password and verifies it

session_start();
include('navbar.php');
include('db_conn2.php');
$userId = $_SESSION['user_id'];

$errorpw = "";


if (isset($_POST['submit'])) {


  
    $password = $_POST['pw'];

    if ("" === $password) {
        $errorpw = "password cannot be null";
    }
  
    $sql = 'SELECT * FROM useracc WHERE user_id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $userId]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

  

    if ($user) {
        $res = $user['passwords'];  
    

        if ($res == $password) {
            header('Location: view_user_profile_details.php');
        }
        else{ 
            $errorpw = "incorrect Details";
            echo "<script>alert('Wrong details'); </script>";
        }
    }
    else
    {
        echo "<script>alert('Wrong details'); </script>";
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
        <legend>PASSWORD CHECK</legend>
        <div>
            <div class="form-item">
                <input placeholder="Password" type="password" name="pw">
            </div>
            <div>
                <input class="form-button" type="submit" value="Confirm" name="submit">
            </div>
            <br>
            <div>
                    <a href="homepage.php" class="form-button">Back</a>
            </div>
        </div>
    </fieldset>
</form>