<?php
    // Carrega o layout base do tema
    echo $this->layout("_theme");
?>

<style>
    /* Estilos para a seção de planos */
    .plans-section {
        background-color: #1a0d0d;
        padding: 60px 0;
        font-family: 'Poppins', sans-serif; /* Usando uma fonte mais moderna, adicione ao seu <head> se não tiver */
    }

    .plans-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 15px;
    }

    .section-title {
        text-align: center;
        font-size: 2.5rem;
        font-weight: 600;
        color: white;
        margin-bottom: 50px;
    }

    .plans-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
        gap: 30px;
        align-items: center;
    }

    .plan-card {
        background-color: #ffffff;
        border-radius: 15px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.07);
        padding: 0;
        text-align: center;
        transition: all 0.3s ease;
        overflow: hidden;
    }

    .plan-card.highlight {
        transform: translateY(-20px);
        box-shadow: 0 20px 40px rgba(0, 0, 0, 0.15);
    }

    .plan-header {
        color: #ffffff;
        padding: 20px;
    }
    
    .plan-card.basic .plan-header { background-color: #333; }
    .plan-card.pro .plan-header { background-color: #333; }
    .plan-card.ultimate .plan-header { background-color: #333; }


    .plan-title {
        font-size: 1.5rem;
        font-weight: 600;
        margin: 0;
    }

    .plan-body {
        padding: 30px;
    }
    
    .plan-price {
        font-size: 3rem;
        font-weight: 700;
        color: #333;
        margin-bottom: 10px;
    }

    .plan-price .plan-period {
        font-size: 1rem;
        font-weight: 400;
        color: #777;
    }

    .plan-features {
        list-style: none;
        padding: 0;
        margin: 30px 0;
        text-align: left;
    }

    .plan-features li {
        display: flex;
        align-items: center;
        margin-bottom: 15px;
        color: #555;
    }

    .plan-features li svg {
        width: 20px;
        height: 20px;
        margin-right: 10px;
        color: #28a745; /* Verde para o check */
    }

    .plan-footer {
        padding: 0 30px 30px;
        text-align: center; /* <<< CORREÇÃO APLICADA AQUI */
    }
    
    .btn-choose {
        display: block;
        width: 100%;
        padding: 15px;
        border: none;
        border-radius: 8px;
        color: #ffffff;
        font-size: 1rem;
        font-weight: 600;
        text-decoration: none;
        transition: background-color 0.3s ease;
    }

    .plan-card.basic .btn-choose { background-color: red; }
    .plan-card.pro .btn-choose { background-color: red; }
    .plan-card.ultimate .btn-choose { background-color: red; }

 
</style>

<section class="plans-section">
    <div class="plans-container">
      <h2 class="section-title">NOSSOS PLANOS</h2>
      <div class="plans-grid">
        
        <div class="plan-card basic">
          <div class="plan-header">
            <h3 class="plan-title">Plano Básico</h3>
          </div>
          <div class="plan-body">
            <div class="plan-price">R$10<span class="plan-period">/mês</span></div>
            <ul class="plan-features">
              <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>Até 100 assinantes</li>
              <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>Gateway de pagamento</li>
              <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>Catálogo de produtos</li>
              <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>Suporte por e-mail</li>
            </ul>
          </div>
          <div class="plan-footer">
            <a href="#" class="btn-choose">ESCOLHER</a>
          </div>
        </div>

        <div class="plan-card pro highlight">
          <div class="plan-header">
            <h3 class="plan-title">Plano Pro</h3>
          </div>
          <div class="plan-body">
            <div class="plan-price">R$20<span class="plan-period">/mês</span></div>
            <ul class="plan-features">
              <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>Até 500 assinantes</li>
              <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>Múltiplos gateways</li>
              <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>Cupons promocionais</li>
              <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>Suporte prioritário</li>
            </ul>
          </div>
          <div class="plan-footer">
            <a href="#" class="btn-choose">ESCOLHER</a>
          </div>
        </div>

        <div class="plan-card ultimate">
          <div class="plan-header">
            <h3 class="plan-title">Plano Ultimate</h3>
          </div>
          <div class="plan-body">
            <div class="plan-price">R$30<span class="plan-period">/mês</span></div>
            <ul class="plan-features">
              <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>Assinantes ilimitados</li>
              <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>API completa</li>
              <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>Relatórios avançados</li>
              <li><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.5 12.75l6 6 9-13.5" /></svg>Suporte 24/7</li>
            </ul>
          </div>
          <div class="plan-footer">
            <a href="#" class="btn-choose">ESCOLHER</a>
          </div>
        </div>
        
      </div>
    </div>
</section>