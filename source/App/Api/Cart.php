<?php

namespace Source\App\Api;

use Source\Models\CartItem;
use Source\Models\Product;

class Cart extends Api
{
    public function __construct()
    {
        parent::__construct();
    }

    public function listItems(): void
    {
        $this->auth();

        $cart = new CartItem();
        $items = $cart->listItemsWithProducts($this->userAuth->id);

        $this->call(200, "success", "Itens do carrinho", "success")->back([
            "items" => $items
        ]);
    }

    public function add(array $data): void
    {
        $this->auth();

        // Aceita tanto snake_case quanto camelCase
        $productId = (int)($data['product_id'] ?? $data['productId'] ?? 0);
        $quantity = (int)($data['quantity'] ?? 1);

        if (!$productId || $quantity < 1) {
            $this->call(400, "error", "Parâmetros inválidos", "validation_error")->back();
            return;
        }

        // Verifica se o produto existe
        $product = new Product();
        $prod = $product->selectById($productId);
        if (!$prod) {
            $this->call(404, "error", "Produto não encontrado", "error")->back();
            return;
        }

        $cart = new CartItem();
        if (!$cart->addOrIncrement($this->userAuth->id, $productId, $quantity)) {
            $this->call(500, "error", $cart->getMessage() ?? "Falha ao adicionar ao carrinho", "error")->back();
            return;
        }

        $this->call(200, "success", $cart->getMessage(), "success")->back();
    }

    public function updateItem(array $data): void
    {
        $this->auth();

        // Aceita tanto snake_case quanto camelCase
        $productId = (int)($data['productId'] ?? $data['product_id'] ?? 0);
        $quantity = (int)($data['quantity'] ?? 1);

        if (!$productId || $quantity < 1) {
            $this->call(400, "error", "Parâmetros inválidos", "validation_error")->back();
            return;
        }

        $cart = new CartItem();
        if (!$cart->setQuantity($this->userAuth->id, $productId, $quantity)) {
            $this->call(500, "error", $cart->getMessage() ?? "Falha ao atualizar item", "error")->back();
            return;
        }

        $this->call(200, "success", $cart->getMessage(), "success")->back();
    }

    public function removeItem(array $data): void
    {
        $this->auth();

        // Aceita tanto snake_case quanto camelCase
        $productId = (int)($data['productId'] ?? $data['product_id'] ?? 0);
        if (!$productId) {
            $this->call(400, "error", "ID do produto inválido", "validation_error")->back();
            return;
        }

        $cart = new CartItem();
        if (!$cart->removeItem($this->userAuth->id, $productId)) {
            $this->call(500, "error", $cart->getMessage() ?? "Falha ao remover item", "error")->back();
            return;
        }

        $this->call(200, "success", $cart->getMessage(), "success")->back();
    }

    public function clear(): void
    {
        $this->auth();

        $cart = new CartItem();
        if (!$cart->clear($this->userAuth->id)) {
            $this->call(500, "error", $cart->getMessage() ?? "Falha ao limpar carrinho", "error")->back();
            return;
        }

        $this->call(200, "success", $cart->getMessage(), "success")->back();
    }
}