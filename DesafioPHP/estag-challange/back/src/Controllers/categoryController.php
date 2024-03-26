<?php

require_once './config.php';
require_once './Models/Category.php';

class CategoryController{
   private $conn;

   public function __construct($config){
        $this->conn = $config->db;
   }

   public function createCategory(){
        $categoryModel = new Category($this->conn);

        $jsonData = file_get_contents("php://input");
        $data = json_decode($jsonData, true);

        $name = $data['name'];
        $tax = $data['tax'];

        if($categoryModel->create($name, $tax)){
            return json_encode(array('message' => 'Categoria criada com sucesso!'));
        } else {
            return json_encode(array('message' => 'Não foi possivel criar a categoria!'));
        }
   }

   public function selectCategory(){
        $categoryModel = new Category($this->conn);    
        $category = $categoryModel->select();
        
        if($category){
            echo json_encode(array('message' => 'Categoria selecionada!', 'data' => $category));
        } else {
            return json_encode(array('message' => 'Não foi possivel mostrar a categoria!'));
        }
   }

   public function updateCategory(){
        $categoryModel = new Category($this->conn);    
     
        $jsonData = file_get_contents("php://input");
        $data = json_decode($jsonData, true);

        $name = $data['name'];
        $tax = $data['tax'];
     
        $category = $categoryModel->update($name, $tax);

        if($category){
            echo json_encode(array('message' => 'Categoria selecionada!', 'data' => $category));
        } else {
            return json_encode(array('message' => 'Não foi possivel mostrar a categoria!'));
        }
   }

   public function deleteCategory(){
        $categoryModel = new Category($this->conn);

        $jsonData = file_get_contents("php://input");
        $data = json_decode($jsonData, true);

        $code = $data['code'];
        
        $category = $categoryModel->delete($code);
        return $category;
   }
}