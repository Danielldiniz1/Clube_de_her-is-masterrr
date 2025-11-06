<?php
echo $this->layout("_theme");
?>

<div class="container">
    <h2>Carrinho de Compras</h2>

    <div id="cart-empty" style="display:none;">
        <p>Seu carrinho está vazio. Adicione produtos para visualizar aqui.</p>
        <a href="<?= url('app/produtos'); ?>" class="btn">Voltar para Produtos</a>
    </div>

    <table class="custom-table" id="cart-table">
        <thead>
            <tr>
                <th>Produto</th>
                <th>Preço</th>
                <th>Quantidade</th>
                <th>Subtotal</th>
                <th>Remover</th>
            </tr>
        </thead>
        <tbody id="cart-body">
            <!-- itens do carrinho renderizados via JS -->
        </tbody>
    </table>

    <div class="cart-summary">
        <h3 id="cart-total">Total: R$ 0,00</h3>
        <a href="#" class="btn">Finalizar Compra</a>
    </div>
</div>

<?php $this->start("post-scripts"); ?>
<script type="module" src="<?= url("assets/js/app/scripts-cart-page.js"); ?>" async></script>
<?php $this->end(); ?>