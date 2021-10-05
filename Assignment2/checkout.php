<?php require_once('includes/authorise.php'); ?>
<?php
    $user = getLoggedInUser();
    $cart = getCart($user['email']);

    if(isset($_POST['pay'])){
        header('Location: index.php' );
    }
?>
<!DOCTYPE html>

<html lang="en">

<head>
    <link rel="stylesheet" href="css/myStyle.css">
    <script type="text/javascript" src="js/jquery.js"></script>
    <title>Checkout</title>
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


        <table id="cart-table">
            <h1>Checkout</h1>
            <tr>
                <th></th>
                <th>Name</th>
                <th>Quantity</th>
                <th>Price($)</th>
            </tr>
            <?php foreach($cart as $key => $value) { ?>
            <tr>
                <form id="cart-form" method="post">


                    <td><img class="cart-image" src="<?= $value['product-image'] ?>"
                            alt="<?= $value['product-name'] ?>"><input type="hidden" name="product-image"
                            value="<?= $value['product-image'] ?>" />
                    </td>
                    <td><?= $value['product-name'] ?> <input type="hidden" id="product-name" name="product-name"
                            value="<?= $value['product-name'] ?>" /></td>
                    <td><?= $value['product-quantity'] ?><input type="hidden" id="product-quantity" name="product-quantity"
                            value="<?= $value['product-quantity'] ?>" min="0"></td>
                    <td id="subPrice"><?php echo $value['product-quantity']*$value['product-price']?> <input
                            type="hidden" name="product-price" value="<?= $value['product-price']?>" /></td>
                </form>
            </tr>


            <?php } ?>

        </table>

        <span id="sum"></span>

        <hr>
        <h1>Payment</h1>
        <form id="payment" method="post">
            <label>Credit Card No:</label><br>
                <input type="text" id="credit-card" name="credit-card">
                <br><br>
            <label>Password:</label><br>
                <input type="password" id="password" name="password">
                <br><br>
                <button name="pay" type="submit" >Pay Now!</button>
            </form>

        <script type="text/javascript">
           var table = document.getElementById("cart-table"), sumVal = 0;
            
            for(var i = 1; i < table.rows.length; i++)
            {
                sumVal = sumVal + parseInt(table.rows[i].cells[3].innerHTML);
            }
            
            document.getElementById("sum").innerHTML = "Total Price: $" + sumVal;
            console.log(sumVal);
        </script>


    </div>
</body>

</html>