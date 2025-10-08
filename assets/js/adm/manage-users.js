import { showToast, adminApi } from './admin-helpers.js';

document.addEventListener('DOMContentLoaded', () => {
    // Modal elements
    const editModal = document.getElementById('edit-user-modal');
    const closeModalBtn = editModal.querySelector('.close-modal-btn');

    // Table
    const tableBody = document.getElementById('users-table-body');

    // Forms
    const addUserForm = document.getElementById('add-user-form');
    const editUserForm = document.getElementById('edit-user-form');
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
    const loadUsers = async () => {
        try {
            const response = await adminApi.request('/users');
            const users = response.data.users;
            
            tableBody.innerHTML = '';
            if (users && users.length > 0) {
                users.forEach(user => {
                    const row = `
                        <tr>
                            <td>${user.id}</td>
                            <td>${user.name}</td>
                            <td>${user.email}</td>
                            <td>${user.idType == 1 ? 'Vendedor' : 'Cliente'}</td>
                            <td>
                                <button class="btn btn-secondary btn-action edit-btn" data-id="${user.id}">Editar</button>
                                <button class="btn btn-primary btn-action delete-btn" data-id="${user.id}">Excluir</button>
                            </td>
                        </tr>
                    `;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="5">Nenhum usuário encontrado.</td></tr>';
            }
        } catch (error) {
            tableBody.innerHTML = '<tr><td colspan="5">Erro ao carregar usuários.</td></tr>';
        }
    };

    // --- Form and CRUD Logic ---
    const handleEdit = async (id) => {
        editUserForm.reset();
        try {
            const response = await adminApi.request(`/users/${id}`);
            const user = response.data.user;
            
            editFormTitle.textContent = `Editar Usuário: ${user.name}`;
            document.getElementById('edit-user_id').value = user.id;
            document.getElementById('edit-name').value = user.name;
            document.getElementById('edit-email').value = user.email;
            document.getElementById('edit-idType').value = user.idType;
            document.getElementById('edit-photo').value = user.photo || '';
            
            openEditModal();
        } catch (error) {
            showToast('Não foi possível carregar os dados do usuário.', 'error');
        }
    };

    const handleDelete = async (id) => {
        if (!confirm('Tem certeza que deseja excluir este usuário? Esta ação é irreversível.')) {
            return;
        }
        try {
            const response = await adminApi.request(`/users/${id}`, 'DELETE');
            showToast(response.message || 'Usuário excluído com sucesso!', 'success');
            loadUsers();
        } catch (error) { /* Error already handled by helper */ }
    };

    // Add User Form Submission
    addUserForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const submitBtn = addUserForm.querySelector('button[type="submit"]');
        const password = addUserForm.querySelector('#add-password').value;
        const confirmPassword = addUserForm.querySelector('#add-confirm-password').value;

        if (password !== confirmPassword) {
            showToast('As senhas não coincidem.', 'error');
            return;
        }

        const formData = new FormData(addUserForm);
        const data = Object.fromEntries(formData.entries());
        delete data.confirm_password;

        submitBtn.disabled = true;
        submitBtn.textContent = 'Adicionando...';

        try {
            const response = await adminApi.request('/users/register', 'POST', data);
            showToast(response.message || 'Usuário adicionado com sucesso!', 'success');
            addUserForm.reset();
            loadUsers();
        } catch (error) { 
            // O erro já é tratado e exibido pelo adminApi helper
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Adicionar Usuário';
        }
    });

    // Edit User Form Submission
    editUserForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const submitBtn = editUserForm.querySelector('button[type="submit"]');
        const password = editUserForm.querySelector('#edit-password').value;
        const confirmPassword = editUserForm.querySelector('#edit-confirm-password').value;

        if (password !== confirmPassword) {
            showToast('As senhas não coincidem.', 'error');
            return;
        }

        const formData = new FormData(editUserForm);
        const data = Object.fromEntries(formData.entries());
        delete data.confirm_password;
        const id = data.id;

        submitBtn.disabled = true;
        submitBtn.textContent = 'Salvando...';

        try {
            const response = await adminApi.request(`/users/${id}`, 'PUT', data);
            showToast(response.message || 'Usuário atualizado com sucesso!', 'success');
            closeEditModal();
            loadUsers();
        } catch (error) { 
            // O erro já é tratado e exibido pelo adminApi helper
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Salvar Alterações';
        }
    });

    // Table event delegation for Edit and Delete buttons
    tableBody.addEventListener('click', (e) => {
        if (e.target.classList.contains('edit-btn')) handleEdit(e.target.dataset.id);
        if (e.target.classList.contains('delete-btn')) handleDelete(e.target.dataset.id);
    });

    // Initial Load
    loadUsers();
});