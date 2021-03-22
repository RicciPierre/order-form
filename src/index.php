<?php
declare(strict_types=1);

//we are going to use session variables so we need to enable sessions
session_cache_expire(30);
session_start();


function whatIsHappening()
{
    echo '<h2>$_GET</h2>';
    var_dump($_GET);
    echo '<h2>$_POST</h2>';
    var_dump($_POST);
    echo '<h2>$_COOKIE</h2>';
    var_dump($_COOKIE);
    echo '<h2>$_SESSION</h2>';
    var_dump($_SESSION);
}

//function to test the inputs
function Test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}

//required inputs, valid format, order confirmed/refused and session
$email = $street = $streetnumber = $city = $zipcode = '';
$emailErr = $streetErr = $streetNumErr = $cityErr = $zipCodeErr = '';
$confirme = $refuse = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (empty($_POST['email'])) {
        $emailErr = '<div class="alert alert-warning" role="alert"> Email is required </div>';
    } else {
        $email = test_input($_POST['email']);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = '<div class="alert alert-warning" role="alert"> Invalid email format</div>';
        }
    }

    if (empty($_POST['street'])) {
        $streetErr = '<div class="alert alert-warning" role="alert"> Name of your street is required</div>';
    } else {
        $street = test_input($_POST['street']);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $street)) {
            $streetErr = '<div class="alert alert-warning" role="alert"> Only letters and white space allowed</div>';
        }
    }
    
    if (empty($_POST['streetnumber'])) {
        $streetNumErr = '<div class="alert alert-warning" role="alert"> Street number is required</div>';
    } else {
        $streetnumber = test_input($_POST['streetnumber']);
        if (!ctype_digit($streetnumber)) {
            $streetNumErr = '<div class="alert alert-warning" role="alert"> Numbers only</div>';
        }
    }
    
    if (empty($_POST['city'])) {
        $cityErr = '<div class="alert alert-warning" role="alert"> Your city is required</div>';
    } else {
        $city = test_input($_POST['city']);
        if (!preg_match("/^[a-zA-Z-' ]*$/", $city)) {
            $cityErr = ' <div class="alert alert-warning" role="alert"> Only letters and white space allowed</div>';
        }
    }
    
    if (empty($_POST['zipcode'])) {
        $zipCodeErr = '<div class="alert alert-warning" role="alert"> Zip code is required</div>';
    } else {
        $zipcode = test_input($_POST['zipcode']);
        if (!ctype_digit($zipcode)) {
            $zipCodeErr = '<div class="alert alert-warning" role="alert"> Numbers only</div>';
        }
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $refuse = '<div class="alert alert-danger" role="alert">
        Your order has not been validated ! </div>';
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $street) || empty($_POST['street'])) {
        $refuse = '<div class="alert alert-danger" role="alert">
        Your order has not been validated ! </div>';
    } elseif (!ctype_digit($streetnumber)) {
        $refuse = '<div class="alert alert-danger" role="alert">
        Your order has not been validated ! </div>';
    } elseif (!preg_match("/^[a-zA-Z-' ]*$/", $city) || empty($_POST['city'])) {
        $refuse = '<div class="alert alert-danger" role="alert">
        Your order has not been validated ! </div>';
    } elseif (!ctype_digit($zipcode)) {
        $refuse = '<div class="alert alert-danger" role="alert">
        Your order has not been validated ! </div>'; 
    } elseif (isset($_POST['express_delivery'])) {
        $confirme = '<div class="alert alert-success" role="alert">
        Your order will be delivered in 30 minutes !</div>';
    } elseif (!isset($_POST['products'])) {
        $refuse = '<div class="alert alert-danger" role="alert">
        Your order has not been validated no items selected! </div>';
    } else {
        $confirme = '<div class="alert alert-success" role="alert">
        Your order will be delivered in 1 hour !</div>';
    }

    $_SESSION['email'] = $email; 
    $_SESSION['street'] = $street; 
    $_SESSION['streetnumber'] = $streetnumber; 
    $_SESSION['zipcode'] = $zipcode; 
    $_SESSION['city'] = $city; 
}


//your products with their price.
$pizzas = [
    ['name' => 'Margharita', 'price' => 8],
    ['name' => 'Hawaï', 'price' => 8.5],
    ['name' => 'Salami pepper', 'price' => 10],
    ['name' => 'Prosciutto', 'price' => 9],
    ['name' => 'Parmiggiana', 'price' => 9],
    ['name' => 'Vegetarian', 'price' => 8.5],
    ['name' => 'Four cheeses', 'price' => 10],
    ['name' => 'Four seasons', 'price' => 10.5],
    ['name' => 'Scampi', 'price' => 11.5]
];

$drinks = [
    ['name' => 'Water', 'price' => 1.8],
    ['name' => 'Sparkling water', 'price' => 1.8],
    ['name' => 'Cola', 'price' => 2],
    ['name' => 'Fanta', 'price' => 2],
    ['name' => 'Sprite', 'price' => 2],
    ['name' => 'Ice-tea', 'price' => 2.2],
];

//put pizzas on default and caculate total price
$totalValue = 0;
$products = $pizzas;

if (isset($_GET['food'])) {
    if ($_GET['food'] == false) {
        $products = $drinks;
    }
}

if (isset($_POST['products'])) {
    foreach ($_POST['products'] AS $i => $product) {
        $totalValue += $products[$i]['price'];
    }
}

if (isset($_POST['express_delivery'])) {
    $totalValue += $_POST['express_delivery'];
}

require 'form-view.php';