<?php
echo $this->layout("_theme");
?>
<div class="container">
    <h2>Histórico de Compras</h2>

    <?php if (empty($orders)): ?>
        <p>Você ainda não possui compras registradas.</p>
        <a href="<?= url('app/produtos'); ?>" class="btn">Ver Produtos</a>
    <?php else: ?>
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Pedido Nº</th>
                    <th>Data</th>
                    <th>Status</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td>#<?= htmlspecialchars($order->order_number) ?></td>
                        <td><?= date('d/m/Y H:i', strtotime($order->created_at)) ?></td>
                        <td><?= htmlspecialchars(ucfirst($order->status)) ?></td>
                        <td>R$ <?= number_format((float)$order->total, 2, ',', '.') ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>