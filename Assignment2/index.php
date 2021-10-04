<?php require_once('includes/authorise.php'); ?>
<?php
    $user = getLoggedInUser();

    if(isset($_POST['addCart'])){
        addCart($_POST, $user['email']);
        echo "<script>alert('Product has been added into shopping cart!')</script>";
    }


?>
<!DOCTYPE html>
<link rel="stylesheet" href="css/myStyle.css">
<script type="text/javascript" src="js/jquery.js"></script>
<html lang="en">

<head>
</head>

<body>

    <div class="container">
        <ul>
            <a href='index.php'>
                <li>Home</li>
            </a>
            <a href='logout.php'>
                <li>Logout</li>
            </a>
            <a href='cart.php'>
                <li>Shopping Cart</li>
            </a>
        </ul>

        <?php foreach(readProduct() as $key => $value) {?>
        <div class="product-details">
            <img src="<?= $value['image'] ?>" alt="<?= $value['name'] ?>">
            <h2>$<?= $value['price'] ?> </h2>
            <div><?= $value['name'] ?> </div>
            <form method="post">
                <input type="hidden" name="product-name" value="<?= $value['name'] ?>">
                <input type="hidden" name="product-price" value="<?= $value['price'] ?>">
                <input type="hidden" name="product-image" value="<?= $value['image'] ?>">
                <input type="hidden" name="product-quantity" value="1">
                <br>
                <button name="addCart">Add to Cart</button>
            </form>
        </div>
        <?php } ?>




    </div>
</body>

</html>