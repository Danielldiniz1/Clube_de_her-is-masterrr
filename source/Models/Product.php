<?php

namespace Source\Models;

use PDOException;
use Source\Core\Connect;
use Source\Core\Model;

class Product extends Model {
    protected $entity = "products";
    
    private $id;
    private $club_id;
    private $name;
    private $description;
    private $price;
    private $stock;
    private $category_id;
    private $fandom;
    private $rarity;

    private $is_physical;
    private $subscription_only;
    private $weight_grams;
    private $dimensions_cm;
    private $image_url;
    private $is_active;
    private $created_at;
    private $message;

    public function __construct(
        int $id = null,
        int $club_id = null,
        string $name = null,
        string $description = null,
        float $price = null,
        int $stock = 0,
        int $category_id = null,
        string $fandom = null,
        string $rarity = 'common',

        bool $is_physical = true,
        bool $subscription_only = false,
        int $weight_grams = null,
        string $dimensions_cm = null,
        bool $is_active = true,
        string $created_at = null
    )
    {
        $this->id = $id;
        $this->club_id = $club_id;
        $this->name = $name;
        $this->description = $description;
        $this->price = $price;
        $this->stock = $stock;
        $this->category_id = $category_id;
        $this->fandom = $fandom;
        $this->rarity = $rarity;

        $this->is_physical = $is_physical;
        $this->subscription_only = $subscription_only;
        $this->weight_grams = $weight_grams;
        $this->dimensions_cm = $dimensions_cm;
        $this->is_active = $is_active;
        $this->created_at = $created_at;
        $this->entity = "products";
    }

    // Getters
    public function getId(): ?int { return $this->id; }
    public function getClubId(): ?int { return $this->club_id; }
    public function getName(): ?string { return $this->name; }
    public function getDescription(): ?string { return $this->description; }
    public function getPrice(): ?float { return $this->price; }
    public function getStock(): ?int { return $this->stock; }
    public function getCategoryId(): ?int { return $this->category_id; }
    public function getFandom(): ?string { return $this->fandom; }
    public function getRarity(): ?string { return $this->rarity; }

    public function getIsPhysical(): ?bool { return $this->is_physical; }
    public function getSubscriptionOnly(): ?bool { return $this->subscription_only; }
    public function getWeightGrams(): ?int { return $this->weight_grams; }
    public function getDimensionsCm(): ?string { return $this->dimensions_cm; }
    public function getIsActive(): ?bool { return $this->is_active; }
    public function getCreatedAt(): ?string { return $this->created_at; }
    public function getMessage(): ?string { return $this->message; }

    // Setters
    public function setId(?int $id): void { $this->id = $id; }
    public function setClubId(?int $club_id): void { $this->club_id = $club_id; }
    public function setName(?string $name): void { $this->name = $name; }
    public function setDescription(?string $description): void { $this->description = $description; }
    public function setPrice(?float $price): void { $this->price = $price; }
    public function setStock(?int $stock): void { $this->stock = $stock; }
    public function setCategoryId(?int $category_id): void { $this->category_id = $category_id; }
    public function setFandom(?string $fandom): void { $this->fandom = $fandom; }
    public function setRarity(?string $rarity): void { $this->rarity = $rarity; }

    public function setIsPhysical(?bool $is_physical): void { $this->is_physical = $is_physical; }
    public function setSubscriptionOnly(?bool $subscription_only): void { $this->subscription_only = $subscription_only; }
    public function setWeightGrams(?int $weight_grams): void { $this->weight_grams = $weight_grams; }
    public function setDimensionsCm(?string $dimensions_cm): void { $this->dimensions_cm = $dimensions_cm; }
    public function setIsActive(?bool $is_active): void { $this->is_active = $is_active; }
    public function setCreatedAt(?string $created_at): void { $this->created_at = $created_at; }

