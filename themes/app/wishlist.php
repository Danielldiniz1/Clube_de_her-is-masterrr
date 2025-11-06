<?php
echo $this->layout("_theme");
?>
<div class="container">
    <h2>Lista de Desejos</h2>

    <?php if (empty($items)): ?>
        <p>Sua lista de desejos está vazia.</p>
        <a href="<?= url('app/produtos'); ?>" class="btn">Ver Produtos</a>
    <?php else: ?>
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Ações</h3>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item): ?>
                    <?php 
                        $imagePath = $item->image_path ? url($item->image_path) : url('assets/img/no-image.jpg');
                    ?>
                    <tr>
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                <img src="<?= $imagePath ?>" alt="<?= htmlspecialchars($item->name) ?>" style="width:60px; height:60px; object-fit:cover; border-radius:8px;">
                                <span><?= htmlspecialchars($item->name) ?></span>
                            </div>
                        </td>
                        <td>R$ <?= number_format((float)$item->price, 2, ',', '.') ?></td>
                        <td>
                            <form action="<?= url('app/carrinho/adicionar'); ?>" method="post" style="display:inline-block; margin-right:10px;">
                                <input type="hidden" name="product_id" value="<?= (int)$item->product_id ?>">
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn">Comprar</button>
                            </form>
                            <form action="<?= url('app/wishlist/remover'); ?>" method="post" style="display:inline-block;">
                                <input type="hidden" name="product_id" value="<?= (int)$item->product_id ?>">
                                <button type="submit" class="btn btn-secondary">Remover</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>