<?php
// The code below checks if the receiver is valid 
// If the receiver is valid it retrieves information regarding the user's balance
session_start();
include ('navbar.php');

$conv = $_SESSION['amount_received'];

$userId2;
$receiverbal;
$finalrecieverbal;
$allFields = "yes";
$errorrem = "";

$host = 'localhost';
$dbname = 'my_stage2';
$username = 'root';
$password = '';



if (isset ($_POST['submit'])) {



    if ("" == $_POST['rem']) {
        $errorem = "email cannot be null";
        $allFields = "no";
    }


    if ($allFields == "yes") {

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


            if (isset ($_POST['rem'])) {

                $receiveremail = $_POST['rem'];
                $sql2 = 'SELECT * FROM useracc WHERE email = :id ';
                $stmt2 = $pdo->prepare($sql2);
                $stmt2->execute(['id' => $receiveremail]);
                $user2 = $stmt2->fetch(PDO::FETCH_ASSOC);

                if ($user2) {
                    $stat = $user2['acc_status'];
                    
                    if($stat != "active"){
                        $errorrem = "The recipient account has been suspended";
                        $allFields = "no";
                    }

                  

                    $receivercurrency = $_SESSION['currency_to'];

                    $_SESSION['user_id2'] = $user2['user_id'];
                    $userId2 = $_SESSION['user_id2'];


                    $sql3 = 'SELECT * FROM currencyacc WHERE user_id = :id AND currency_id = :id2';
                    $stmt3 = $pdo->prepare($sql3);
                    $stmt3->bindParam(':id', $userId2);
                    $stmt3->bindParam(':id2', $receivercurrency);
                    $stmt3->execute();
                    $user3 = $stmt3->fetch(PDO::FETCH_ASSOC);

                    if($_SESSION['user_id'] == $user3['user_id']){
                        if($_SESSION['currency_from'] == $receivercurrency){
                        $errorrem = "You cannot be your own recipient ";
                        $allFields = "no";
                        }
                    }

                    if ($user3) {

                        $receiverbal = $user3['balance'];
                        $finalreceiverbal = $receiverbal + $conv;
                        $_SESSION['Final_receiver_balance'] = $finalreceiverbal;
                    } else {
                        $errorrem = "The recipient account does not exist";
                        $allFields = "no";
                    }


                } else {
                    $errorrem = "The recipient does not exist";
                    $allFields = "no";
                }
            } else {
                echo "No user ID set in session.";
                $allFields = "no";
            }

        } catch (PDOException $e) {
            die ("Connection failed: " . $e->getMessage());
        }
    }

    if ($allFields == "yes") {
        header('Location: sendpage4.php');
    }


}


?>


<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">
    <title>Who Is Your Receiver?</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</head>

<form class="form" method="post">
    <fieldset>
        <legend>SELECT RECEIVER</legend>
        <div class="form-item">
            <input class="form-item" placeholder="Receiver's Email" type="email" name="rem">
            <span class="danger">
                <?php echo $errorrem; ?>
            </span>
        </div>
        <div>
            <input class="form-button" type="submit" value="Review" name="submit">
        </div>

        <br>
        <div>
            <a href="sendpage2.php" class="form-button">Back</a>
        </div>
    </fieldset>
</form>