    public function insert(): ?int
    {
        $conn = Connect::getInstance();

        if(empty($this->name)){
            $this->message = "Nome do produto é obrigatório!";
            return false;
        }

        if(empty($this->club_id)){
            $this->message = "ID do clube é obrigatório!";
            return false;
        }

        if($this->price === null || $this->price < 0){
            $this->message = "Preço é obrigatório e deve ser maior que zero!";
            return false;
        }

        // Verificar se o clube existe
        $query = "SELECT * FROM clubs WHERE id = :club_id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":club_id", $this->club_id);
        $stmt->execute();

        if($stmt->rowCount() == 0) {
            $this->message = "Clube não encontrado!";
            return false;
        }


        $query = "INSERT INTO products (club_id, name, description, price, stock, category_id, fandom, rarity, is_physical, subscription_only, weight_grams, dimensions_cm, is_active) 
                  VALUES (:club_id, :name, :description, :price, :stock, :category_id, :fandom, :rarity, :is_physical, :subscription_only, :weight_grams, :dimensions_cm, :is_active)";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(":club_id", $this->club_id);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":stock", $this->stock);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":fandom", $this->fandom);
        $stmt->bindParam(":rarity", $this->rarity);

        $stmt->bindParam(":is_physical", $this->is_physical);
        $stmt->bindParam(":subscription_only", $this->subscription_only);
        $stmt->bindParam(":weight_grams", $this->weight_grams);
        $stmt->bindParam(":dimensions_cm", $this->dimensions_cm);
        $stmt->bindParam(":is_active", $this->is_active);

        try {
            $stmt->execute();
            $this->message = "Produto cadastrado com sucesso!";
            return $conn->lastInsertId();
        } catch (PDOException $exception) {
            $this->message = "Erro ao cadastrar produto: {$exception->getMessage()}";
            return false;
        }
    }

    public function update(): bool
    {
        $conn = Connect::getInstance();

        if(empty($this->name)){
            $this->message = "Nome do produto é obrigatório!";
            return false;
        }

        if($this->price === null || $this->price < 0){
            $this->message = "Preço é obrigatório e deve ser maior que zero!";
            return false;
        }


        $query = "UPDATE products 
                  SET name = :name, description = :description, price = :price, stock = :stock, 
                      category_id = :category_id, fandom = :fandom, rarity = :rarity, 
                      is_physical = :is_physical, subscription_only = :subscription_only, 
                      weight_grams = :weight_grams, dimensions_cm = :dimensions_cm, 
                      is_active = :is_active
                  WHERE id = :id";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(":name", $this->name);
        $stmt->bindParam(":description", $this->description);
        $stmt->bindParam(":price", $this->price);
        $stmt->bindParam(":stock", $this->stock);
        $stmt->bindParam(":category_id", $this->category_id);
        $stmt->bindParam(":fandom", $this->fandom);
        $stmt->bindParam(":rarity", $this->rarity);
        $stmt->bindParam(":is_physical", $this->is_physical);
        $stmt->bindParam(":subscription_only", $this->subscription_only);
        $stmt->bindParam(":weight_grams", $this->weight_grams);
        $stmt->bindParam(":dimensions_cm", $this->dimensions_cm);
        $stmt->bindParam(":is_active", $this->is_active);
        $stmt->bindParam(":id", $this->id);

        try {
            $stmt->execute();
            $this->message = "Produto atualizado com sucesso!";
            return true;
        } catch (PDOException $exception) {
            $this->message = "Erro ao atualizar produto: {$exception->getMessage()}";
            return false;
        }
    }

    public function selectByClubId(int $club_id): ?array
    {
        $conn = Connect::getInstance();
        $query = "SELECT * FROM products WHERE club_id = :club_id AND is_active = 1";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":club_id", $club_id);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function updateStock(int $quantity): bool
    {
        $conn = Connect::getInstance();
        
        $query = "UPDATE products SET stock = :stock WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":stock", $quantity);
        $stmt->bindParam(":id", $this->id);

        try {
            $stmt->execute();
            $this->stock = $quantity;
            $this->message = "Estoque atualizado com sucesso!";
            return true;
        } catch (PDOException $exception) {
            $this->message = "Erro ao atualizar estoque: {$exception->getMessage()}";
            return false;
        }
    }

    public function delete(): bool
    {
        $conn = Connect::getInstance();
        
        $query = "DELETE FROM products WHERE id = :id";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":id", $this->id);

        try {
            $stmt->execute();
            $this->message = "Produto removido com sucesso!";
            return true;
        } catch (PDOException $exception) {
            $this->message = "Erro ao remover produto: {$exception->getMessage()}";
            return false;
        }
    }
}
