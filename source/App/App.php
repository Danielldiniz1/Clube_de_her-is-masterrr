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
        $user = current_user();
        $items = [];
        if ($user && isset($user->id)) {
            require_once __DIR__ . '/../Models/WishlistItem.php';
            $wl = new \Source\Models\WishlistItem();
            $wl->ensureTable();
            $items = $wl->listItemsWithProducts((int)$user->id);
        }
        echo $this->view->render("wishlist", [
            "items" => $items,
            "user" => $user
        ]);
    }
    public function myClub (array $data)
    {
        echo $this->view->render("myClub", []);
    }
    public function myBuys (array $data)
    {
        $user = current_user();
        $orders = [];
        if ($user && isset($user->id)) {
            require_once __DIR__ . '/../Models/Order.php';
            $orderModel = new \Source\Models\Order();
            $orderModel->ensureTables();
            $orders = $orderModel->listByUser((int)$user->id);
        }
        echo $this->view->render("myBuys", [
            "orders" => $orders,
            "user" => $user
        ]);
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
        header('Location: ' . url('app/carrinho?success=removed_cart'));
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
        header('Location: ' . url('app/carrinho?success=cleared_cart'));
        exit;
    }

    // --- Wishlist ---
    public function addWishlist(array $data): void
    {
        $user = current_user();
        if (!$user || empty($user->id)) {
            header('Location: ' . url('login'));
            exit;
        }
        $productId = (int)($data['product_id'] ?? 0);
        if ($productId <= 0) {
            header('Location: ' . url('app/produtos'));
            exit;
        }
        require_once __DIR__ . '/../Models/WishlistItem.php';
        $wl = new \Source\Models\WishlistItem();
        $wl->ensureTable();
        $wl->add((int)$user->id, $productId);
        // Fallback sem JS: permanecer na página de produtos com toast
        header('Location: ' . url('app/produtos?success=added_wishlist'));
        exit;
    }

    public function removeWishlist(array $data): void
    {
        $user = current_user();
        if (!$user || empty($user->id)) {
            header('Location: ' . url('login'));
            exit;
        }
        $productId = (int)($data['product_id'] ?? 0);
        if ($productId <= 0) {
            header('Location: ' . url('app/listadedesejos'));
            exit;
        }
        require_once __DIR__ . '/../Models/WishlistItem.php';
        $wl = new \Source\Models\WishlistItem();
        $wl->ensureTable();
        $wl->remove((int)$user->id, $productId);
        header('Location: ' . url('app/listadedesejos?success=removed_wishlist'));
        exit;
    }

    public function finalizePurchase(array $data): void
    {
        $user = current_user();
        if (!$user || empty($user->id)) {
            header('Location: ' . url('login'));
            exit;
        }

        // Buscar itens do carrinho
        $cart = new \Source\Models\CartItem();
        $items = $cart->listItemsWithProducts((int)$user->id);
        if (empty($items)) {
            header('Location: ' . url('app/carrinho?error=empty_cart'));
            exit;
        }
        // Persistir pedido e itens
        require_once __DIR__ . '/../Models/Order.php';
        $orderModel = new \Source\Models\Order();
        $orderModel->ensureTables();
        $created = $orderModel->createFromCart((int)$user->id, $items);
        if (!$created) {
            header('Location: ' . url('app/carrinho?error=server_error'));
            exit;
        }

        $orderNumber = $created['order_number'];
        $total = (float)$created['total'];

        // Limpar carrinho após salvar pedido
        $cart->clear((int)$user->id);

        // Corpo do e-mail do comprovante
        $body = '<h2>Comprovante de Compra</h2>';
        $body .= '<p>Olá ' . htmlspecialchars($user->name ?? 'Cliente') . ',</p>';
        $body .= '<p>Obrigado pela sua compra! Aqui estão os detalhes:</p>';
        $body .= '<p><strong>Número do Pedido:</strong> ' . $orderNumber . '</p>';
        $body .= '<p><strong>Total:</strong> R$ ' . number_format($total, 2, ',', '.') . '</p>';
        $body .= '<p>Data: ' . date('d/m/Y H:i') . '</p>';

        // Enviar e-mail (não bloqueia o registro do pedido)
        try {
            $email = new \Source\Core\Email();
            $sent = $email->sendEmail((string)($user->email ?? ''),
                'Comprovante de Compra — Pedido ' . $orderNumber,
                $body
            );
        } catch (\Throwable $e) {
            $sent = false;
        }

        // Redireciona com toasts; se e-mail falhou, retornamos ambos os parâmetros
        $redirect = 'app/carrinho?success=purchase_complete';
        if (!$sent) {
            $redirect .= '&error=email_failed';
        }
        header('Location: ' . url($redirect));
        exit;
    }

}