<?php
require 'database_connect.php';
//var_dump($_GET);
function array_push_assoc($array, $key, $value)
{
    $array[$key] = $value;
    return $array;
}

$clientDataArray = array(
    'cmd' => '_cart',
    'first_name' => $_GET['firstName'],
    'last_name' =>  $_GET['lastName'],
    'email' =>  $_GET['email'],
    'country' => '',
    'settle_amount' => '',
    'state' => '',
    'charset' => 'utf8',
    'city' => '',
    'address1' => '',
    'address2' => '',
    'zip' => '',
    'night_phone_a' =>  $_GET['phone'],
    'custom' => '',
    'currency_code' => 'PLN',
    'rm' => '2',
    'tax_rate' => '22',
    'upload' => '1',
    'return' => 'http://www.jakubadamus.cba.pl/return.php?status=ok',
    'notify_url' => 'http://www.jakubadamus.cba.pl/ipn_paypal.php',
    'cancel_payment' => 'http://www.jakubadamus.cba.pl/return.php?status=cancelled',
    'business' => 'jakubadamus1991-facilitator@gmail.com',
    'landing_page' => 'Billing'
);

//var_dump(json_decode($_GET['products'][0])->product->id);

//$products = json_decode($_GET['products']);



foreach ($_GET['products'] as $key => $value) {
    $clientDataArray = array_push_assoc($clientDataArray, 'item_number_' . ($key + 1), json_decode($_GET['products'][$key])->product->id);
    $clientDataArray = array_push_assoc($clientDataArray, 'item_name_' . ($key + 1), json_decode($_GET['products'][$key])->product->title);
    $clientDataArray = array_push_assoc($clientDataArray, 'amount_' . ($key + 1), floatval(json_decode($_GET['products'][$key])->product->price));
    $clientDataArray = array_push_assoc($clientDataArray, 'quantity_' . ($key + 1), 1);
}


//echo '<pre>', print_r($clientDataArray, 1), '<pre>';
$content = "";
$counter = 0;
foreach ($clientDataArray as $key => $value) {

  $content .= ($key . '=' . $value);

  if ($counter < count($clientDataArray) - 1) {
    $content .= "&";
  }
  $counter++;
}


header("Location: https://www.sandbox.paypal.com/cgi-bin/webscr?$content");