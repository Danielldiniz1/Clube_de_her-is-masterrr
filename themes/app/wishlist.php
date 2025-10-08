<?php
    echo $this->layout("_theme");
?>
<div class="container">
        <h2>Lista de Desejos</h2>
        <table class="custom-table">
            <thead>
                <tr>
                    <th>Produto</th>
                    <th>Preço</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="product-info">
                            <img src="https://via.placeholder.com/150" alt="Produto">
                            <span>Estátua Edição Limitada</span>
                        </div>
                    </td>
                    <td>R$ 799,90</td>
                    <td>
                        <a href="#" class="btn" style="margin-right: 10px;">Comprar</a>
                        <a href="#" class="btn btn-secondary">Remover</a>
                    </td>
                </tr>
                </tbody>
        </table>
    </div>
</body>
</html>