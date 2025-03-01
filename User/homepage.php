<?php
// The following code is to retrieve and display data on the homepage. 
// It calls the navbar and user charts pages.
session_start();
include("navbar.php");


$current_date = date('Y-m-d');
$current_month = date('m', strtotime($current_date));
$state = "success";

include ("db_conn2.php");

try {

	if (isset($_SESSION['user_id'])) {
		$userId = $_SESSION['user_id'];

		$sql = 'SELECT * FROM useracc WHERE user_id = :id';
		$stmt = $pdo->prepare($sql);
		$stmt->execute(['id' => $userId]);
		$user = $stmt->fetch(PDO::FETCH_ASSOC);

		$sql2 = 'SELECT * FROM currencyacc WHERE user_id = :id AND currency_id = 1';
		$stmt2 = $pdo->prepare($sql2);
		$stmt2->execute(['id' => $userId]);
		$user2 = $stmt2->fetch(PDO::FETCH_ASSOC);

		$sql3 = 'SELECT * FROM currencyacc WHERE user_id = :id AND currency_id = 2';
		$stmt3 = $pdo->prepare($sql3);
		$stmt3->execute(['id' => $userId]);
		$user3 = $stmt3->fetch(PDO::FETCH_ASSOC);

		$sql4 = 'SELECT * FROM currencyacc WHERE user_id = :id AND currency_id = 3';
		$stmt4 = $pdo->prepare($sql4);
		$stmt4->execute(['id' => $userId]);
		$user4 = $stmt4->fetch(PDO::FETCH_ASSOC);

		if ($user) {
			$name2 = $user['lname'];
			$name = $user['fname'];
		} else {
			echo "No user found with ID " . $userId;
		}

		if ($user2) {
			$bal1 = $user2['balance'];
			$sign1 = "£";
			$text1 = "Balance: " . $sign1 . $bal1;
		} else {
			$text1 = "Account not created";
		}

		if ($user3) {
			$bal2 = $user3['balance'];
			$sign2 = "$";
			$text2 = "Balance: " . $sign2 . $bal2;
		} else {
			$text2 = "Account not created";
		}

		if ($user4) {
			$bal3 = $user4['balance'];
			$sign3 = "€";
			$text3 = "Balance: " . $sign3 . $bal3;
		} else {
			$text3 = "Account not created";
		}
	} else {
		echo "No user ID set in session.";
	}

} catch (PDOException $e) {
	die("Connection failed: " . $e->getMessage());
}


if (isset($_POST['send'])) {
	header('Location: sendpage1.php');
}

if (isset($_POST['add'])) {
	header('Location: addpage1.php');
}

?>

<!DOCTYPE html>
<html>

<head>
	<link rel="stylesheet" type="text/css" href="../CSS/style3.css">
	<title>Login Page</title>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
		integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
	<script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
		integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
		crossorigin="anonymous">
	</script>
	<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<div>
	<main>
		<center>
			<h2 style="font-family: 'Work Sans', sans-serif">
				<?php echo "Welcome " . $name . " " . $name2; ?>
			</h2>
		</center>

		<form method="post">
			<center>
				<div class="col-md-6 d-flex justify-content-center">
					<div class="col-md-1 d-flex justify-content-center">
						<div class="form-check mb-7 mb-md-7">
							<button type="submit" name="send" class="btn btn-primary btn-block mb-4">Send</button>
						</div>
						<div class="form-check mb-7 mb-md-7">
							<button type="submit" name="add" class="btn btn-primary btn-block mb-4">Request</button>
						</div>
					</div>

				</div>
			</center>
		</form>
	</main>
	<div class="cards">
		<div class="card red">
			<p class="tip">GBP Account</p>
			<p class="second-text">
				<?php echo $text1; ?>
			</p>
		</div>
		<div class="card blue">
			<p class="tip">USD Account</p>
			<p class="second-text">
				<?php echo $text2; ?>
			</p>
		</div>
		<div class="card green">
			<p class="tip">EUR Account</p>
			<p class="second-text">
				<?php echo $text3; ?>
			</p>
		</div>
	</div>

	<br>
	<br>

	<?php include("user_charts.php"); ?>

	<br>
	<br>
	
	<?php include("../Resources/footer.php"); ?>
	
</div>