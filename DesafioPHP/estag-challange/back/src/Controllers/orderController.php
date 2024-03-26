<?php

require_once './config.php';
require_once './Models/Order.php';

class OrderController{
   private $conn;

    public function __construct($config){
        $this->conn = $config->db;
    }

    public function createOrderController(){
        $cartModel = new Order($this->conn);

        if($cartModel->createOrder()){
            echo json_encode ($cartModel);
        } else {
            return false;
        }
    }   

    public function select(){
        $orderModel = new Order($this->conn);    
        $orders = $orderModel->showTable();
        
        if($orders){
            echo json_encode(array('data' => $orders));
        } else {
            return false;
        }
    }

    public function createDetailsController(){
        $cartModel = new Order($this->conn);

        if($cartModel->createOrderDetais()){
            echo json_encode ($cartModel);
        } else {
            return false;
        }
    }   

    public function showDetailsController(){
        $cartModel = new Order($this->conn);

        $jsonData = file_get_contents("php://input");
        $data = json_decode($jsonData, true);

        $order_code = $data;

        if($cartModel->showDetails($order_code)){
            echo json_encode ($cartModel);
        } else {
            return false;
        }
    }   
    
}