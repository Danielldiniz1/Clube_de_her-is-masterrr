<?php
echo $this->layout("_theme");
?>
<section class="subscription-section" style="padding:2rem 0; min-height:70vh;">
  <div class="container" style="max-width:900px; margin:0 auto;">
    <h1 class="section-title" style="text-align:center; margin-bottom:1.5rem;">Assinatura</h1>
    <p style="text-align:center; opacity:0.85; margin-bottom:2rem;">
      Assine e ganhe 10% de desconto em todos os produtos.
    </p>

    <?php $isSubscriber = $isSubscriber ?? false; $justSubscribed = $justSubscribed ?? false; ?>
    <div class="plan-card" style="background:#1a0d0d; border:1px solid #333; border-radius:12px; padding:1.5rem; box-shadow:0 10px 25px rgba(0,0,0,0.3); max-width:520px; margin:0 auto;">
      <div class="plan-header" style="text-align:center; margin-bottom:1rem;">
        <h3 class="plan-title" style="font-family:'Bangers', cursive; font-size:1.8rem; color:#ff3333; margin:0;">Plano Único</h3>
        <div class="plan-price" style="font-family:'Bangers', cursive; font-size:2rem; color:#ffd700; margin-top:0.5rem;">
          R$19,90 <span class="plan-period" style="font-size:1rem; color:#a0a0a0;">/mês</span>
        </div>
      </div>
      <ul class="plan-features" style="list-style:none; padding:0; margin:1rem 0;">
        <li style="margin:0.4rem 0; color:#a0a0a0;">10% de desconto em todos os produtos</li>
        <li style="margin:0.4rem 0; color:#a0a0a0;">Acesso a ofertas exclusivas</li>
        <li style="margin:0.4rem 0; color:#a0a0a0;">Cancelamento fácil</li>
      </ul>
      <div class="plan-footer" style="text-align:center; margin-top:1rem;">
        <?php if ($isSubscriber || $justSubscribed): ?>
          <button class="btn" style="padding:0.8rem 1.2rem; border-radius:12px; background:#00e676; color:#000; border:none; font-family:'Bangers', cursive;">
            Sucesso! Você virou assinante
          </button>
          <p style="margin-top:0.8rem; color:#a0a0a0;">Um e-mail de confirmação foi enviado.</p>
        <?php else: ?>
          <form action="<?= url('app/assinatura/ativar'); ?>" method="post">
            <button type="submit" class="btn" style="padding:0.8rem 1.2rem; border-radius:12px; background:#ff3333; color:#000; border:none; font-family:'Bangers', cursive;">
              ASSINAR
            </button>
          </form>
        <?php endif; ?>
      </div>
    </div>
  </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function(){
  var just = <?php echo isset($justSubscribed) && $justSubscribed ? 'true' : 'false'; ?>;
  if (just && typeof window.showToast === 'function') {
    window.showToast('Assinatura ativada com sucesso!', 'success');
  }
});
</script>
