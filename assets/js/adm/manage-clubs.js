import Toast from '../../class/Toast.js';
import HttpClub from '../../class/HttpClub.js';

document.addEventListener('DOMContentLoaded', () => {
    const toast = new Toast();
    const api = new HttpClub();
    // Modal elements
    const editModal = document.getElementById('edit-club-modal');
    const closeModalBtn = editModal.querySelector('.close-modal-btn');

    // Table
    const tableBody = document.getElementById('clubs-table-body');

    // Forms
    const addClubForm = document.getElementById('add-club-form');
    const editClubForm = document.getElementById('edit-club-form');
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
    const loadClubs = async () => {
        try {
            const response = await api.list();
            const clubs = response?.data?.clubs || response?.clubs || [];

            tableBody.innerHTML = '';
            if (clubs && clubs.length > 0) {
                clubs.forEach(club => {
                    const row = `
                        <tr>
                            <td>${club.id}</td>
                            <td>${club.club_name}</td>
                            <td>${club.user_id}</td>
                            <td>${club.is_active == 1 ? 'Sim' : 'Não'}</td>
                            <td>
                                <button class="btn btn-secondary btn-action edit-btn" data-id="${club.id}">Editar</button>
                                <button class="btn btn-primary btn-action delete-btn" data-id="${club.id}">Excluir</button>
                            </td>
                        </tr>
                    `;
                    tableBody.insertAdjacentHTML('beforeend', row);
                });
            } else {
                tableBody.innerHTML = '<tr><td colspan="5">Nenhum clube encontrado.</td></tr>';
            }
        } catch (error) {
            tableBody.innerHTML = '<tr><td colspan="5">Erro ao carregar clubes.</td></tr>';
            toast.show(error.message || 'Falha ao carregar clubes', 'error');
        }
    };

    // --- Form and CRUD Logic ---
    const handleEdit = async (id) => {
        try {
            const response = await api.getById(id);
            const club = response?.data?.club || response?.club;

            editFormTitle.textContent = `Editar Clube: ${club.club_name}`;
            editClubForm.innerHTML = `
                <input type="hidden" name="id" value="${club.id}">
                <div class="form-group">
                    <label for="edit-club_name">Nome do Clube:</label>
                    <input type="text" id="edit-club_name" name="club_name" value="${club.club_name}" required>
                </div>
                <div class="form-group">
                    <label for="edit-description">Descrição:</label>
                    <textarea id="edit-description" name="description">${club.description || ''}</textarea>
                </div>
                <div class="form-group form-group-checkbox">
                    <input type="checkbox" id="edit-is_active" name="is_active" value="1" ${club.is_active == 1 ? 'checked' : ''}>
                    <label for="edit-is_active">Clube Ativo</label>
                </div>
                <button type="submit" class="btn">Salvar Alterações</button>
            `;
            openEditModal();
        } catch (error) {
            toast.show(error.message || 'Não foi possível carregar os dados do clube.', 'error');
        }
    };

    const handleDelete = async (id) => {
        if (!confirm('Tem certeza que deseja excluir este clube?')) return;
        try {
            const response = await api.delete(id);
            toast.show(response?.message || 'Clube excluído com sucesso!', 'success');
            loadClubs();
        } catch (error) {}
    };

    addClubForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const submitBtn = addClubForm.querySelector('button[type="submit"]');
        const formData = new FormData(addClubForm);

        submitBtn.disabled = true;
        submitBtn.textContent = 'Adicionando...';

        try {
            const response = await api.create(formData);
            toast.show(response?.message || 'Clube adicionado com sucesso!', 'success');
            addClubForm.reset();
            loadClubs();
        } catch (error) {} 
        finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Adicionar Clube';
        }
    });

    editClubForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const submitBtn = editClubForm.querySelector('button[type="submit"]');
        const formData = new FormData(editClubForm);
        const isActive = editClubForm.querySelector('#edit-is_active').checked ? '1' : '0';
        formData.set('is_active', isActive);
        const id = formData.get('id');

        submitBtn.disabled = true;
        submitBtn.textContent = 'Salvando...';

        try {
            const response = await api.update(id, formData);
            toast.show(response?.message || 'Clube atualizado com sucesso!', 'success');
            closeEditModal();
            loadClubs();
        } catch (error) {} 
        finally {
            submitBtn.disabled = false;
            submitBtn.textContent = 'Salvar Alterações';
        }
    });

    tableBody.addEventListener('click', (e) => {
        if (e.target.classList.contains('edit-btn')) handleEdit(e.target.dataset.id);
        if (e.target.classList.contains('delete-btn')) handleDelete(e.target.dataset.id);
    });

    loadClubs();
});