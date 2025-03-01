<?php
// The code below displays the receivers information. It updates the records and balances of the receiver. 
// It also applies the suspicious algorithm by calling the function.
session_start();
include ('navbar.php');

$userId = $_SESSION['user_id'];
$userId2 = $_SESSION['user_id'];
$ams = $_SESSION['amount_to_add'];
$profit = $_SESSION['profit'];
$currency_to = $_SESSION['currency_to'];
$currency_from = $_SESSION['currency_from'];
$conv = $_SESSION['amount_added'];


$fname;
$lname;

$currency_from;
$senderbal;
$finalsenderbal;


$host = 'localhost';
$dbname = 'my_stage2';
$username = 'root';
$password = '';


$allFields = "yes";

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


    if (isset($_SESSION['user_id'])) {

        $sql2 = "SELECT account_no, acc_name, bank_name FROM bank WHERE user_id = $userId";
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->execute();
        $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

        if ($row2) {
            $bankname = $row2["bank_name"];
            $accountno = $row2["account_no"];
            $accountname = $row2["acc_name"];
        }

        $sql3 = "SELECT fname, lname, username FROM useracc WHERE user_id = $userId";
        $stmt3 = $pdo->prepare($sql3);
        $stmt3->execute();
        $row3 = $stmt3->fetch(PDO::FETCH_ASSOC);

        if ($row3) {
            $fname = $row3["fname"];
            $lname = $row3["lname"];
            $Receiver_username = $row3["username"];
        }
    } else {
        echo "No receiver user ID set in session.";
        $allFields = "no";
    }
} catch (PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}
$pdo = null;

if (isset($_POST['submit'])) {


    if ($allFields == "yes") {

        try {
            $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            if (isset($_SESSION['user_id'])) {

                $receivercurrency = $_SESSION['currency_to'];

                $sql3 = 'SELECT * FROM currencyacc WHERE user_id = :id AND currency_id = :id2';
                $stmt3 = $pdo->prepare($sql3);
                $stmt3->bindParam(':id', $userId);
                $stmt3->bindParam(':id2', $receivercurrency);
                $stmt3->execute();
                $user3 = $stmt3->fetch(PDO::FETCH_ASSOC);



                if ($user3) {

                    $receiverbal = $user3['balance'];
                    $finalreceiverbal = $receiverbal + $conv;
                    $_SESSION['Final_receiver_balance'] = $finalreceiverbal;
                } else {
                    $errorrem = "The recipient account does not exist";
                    $allFields = "no";
                }

            } else {
                echo "No user ID set in session.";
                $allFields = "no";
            }



        } catch (PDOException $e) {
            die("Connection failed: " . $e->getMessage());
        }
    }

    if ($allFields == "yes") {
        include ('suspicious_algorithm.php');
        $legit = suspicious2();
        if ($legit) {
            include_once ('db_conn.php');
            $date = date('Y-m-d');
            $state = "success";
            $finalrecieverbal = $_SESSION['Final_receiver_balance'];
            // prepare and bind
            $stmt = $mysqli->prepare("INSERT INTO transactions (amount_sent, amount_received, senderacc_id, receiveracc_id, trans_date, currency_from_id, currency_to_id, trans_state) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ddiisiis', $ams, $conv, $userId, $userId2, $date, $currency_from, $currency_to, $state);


            // prepare and bind
            $stmt2 = $mysqli->prepare("UPDATE currencyacc SET balance = ? WHERE user_id = $userId and currency_id = ?");
            $stmt2->bind_param('di', $finalrecieverbal, $currency_to);


            $stmt2->execute();
            $stmt->execute();



            if ($stmt) {
                $lastId = $mysqli->insert_id;
                $stmt4 = $mysqli->prepare("INSERT INTO profit (transaction_id, trans_profit_GBP, trans_date) VALUES (?, ?, ?)");
                $stmt4->bind_param('ids', $lastId, $profit, $date);

                $stmt4->execute();
            }

            $mysqli->close();

            if ($stmt && $stmt2 && $stmt3 && $stmt4) {
                echo "<script>alert('Bank Request Successful'); window.location.href='homepage.php';</script>";
                exit;
            }
        } else {

            include_once ('db_conn.php');
            $date = date('Y-m-d');
            $state = "failed";
            $finalrecieverbal = $_SESSION['Final_receiver_balance'];
            $nextid = $_SESSION['nextid'];
            $description = "Receiver went over their max spend limit";
            $adminid = mt_rand(1, 2);

            $stmt = $mysqli->prepare("INSERT INTO transactions (amount_sent, amount_received, senderacc_id, receiveracc_id, trans_date, currency_from_id, currency_to_id, trans_state) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param('ddiisiis', $ams, $conv, $userId, $userId2, $date, $currency_from, $currency_to, $state);


            $stmt->execute();

            $mysqli->close();

            try {
                $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
                $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                $stmt = $pdo->prepare("INSERT INTO suspicious (sa_description, transaction_id, admin_id) VALUES (:val1, :val2, :val3)");

                $stmt->bindParam(':val1', $description);
                $stmt->bindParam(':val2', $nextid);
                $stmt->bindParam(':val3', $adminid);


                $stmt->execute();

                $pdo = NULL;
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }


            if ($stmt) {
                session_destroy();
                echo "<script>alert('Bank Request Unsuccessful'); window.location.href='../index.php';</script>";
                exit;
            }

        }



    }


}


?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="stylesheet" href="../CSS/style2.css">
    <title>View Transaction</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</head>

<div class="container">
    <div class="card cart">
        <label class="title">YOUR DETAILS</label>
        <div class="steps">
            <div class="checkout">
                <div>
                    <p>Full Name:
                        <?php echo $fname . " " . $lname; ?>
                    </p>
                    <p>Username:
                        <?php echo $Receiver_username; ?>
                    </p>

                    <p>Bank Name:
                        <?php echo $bankname; ?>
                    </p>
                    <p>Acount Name:
                        <?php echo $accountname; ?>
                    </p>
                    <p>Account Number:
                        <?php echo $accountno; ?>
                    </p>

                    <form method="post">
                        <input class="checkout-btn" type="submit" value="Request" name="submit">
                    </form>
                    <br>
                    <div>
                        <a href="addpage2.php" class="checkout-btn">Back</a>
                    </div>
                </div>

            </div>
        </div>
    </div>


</div>