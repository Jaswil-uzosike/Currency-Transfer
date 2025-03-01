<?php
//The following code is used to identify if an email is in the system
session_start();


$res;

$errorpw = $errorem = "";

if (isset($_POST['submit'])) {

    if ("" === $_POST['em']) {
        $errorem = "email cannot be null";
        $allFields = "no";
    }



    include ('db_conn2.php');

    try {

        $email = $_POST['em'];
        $sql = 'SELECT * FROM useracc WHERE email = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            $_SESSION['mail_recipient'] = $email;
            $_SESSION['mail_recipient_fname'] = $user['fname'];
            $_SESSION['mail_recipient_lname'] = $user['lname'];
            header('Location: ../User/mailer.php');

        } else {
            $errorem = "No user found with email " . $email;
        }


    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }





}
?>


<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">
    <title>Password Reset</title>
    <link href=ttps://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
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
                <input class="form-item" placeholder="Input User Email" type="text" name="em">
                <span class="danger">
                    <?php echo $errorem; ?>
                </span>
            </div>

            <div>
                <input class="form-button" type="submit" value="Send" name="submit">
            </div>
            <br>
            <div>
                <a href="../index.php" class="form-button">Back</a>
            </div>
        </div>
    </fieldset>
</form>