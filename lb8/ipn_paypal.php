
<?php

require 'database_connect.php';
$payer_email = $_POST['payer_email']; // Email kupującego
mb_send_mail($payer_email, 'E-commerce', 'Dziękujemy za zakupy!');

// Ogarnij sobie dane i złóż z nich porządny IPN
$raw_post_data = file_get_contents('php://input');
$raw_post_array = explode('&', $raw_post_data);
$myPost = array();
foreach ($raw_post_array as $keyval) {
    $keyval = explode('=', $keyval);
    if (count($keyval) == 2)
        $myPost[$keyval[0]] = ($keyval[1]);
}
$ipn_content = json_encode($myPost);
$myPost = json_decode($ipn_content);
$date_end = date("Y-m-d-H:i:s");
$sql = "INSERT INTO `ecommerce_orders` 
(`id`, `first_name`, `last_name`, `email`, `phone`, 
`price`, `products`, `ipn`, `date_end`) 
VALUES 
(NULL, '$myPost->first_name', '$myPost->last_name', '$myPost->payer_email', '$myPost->night_phone',
 '$myPost->mc_gross', '$myPost->custom', '$ipn_content', '$date_end')";
$result = mysqli_query($database, $sql);
?>