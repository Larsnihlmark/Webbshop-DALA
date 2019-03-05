<?php

class Product{
    private $name;
    private $price;
    private $productID;
    private $category;
    private $image;
    function __construct($productID, $name, $price, $category, $image){
        $this->name = $name;
        $this->price = $price;
        $this->productID = $productID;
        $this->category = $category;
        $this->image = $image;
        
    }

    function addProduct() {
        try {
            $sql = "SELECT COUNT(name) AS num FROM product WHERE name = :name";
            $statement = $this->connection->prepare($sql);
            $statement->bindParam(':name', $this->name);
            $statement->execute();

            $producetExist = $statement->fetch(PDO::FETCH_ASSOC);
            if($producetExist['num'] > 0) {
                $response_array['status'] = 'error'; 
                 
                header('Content-type: application/json');
                echo json_encode($response_array);
            }else{
               /*  Lägg till image i database */
                $statement = $this->connection->prepare("INSERT INTO product (ProductID, Name, Price, Category) VALUES (:productID, :name, :price, :category)");
                $statement->bindParam(':productID', $this->productID);
                $statement->bindParam(':name', $this->name);
                $statement->bindParam(':price', $this->price);
                $statement->bindParam(':category', $this->category);
                /* $statement->bindParam(':image', $this->image); */
                $statement->execute();
            }
            
        } catch (EXCEPTION $err) {
            throw new Exception($err);
        }

    }
 
}
?>