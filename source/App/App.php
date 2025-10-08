<?php

namespace Source\App;

use League\Plates\Engine;

class App
{
    private $view;

    public function __construct()
    {
        $this->view = new Engine(__DIR__ . "/../../themes/app","php");
    }

    public function home ()
    {
        //echo "<h1>Eu sou a Home...</h1>";
        echo $this->view->render("home",[]);
    }

    public function profile ()
    {
        echo $this->view->render("profile",[]);
    }

    public function cart (array $data)
    {
        echo $this->view->render("cart", []);
    }
    public function wishlist (array $data)
    {
        echo $this->view->render("wishlist", []);
    }
    public function myClub (array $data)
    {
        echo $this->view->render("myClub", []);
    }
    public function myBuys (array $data)
    {
        echo $this->view->render("myBuys", []);
    }
    public function products (array $data)
    {
        // Buscar produtos diretamente do banco de dados
        require_once __DIR__ . '/../Models/Product.php';
        require_once __DIR__ . '/../Models/ProductImage.php';
        
        $productModel = new \Source\Models\Product();
        $imageModel = new \Source\Models\ProductImage();
        
        $products = [];
        $allProducts = $productModel->selectAll();
        
        if ($allProducts) {
            foreach ($allProducts as $product) {
                $images = $imageModel->find("product_id = :product_id", "product_id={$product->id}")->fetch(true);
                $productArray = (array) $product;
                $productArray['images'] = $images ? array_map(function($img) { return (array) $img; }, $images) : [];
                $products[] = $productArray;
            }
        }
        
        echo $this->view->render("products", [
            "products" => $products
        ]);
    }

}