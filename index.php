<?php
//The following code displays the form for the user to login
// It also checks if the user has entered the correct email and password and then starts a session if yes.
session_start();


$host = 'localhost';
$dbname = 'my_stage2';
$username = 'root';
$password = '';

$res;
$pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$errorem = $errorpw = "";


if (isset($_POST['submit'])) {


  $email = $_POST['em'];
  $password = $_POST['pw'];

  
  if ("" === $_POST['em']) {
    $errorem = "email cannot be null";
  }


  if ("" === $_POST['pw']) {
    $errorpw = "password cannot be null";
  }

  $sql = 'SELECT * FROM useracc WHERE email = :id';
  $stmt = $pdo->prepare($sql);
  $stmt->execute(['id' => $email]);
  $user = $stmt->fetch(PDO::FETCH_ASSOC);



  if ($user) {
    $res = $user['passwords'];

    if ( $res === $_POST['pw'] && $user['acc_status'] == "active") {

      $_SESSION['user_id'] = $user['user_id'];
      // echo "<script>alert('Login Successful'); </script>";
      // header('Location: homepage.php');
      echo "<script>alert('Login Successful'); window.location.href='User/homepage.php';</script>";
      exit;

    } elseif ( $res === $_POST['pw'] && $user['acc_status'] == "suspended") {
      header('Location: Login/user_suspended_page.php');
    } elseif ( $res === $_POST['pw'] && $user['acc_status'] == "deactivated") {
      header('Location: Login/user_deactivated_page.php');
    } elseif ( $res === $_POST['pw'] && $user['acc_status'] == "closed") {
      header('Location: Login/user_closed_page.php');
    } else {
      $errorem = "incorrect Details";
      $errorpw = "incorrect Details";
      echo "<script>alert('Wrong details'); </script>";
    }
  } else {
    echo "<script>alert('Email not found'); </script>";
  }

}

?>



<!DOCTYPE html>
<html>

<head>
  <title>Login Page</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
    integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
  <script defer src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"
    integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p"
    crossorigin="anonymous"></script>
</head>

<body>
  <section class="vh-100" style="background-color: #9A616D;">
    <div class="container py-5 h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col col-xl-10">
          <div class="card" style="border-radius: 1rem;">
            <div class="row g-0">
              <div class="col-md-6 col-lg-5 d-none d-md-block">
                <img src="Resources/SP_image2.png" alt="login form" class="img-fluid" style="border-radius: 1rem 0 0 1rem;" />
              </div>
              <div class="col-md-6 col-lg-7 d-flex align-items-center">
                <div class="card-body p-4 p-lg-5 text-black">

                  <form method="post">

                    <div class="d-flex align-items-center mb-3 pb-1">
                      <i class="fas fa-cubes fa-2x me-3" style="color: #ff6219;"></i>
                      <span class="h1 fw-bold mb-0">TRANSFERX</span>
                    </div>

                    <h5 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Sign into your account</h5>

                    <div class="form-outline mb-4">
                      <input type="email" name="em" class="form-control form-control-lg" />
                      <label class="form-label">Email address</label>
                    </div>

                    <div class="form-outline mb-4">
                      <input type="password" name="pw" class="form-control form-control-lg" />
                      <label class="form-label">Password</label>
                    </div>

                    <div class="pt-1 mb-4">
                      <button class="btn btn-dark btn-lg btn-block" type="submit" name="submit">Login</button>
                    </div>

                    <a class="small text-muted" href="Login/adminlogin.php" >Admin sign in</a>
                    <br>
                    <a class="small text-muted" href="Login/forgot_password_auth.php">Forgot password?</a>
                    <p class="mb-5 pb-lg-2" style="color: #393f81;">Don't have an account? <a
                        href="Register/personal_details.php" style="color: #393f81;">Register here</a></p>
                    
                  </form>

                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

</body>

</html>