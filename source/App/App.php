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
        $user = current_user();
        $items = [];
        if ($user && isset($user->id)) {
            $cart = new \Source\Models\CartItem();
            $items = $cart->listItemsWithProducts((int)$user->id);
        }
        echo $this->view->render("cart", [
            "items" => $items,
            "user" => $user
        ]);
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
    public function purchaseTest(array $data)
    {
        $user = current_user();
        echo $this->view->render("purchase-test", [
            "user" => $user
        ]);
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

    // --- Carrinho server-side ---
    public function addCart(array $data): void
    {
        $user = current_user();
        if (!$user || empty($user->id)) {
            header('Location: ' . url('login'));
            exit;
        }

        $productId = (int)($data['product_id'] ?? 0);
        $quantity = (int)($data['quantity'] ?? 1);
        if ($productId <= 0 || $quantity <= 0) {
            header('Location: ' . url('app/produtos'));
            exit;
        }

        $productModel = new \Source\Models\Product();
        $product = $productModel->selectById($productId);
        if (!$product) {
            header('Location: ' . url('app/produtos'));
            exit;
        }

        $cart = new \Source\Models\CartItem();
        $cart->addOrIncrement((int)$user->id, $productId, $quantity);
        // Volta para a página de produtos com indicador de sucesso
        header('Location: ' . url('app/produtos?success=added_cart'));
        exit;
    }

    public function updateCart(array $data): void
    {
        $user = current_user();
        if (!$user || empty($user->id)) {
            header('Location: ' . url('login'));
            exit;
        }

        $productId = (int)($data['product_id'] ?? 0);
        $quantity = (int)($data['quantity'] ?? 1);
        if ($productId <= 0 || $quantity <= 0) {
            header('Location: ' . url('app/carrinho'));
            exit;
        }

        $cart = new \Source\Models\CartItem();
        $cart->setQuantity((int)$user->id, $productId, $quantity);
        header('Location: ' . url('app/carrinho'));
        exit;
    }

    public function removeCart(array $data): void
    {
        $user = current_user();
        if (!$user || empty($user->id)) {
            header('Location: ' . url('login'));
            exit;
        }

        $productId = (int)($data['product_id'] ?? 0);
        if ($productId <= 0) {
            header('Location: ' . url('app/carrinho'));
            exit;
        }

        $cart = new \Source\Models\CartItem();
        $cart->removeItem((int)$user->id, $productId);
        header('Location: ' . url('app/carrinho'));
        exit;
    }

    public function clearCart(array $data): void
    {
        $user = current_user();
        if (!$user || empty($user->id)) {
            header('Location: ' . url('login'));
            exit;
        }
        $cart = new \Source\Models\CartItem();
        $cart->clear((int)$user->id);
        header('Location: ' . url('app/carrinho'));
        exit;
    }

}