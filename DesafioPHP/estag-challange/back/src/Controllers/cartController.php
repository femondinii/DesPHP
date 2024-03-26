<?php

require_once './config.php';
require_once './Models/Cart.php';

class CartController{
   private $conn;

    public function __construct($config){
        $this->conn = $config->db;
    }

    public function createCart(){
        $cartModel = new Cart($this->conn);

        $jsonData = file_get_contents("php://input");
        $data = json_decode($jsonData, true);

        $code = $data['code'];
        $amount = $data['amount'];
        $tax = $data['tax'];
        $price = $data['price'];
        $total = $data['total'];
        $totalTaxa = $data['totalTaxa'];

        if($cartModel->create($code, $amount, $tax, $price, $total, $totalTaxa)){
            return json_encode(array('message' => 'Produto criado com sucesso!'));
        } else {
            return json_encode(array('message' => 'Não foi possivel criar o produto!'));
        }
    }

    public function selectCart(){
        $cartModel = new Cart($this->conn);    
        $cart = $cartModel->select();
            
        if($cart){
            echo json_encode(array('message' => 'Produtos selecionados!', 'data' => $cart));
        } else {
            return json_encode(array('message' => 'Não foi possivel mostrar os produtos!'));
        }
    }

    public function getProductController(){
        $cartModel = new Cart($this->conn);    
        $get = $cartModel->getProduct();
        echo json_encode($get);
    }

    public function selectAllCart(){
        $cartModel = new Cart($this->conn);    
        $cart = $cartModel->selectAll();
        
        if($cart){
            echo json_encode(array('message' => 'Produtos selecionados!', 'data' => $cart));
        } else {
            return json_encode(array('message' => 'Não foi possivel mostrar os produtos!'));
        }
    }

    public function updateCartController(){
        $cartModel = new Cart($this->conn);    
    
        $jsonData = file_get_contents("php://input");
        $data = json_decode($jsonData, true);

        $id = $data['id'];
        $amount = $data['amount'];
    
        $cart = $cartModel->updateCart($id, $amount);

        if($cart){
            echo json_encode(array('message' => 'Categoria selecionada!', 'data' => $cart));
        } else {
            return json_encode(array('message' => 'Não foi possivel mostrar a categoria!'));
        }
    }

    public function updateCartRemoveCttr(){
        $cartModel = new Cart($this->conn);    
    
        $jsonData = file_get_contents("php://input");
        $data = json_decode($jsonData, true);

        $id = $data['id'];
        $amount = $data['amount'];
    
        $cart = $cartModel->updateCartRemove($id, $amount);

        if($cart){
            echo json_encode(array('message' => 'Categoria selecionada!', 'data' => $cart));
        } else {
            return json_encode(array('message' => 'Não foi possivel mostrar a categoria!'));
        }
    }

    public function deleteCartController(){
        $cartModel = new Cart($this->conn);

        $jsonData = file_get_contents("php://input");
        $data = json_decode($jsonData, true);

        $id = $data['id'];
            
        $cart = $cartModel->deleteCart($id);
        return $cart;
    }

    public function deleteAllCartController(){
        $cartModel = new Cart($this->conn);
        $jsonData = file_get_contents("php://input");
        $data = json_decode($jsonData, true);
            
        $cart = $cartModel->deleteAllCart();
        return $cart;
    }

    
    public function getAmountController(){
        $cartModel = new Cart($this->conn);    
        $get = $cartModel->getAmount();
        echo json_encode($get);
    }

    public function getIdController(){
        $cartModel = new Cart($this->conn);    
        $get = $cartModel->getID();
        echo json_encode($get);
    }
    
}