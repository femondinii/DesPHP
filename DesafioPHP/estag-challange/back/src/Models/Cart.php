<?php

require_once './config.php';

class Cart{
    private $conn;
    
    public function __construct($conn){
        $this->conn = $conn;
    }

    public function create($code, $amount, $tax, $price, $total, $totalTaxa){   
        $stmt = $this->conn->prepare("SELECT ID, AMOUNT_TEMP FROM CART WHERE PRODUCT_TEMP_CODE = :code");
        $stmt->bindParam(':code', $code);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result && isset($result['amount_temp'])) {
            $newAmount = $result['amount_temp'] + $amount;

            $stmt = $this->conn->prepare("SELECT AMOUNT FROM PRODUCTS WHERE CODE_PROD = :code");
            $stmt->bindParam(':code', $code);
            $stmt->execute();
            $availableStock = $stmt->fetchColumn();
            
            if($availableStock >= $newAmount){
                $stmt = $this->conn->prepare("UPDATE CART SET AMOUNT_TEMP = :newAmount WHERE ID = :id");
                $stmt->bindParam(':newAmount', $newAmount);
                $stmt->bindParam(':id', $result['id']);
                $stmt->execute();
                return true; 
            }  
        }else{
            $stmt = $this->conn->prepare("SELECT AMOUNT FROM PRODUCTS WHERE CODE_PROD = :code");
            $stmt->bindParam(':code', $code);
            $stmt->execute();
            $availableStock = $stmt->fetchColumn();
    
            if ($availableStock >= $amount) {
                $query = "INSERT INTO CART (PRODUCT_TEMP_CODE, AMOUNT_TEMP, TAX, PRICE, TOTAL, TOTAL_TAXA) VALUES (:code, :amount, :tax, :price, :total, :totalTaxa)";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':code', $code);   
                $stmt->bindParam(':amount', $amount);
                $stmt->bindParam(':tax', $tax);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':total', $total);
                $stmt->bindParam(':totalTaxa', $totalTaxa);
                $sucess = $stmt->execute();
                return true; 
            } else {
                return false; 
            }
        }
    }

    public function select(){
        $query = "SELECT p.CODE_PROD, p.NAME_PROD, p.PRICE, c.TAX FROM PRODUCTS AS p INNER JOIN CATEGORIES AS c ON p.CATEGORY_CODE = c.CODE;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function getAmount(){
        $query = "SELECT p.AMOUNT, c.AMOUNT_TEMP, p.CODE_PROD FROM CART AS c INNER JOIN PRODUCTS AS p ON p.CODE_PROD = c.PRODUCT_TEMP_CODE;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        foreach ($results as $result) {
            $newAmount = $result['amount'] - $result['amount_temp'];
            $updateQuery = "UPDATE PRODUCTS SET AMOUNT = :newAmount WHERE CODE_PROD = :codeProd";
            $updateStmt = $this->conn->prepare($updateQuery);
            $updateStmt->bindParam(':newAmount', $newAmount);
            $updateStmt->bindParam(':codeProd', $result['code_prod']);
            $updateStmt->execute();
        }
        return true; 
    }
    

    public function getID(){
        $query = "SELECT * FROM PRODUCTS;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function selectAll(){
        $query = "SELECT c.ID, p.NAME_PROD, c.PRICE, c.AMOUNT_TEMP, p.AMOUNT, c.TAX, c.TOTAL, c.TOTAL_TAXA FROM CART AS c INNER JOIN PRODUCTS AS P ON p.CODE_PROD = c.PRODUCT_TEMP_CODE ORDER BY ID ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $valor = (object)['tbody' => '', 'form' => ''];
        $total = 0;
        $totalTaxa = 0;
        $ttotal = 0;
        $ttaxa = 0;

        while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($result);
   

            $valor->tbody .= "<tr>
                                <td>$name_prod</td>
                                <td>R$$price</td>   
                                <td>$amount_temp</td> 
                                <td>R$$total</td> 
                                <td>
                                    <button class='modal-delete' id='$id' onclick='deleteCart($id)'>Delete</button>
                                </td>                             
                            </tr>";  
            
               
            $ttotal += $total;
            $ttaxa += $total_taxa;


            $valor->form = "<label id='ttotal'>Total: R$$ttotal</label><br/>
                            <label id='ttaxa'>Tax: R$$ttaxa</label>"; 
        } 

        echo json_encode($valor);  
    }

    public function getProduct(){
        $query = "SELECT CODE_PROD, NAME_PROD FROM PRODUCTS";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        if(($stmt) and ($stmt->rowCount() != 0)){
            while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
                extract($result);
                $data[] = [
                    "code_prod" => $code_prod,
                    "name_prod" => $name_prod
                ];
            }
            $retorna = ['status' => true, 'data' => $data];
        }else{
            $retorna = ['status' => false, 'message' => 'Não foi possivel!'];
        }
        return $retorna;
    }

    public function deleteCart($id){
        $query = "DELETE FROM CART WHERE ID = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $sucess = $stmt->execute();
        return $sucess;
    }

    public function deleteAllCart(){
        $query = "TRUNCATE TABLE CART;";
        $stmt = $this->conn->prepare($query);
        $sucess = $stmt->execute();
        return $sucess;
    }
}

