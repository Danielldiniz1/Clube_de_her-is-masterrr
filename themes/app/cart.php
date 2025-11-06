<?php
echo $this->layout("_theme");
?>

<div class="container">
    <h2>Carrinho de Compras</h2>

    <?php if (empty($items)): ?>
        <div id="cart-empty">
            <p>Seu carrinho está vazio. Adicione produtos para visualizar aqui.</p>
            <a href="<?= url('app/produtos'); ?>" class="btn">Voltar para Produtos</a>
        </div>
    <?php else: ?>

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
            <?php 
            $total = 0.0; 
            foreach ($items as $item): 
                $price = (float)($item->price ?? 0);
                $qty = (int)($item->quantity ?? 1);
                $subtotal = $price * $qty;
                $total += $subtotal;
                $imagePath = $item->image_path ? url($item->image_path) : url('assets/img/no-image.jpg');
            ?>
            <tr>
                <td>
                    <div style="display:flex; align-items:center; gap:10px;">
                        <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($item->name) ?>" style="width:60px; height:60px; object-fit:cover; border-radius:8px;">
                        <span><?= htmlspecialchars($item->name) ?></span>
                    </div>
                </td>
                <td>R$ <?= number_format($price, 2, ',', '.') ?></td>
                <td>
                    <form action="<?= url('app/carrinho/atualizar'); ?>" method="post" style="display:flex; gap:8px; align-items:center;">
                        <input type="hidden" name="product_id" value="<?= (int)$item->product_id ?>">
                        <input type="number" name="quantity" min="1" value="<?= $qty ?>" style="width:70px;">
                        <button type="submit" class="btn">Atualizar</button>
                    </form>
                </td>
                <td>R$ <?= number_format($subtotal, 2, ',', '.') ?></td>
                <td>
                    <form action="<?= url('app/carrinho/remover'); ?>" method="post">
                        <input type="hidden" name="product_id" value="<?= (int)$item->product_id ?>">
                        <button type="submit" class="btn" style="background:#ff3333; color:#000;">Remover</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <div class="cart-summary">
        <h3 id="cart-total">Total: R$ <?= number_format($total ?? 0, 2, ',', '.') ?></h3>
        <form action="<?= url('app/carrinho/limpar'); ?>" method="post" style="display:inline-block; margin-right:10px;">
            <button type="submit" class="btn" style="background:#1a0d0d;">Limpar Carrinho</button>
        </form>
        <a href="#" class="btn">Finalizar Compra</a>
    </div>
    <?php endif; ?>
</div>

<?php $this->start("post-scripts"); ?>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const table = document.getElementById('cart-table');
    const totalEl = document.getElementById('cart-total');

    const recalc = () => {
        if (!table || !totalEl) return;
        let total = 0;
        table.querySelectorAll('tbody tr').forEach(row => {
            const priceCell = row.querySelector('td:nth-child(2)');
            const qtyInput = row.querySelector('input[name="quantity"]');
            const subtotalCell = row.querySelector('td:nth-child(4)');

            if (!priceCell || !qtyInput || !subtotalCell) return;

            // Extrai o preço em formato pt-BR e converte para número
            const priceText = (priceCell.textContent || '').replace(/[^0-9,.-]/g, '').replace(/\./g, '').replace(',', '.');
            const price = parseFloat(priceText) || 0;
            const qty = Math.max(1, parseInt(qtyInput.value || '1', 10));

            const subtotal = price * qty;
            subtotalCell.textContent = 'R$ ' + subtotal.toFixed(2).replace('.', ',');
            total += subtotal;
        });
        totalEl.textContent = 'Total: R$ ' + total.toFixed(2).replace('.', ',');
    };

    // Atualiza valores automaticamente ao editar quantidade
    table.querySelectorAll('input[name="quantity"]').forEach(inp => {
        inp.addEventListener('input', recalc);
        inp.addEventListener('change', recalc);
    });

    // Recalcula ao carregar
    recalc();
});
</script>
<?php $this->end(); ?>