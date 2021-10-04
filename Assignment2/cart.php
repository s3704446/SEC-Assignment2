<?php require_once('includes/authorise.php'); ?>
<?php
    $user = getLoggedInUser();
    $cart = getCart($user['email']);

    if(isset($_POST['delete'])){
        deleteCart($_POST, $user['email']);
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



        <table>
            <h1>Shopping Cart</h1>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Price</th>
            </tr>
            <?php foreach($cart as $key => $value) { ?>
            <tr>
                <form method="post">
                    <input type="hidden" value="<?= $value['product-image'] ?>" <?php displayValue($_POST, 'id'); ?> />
                <td><img class="cart-image" src="<?= $value['product-image'] ?>" alt="<?= $value['product-name'] ?>">
                </td>
                <td><?= $value['product-name'] ?> </td>
                <td><input type="number" value="<?= $value['product-quantity'] ?>" min="0"></td>
                <td><?php echo $value['product-quantity']*$value['product-price']?></td>
                <td><button class="delete-btn" name="delete">X</button></td>
            </tr>

            <?php } ?>


        </table>

    </div>
</body>

</html>