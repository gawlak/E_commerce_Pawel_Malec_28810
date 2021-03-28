<?php
header("Access-Control-Allow-Origin: *");
require 'database_connect.php'; // Skrypt łączy się z bazą danych
//Rozkodowanie jsonowego obiektu przesyłanego przez nasz frontend//
$object = $_GET['object'];
$object = urldecode($object);
$object = json_decode($object);
$action = $object->action;


if ($action == "getProducts") { // Pobiera produkty bazując na filtrach

    $sql = "SELECT * FROM ecommerce_products WHERE 1=1";

    if ($object->category != '') {
        $sql .= " AND category = $object->category";
    }
    if ($object->name != '') {
        $sql .= " AND title LIKE '%$object->name%'";
    }

    $productsTable = mysqli_fetch_all(mysqli_query($database, $sql), MYSQLI_ASSOC);

    $resultObject = new stdClass();
    $resultObject->productsTable = $productsTable;
    $resultObject->name = "Obiekt z produktami";
    echo json_encode($resultObject);
}
if ($action == 'addProduct') {
    //var_dump($object->newProduct);
    $title = $object->newProduct->title;
    $thumbnail = $object->newProduct->thumbnail;
    $category = $object->newProduct->category;
    $price = $object->newProduct->price;

    $sql = "INSERT INTO 
    `ecommerce_products` (`id`, `title`, `thumbnail`, `category`, `price`) 
    VALUES 
    (NULL, '$title', '$thumbnail', '$category', '$price')";
    mysqli_query($database, $sql);

    $resultObject = new stdClass();
    $resultObject->name = "Pomyślnie dodano nowy produkt!";
    echo json_encode($resultObject);
}
if ($action == 'removeProduct') {

    $sql = "DELETE FROM ecommerce_products WHERE id = '$object->id'";
    mysqli_query($database, $sql);

    $resultObject = new stdClass();
    $resultObject->name = "Pomyślnie usunięto produkt!";
    echo json_encode($resultObject);
}
if ($action == 'getOrders') {

    $sql = "SELECT * FROM ecommerce_orders";
    mysqli_query($database, $sql);
    $orders = mysqli_fetch_all(mysqli_query($database, $sql), MYSQLI_ASSOC);

    $resultObject = new stdClass();
    $resultObject->name = "Pomyślnie pobrano zamówienia!";
    $resultObject->orders = $orders;
    echo json_encode($resultObject);
}
