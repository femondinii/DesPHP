<?php

require_once './config.php';

class Category{
    private $conn;
    
    public function __construct($conn){
        $this->conn = $conn;
    }

    public function create($name, $tax){   
        $query = "INSERT INTO CATEGORIES (NAME, TAX) VALUES (:name, :tax)";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':tax', $tax);
        $sucess = $stmt->execute();
    }

    public function select(){
        $query = "SELECT * FROM CATEGORIES";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $data = "";
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($result);
            $data .= "<tr>
                            <td>$code</td>   
                            <td>$name</td>   
                            <td>$tax %</td>  
                            <td>
                                <button class='modal-delete' id='$code' onclick='deleteCategory($code)'>Delete</button>
                            </td>                             
                     </tr>";
        }
        echo $data;
    }

    public function update($name, $tax){
        $query = "UPDATE CATEGORIES SET TAX = :tax WHERE NAME = :name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':tax', $tax);
        $sucess = $stmt->execute();
    }

    public function delete($code){
        $query = "DELETE FROM CATEGORIES WHERE CODE = :code";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':code', $code);
        $sucess = $stmt->execute();
        return $sucess;
    }
}

