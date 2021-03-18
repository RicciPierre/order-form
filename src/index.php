<?php
declare(strict_types=1);

//we are going to use session variables so we need to enable sessions
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

$email = $street = $streetnumber = $city = $zipcode = "";
$emailErr = $streetErr = $streetNumErr = $cityErr = $zipCodeErr = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST["email"])) {
        $emailErr = "Email is required";
    } else {
        $email = test_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $emailErr = "Invalid email format";
        }
    }
    if (empty($_POST["street"])) {
        $streetErr = "Name of your street is required";
    } else {
        $street = test_input($_POST["street"]);
    }
    
    if (empty($_POST["streetnumber"])) {
        $streetNumErr = "Street number is required";
    } else {
        $streetnumber = test_input($_POST["streetnumber"]);
        if (!ctype_digit($streetnumber)) {
            $streetNumErr = "Numbers only";
        }
    }
    
    if (empty($_POST["city"])) {
        $cityErr = "Your city is required";
    } else {
        $city = test_input($_POST["city"]);
    }
    
    if (empty($_POST["zipcode"])) {
        $zipCodeErr = "Zip code is required";
    } else {
        $zipcode = test_input($_POST["zipcode"]);
        if (!ctype_digit($zipcode)) {
            $zipCodeErr = "Numbers only";
        }
    }
}



function Test_input($data)
{
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
//your products with their price.
$products = [
    ['name' => 'Margherita', 'price' => 8],
    ['name' => 'Hawaï', 'price' => 8.5],
    ['name' => 'Salami pepper', 'price' => 10],
    ['name' => 'Prosciutto', 'price' => 9],
    ['name' => 'Parmiggiana', 'price' => 9],
    ['name' => 'Vegetarian', 'price' => 8.5],
    ['name' => 'Four cheeses', 'price' => 10],
    ['name' => 'Four seasons', 'price' => 10.5],
    ['name' => 'Scampi', 'price' => 11.5]
];

$products = [
    ['name' => 'Water', 'price' => 1.8],
    ['name' => 'Sparkling water', 'price' => 1.8],
    ['name' => 'Cola', 'price' => 2],
    ['name' => 'Fanta', 'price' => 2],
    ['name' => 'Sprite', 'price' => 2],
    ['name' => 'Ice-tea', 'price' => 2.2],
];

$totalValue = 0;

require 'form-view.php';