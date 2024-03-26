<?php

require_once './config.php';

class Products{
    private $conn;
    
    public function __construct($conn){
        $this->conn = $conn;
    }

    public function create($name, $price, $amount, $ccode){   
        $query = "INSERT INTO PRODUCTS (NAME_PROD, PRICE, AMOUNT, CATEGORY_CODE) VALUES (:name, :price, :amount, :ccode)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':price', $price);
        $stmt->bindParam(':amount', $amount);
        $stmt->bindParam(':ccode', $ccode);
        $sucess = $stmt->execute();
    }

    public function select(){
        $query = "SELECT * FROM PRODUCTS AS p INNER JOIN CATEGORIES AS c ON p.CATEGORY_CODE = c.CODE";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $data = "";
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($result);
            $data .= "<tr>
                            <td>$code_prod</td>   
                            <td>$name_prod</td>   
                            <td>$amount</td>  
                            <td>R$$price</td>  
                            <td>$name</td>  
                            <td>
                                <button class='modal-delete' id='$code_prod' onclick='deleteProducts($code_prod)'>Delete</button>
                            </td>                             
                     </tr>";
        }
        echo $data;
    }

    public function removeProductStock(){
        $query = "SELECT CODE_PROD FROM PRODUCTS WHERE AMOUNT = 0;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        if (!is_null($results)) {
            foreach ($results as $result) {
                $deleteQuery = "DELETE FROM PRODUCTS WHERE CODE_PROD = :codeProd";
                $deleteStmt = $this->conn->prepare($deleteQuery);
                $deleteStmt->bindParam(':codeProd', $result['code_prod']);
                $deleteStmt->execute();
            }
        }
        return true; 
    }

    public function getCategory(){
        $query = "SELECT CODE, NAME FROM CATEGORIES";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        if(($stmt) and ($stmt->rowCount() != 0)){
            while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($result);
                $data[] = [
                    "code" => $code,
                    "name" => $name
                ];
            }
            $retorna = ['status' => true, 'data' => $data];
        }else{
            $retorna = ['status' => false, 'message' => 'Não foi possivel!'];
        }
    
        return $retorna;
    }

    public function delete($code_prod){
        $query = "DELETE FROM PRODUCTS WHERE CODE_PROD = :code_prod";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':code_prod', $code_prod);
        $sucess = $stmt->execute();
        return $sucess;
    }
}

