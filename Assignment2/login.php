<?php require_once('includes/functions.php'); ?>
<?php
    $errors = [];
    if(isset($_POST['login'])) {
        $errors = loginUser($_POST);

        if(count($errors) === 0) {
            echo "<script>alert('Login Successful!');location.href='index.php'</script>";
            exit();
        }
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <link rel="stylesheet" href="css/myStyle.css">
</head>

<body>



    <div class="row">
        <form class="user-form" method="post">
            <div class="form-content">
                <h1>Login</h1>
                <label for="secondname">Email</label><br>
                <input type="text" id="email" name="email" <?php displayValue($_POST, 'email'); ?> />
                <?php displayError($errors, 'email'); ?>
                <br><br>
                <label>Password</label><br>
                <input type="password" id="password" name="password" />
                <?php displayError($errors, 'password'); ?>
            <br><br>
            <button type="submit" class="btnlogin" name="login">Login</button>
            <a href="register.php">Register</a>
            </div>
        </form>


    </div>
</body>

</html>