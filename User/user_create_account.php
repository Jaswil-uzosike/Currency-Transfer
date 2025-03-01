<?php
// The following code displays the form for new user account creation 
// It also validates the form data and creates a new currency account in the database.
session_start();
include ('navbar.php');

$exists = true;




$errorex = $errormin = $errormax = "";
$allFields = "yes";

if (isset ($_POST['submit'])) {


    include ('db_conn2.php');

    try {

        if (isset ($_SESSION['user_id'])) {
            $userId = $_SESSION['user_id'];
            $currency = $_POST['cid'];
            $sql = 'SELECT * FROM currencyacc WHERE user_id = :id and currency_id = :id2';
            $stmt = $pdo->prepare($sql);
            $stmt->execute(['id' => $userId, 'id2' => $currency]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                $exists = true;
            } else {
                $exists = false;
            }
        }

    } catch (PDOException $e) {
        die ("Connection failed: " . $e->getMessage());
    }

    if ("" === $_POST['max']) {
        $errormax = "Max income cannot be null";
        $allFields = "no";
    }

    if (1 == $_POST['tid']) {
        $limit = 10000;
    } elseif (2 == $_POST['tid']) {
        $limit = 50000;
    } else {
        $limit = 5000;
    }

    if ($limit <= $_POST['max']) {
        $errormax = "Income limit reached for this account type";
        $allFields = "no";
    }

    if ($exists) {
        echo "<script>alert('Currency account already exists'); </script>";
        $allFields = "no";
    }

    if ($allFields == "yes") {


        include_once ('db_conn.php');

        // prepare and bind
        $stmt2 = $mysqli->prepare("INSERT INTO currencyacc (balance, max_income, type_id, user_id, currency_id) VALUES (?, ?, ?, ?, ?)");
        $stmt2->bind_param('iiiii', $balance, $max_income, $type_id, $userId, $currency_id);

        // set parameters and execute
        $balance = 0.00;
        $max_income = $_POST['max'];
        $type_id = $_POST['tid'];
        $currency_id = $_POST['cid'];

        $stmt2->execute();

        //the logic
        if ($stmt2) {
            echo "<script>alert('Currency Account Creation Successful'); window.location.href='homepage.php';</script>";
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
        <legend>ACCOUNT DETAILS</legend>
        <div class="form-item">
            <label>Account Type</label>
            <?php
            include ('db_conn.php');
            $sql = "SELECT type_id, type_name FROM acctype";
            if ($result = $mysqli->query($sql)) {

                // Create the dropdown list
                echo '<select name="tid">';

                // Loop through the results and create the options
                while ($row = $result->fetch_assoc()) {
                    echo '<option value="' . $row['type_id'] . '">' . $row['type_name'] . '</option>';
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
        <div class="form-item">
            <label>Currency Preference</label>
            <?php
            include ('db_conn.php');
            $sql = "SELECT currency_id, currency_name FROM currency";
            if ($result = $mysqli->query($sql)) {

                // Create the dropdown list
                echo '<select name="cid">';

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
        <div class="form-item">

            <input placeholder="Max income per month" type="number" name="max" min="0" step=".01">
            <span class="danger">
                <?php echo $errormax; ?>
            </span>
        </div>

        <div>
            <input class="form-button" type="submit" value="Create" name="submit">
        </div>

        <br>
        <div>
            <a href="homepage.php" class="form-button">Back</a>
        </div>
    </fieldset>
</form>