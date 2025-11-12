import Toast from '../../class/Toast.js';
import HttpProduct from '../../class/HttpProduct.js';

document.addEventListener('DOMContentLoaded', () => {
    const APP_BASE = typeof window.__APP_BASE === 'string' ? window.__APP_BASE : `${window.location.origin}`;
    const api = new HttpProduct(`${APP_BASE}/api/products`);
    const toast = new Toast();
    // Modal elements
    const editModal = document.getElementById('edit-product-modal');
    const closeModalBtn = editModal.querySelector('.close-modal-btn');

    // Table
    const tableBody = document.getElementById('products-table-body');

    // Forms
    const addProductForm = document.getElementById('add-product-form');
    const editProductForm = document.getElementById('edit-product-form');
    const editFormTitle = document.getElementById('edit-form-title');

    // --- Modal Control ---
    const openEditModal = () => editModal.style.display = 'block';
    const closeEditModal = () => editModal.style.display = 'none';

    closeModalBtn.addEventListener('click', closeEditModal);
    window.addEventListener('click', (event) => {
        if (event.target === editModal) {
            closeEditModal();
        }
    });

    // --- Data Loading ---
    const loadProducts = async () => {
        try {
            const response = await api.list();
            const products = response?.data?.products || response?.products || [];
            
            tableBody.innerHTML = '';
            if (products && products.length > 0) {
                products.forEach(product => {
                    const row = `
                        <tr>
                            <td>${product.id}</td>
                            <td>${product.name}</td>
                            <td>R$ ${parseFloat(product.price).toFixed(2)}</td>
                            <td>${product.stock}</td>
                            <td>${product.club_id}</td>
                            <td>
                                <button class="btn btn-secondary btn-action edit-btn" data-id="${product.id}">Editar</button>
                                <button class="btn btn-primary btn-action delete-btn" data-id="${product.id}">Excluir</button>
                            </td>
                        </tr>
                    `;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="6">Nenhum produto encontrado.</td></tr>';
            }
        } catch (error) {
            tableBody.innerHTML = '<tr><td colspan="6">Erro ao carregar produtos.</td></tr>';
            toast.show(error.message || 'Erro ao carregar produtos.', 'error');
        }
    };

    // --- Form and CRUD Logic ---
    const handleAdd = async (event) => {
        event.preventDefault();
        const formData = new FormData(addProductForm);
        
        try {
            const result = await api.create(formData);
            toast.fromApi(result);
            addProductForm.reset();
            loadProducts();
        } catch (error) {
            console.error('Erro ao adicionar produto:', error);
            toast.show(error.message || 'Erro ao adicionar produto.', 'error');
        }
    };
    const handleEdit = async (id) => {
        try {
            const response = await api.getById(id);
            const product = response?.data?.product || response?.product;
            
            editFormTitle.textContent = `Editar Produto: ${product.name}`;
            // Populando o formulário do modal dinamicamente
            editProductForm.innerHTML = `
                <input type="hidden" name="id" value="${product.id}">
                <div class="form-group">
                    <label for="edit-name">Nome do Produto:</label>
                    <input type="text" id="edit-name" name="name" value="${product.name || ''}" required>
                </div>
                <div class="form-group">
                    <label for="edit-price">Preço (R$):</label>
                    <input type="number" id="edit-price" name="price" value="${product.price || ''}" step="0.01" required>
                </div>
                <div class="form-group">
                    <label for="edit-stock">Estoque:</label>
                    <input type="number" id="edit-stock" name="stock" value="${product.stock || '0'}">
                </div>
                <div class="form-group">
                    <label for="edit-category_id">ID da Categoria:</label>
                    <input type="number" id="edit-category_id" name="category_id" value="${product.category_id || ''}">
                </div>
                <div class="form-group">
                    <label for="edit-fandom">Fandom:</label>
                    <input type="text" id="edit-fandom" name="fandom" value="${product.fandom || ''}">
                </div>
                <div class="form-group">
                    <label for="edit-rarity">Raridade:</label>
                    <select id="edit-rarity" name="rarity">
                        <option value="common" ${product.rarity === 'common' ? 'selected' : ''}>Comum</option>
                        <option value="rare" ${product.rarity === 'rare' ? 'selected' : ''}>Raro</option>
                        <option value="exclusive" ${product.rarity === 'exclusive' ? 'selected' : ''}>Exclusivo</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="edit-images">Imagens do Produto:</label>
                    <input type="file" id="edit-images" name="images[]" multiple accept="image/*">
                    <small>Selecione múltiplas imagens (máximo 5). A primeira será definida como principal.</small>
                    <div id="current-images" class="current-images-container">
                        <!-- Imagens atuais serão carregadas aqui -->
                    </div>
                </div>
                <div class="form-group">
                    <label for="edit-weight_grams">Peso (gramas):</label>
                    <input type="number" id="edit-weight_grams" name="weight_grams" value="${product.weight_grams || ''}">
                </div>
                <div class="form-group">
                    <label for="edit-dimensions_cm">Dimensões (cm):</label>
                    <input type="text" id="edit-dimensions_cm" name="dimensions_cm" value="${product.dimensions_cm || ''}" placeholder="Ex: 30x20x10">
                </div>
                <div class="form-group">
                    <label for="edit-description">Descrição:</label>
                    <textarea id="edit-description" name="description">${product.description || ''}</textarea>
                </div>
                <div class="form-group form-group-checkbox">
                    <input type="checkbox" id="edit-is_physical" name="is_physical" value="1" ${product.is_physical == 1 ? 'checked' : ''}>
                    <label for="edit-is_physical">Produto Físico</label>
                </div>
                <div class="form-group form-group-checkbox">
                    <input type="checkbox" id="edit-subscription_only" name="subscription_only" value="1" ${product.subscription_only == 1 ? 'checked' : ''}>
                    <label for="edit-subscription_only">Apenas para Assinantes</label>
                </div>
                <div class="form-group form-group-checkbox">
                    <input type="checkbox" id="edit-is_active" name="is_active" value="1" ${product.is_active == 1 ? 'checked' : ''}>
                    <label for="edit-is_active">Produto Ativo</label>
                </div>
                <button type="submit" class="btn">Salvar Alterações</button>
            `;
            openEditModal();
        } catch (error) {
            toast.show(error.message || 'Não foi possível carregar os dados do produto.', 'error');
        }
    };

    const handleDelete = async (id) => {
        if (!confirm('Tem certeza que deseja excluir este produto?')) return;
        try {
            const response = await api.delete(id);
            toast.fromApi(response);
            loadProducts();
        } catch (error) {
            toast.show(error.message || 'Erro ao excluir produto.', 'error');
        }
    };

    addProductForm.addEventListener('submit', handleAdd);

    editProductForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const submitBtn = editProductForm.querySelector('button[type="submit"]');
        const formData = new FormData(editProductForm);
        const id = formData.get('id');

        submitBtn.disabled = true;
        submitBtn.textContent = 'Salvando...';

        try {
            const result = await api.update(id, formData);
            toast.fromApi(result);
            closeEditModal();
            loadProducts();
        } catch (error) {
            console.error('Erro ao atualizar produto:', error);
            toast.show(error.message || 'Erro ao atualizar produto.', 'error');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Salvar Alterações';
        }
    });

    tableBody.addEventListener('click', (e) => {
        if (e.target.classList.contains('edit-btn')) handleEdit(e.target.dataset.id);
        if (e.target.classList.contains('delete-btn')) handleDelete(e.target.dataset.id);
    });

    loadProducts();
});