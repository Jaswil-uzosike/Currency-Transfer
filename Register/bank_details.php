<?php
session_start();

$res;

include ("db_conn2.php");

try {
 
    if (isset($_SESSION['username'])) {
        $userId = $_SESSION['username'];
        $sql = 'SELECT * FROM useracc WHERE username = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $userId]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $res =  $user['user_id'];
            //echo $res;
        } else {
            echo "No user found with ID " . $userId;
        }
    } else {
        echo "No user ID set in session.";
    }
    
} catch(PDOException $e) {
    die("Connection failed: " . $e->getMessage());
}



$erroracno = $errorssc = $errorbn = $erroracna = "";
$allFields = "yes";

if (isset($_POST['submit'])) {


    if ("" === $_POST['bn']) {
		$errorbn = "Bank name cannot be null";
		$allFields = "no";
	}

    if ("" === $_POST['acno']) {
		$erroracno = "Account number  cannot be null";
		$allFields = "no";
	}

    if ("" === $_POST['acna']) {
		$erroracna = "Account name  cannot be null";
		$allFields = "no";
	}

    if ("" === $_POST['ssc']) {
		$errorssc = "Sort/Swift Code cannot be null";
		$allFields = "no";
	}

    if (strlen($_POST['acno']) < 8) {
		$erroracno = "Account number contains No less than 8 digits";
		$allFields = "no";
	}

    if ($allFields == "yes") {

        include_once('db_conn.php');

    
        // prepare and bind
        $stmt = $mysqli->prepare("INSERT INTO bank (account_no, acc_name, sort_swift_code, currency_id, user_id, bank_name) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('issiis', $account_no, $acc_name, $sort_swift_code, $currency_id, $user_id, $bank_name);
        
    
        // prepare and bind
        $stmt2 = $mysqli->prepare("UPDATE useracc SET acc_status = ? WHERE user_id = $res");
        $stmt2->bind_param('s', $status);
    
        // set parameters and execute
        $account_no = $_POST['acno'];
        $acc_name = $_POST['acna'];
        $sort_swift_code = $_POST['ssc'];
        $currency_id = $_POST['cid'];
        $user_id = $res;
        $bank_name = $_POST['bn'];
        $status = "active";
    
        $stmt->execute();
        $stmt2->execute();
    
        
       
        if ($stmt && $stmt2) {
            session_destroy();
            echo "<script>alert('Registration Successful'); window.location.href='../index.php';</script>";
            exit; 
        } else {
            echo "<script>alert('Registration Failed'); </script>";
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
		<legend>BANK DETAILS</legend>
        <div class="form-item">

			<input placeholder="Bank Name" type="text" name="bn">
			<span class="danger">
				<?php echo $errorbn; ?>
			</span>
		</div>
		<div class="form-item">
			<input class="form-item" placeholder="Account Number" type="number" name="acno">
			<span class="danger">
				<?php echo $erroracno; ?>
			</span>
		</div>
		<div class="form-item">

			<input placeholder="Account Name" type="text" name="acna">
			<span class="danger">
				<?php echo $erroracna; ?>
			</span>
		</div>
		<div class="form-item">

			<input placeholder="Sort/Swift Code" type="text" name="ssc">
			<span class="danger">
				<?php echo $errorssc; ?>
			</span>
		</div>
        <div class="form-item">
            <label>Account Currency</label>
            <?php
            include('db_conn.php');
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
		<div>
			<input class="form-button" type="submit" value="Finish" name="submit">
		</div>
	</fieldset>
</form>