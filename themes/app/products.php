<?php
    echo $this->layout("_theme");
?>

<div class="products-section">
    <div class="products-header">
        <h1>🛍️ LOJA DE HERÓIS</h1>
        <p>Descubra produtos épicos para verdadeiros heróis!</p>
    </div>

    <div class="products-grid">
        <?php if (!empty($products)): ?>
            <?php foreach ($products as $product): ?>
                <div class="product-card">
                    <div class="product-image">
                        <?php if (!empty($product['images']) && count($product['images']) > 1): ?>
                            <!-- Carrossel para múltiplas imagens -->
                            <div class="carousel-container" data-product-id="<?= $product['id'] ?>">
                                <div class="carousel-images">
                                    <?php foreach ($product['images'] as $index => $image): ?>
                                        <img src="<?= url($image['image_path']) ?>" 
                                             alt="<?= htmlspecialchars($product['name']) ?>" 
                                             class="carousel-image <?= $index === 0 ? 'active' : '' ?>"
                                             data-index="<?= $index ?>">
                                    <?php endforeach; ?>
                                </div>
                                
                                <!-- Navegação do carrossel -->
                                <button class="carousel-btn carousel-prev" onclick="changeImage(<?= $product['id'] ?>, -1)">
                                    ‹
                                </button>
                                <button class="carousel-btn carousel-next" onclick="changeImage(<?= $product['id'] ?>, 1)">
                                    ›
                                </button>
                                
                                <!-- Indicadores -->
                                <div class="carousel-indicators">
                                    <?php foreach ($product['images'] as $index => $image): ?>
                                        <span class="indicator <?= $index === 0 ? 'active' : '' ?>" 
                                              onclick="goToImage(<?= $product['id'] ?>, <?= $index ?>)"
                                              data-index="<?= $index ?>"></span>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        <?php elseif (!empty($product['images'])): ?>
                            <!-- Imagem única -->
                            <img src="<?= url($product['images'][0]['image_path']) ?>" alt="<?= htmlspecialchars($product['name']) ?>">
                        <?php else: ?>
                            <!-- Imagem padrão -->
                            <img src="<?= url('assets/img/no-image.jpg') ?>" alt="Sem imagem">
                        <?php endif; ?>
                        
                        <?php if ($product['stock'] > 0): ?>
                            <div class="product-badge">EM ESTOQUE</div>
                        <?php endif; ?>
                    </div>
                    
                    <div class="product-info">
                        <h3 class="product-name"><?= htmlspecialchars($product['name']) ?></h3>
                        
                        <?php if (!empty($product['description'])): ?>
                            <p class="product-description"><?= htmlspecialchars($product['description']) ?></p>
                        <?php endif; ?>
                        
                        <div class="product-price">R$ <?= number_format($product['price'], 2, ',', '.') ?></div>
                        
                        <div class="product-stock <?= $product['stock'] <= 0 ? 'out-of-stock' : '' ?>">
                            <?php if ($product['stock'] > 0): ?>
                                ⚡ <?= $product['stock'] ?> unidades disponíveis
                            <?php else: ?>
                                ❌ Produto esgotado
                            <?php endif; ?>
                        </div>
                        
                        <div class="product-actions">
                            <?php if ($product['stock'] > 0): ?>
                                <button class="btn-add-cart" onclick="addToCart(<?= $product['id'] ?>)">
                                    🛒 ADICIONAR AO CARRINHO
                                </button>
                            <?php else: ?>
                                <button class="btn-add-cart" disabled>
                                    PRODUTO ESGOTADO
                                </button>
                            <?php endif; ?>
                            
                            <button class="btn-wishlist" onclick="addToWishlist(<?= $product['id'] ?>)" title="Adicionar à Lista de Desejos">
                                ❤️
                            </button>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="no-products">
                <h3>😔 NENHUM PRODUTO ENCONTRADO</h3>
                <p>Não há produtos disponíveis no momento. Volte em breve para descobrir novos itens heroicos!</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<style>
    /* Usando as variáveis CSS do tema principal */
    .products-section {
        padding: 2rem 0;
        background: #1a0d0d;
        min-height: 100vh;
    }

    .products-header {
        text-align: center;
        margin-bottom: 3rem;
        padding: 2rem 0;
        background: linear-gradient(135deg, #ff3333 0%, #cc0000 100%);
        color: #000;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(255, 51, 51, 0.3);
        border: 2px solid #ff5050;
        margin: 0 auto 3rem auto;
    }

    .products-header h1 {
        font-family: 'Bangers', cursive;
        font-size: 3.5rem;
        margin: 0;
        letter-spacing: 2px;
        text-shadow: 2px 2px 0px rgba(0,0,0,0.3);
        color: #000;
    }

    .products-header p {
        font-size: 1.3rem;
        margin: 15px 0 0 0;
        opacity: 0.9;
        font-weight: 00;
        color: #000;
        font-family: 'Roboto', sans-serif;
    }

    .products-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
        gap: 1rem;
        margin-top: 1.5rem;
        max-width: 1200px;
        margin: 1.5rem auto 0 auto;
        padding: 0 1rem;
    }

    .product-card {
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3), 0 3px 10px rgba(0, 0, 0, 0.2);
            transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
            overflow: hidden;
            position: relative;
            min-height: 200px;
            display: flex;
            flex-direction: column;
        }

    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px rgba(255, 51, 51, 0.2);
        border-color: #ff3333;
    }

    .product-image {
            position: relative;
            width: 100%;
            height: 220px;
            overflow: hidden;
            border-radius: 10px;
            background: #1a0d0d;
            margin-bottom: 12px;
            flex: 1;
        }

    .product-image img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 15px;
            transition: transform 0.3s ease;
        }

    .product-card:hover .product-image img {
        transform: scale(1.05);
    }

    .product-badge {
        position: absolute;
        top: 15px;
        right: 15px;
        background: #ff3333;
        color: #000;
        padding: 8px 15px;
        border-radius: 20px;
        font-size: 0.9rem;
        font-weight: 700;
        font-family: 'Bangers', cursive;
        letter-spacing: 1px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .product-info {
            flex-shrink: 0;
            padding: 12px 8px 8px 8px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

    .product-name {
            font-family: 'Bangers', cursive;
            font-size: 1.1rem;
            font-weight: 700;
            color: #ff3333;
            margin: 0;
            line-height: 1.2;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        }

    .product-description {
        color: #a0a0a0;
        font-size: 0.85rem;
        line-height: 1.4;
        margin: 0;
        display: -webkit-box;
        -webkit-box-orient: vertical;
        overflow: hidden;
        font-family: 'Roboto', sans-serif;
    }

    .product-price {
            font-family: 'Bangers', cursive;
            font-size: 1.3rem;
            font-weight: 800;
            color: #ffd700;
            margin: 0;
            letter-spacing: 0.5px;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.4);
        }

    .product-stock {
        font-size: 0.8rem;
        color: #ff3333;
        margin: 0;
        font-weight: 600;
        font-family: 'Roboto', sans-serif;
    }

    .product-stock.out-of-stock {
        color: #ff3333;
    }

    .product-actions {
        display: flex;
        gap: 10px;
        margin-top: 4px;
    }

    .btn-add-cart {
        flex: 1;
        background: #ff3333;
        color: #000;
        border: none;
        padding: 10px 16px;
        border-radius: 15px;
        font-family: 'Bangers', cursive;
        font-weight: 400;
        font-size: 1rem;
        letter-spacing: 1px;
        cursor: pointer;
        transition: all 0.3s ease;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    .btn-add-cart:hover:not(:disabled) {
        background: #ff5050;
        transform: translateY(-2px);
        box-shadow: 0 10px 25px rgba(255, 51, 51, 0.4);
    }

    .btn-add-cart:disabled {
        background: #a0a0a0;
        color: #1a0d0d;
        cursor: not-allowed;
        opacity: 0.6;
    }

    .btn-wishlist {
        background: #1a0d0d;
        border: 2px solid #333;
        color: #a0a0a0;
        padding: 10px 12px;
        border-radius: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
        font-size: 1.1rem;
    }

    .btn-wishlist:hover {
        background: #ff3333;
        border-color: #ff3333;
        color: #000;
        transform: translateY(-2px);
    }

    .no-products {
        text-align: center;
        padding: 4rem 2rem;
        color: #a0a0a0;
        grid-column: 1 / -1;
    }

    .no-products h3 {
        font-family: 'Bangers', cursive;
        font-size: 2.5rem;
        margin-bottom: 20px;
        color: #f5f5f5;
        letter-spacing: 2px;
    }

    .no-products p {
        font-size: 1.2rem;
        color: #a0a0a0;
        font-family: 'Roboto', sans-serif;
    }

    .loading {
        text-align: center;
        padding: 4rem 2rem;
        color: #a0a0a0;
        font-size: 1.3rem;
        font-family: 'Bangers', cursive;
        letter-spacing: 1px;
        grid-column: 1 / -1;
    }

    /* Carrossel de Imagens */
        .carousel-container {
            position: relative;
            width: 100%;
            height: 220px;
            overflow: hidden;
            border-radius: 15px;
            flex: 1;
        }

        .carousel-images {
            position: relative;
            width: 100%;
            height: 100%;
        }

        .carousel-image {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0;
            transition: opacity 0.5s ease-in-out;
        }

        .carousel-image.active {
            opacity: 1;
        }

        .carousel-btn {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background: rgba(255, 51, 51, 0.8);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            font-size: 18px;
            font-weight: bold;
            cursor: pointer;
            z-index: 10;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .carousel-btn:hover {
            background: rgba(255, 51, 51, 1);
            transform: translateY(-50%) scale(1.1);
        }

        .carousel-prev {
            left: 10px;
        }

        .carousel-next {
            right: 10px;
        }

        .carousel-indicators {
            position: absolute;
            bottom: 15px;
            left: 50%;
            transform: translateX(-50%);
            display: flex;
            gap: 8px;
            z-index: 10;
        }

        .indicator {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: rgba(255, 255, 255, 0.5);
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .indicator.active {
            background: #ff3333;
            transform: scale(1.2);
        }

        .indicator:hover {
            background: rgba(255, 51, 51, 0.8);
        }
    @media (max-width: 768px) {
        .products-header h1 {
            font-size: 2.2rem;
        }
        
        .products-header p {
            font-size: 1.1rem;
        }
        
        .products-grid {
            grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
            gap: 0.8rem;
        }
        
        .product-info {
            padding: 10px 6px 6px 6px;
        }

        .product-name {
            font-size: 1rem;
            margin-bottom: 4px;
        }

        .product-price {
            font-size: 1.1rem;
            margin-bottom: 4px;
        }

        .product-card {
            min-height: 180px;
            padding: 6px;
        }

        .product-image,
        .carousel-container {
            height: 180px;
        }

        .product-image img {
            height: 100%;
        }

        .carousel-btn {
            width: 25px;
            height: 25px;
            font-size: 12px;
        }

        .indicator {
            width: 6px;
            height: 6px;
        }
    }

    @media (max-width: 480px) {
        .products-header {
            padding: 1.5rem 1rem;
            margin-bottom: 2rem;
        }

        .products-header h1 {
            font-size: 2rem;
        }

        .product-actions {
            flex-direction: column;
        }

        .btn-wishlist {
            align-self: center;
            width: 50px;
        }
    }
</style>

<script>
    // Carrossel de Imagens
    function changeImage(productId, direction) {
        const container = document.querySelector(`[data-product-id="${productId}"]`);
        const images = container.querySelectorAll('.carousel-image');
        const indicators = container.querySelectorAll('.indicator');
        
        let currentIndex = 0;
        
        // Encontrar imagem ativa atual
        images.forEach((img, index) => {
            if (img.classList.contains('active')) {
                currentIndex = index;
            }
        });
        
        // Calcular novo índice
        let newIndex = currentIndex + direction;
        
        if (newIndex >= images.length) {
            newIndex = 0;
        } else if (newIndex < 0) {
            newIndex = images.length - 1;
        }
        
        // Atualizar imagens
        images[currentIndex].classList.remove('active');
        images[newIndex].classList.add('active');
        
        // Atualizar indicadores
        indicators[currentIndex].classList.remove('active');
        indicators[newIndex].classList.add('active');
    }
    
    function goToImage(productId, index) {
        const container = document.querySelector(`[data-product-id="${productId}"]`);
        const images = container.querySelectorAll('.carousel-image');
        const indicators = container.querySelectorAll('.indicator');
        
        // Remover classe active de todos
        images.forEach(img => img.classList.remove('active'));
        indicators.forEach(indicator => indicator.classList.remove('active'));
        
        // Adicionar classe active ao selecionado
        images[index].classList.add('active');
        indicators[index].classList.add('active');
    }
    
    // Auto-play opcional (descomente para ativar)
    /*
    document.addEventListener('DOMContentLoaded', function() {
        const carousels = document.querySelectorAll('.carousel-container');
        
        carousels.forEach(carousel => {
            const productId = carousel.getAttribute('data-product-id');
            const images = carousel.querySelectorAll('.carousel-image');
            
            if (images.length > 1) {
                setInterval(() => {
                    changeImage(productId, 1);
                }, 5000); // Muda a cada 5 segundos
            }
        });
    });
    */

    // Adicionar suporte a teclado
    document.addEventListener('keydown', function(e) {
        const activeCarousel = document.querySelector('.carousel-container:hover');
        if (activeCarousel) {
            const productId = activeCarousel.getAttribute('data-product-id');
            
            if (e.key === 'ArrowLeft') {
                changeImage(productId, -1);
            } else if (e.key === 'ArrowRight') {
                changeImage(productId, 1);
            }
        }
    });

function addToCart(productId) {
    // Implementar lógica do carrinho
    const button = event.target;
    const originalText = button.textContent;
    
    button.textContent = '✅ ADICIONADO!';
    button.style.background = '#28a745';
    
    setTimeout(() => {
        button.textContent = originalText;
        button.style.background = '#ff3333';
    }, 2000);
    
    console.log('Produto adicionado ao carrinho! (ID: ' + productId + ')');
}

function addToWishlist(productId) {
    // Implementar lógica da lista de desejos
    const button = event.target;
    button.style.transform = 'scale(1.2)';
    
    setTimeout(() => {
        button.style.transform = 'scale(1)';
    }, 200);
    
    console.log('Produto adicionado à lista de desejos! (ID: ' + productId + ')');
}
</script>