<?php
require_once '../application/bootstrap.php';
if(!$a->checkAuth()) {
    Tools::redirect('login.php');
}

$category = new Category();
$category->whereAdd('id_category = 1');
if($category->find()) {
    $category->fetch();
    $products = $category->getActiveProducts();
    if($products) {
        foreach($products as $product) {
            echo $product->getName();
        }
    }
}