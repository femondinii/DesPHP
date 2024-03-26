<?php

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE");

require_once './Controllers/categoryController.php';
require_once './Controllers/cartController.php';
require_once './Controllers/productsController.php';
require_once './Controllers/orderController.php';
require_once './config.php';

$config = new Config;
$controller = new CategoryController($config);
$prodController = new ProductsController($config);
$cartController = new CartController($config);
$orderController = new OrderController($config);

switch($_GET['action']){
    case 1:
        $controller->createCategory();
    break;
    
    case 2:
        $controller->selectCategory();
    break;
    
    case 3:
        $controller->updateCategory();
    break;
    
    case 4:
        $controller->deleteCategory();
    break;

    case 5:
        $prodController->createProducts();
    break;

    case 6:
        $prodController->selectProducts();
    break;

    case 7:
        $prodController->getCategoryController();
    break;

    case 8:
        $prodController->deleteProducts();
    break;

    case 9:
        $cartController->createCart();
    break;

    case 10:
        $cartController->selectCart();
    break;

    case 11:
        $cartController->getProductController();
    break;

    case 12:
        $cartController->selectAllCart();
    break;

    case 13:
        $cartController->deleteCartController();
    break;

    case 14:
        $cartController->updateCartController();
    break;
    
    case 15:
        $cartController->getAmountController();
    break;

    case 16:
        $cartController->updateCartRemoveCttr();
    break;

    case 17:
        $cartController->getIdController();
    break;

    case 18:
        $cartController->deleteAllCartController();
    break;
    
    case 19:
        $orderController->createOrderController();
    break;

    case 20:
        $orderController->select();
    break;

    case 21:
        $prodController->removeStock();
    break;

    case 22:
        $orderController->createDetailsController();
    break;

    case 23:
        $orderController->showDetailsController();
    break;
    
    default:
        echo "Página principal!";
    break;
}

