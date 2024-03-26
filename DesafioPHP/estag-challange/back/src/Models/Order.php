<?php

require_once './config.php';

class Order {

    private $conn;
    
    public function __construct($conn){
        $this->conn = $conn;
    }

    public function selectTotais(){
        $query = "SELECT SUM(TOTAL) AS TOTAL, SUM(TOTAL_TAXA) AS TAXA FROM CART";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $result;
    }

    public function createOrder() {
        $totais = $this->selectTotais();
        if (isset($totais)) {
            $total = $totais[0]['total'];
            $tax = $totais[0]['taxa'];
            $query = "INSERT INTO ORDERS (TOTAL, TAX) VALUES (:total, :tax)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':total', $total);
            $stmt->bindParam(':tax', $tax);
            $result = $stmt->execute();

            $ordercode = $this->conn->lastInsertId();
            echo $ordercode;

            $query = "SELECT * FROM CART";
            $stm = $this->conn->prepare($query);
            $stm->execute();
            $arrayCart = $stm->fetchAll(PDO::FETCH_ASSOC);
            var_dump($arrayCart);

            foreach($arrayCart as $detail){
                $name = $detail['product_temp_code'];
                $amount = $detail['amount_temp'];
                $price = $detail['price'];
                $tax = $detail['tax'];
                $query = "INSERT INTO ORDER_ITEM (ORDER_CODE, PRODUCT_CODE, AMOUNT, PRICE, TAX) VALUES (:order_code, :code_prod, :amount, :price, :tax)";
                $stmt = $this->conn->prepare($query);
                $stmt->bindParam(':order_code', $ordercode);
                $stmt->bindParam(':code_prod', $name);
                $stmt->bindParam(':amount', $amount);
                $stmt->bindParam(':price', $price);
                $stmt->bindParam(':tax', $tax);
                $result = $stmt->execute();
            }

            if($result) {
                return true;
            } else {
                return false;
            }
        } else {
            echo "Nenhum total encontrado na tabela CART";
            return false;
        }
    }

    public function showTable(){
        $query = "SELECT * FROM ORDERS;";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $data = "";
        while($result = $stmt->fetch(PDO::FETCH_ASSOC)){
            extract($result);
            $data .= "<tr>
                            <td>$code_orders</td>   
                            <td>R$$tax</td>   
                            <td>R$$total</td>
                            <td>
                                <button class='view' id='$code_orders' onclick='showModal($code_orders)'>View</button>
                            </td>                             
                     </tr>";
        }
        echo $data;
    }

    public function showDetails($order_code){
        $query = "SELECT p.NAME_PROD, o.ORDER_CODE, p.CODE_PROD, o.PRODUCT_CODE, o.AMOUNT, o.PRICE, o.TAX FROM ORDER_ITEM AS o INNER JOIN PRODUCTS AS p ON o.PRODUCT_CODE = p.CODE_PROD WHERE o.ORDER_CODE = :order_code";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":order_code", $order_code);
        $stmt->execute();
        $data = "";
        while($result = $stmt->fetchAll(PDO::FETCH_ASSOC)){
            extract($result);
            $size = count($result);
            for($i = 0; $i < $size; $i++){
                $data .= "<tr>   
                            <td>" .$result[$i]['name_prod']. "</td>   
                            <td>".$result[$i]['amount']."</td>
                            <td>R$" .$result[$i]['price']."</td>
                            <td>" .$result[$i]['tax']. "</td>                         
                     </tr>";
            }
        }
        echo $data;
    }
}