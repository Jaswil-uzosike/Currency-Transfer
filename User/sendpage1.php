<?php
// The code below is to collect and save in session variables the amount to be sent, the currency to and the currency from
session_start();
include('navbar.php');
$userId = $_SESSION['user_id'];


$curr;
$bal = 0;
$errorams = "";
$allFields = "yes";

include('db_conn2.php');

if (isset($_POST['submit'])) {


    try {

        if (isset($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $currf = $_POST["cfid"];

            $sql2 = 'SELECT * FROM currencyacc WHERE user_id = :id and currency_id = :id2';
            $stmt2 = $pdo->prepare($sql2);
            $stmt2->bindParam(':id', $userId);
            $stmt2->bindParam(':id2', $currf);
            $stmt2->execute();
            $user2 = $stmt2->fetch(PDO::FETCH_ASSOC);

            if ($user2) {
                $bal = $user2['balance'];
                if ($user2['currency_id'] == 1) {
                    $curr = "GBP";
                    $currfs = "£";
                }
                if ($user2['currency_id'] == 2) {
                    $curr = "USD";
                    $currfs = "$";
                }
                if ($user2['currency_id'] == 3) {
                    $curr = "EUR";
                    $currfs = "€";
                }
                //echo $res;
            } else {
                $allFields = "no";
                $errorams = "You don't have this currency account";
            }
        } else {
            echo "No user ID set in session.";
        }

    } catch (PDOException $e) {
        die("Connection failed: " . $e->getMessage());
    }


    if ($allFields == "yes") {

        if ($_POST['ams'] < 0) {
            $allFields = "no";
            $errorams = "Wrong Input";
        }

        if ($_POST['ams'] > $bal) {
            $allFields = "no";
            $errorams = "Insufficient Balance";
        }
    }

    if ($allFields == "yes") {
        $_SESSION['amount_sent'] = $_POST['ams'];
        $_SESSION['currency_to'] = $_POST['ctid'];
        $_SESSION['currency_from'] = $currf;
        $_SESSION['currency_from_sign'] = $currfs;
        header('Location: sendpage2.php');
    }

}
?>


<!DOCTYPE html>
<html>

<head>
    <link rel="stylesheet" type="text/css" href="../CSS/style.css">
    <title>How Much Do You Want To Transfer</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
        crossorigin="anonymous"></script>
</head>

<form class="form" method="post">
    <fieldset>
        <legend></legend>
        <div class="form-item">
            <input class="form-item" placeholder="Enter Amount" type="number" name="ams">
            <span class="danger">
                <?php echo $errorams; ?>
            </span>
        </div>



        <div class="row mb-4">
            <div class="col-md-6 d-flex justify-content-center">
                <div class="form-item">
                    <label>Currency From</label>
                    <?php
                    include('db_conn.php');
                    $sql = "SELECT currency_id, currency_name FROM currency";
                    if ($result = $mysqli->query($sql)) {

                        // Create the dropdown list
                        echo '<select name="cfid">';

                        // Loop through the results and create the options
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['currency_id'] . '">' . $row['currency_name'] . '</option>';
                        }

                        // Close the dropdown list
                        echo '</select>';

                        // Free the result set
                        $result->free();
                    }
                    // Close the database connection
                    $mysqli->close();

                    ?>
                </div>
            </div>
        </div>

        <div class="row mb-4">
            <div class="col-md-6 d-flex justify-content-center">
                <div class="form-item">
                    <label>Currency To</label>
                    <?php
                    include('db_conn.php');
                    $sql = "SELECT currency_id, currency_name FROM currency";
                    if ($result = $mysqli->query($sql)) {

                        // Create the dropdown list
                        echo '<select name="ctid">';

                        // Loop through the results and create the options
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row['currency_id'] . '">' . $row['currency_name'] . '</option>';
                        }

                        // Close the dropdown list
                        echo '</select>';

                        // Free the result set
                        $result->free();
                    }
                    // Close the database connection
                    $mysqli->close();

                    ?>
                </div>
            </div>
        </div>

        <div>
            <input class="form-button" type="submit" value="Next" name="submit">
        </div>

        <br>
            <div>
                    <a href="homepage.php" class="form-button">Back</a>
            </div>
    </fieldset>
</form>