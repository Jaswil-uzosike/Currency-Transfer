<?php
//The following code displays the form for the user to login
// It also checks if the user has entered the correct email and password and then starts a session if yes.
session_start();
include ("db_conn2.php");

$res;
$errorem = $errorpw = "";


if (isset($_POST['submit'])) {


    $email = $_POST['em'];
    $password = $_POST['pw'];


    $sql = 'SELECT * FROM admins WHERE email = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);


    if ("" === $_POST['em']) {
        $errorem = "email cannot be null";
    }


    if ("" === $_POST['pw']) {
        $errorpw = "password cannot be null";
    }

    if ($user) {
        $res = $user['passwords'];


        if ($res === $_POST['pw']) {
            $_SESSION['admin_id'] = $user['admin_id'];
            echo "<script>alert('Login Successful'); window.location.href='../Admin/adminhomepage.php';</script>";
            exit;
        } else {
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

<section class="vh-100" style="background-color: #508bfc;">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                <div class="card shadow-2-strong" style="border-radius: 1rem;">
                    <div class="card-body p-5 text-center">

                        <form method="post">
                            <h3 class="mb-5">TRANSFERX</h3>
                            <h3 class="mb-5">Admin Sign in</h3>

                            <div class="form-outline mb-4">
                                <input type="email" name="em" class="form-control form-control-lg" />
                                <label class="form-label" for="typeEmailX-2">Email</label>
                            </div>
                            <span class="danger">
                                <?php echo $errorem; ?>
                            </span>

                            <div class="form-outline mb-4">
                                <input type="password" name="pw" class="form-control form-control-lg" />
                                <label class="form-label" for="typePasswordX-2">Password</label>
                            </div>
                            <span class="danger">
                                <?php echo $errorpw; ?>
                            </span>

                            <div class="form-check d-flex justify-content-start mb-4">
                                <p class="form-check-label" style="color: #393f81;">Not an admin? <a href="../index.php"
                                        style="color: #393f81;">User Login</a></p>
                            </div>

                            <button class="btn btn-primary btn-lg btn-block" type="submit" name="submit">Login</button>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>