<?php

require_once './config.php';
require_once './Models/Products.php';

class ProductsController{
   private $conn;

   public function __construct($config){
        $this->conn = $config->db;
   }

   public function createProducts(){
        $productsModel = new Products($this->conn);

        $jsonData = file_get_contents("php://input");
        $data = json_decode($jsonData, true);

        $name = $data['name'];
        $price = $data['price'];
        $amount = $data['amount'];
        $ccode = $data['ccode'];

        if($productsModel->create($name, $price, $amount, $ccode)){
            return json_encode(array('message' => 'Produto criado com sucesso!'));
        } else {
            return json_encode(array('message' => 'Não foi possivel criar o produto!'));
        }
   }

   public function selectProducts(){
        $productsModel = new Products($this->conn);    
        $products = $productsModel->select();
        
        if($products){
            echo json_encode(array('message' => 'Produtos selecionados!', 'data' => $products));
        } else {
            return json_encode(array('message' => 'Não foi possivel mostrar os produtos!'));
        }
   }

   public function removeStock(){
        $productsModel = new Products($this->conn);

        $jsonData = file_get_contents("php://input");
        $data = json_decode($jsonData, true);

        $code_prod = $data['code_prod'];
     
        $products = $productsModel->removeProductStock($code_prod);
        return $products;
   }

   public function getCategoryController(){
        $productsModel = new Products($this->conn);    
        $get = $productsModel->getCategory();
        echo json_encode($get);
   }

   public function deleteProducts(){
        $productsModel = new Products($this->conn);

        $jsonData = file_get_contents("php://input");
        $data = json_decode($jsonData, true);

        $code_prod = $data['code_prod'];
        
        $products = $productsModel->delete($code_prod);
        return $products;
   }
}