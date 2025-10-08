<?php
echo $this->layout("_theme");
?>
<link rel="stylesheet" href="<?= url("themes/app/assets/css/style.css"); ?>">

    <div class="container">
        <h2>Carrinho de Compras</h2>
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Quantidade</th>
                    <th>Subtotal</th>
                    <th>Remover</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="product-info">
                            <img src="https://via.placeholder.com/150" alt="Produto">
                            <span>Action Figure Modelo X</span>
                        </div>
                    </td>
                    <td>R$ 349,90</td>
                    <td><input type="number" value="1"></td>
                    <td>R$ 349,90</td>
                    <td><button class="btn btn-secondary">X</button></td>
                </tr>
                <tr>
                    <td>
                        <div class="product-info">
                            <img src="https://via.placeholder.com/150" alt="Produto">
                            <span>Camiseta Geek</span>
                        </div>
                    </td>
                    <td>R$ 89,90</td>
                    <td><input type="number" value="2"></td>
                    <td>R$ 179,80</td>
                    <td><button class="btn btn-secondary">X</button></td>
                </tr>
            </tbody>
        </table>

        <div class="cart-summary">
            <h3>Total: R$ 529,70</h3>
            <a href="#" class="btn">Finalizar Compra</a>
        </div>
    </div>
</body>
</html>