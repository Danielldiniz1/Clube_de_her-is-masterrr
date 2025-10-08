<?php
    echo $this->layout("_theme");
?>
<?php
  $this->start("specific-script");
?>
<script type="module" src="<?= url("assets/js/app/scripts-get-profile.js"); ?>" async></script>
<script type="module" src="<?= url("assets/js/app/scripts-edit-profile.js"); ?>" async></script>
<script type="module" src="<?= url("assets/js/app/scripts-upload-photo.js"); ?>" async></script>
<?php
    $this->end();
?>
<?php
  $this->start("post-scripts");
?>
<script type="module" src="<?= url("assets/js/app/scripts-edit-profile.js"); ?>"></script>
<?php
    $this->end();
?>
<!-- Container para as notificações (toasts) -->
<div id="toast-container"></div>

<div class="container">
    <h2>Meu Perfil</h2>
    
    <!-- Área de exibição de informações estáticas -->
    <div class="profile-info-card">
        <div class="profile-pic-area">
           <img src="#" alt="Foto do Perfil" id="profile-pic" class="profile-pic">

        <form id="formPhotoUpload" class="photo-upload-form">
            <input type="file" id="photo" name="photo" accept="image/*" style="display: none;">

            <label for="photo" class="btn btn-secondary">Escolher Foto</label>


            <button type="submit" class="btn">Alterar Foto</button>
        </form>
        
    </div>

        <div class="profile-info-item">
            <span class="info-label">Nome Completo:</span>
            <p id="static-name">Carregando...</p>
        </div>
        <div class="profile-info-item">
            <span class="info-label">E-mail:</span>
            <p id="static-email">Carregando...</p>
        </div>
        <div class="profile-info-item">
            <span class="info-label">Tipo de Conta:</span>
            <p id="static-idType">Carregando...</p>
        </div>
        <div class="profile-info-item">
            <span class="info-label">Assinatura:</span>
            <p id="static-assinatura">Nenhuma assinatura ativa</p>
        </div>
        
        <button id="openEditProfileModal" class="btn" style="margin-top: 20px;">Editar Perfil</button>
    </div>

</div>