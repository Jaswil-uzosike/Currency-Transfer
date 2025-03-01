<?php
// The following code displays user profile details
session_start();
include("navBar.php");

$userId = $_SESSION['user_id'];
$fname;
$lname;
$username;
$email;
$phone;

include("db_conn2.php");
$sql = "SELECT fname, lname, username, email, phone_number FROM useracc WHERE user_id = $userId";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$row1 = $stmt->fetch(PDO::FETCH_ASSOC);

$sql2 = "SELECT account_no, acc_name, bank_name, currency_id FROM bank WHERE user_id = $userId";
$stmt2 = $pdo->prepare($sql2);
$stmt2->execute();
$row2 = $stmt2->fetch(PDO::FETCH_ASSOC);

if($row1)
{
    $fname = $row1["fname"];
    $lname =  $row1["lname"];
    $username =  $row1["username"];
    $email = $row1['email'];
    $phone = $row1['phone_number'] ;
}

if($row2)
{
    $bankname = $row2["bank_name"];
    $accountno =  $row2["account_no"];
    $accountname =  $row2["acc_name"];
    if  ($row2['currency_id'] == 1) {
        $curr = "GBP";
    }
    if  ($row2['currency_id'] == 2) {
        $curr = "USD";
    }
    if  ($row2['currency_id'] == 3) {
        $curr = "EUR";
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
    <label class="title">PROFILE DETAILS</label>
    <div class="steps">
      <div class="step">
        <div>
          <span>PERSONAL DETAILS</span>
          <p>Full Name: <?php echo $fname ." ". $lname;?></p>
          <p>Username: <?php echo $username;?></p>

        </div>
        <hr>
        <div>
          <span>CONTACT DETAILS</span>
          <p>Email: <?php echo $email;?></p>
          <p>Phone Number: <?php echo $phone;?></p>
        </div>
        <hr>
        <div class="promo">
          <span>BANK DETAILS</span>
          <p>Bank Name: <?php echo $bankname;?></p>
          <p>Acount Name: <?php echo $accountname;?></p>
          <p>Account Number: <?php echo $accountno;?></p>
          <p>Account Currency: <?php echo $curr;?></p>
        </div>
       
      </div>
    </div>
  </div>

  
</div>