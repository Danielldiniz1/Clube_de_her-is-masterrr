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
        $user = current_user();
        if (!$user || empty($user->id)) {
            header('Location: ' . url('login'));
            exit;
        }
        if ((int)($user->idType ?? 2) !== 1) {
            header('Location: ' . url('app?error=forbidden'));
            exit;
        }
        require_once __DIR__ . '/../Models/Club.php';
        require_once __DIR__ . '/../Models/Product.php';
        $clubModel = new \Source\Models\Club();
        $clubs = $clubModel->selectByUserId((int)$user->id);
        $club = $clubs[0] ?? null;
        $products = [];
        if ($club && isset($club->id)) {
            $productModel = new \Source\Models\Product();
            $products = $productModel->selectByClubId((int)$club->id) ?? [];
        }
        echo $this->view->render("myClub", [
            "user" => $user,
            "club" => $club,
            "products" => $products
        ]);
    }
    public function createClub(array $data): void
    {
        $user = current_user();
        if (!$user || empty($user->id)) { header('Location: ' . url('login')); exit; }
        if ((int)($user->idType ?? 2) !== 1) { header('Location: ' . url('app?error=forbidden')); exit; }
        require_once __DIR__ . '/../Models/Club.php';
        $clubModel = new \Source\Models\Club();
        $existing = $clubModel->selectByUserId((int)$user->id);
        if (!empty($existing)) { header('Location: ' . url('app/meuclube?error=already_has_club')); exit; }
        $name = trim($data['club_name'] ?? '');
        $desc = (string)($data['description'] ?? '');
        $club = new \Source\Models\Club(null, (int)$user->id, $name, $desc, true);
        $createdId = $club->insert();
        if (!$createdId) { header('Location: ' . url('app/meuclube?error=club_create_failed')); exit; }
        try {
            $email = new \Source\Core\Email();
            $body = '<h2>Clube criado</h2>'
                . '<p>Olá ' . htmlspecialchars($user->name ?? 'Vendedor') . ',</p>'
                . '<p>Seu clube "' . htmlspecialchars($name) . '" foi criado com sucesso.</p>';
            $email->sendEmail((string)($user->email ?? ''), 'Seu clube foi criado', $body);
        } catch (\Throwable $e) {}
        header('Location: ' . url('app/meuclube?success=club_created'));
        exit;
    }
    public function createClubProduct(array $data): void
    {
        $user = current_user();
        if (!$user || empty($user->id)) { header('Location: ' . url('login')); exit; }
        if ((int)($user->idType ?? 2) !== 1) { header('Location: ' . url('app?error=forbidden')); exit; }
        require_once __DIR__ . '/../Models/Club.php';
        require_once __DIR__ . '/../Models/Product.php';
        $clubModel = new \Source\Models\Club();
        $clubs = $clubModel->selectByUserId((int)$user->id);
        $club = $clubs[0] ?? null;
        if (!$club || !isset($club->id)) { header('Location: ' . url('app/meuclube?error=club_not_found')); exit; }
        // Upload de imagens (até 5)
        $uploadedImages = [];
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $uploader = new \SorFabioSantos\Uploader\Uploader();
            $count = count($_FILES['images']['name']);
            for ($i = 0; $i < $count && $i < 5; $i++) {
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    $tempFile = [
                        'name' => $_FILES['images']['name'][$i],
                        'type' => $_FILES['images']['type'][$i],
                        'tmp_name' => $_FILES['images']['tmp_name'][$i],
                        'error' => $_FILES['images']['error'][$i],
                        'size' => $_FILES['images']['size'][$i]
                    ];
                    $imagePath = $uploader->Image($tempFile, 'products');
                    if ($imagePath) { $uploadedImages[] = $imagePath; }
                }
            }
        }

        // Campos do produto (compatíveis com Admin)
        $name = trim($data['name'] ?? '');
        $price = isset($data['price']) ? (float)$data['price'] : null;
        $stock = isset($data['stock']) ? (int)$data['stock'] : 0;
        $categoryId = isset($data['category_id']) ? (int)$data['category_id'] : null;
        $fandom = $data['fandom'] ?? null;
        $rarity = $data['rarity'] ?? 'common';
        $isPhysical = isset($data['is_physical']) ? (bool)$data['is_physical'] : true;
        $subscriptionOnly = isset($data['subscription_only']) ? (bool)$data['subscription_only'] : false;
        $weightGrams = isset($data['weight_grams']) ? (int)$data['weight_grams'] : null;
        $dimensionsCm = $data['dimensions_cm'] ?? null;
        $description = (string)($data['description'] ?? '');
        $isActive = isset($data['is_active']) ? (bool)$data['is_active'] : true;

        $product = new \Source\Models\Product(
            null,
            (int)$club->id,
            $name,
            $description,
            $price,
            $stock,
            $categoryId,
            $fandom,
            $rarity,
            $isPhysical,
            $subscriptionOnly,
            $weightGrams,
            $dimensionsCm,
            $isActive
        );
        $createdId = $product->insert();
        if (!$createdId) { header('Location: ' . url('app/meuclube?error=create_failed')); exit; }

        if (!empty($uploadedImages)) {
            require_once __DIR__ . '/../Models/ProductImage.php';
            foreach ($uploadedImages as $index => $imagePath) {
                $pi = new \Source\Models\ProductImage(
                    null,
                    (int)$createdId,
                    $imagePath,
                    $index === 0,
                    $index + 1
                );
                $pi->insert();
            }
        }

        header('Location: ' . url('app/meuclube?success=product_created'));
        exit;
    }
    public function updateClubProduct(array $data): void
    {
        $user = current_user();
        if (!$user || empty($user->id)) { header('Location: ' . url('login')); exit; }
        if ((int)($user->idType ?? 2) !== 1) { header('Location: ' . url('app?error=forbidden')); exit; }
        require_once __DIR__ . '/../Models/Product.php';
        $id = (int)($data['id'] ?? 0);
        if ($id <= 0) { header('Location: ' . url('app/meuclube?error=invalid_product')); exit; }
        $conn = \Source\Core\Connect::getInstance();
        $stmt = $conn->prepare("SELECT * FROM products WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $current = $stmt->fetch();
        require_once __DIR__ . '/../Models/Club.php';
        $clubModel = new \Source\Models\Club();
        $clubs = $clubModel->selectByUserId((int)$user->id);
        $club = $clubs[0] ?? null;
        if (!$current || !$club || (int)$current->club_id !== (int)$club->id) { header('Location: ' . url('app/meuclube?error=forbidden')); exit; }
        if (!$current) { header('Location: ' . url('app/meuclube?error=product_not_found')); exit; }
        $name = trim($data['name'] ?? $current->name);
        $price = (float)($data['price'] ?? $current->price);
        $stock = (int)($data['stock'] ?? $current->stock);
        $description = (string)($data['description'] ?? $current->description);
        $subscriptionOnly = isset($data['subscription_only']) ? (bool)$data['subscription_only'] : (bool)$current->subscription_only;
        $isActive = isset($data['is_active']) ? (bool)$data['is_active'] : (bool)$current->is_active;
        $product = new \Source\Models\Product(
            (int)$id,
            (int)$current->club_id,
            $name,
            $description,
            $price,
            $stock,
            (int)$current->category_id,
            (string)$current->fandom,
            (string)$current->rarity,
            (bool)$current->is_physical,
            $subscriptionOnly,
            (int)$current->weight_grams,
            (string)$current->dimensions_cm,
            $isActive
        );
        if (!$product->update()) { header('Location: ' . url('app/meuclube?error=update_failed')); exit; }
        header('Location: ' . url('app/meuclube?success=product_updated'));
        exit;
    }
    public function deleteClubProduct(array $data): void
    {
        $user = current_user();
        if (!$user || empty($user->id)) { header('Location: ' . url('login')); exit; }
        if ((int)($user->idType ?? 2) !== 1) { header('Location: ' . url('app?error=forbidden')); exit; }
        require_once __DIR__ . '/../Models/Product.php';
        $id = (int)($data['id'] ?? 0);
        if ($id <= 0) { header('Location: ' . url('app/meuclube?error=invalid_product')); exit; }
        // verify ownership
        $conn = \Source\Core\Connect::getInstance();
        $stmt = $conn->prepare("SELECT club_id FROM products WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();
        require_once __DIR__ . '/../Models/Club.php';
        $clubModel = new \Source\Models\Club();
        $clubs = $clubModel->selectByUserId((int)$user->id);
        $club = $clubs[0] ?? null;
        if (!$row || !$club || (int)$row->club_id !== (int)$club->id) { header('Location: ' . url('app/meuclube?error=forbidden')); exit; }
        $product = new \Source\Models\Product((int)$id);
        if (!$product->delete()) { header('Location: ' . url('app/meuclube?error=delete_failed')); exit; }
        header('Location: ' . url('app/meuclube?success=product_deleted'));
        exit;
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
    public function subscription(): void
    {
        $user = current_user();
        $isSubscriber = false;
        if ($user && isset($user->id)) {
            $isSubscriber = user_has_active_subscription((int)$user->id);
        }
        echo $this->view->render("subscription", [
            "user" => $user,
            "isSubscriber" => $isSubscriber
        ]);
    }
    public function activateSubscription(array $data): void
    {
        $user = current_user();
        if (!$user || empty($user->id)) {
            header('Location: ' . url('login'));
            exit;
        }
        $conn = \Source\Core\Connect::getInstance();
        $stmt = $conn->prepare("SELECT id FROM subscriptions WHERE user_id = :uid AND status = 'active' LIMIT 1");
        $stmt->bindValue(":uid", (int)$user->id, \PDO::PARAM_INT);
        $stmt->execute();
        if ($stmt->fetch()) {
            try {
                $email = new \Source\Core\Email();
                $body = '<h2>Assinatura Ativada</h2>'
                    . '<p>Olá ' . htmlspecialchars($user->name ?? 'Cliente') . ',</p>'
                    . '<p>Sua assinatura foi ativada com sucesso.</p>';
                $email->sendEmail((string)($user->email ?? ''), 'Assinatura ativada', $body);
            } catch (\Throwable $e) {}
            $isSubscriber = true;
            echo $this->view->render("subscription", [
                "user" => $user,
                "isSubscriber" => $isSubscriber,
                "justSubscribed" => true
            ]);
            exit;
        }
        $clubStmt = $conn->query("SELECT id FROM clubs ORDER BY id ASC LIMIT 1");
        $clubRow = $clubStmt ? $clubStmt->fetch() : null;
        if ($clubRow && isset($clubRow->id)) {
            $ins = $conn->prepare("INSERT INTO subscriptions (user_id, club_id, status) VALUES (:uid, :cid, 'active')");
            $ins->bindValue(":uid", (int)$user->id, \PDO::PARAM_INT);
            $ins->bindValue(":cid", (int)$clubRow->id, \PDO::PARAM_INT);
            $ins->execute();
        } else {
            // fallback sem clube: apenas cookie de assinante
        }
        try {
            $email = new \Source\Core\Email();
            $body = '<h2>Assinatura Ativada</h2>'
                . '<p>Olá ' . htmlspecialchars($user->name ?? 'Cliente') . ',</p>'
                . '<p>Sua assinatura foi ativada com sucesso.</p>';
            $email->sendEmail((string)($user->email ?? ''), 'Assinatura ativada', $body);
        } catch (\Throwable $e) {}
        $isSubscriber = true;
        echo $this->view->render("subscription", [
            "user" => $user,
            "isSubscriber" => $isSubscriber,
            "justSubscribed" => true
        ]);
        exit;
    }
    public function products (array $data)
    {
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
        $user = current_user();
        $isSubscriber = false;
        if ($user && isset($user->id)) {
            $isSubscriber = user_has_active_subscription((int)$user->id);
        }
        echo $this->view->render("products", [
            "products" => $products,
            "isSubscriber" => $isSubscriber
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
