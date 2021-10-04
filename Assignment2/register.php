<?php require_once('includes/functions.php'); ?>
<?php
    $errors = [];
    if(isset($_POST['register'])) {
        $errors = registerUser($_POST);

        if(count($errors) === 0) {
            echo "<script>alert('Successful!');parent.location.href='login.php';</script>";
            exit();
        }
    }
?>
<!DOCTYPE html>
<html>

<head>
    <title>Register</title>
    <link rel="stylesheet" href="css/breadcrumbs.css">
    <link rel="stylesheet" href="css/myStyle.css">
    <script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
    <script src="javaScript/jquery.breadcrumbs-generator.js"></script>
    <script>
        $(function () {
            $('#breadcrumbs').breadcrumbsGenerator();
        });
    </script>
</head>

<body>
    <!--header-->



    <form class="user-form" id="register-form" method="post">
        <div class="form-content">
            <h1>Register</h1>
                <label for="firstname">First name:</label><br>
                <input type="text" id="firstname" name="firstname" <?php displayValue($_POST, 'firstname'); ?> />
                <?php displayError($errors, 'firstname'); ?>

                <br><br>

                <label>Last name:</label><br>
                <input type="text" id="lastname" name="lastname" <?php displayValue($_POST, 'lastname'); ?> />
                <?php displayError($errors, 'lastname'); ?>


                <br><br>

                <label>Email:</label><br>
                <input type="text" id="email" name="email" <?php displayValue($_POST, 'email'); ?> />
                <?php displayError($errors, 'email'); ?>

                <br><br>
                <label>Password:</label><br>
                <input type="password" id="password" name="password" />
                <?php displayError($errors, 'password'); ?>


                <br><br>
                <label>Confirm password:</label><br>
                <input type="password" id="confirmPassword" name="confirmPassword" />
                <?php displayError($errors, 'confirmPassword'); ?>


                <br><br>

                <button type="submit" class="submit" name="register" value="register">Register</button>
                <a href="login.php">back</a>
        </div>
    </form>

    <!--footer-->


</body>

</html>