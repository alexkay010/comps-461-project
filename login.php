<?php

include "db_connect.php";
$success_msg = "";
$user_email = "";
$user_password = "";

//gets called when the login form is submitted
if (isset($_POST["btn_login"])) {
    if (isset($_POST['email']) && isset($_POST['password'])) {

        $user_email = trim($_POST['email']);
        $user_password = trim($_POST['password']);

        $is_error = false;

        if (empty($user_email)) {
            $success_msg = 'Email must no be empty';
            $is_error = true;

        } elseif (!filter_var($user_email, FILTER_DEFAULT)) {
            $success_msg = 'That email is invalid';
            $is_error = true;
        }
        if (empty($user_password)) {
            $success_msg = 'Password must not be empty';
            $is_error = true;
        } elseif (strlen($user_password) < 8 || strlen($user_password) > 50) {
            $success_msg = 'Password must be at least 8 characters';
            $is_error = true;
        }

        //if there are no errors then login the user into his account
        if (!$is_error) {

            //check if there is an account with the email the user provided
            $sql = "SELECT UserID,FullName,Email,UserPassword FROM userregister WHERE Email = ?";
            if ($stmt = $con->prepare($sql)) {

                $stmt->bind_param("s", $user_email);
                if ($stmt->execute()) {
                    $stmt->store_result();

                    // if there is an account then continue
                    if ($stmt->num_rows == 1) {

                        // Bind result variables
                        $stmt->bind_result($id, $fullname, $email, $password);
                        if ($stmt->fetch()) {

                            //check if the password provided by the user matches the one in the database
                            if (password_verify($user_password, $password)) {
                                //if the password is correct then start a new user session
                                session_start();
                                // Store data in session variables
                                $_SESSION["loggedIn"] = true;
                                $_SESSION["user_id"] = $id;
                                $_SESSION["username"] = $fullname;
                                $_SESSION["email"] = $email;

                                // Take the user to the index/home page of the application
                                header("location: index.php");
                            } else {
                                $success_msg = 'Incorrect Email or Password';
                            }
                        }
                    } else {
                        echo 'Email or Password is incorrect';
                    }
                }
            }
        }
    } else {
        header("location: login.php");
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="./css/bootstrap.css" type="text/css">
    <link rel="stylesheet" href="./css/main.css" type="text/css">
</head>

<body>
    <div class="container">
        <div class="row mt-5">


            <div class="col-md-5 col-12 offset-md-4 p-md-5 p-sm-3 my-5 login-box">
                <?php
if (isset($success_msg) && $success_msg != "") {
    echo ' <div class="alert alert-danger alert-dismissible fade show" role="alert">

                <strong>' . $success_msg . '</strong>
            </div>';
}
?>
                <form action="login.php" method="post" autocomplete="off">
                    <h4 class="text-uppercase">Enter credentials to login</h4>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" class="form-control" name="email" id="email" placeholder="enter your email"
                            value="<?php echo $user_email ?>">
                    </div>

                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" class="form-control" name="password" id="password"
                            placeholder="enter password" value="<?php echo $user_password ?>">
                    </div>

                    <input type="submit" value="Login" class="form-control btn login-button" name="btn_login">
                </form>


                <p class=" text-center ml-5">Don't have an account <a class="login-link" href="registeration.php">Sign
                        up</a>
                </p>
            </div>

        </div>
    </div>

    <?php set_footer()?>