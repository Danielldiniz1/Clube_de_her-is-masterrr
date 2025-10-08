<?php
    echo $this->layout("_theme");
?>
<div class="container">
        <h2>Nossa Coleção</h2>

        <div class="product-grid">
            <div class="product-card">
                <img src="https://via.placeholder.com/300" alt="Nome do Colecionável">
                <h3>Action Figure Modelo X</h3>
                <p class="price">R$ 349,90</p>
                <a href="#" class="btn">Editar</a>
                <a href="#" class="btn">Excluir</a>
            </div>
            <div class="product-card">
                <img src="https://via.placeholder.com/300" alt="Nome do Colecionável">
                <h3>Estátua Edição Limitada</h3>
                <p class="price">R$ 799,90</p>
                <a href="#" class="btn">Editar</a>
                <a href="#" class="btn">Excluir</a>
            </div>
            </div>
    </div>
    
    <div class="container add-product-container">
        <h2>Adicionar Novo Produto</h2>
        <form class="add-product-form">
            <div class="form-group">
                <label for="product-name">Nome do Produto</label>
                <input type="text" id="product-name" placeholder="Ex: Action Figure do Herói Y" required>
            </div>
    
            <div class="form-group">
                <label for="product-description">Descrição</label>
                <textarea id="product-description" placeholder="Detalhes do produto, material, dimensões, etc."></textarea>
            </div>
            
            <div class="form-group">
                <label for="product-price">Preço (R$)</label>
                <input type="number" id="product-price" placeholder="Ex: 349.90" step="0.01" required>
            </div>
    
            <div class="form-group">
                <label for="product-image">Imagem do Produto</label>
                <input type="file" id="product-image" accept="image/*" required>
            </div>
    
            <button type="submit" class="btn" style="margin-top: 20px;">Cadastrar Produto</button>
        </form>
    </div>
</body>
</html>