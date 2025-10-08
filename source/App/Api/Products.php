<?php

namespace Source\App\Api;

use Source\Models\Product;
use Source\Models\Club;
use Source\Models\ProductImage;

class Products extends Api
{
    public function __construct()
    {
        parent::__construct();
    }

    public function getProduct()
    {
        $this->auth();

        // Verificar se a autenticação foi bem-sucedida
        if (!$this->userAuth) {
            $this->call(401, "error", "Usuário não autenticado", "error")->back();
            return;
        }

        // Buscar produtos dos clubes do usuário
        $club = new Club();
        $userClubs = $club->selectByUserId($this->userAuth->id);
        
        $allProducts = [];
        foreach($userClubs as $userClub) {
            $product = new Product();
            $clubProducts = $product->selectByClubId($userClub->id);
            $allProducts = array_merge($allProducts, $clubProducts);
        }

        $this->back([
            "tipo" => "success",
            "mensagem" => "Produtos dos seus clubes",
            "products" => $allProducts
        ]);
    }

    public function listProducts()
    {
        $products = new Product();
        $allProducts = $products->selectAll() ?? [];

        $this->call(200, "success", "Lista de produtos recuperada", "success")->back([
            "products" => $allProducts
        ]);
    }

    public function listProductsWithImages()
    {
        $product = new Product();
        $allProducts = $product->selectAll() ?? [];
        
        $productsWithImages = [];
        foreach ($allProducts as $productData) {
            $productImage = new ProductImage();
            $images = $productImage->getByProductId($productData->id) ?? [];
            
            $productArray = (array) $productData;
            $productArray['images'] = $images;
            $productsWithImages[] = $productArray;
        }

        $this->call(200, "success", "Lista de produtos com imagens recuperada", "success")->back([
            "products" => $productsWithImages
        ]);
    }

    public function listProductsByClub(array $data)
    {
        if(empty($data["club_id"])) {
            $this->call(400, "error", "ID do clube é obrigatório", "error")->back();
            return;
        }

        $product = new Product();
        $clubProducts = $product->selectByClubId($data["club_id"]);

        $this->call(200, "success", "Produtos do clube recuperados", "success")->back([
            "products" => $clubProducts
        ]);
    }

