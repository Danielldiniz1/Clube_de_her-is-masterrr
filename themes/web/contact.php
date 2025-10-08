<?php
    echo $this->layout("_theme");
?>

<section class="contact-section">
    <div class="container">
        <h1 class="section-title">ENTRE EM CONTATO</h1>
        
        <div class="contact-grid">
            <!-- Informações de Contato -->
            <div class="contact-info">
                <div class="info-card">
                    <div class="info-icon">📍</div>
                    <h3>Nosso Endereço</h3>
                    <p>Av. dos Quadrinhos, 1234</p>
                    <p>Bairro Geek - Pelotas/RS</p>
                    <p>CEP: 96010-000</p>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">📞</div>
                    <h3>Telefones</h3>
                    <p>+55 (53) 3222-1234</p>
                    <p>+55 (53) 99999-8888</p>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">✉️</div>
                    <h3>E-mail</h3>
                    <p>contato@clubedeherois.com.br</p>
                    <p>suporte@clubedeherois.com.br</p>
                </div>
                
                <div class="info-card">
                    <div class="info-icon">🌐</div>
                    <h3>Redes Sociais</h3>
                    <div class="social-links">
                        <a href="#" class="social-link">Instagram</a>
                        <a href="#" class="social-link">Facebook</a>
                        <a href="#" class="social-link">Twitter</a>
                    </div>
                </div>
            </div>
            
            <!-- Formulário de Contato -->
            <div class="contact-form">
                <h2>Envie sua Mensagem</h2>
                <form id="messageForm">
                    <div class="form-group">
                        <label for="name">Nome Completo</label>
                        <input type="text" id="name" name="name" required autocomplete="name">
                    </div>
                    
                    <div class="form-group">
                        <label for="email">E-mail</label>
                        <input type="email" id="email" name="email" required autocomplete="email">
                    </div>
                    
                    <div class="form-group">
                        <label for="subject">Assunto</label>
                        <input type="text" id="subject" name="subject" required autocomplete="off">
                    </div>
                    
                    <div class="form-group">
                        <label for="message">Mensagem</label>
                        <textarea id="message" name="message" rows="5" required></textarea>
                    </div>
                    
                    <button type="submit" class="btn-submit">Enviar Mensagem</button>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- Mapa -->
<section class="map-section">
    <div class="container">
        <h2 class="section-title">NOSSA LOCALIZAÇÃO</h2>
        <div class="map-container">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3387.3!2d-52.3425!3d-31.7754!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMzHCsDQ2JzMxLjQiUyA1MsKwMjAnMzMuMCJX!5e0!3m2!1spt-BR!2sbr!4v1234567890!5m2!1spt-BR!2sbr" 
                width="100%" 
                height="450" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy">
            </iframe>
        </div>
    </div>
</section>
