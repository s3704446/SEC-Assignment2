<?php
    const USERS_PATH = 'data/users.json';
    const PRODUCT_PATH = 'data/product.json';
    const CART_PATH = 'data/cart.json';

    const USER_SESSION_KEY = 'user';

    const MINUTES_MINIMUM = 1;
    const MINUTES_MAXIMUM = 2400;

    const DATE_FORMAT = 'd/m/Y';
    error_reporting(E_ERROR); 
    ini_set("display_errors","Off");


    session_start();

    //JSON files settings
    function readJsonFile($path) {
        $json = file_get_contents($path);

        return json_decode($json, true);
    }

    function updateJsonFile($data, $path) {
        $json = json_encode($data, JSON_PRETTY_PRINT);
        file_put_contents($path, $json, LOCK_EX);
    }
    function deleteJsonFile($data, $path) {
        $json = file_get_contents($path);
        $json = json_decode($json,true);

        if  (!empty($json[$data['email']])){
            unset($json[$data['email']]);
        }
        $json = json_encode($json, JSON_PRETTY_PRINT);
        file_put_contents($path, $json, LOCK_EX);
    }

 
    function deleteJsonFileUserStatus($id,$path,$email){
        $json = file_get_contents($path);
        $json = json_decode($json,true);
        if  (!empty($json[$email][$id])){
            unset($json[$email][$id]);
        }
        $json = json_encode($json, JSON_PRETTY_PRINT);
        file_put_contents($path, $json, LOCK_EX);
    }
    //display error settings
    function displayError($errors, $name) {
        if(isset($errors[$name]))
            echo "<div class='text-danger' style='text-align:center;color:red'>{$errors[$name]}</div>";
    }
    //display value
    function displayValue($form, $name) {
        if(isset($form[$name]))
            echo 'value="' . htmlspecialchars($form[$name]) . '"';
    }


    function generatePasswordHash($password, $salt = null) {
        if($salt === null)
            $salt = bin2hex(openssl_random_pseudo_bytes(10));
        $blowfish_salt = '$2y$10$' . $salt;

        return crypt($password, $blowfish_salt);
    }


    function verifyPasswordHash($password, $hash) {
        $tokens = explode('$', $hash);
        $salt = $tokens[3];
        return $hash === generatePasswordHash($password, $salt);
    }

    function get_rsa_publickey($filename){
        return openssl_pkey_get_public('file://'.$filename);
    }
    function get_rsa_privatekey($filename){
        return openssl_pkey_get_private('file://'.$filename);
    }
    function rsa_encryption($plaintext, $publicKey){
        openssl_public_encrypt($plaintext, $encrypted, $publicKey);
        openssl_free_key($publicKey);
        return base64_encode($encrypted);
    }
    function rsa_decryption($encrypted, $privateKey){
        openssl_private_decrypt(base64_decode($encrypted), $decrypted, $privateKey);
        openssl_free_key($privateKey);
        return $decrypted;
    }
  
    function readCart() {
        return readJsonFile(CART_PATH);
    }

    function updateCartStats($userStats) {
        updateJsonFile($userStats, CART_PATH);
    }
    function updateCart($form,$email)
    {
        $userStats = readCart();

        if (!empty($userStats[$email][$form['product-name']])){
            $userStats[$email][$form['product-name']] = $form;
        }
        updateCartStats($userStats);
    }
    function deleteCart($form,$email){
        deleteJsonFileUserStatus($form['product-name'], CART_PATH,$email);
    }

    function getCart($email) {
        $cartStats = readCart();

        return isset($cartStats[$email]) ? $cartStats[$email] : [];
    }

    function addCart($form, $email) {
        

        $key = 'product-name';
        if(!isset($form[$key]) || preg_match('/^\s*$/', $form[$key]) === 1);

        $key = 'product-image';
        if(!isset($form[$key]) || preg_match('/^\s*$/', $form[$key]) === 1);

        $key = 'product-price';
        if(!isset($form[$key]) || preg_match('/^\s*$/', $form[$key]) === 1);

        $key = 'product-quantity';
        if(!isset($form[$key]) || preg_match('/^\s*$/', $form[$key]) === 1);

        
            $activity = [
                'product-name' => htmlspecialchars(trim($form['product-name'])),
                'product-price' => htmlspecialchars(trim($form['product-price'])),
                'product-image' => htmlspecialchars(trim($form['product-image'])),
                'product-quantity' => htmlspecialchars(trim($form['product-quantity']))
            ];

            $cartStats = readCart();
            $cartStats[$email][$activity['product-name']] = $activity;

            updateCartStats($cartStats);
        }

        return $errors;
    
    //user settings
    function readUsers() {
        return readJsonFile(USERS_PATH);
    }

    function updateUsers($users) {
        updateJsonFile($users, USERS_PATH);
    }

    function getUser($email) {
        $users = readUsers();

        return isset($users[$email]) ? $users[$email] : null;
    }
    function deleteUser($form){
        deleteJsonFile($form, USER_PATH);
    }
    //login settings
    function isUserLoggedIn() {
        return isset($_SESSION[USER_SESSION_KEY]);
    }

    function getLoggedInUser() {
        return isUserLoggedIn() ? $_SESSION[USER_SESSION_KEY] : null;
    }
    

    //user login
    function loginUser($form) {
        $errors = [];

        //validate email and password
        $key = 'email';
        if(!isset($form[$key]) || filter_var($form[$key], FILTER_VALIDATE_EMAIL) === false)
            $errors[$key] = 'Email is invalid.';

        $key = 'password';
        $ciphertextReceived = $form[$key];
        $privateKey = get_rsa_privatekey('private.key');
	    $decrypted = rsa_decryption($ciphertextReceived, $privateKey);
        if(!isset($form[$key]) || preg_match('/^\s*$/',$form[$key])===1)
            $errors[$key] = 'Password Error!';
            

        if(count($errors) === 0) {
            $user = getUser($form['email']);

            if($user !== null && verifyPasswordHash($decrypted, $user['password-hash']))

                $_SESSION[USER_SESSION_KEY] = $user;
            else
                $errors[$key] = 'Sorry, your email or password is incorrect. Please try again.';
        }

        return $errors;
    }

    //user log out
    function logoutUser() {
        session_unset();

    }

    //register user
    function registerUser($form) {
        $errors = [];

        //validate information
        $key = 'firstname';
        if(!isset($form[$key]) || preg_match('/^\s*$/', $form[$key]) === 1)
            $errors[$key] = 'Please enter your first name.';

        $key = 'lastname';
        if(!isset($form[$key]) || preg_match('/^\s*$/', $form[$key]) === 1)
            $errors[$key] = 'Please enter your last name.';

        $key = 'email';
        if(!isset($form[$key]) || filter_var($form[$key], FILTER_VALIDATE_EMAIL) === false)
            $errors[$key] = 'Please enter a valid email.';
        else if(getUser($form[$key]) !== null)
            $errors[$key] = 'Email is already registered.';


        $key = 'password';
        if(!isset($form[$key]) || preg_match('/^\s*$/',$form[$key])===1)
            $errors[$key] = 'Please enter your password';

        $key = 'confirmPassword';
        if(isset($form['password']) && (!isset($form[$key]) || $form['password'] !== $form[$key]))
            $errors[$key] = 'Passwords do not match.';

        if(count($errors) === 0) {

            $user = [
                'firstname' => htmlspecialchars(trim($form['firstname'])),
                'lastname' => htmlspecialchars(trim($form['lastname'])),
                'email' => htmlspecialchars(trim($form['email'])),
                'password-hash' => generatePasswordHash($form['password'])
            ];
            $users = readUsers();
            $users[$user['email']] = $user;
            updateUsers($users);
        }

        return $errors;
    }

    function readProduct() {
        return readJsonFile(PRODUCT_PATH);
    }

    function getProduct($name) {
        $Product = readProduct();

        return isset($Product[$name]) ? $Product[$name] : null;
    }