    public function createProduct(array $data)
    {
        // Verificar se há arquivos de imagem enviados
        $uploadedImages = [];
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $uploader = new \SorFabioSantos\Uploader\Uploader();
            
            for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
                if ($i >= 5) break; // Máximo 5 imagens
                
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    // Criar array temporário para cada arquivo
                    $tempFile = [
                        'name' => $_FILES['images']['name'][$i],
                        'type' => $_FILES['images']['type'][$i],
                        'tmp_name' => $_FILES['images']['tmp_name'][$i],
                        'error' => $_FILES['images']['error'][$i],
                        'size' => $_FILES['images']['size'][$i]
                    ];
                    
                    $imagePath = $uploader->Image($tempFile, "products");
                    if ($imagePath) {
                        $uploadedImages[] = $imagePath;
                    }
                }
            }
        }

        $product = new Product(
            null,
            $data["club_id"] ?? null,
            $data["name"] ?? null,
            $data["description"] ?? null,
            isset($data["price"]) ? (float)$data["price"] : null,
            isset($data["stock"]) ? (int)$data["stock"] : 0,
            isset($data["category_id"]) ? (int)$data["category_id"] : null,
            $data["fandom"] ?? null,
            $data["rarity"] ?? 'common',
            isset($data["is_physical"]) ? filter_var($data["is_physical"], FILTER_VALIDATE_BOOLEAN) : true,
            isset($data["subscription_only"]) ? filter_var($data["subscription_only"], FILTER_VALIDATE_BOOLEAN) : false,
            isset($data["weight_grams"]) ? (int)$data["weight_grams"] : null,
            $data["dimensions_cm"] ?? null
        );

        $insertId = $product->insert();

        if(!$insertId){
            $this->call(400, "error", $product->getMessage(), "error")->back();
            return;
        }

        // Salvar todas as imagens na tabela product_images
        if (!empty($uploadedImages)) {
            foreach ($uploadedImages as $index => $imagePath) {
                $productImage = new \Source\Models\ProductImage(
                    null,
                    $insertId,
                    $imagePath,
                    $index === 0, // Primeira imagem é principal
                    $index + 1
                );
                $productImage->insert();
            }
        }

        $this->call(201, "success", "Produto cadastrado com sucesso!", "success")->back([
            "product_id" => $insertId,
            "images_uploaded" => count($uploadedImages)
        ]);
    }

    public function updateProduct(array $data)
    {
        if(empty($data["id"])) {
            $this->call(400, "error", "ID do produto é obrigatório", "error")->back();
            return;
        }

        $products = new Product();
        $productData = $products->selectById($data["id"]);

        if(!$productData) {
            $this->call(404, "error", "Produto não encontrado", "error")->back();
            return;
        }

        // Verificar se há arquivos de imagem enviados
        $uploadedImages = [];
        if (isset($_FILES['images']) && !empty($_FILES['images']['name'][0])) {
            $uploader = new \SorFabioSantos\Uploader\Uploader();
            
            for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
                if ($i >= 5) break; // Máximo 5 imagens
                
                if ($_FILES['images']['error'][$i] === UPLOAD_ERR_OK) {
                    // Criar array temporário para cada arquivo
                    $tempFile = [
                        'name' => $_FILES['images']['name'][$i],
                        'type' => $_FILES['images']['type'][$i],
                        'tmp_name' => $_FILES['images']['tmp_name'][$i],
                        'error' => $_FILES['images']['error'][$i],
                        'size' => $_FILES['images']['size'][$i]
                    ];
                    
                    $imagePath = $uploader->Image($tempFile, "products");
                    if ($imagePath) {
                        $uploadedImages[] = $imagePath;
                    }
                }
            }
        }

        $product = new Product(
            $data["id"],
            $productData->club_id,
            $data["name"],
            $data["description"] ?? $productData->description,
            $data["price"] ?? $productData->price,
            $data["stock"] ?? $productData->stock,
            $data["category_id"] ?? $productData->category_id,
            $data["fandom"] ?? $productData->fandom,
            $data["rarity"] ?? $productData->rarity,
            isset($data["is_physical"]) ? filter_var($data["is_physical"], FILTER_VALIDATE_BOOLEAN) : $productData->is_physical,
            isset($data["subscription_only"]) ? filter_var($data["subscription_only"], FILTER_VALIDATE_BOOLEAN) : $productData->subscription_only,
            $data["weight_grams"] ?? $productData->weight_grams,
            $data["dimensions_cm"] ?? $productData->dimensions_cm,
            !empty($uploadedImages) ? $uploadedImages[0] : $productData->image_url,
            isset($data["is_active"]) ? filter_var($data["is_active"], FILTER_VALIDATE_BOOLEAN) : $productData->is_active
        );

        if(!$product->update()){
            $this->call(400, "error", $product->getMessage(), "error")->back();
            return;
        }

        // Se novas imagens foram enviadas, atualizar a tabela product_images
        if (!empty($uploadedImages)) {
            $productImage = new ProductImage();
            // Remover imagens antigas
            $productImage->deleteByProductId($data["id"]);
            
            // Adicionar novas imagens
            foreach ($uploadedImages as $index => $imagePath) {
                $productImage->insert(
                    $data["id"],
                    $imagePath,
                    $index === 0, // Primeira imagem é principal
                    $index + 1
                );
            }
        }

        $this->call(200, "success", $product->getMessage(), "success")->back([
            "product" => (array)$productData,
            "images_updated" => count($uploadedImages)
        ]);
    }

    public function getProductById(array $data)
    {
        if(empty($data["id"])) {
            $this->call(400, "error", "ID do produto é obrigatório", "error")->back();
            return;
        }

        $products = new Product();
        $product = $products->selectById($data["id"]);

        if(!$product) {
            $this->call(404, "error", "Produto não encontrado", "error")->back();
            return;
        }

        $this->call(200, "success", "Dados do produto recuperados", "success")->back([
            "product" => $product
        ]);
    }

    public function updateStock(array $data)
    {
        $this->auth();

        if(empty($data["id"]) || !isset($data["stock"])) {
            $this->call(400, "error", "ID do produto e quantidade em estoque são obrigatórios", "error")->back();
            return;
        }

        // Verificar se o produto existe e pertence a um clube do usuário
        $products = new Product();
        $productData = $products->selectById($data["id"]);

        if(!$productData) {
            $this->call(404, "error", "Produto não encontrado", "error")->back();
            return;
        }

        // Verificar se o clube do produto pertence ao usuário autenticado
        $club = new Club();
        $clubData = $club->selectById($productData->club_id);

        // Verificar se a autenticação foi bem-sucedida
        if (!$this->userAuth) {
            $this->call(401, "error", "Usuário não autenticado", "error")->back();
            return;
        }

        if(!$clubData || $clubData->user_id != $this->userAuth->id) {
            $this->call(403, "error", "Você não tem permissão para atualizar o estoque deste produto", "error")->back();
            return;
        }

        $product = new Product($data["id"]);
        
        if(!$product->updateStock((int)$data["stock"])){
            $this->call(400, "error", $product->getMessage(), "error")->back();
            return;
        }

        $this->call(200, "success", $product->getMessage(), "success")->back([
            "new_stock" => (int)$data["stock"]
        ]);
    }

    public function deleteProduct(array $data)
    {
        if(empty($data["id"])) {
            $this->call(400, "error", "ID do produto é obrigatório", "error")->back();
            return;
        }

        $products = new Product();
        $productData = $products->selectById($data["id"]);

        if(!$productData) {
            $this->call(404, "error", "Produto não encontrado", "error")->back();
            return;
        }

        $product = new Product($data["id"]);

        if(!$product->delete()){
            $this->call(500, "error", $product->getMessage(), "error")->back();
            return;
        }

        $this->call(200, "success", "Produto removido com sucesso!", "success")->back();
    }
}
