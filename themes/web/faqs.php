<?php
    echo $this->layout("_theme");
?>
<section class="faqs-section">
    <div class="container">
        <header class="faqs-head">
            <h1>Perguntas Frequentes</h1>
            <p>Encontre respostas para as dúvidas mais comuns sobre o Clube de Heróis</p>
        </header>

        <div class="faqs-container">
            <div id="faqs-list">
                <!-- Perguntas e respostas pré-definidas -->
                <div class="faq">
                    <div class="faq-question">Como faço para me tornar um membro do Clube de Heróis?</div>
                    <div class="faq-answer">
                        <p>Para se tornar um membro do Clube de Heróis, basta clicar no botão "Cadastre-se" no topo do site, preencher o formulário com seus dados pessoais e escolher um plano de assinatura. Após a confirmação do pagamento, você receberá um e-mail com as instruções de acesso à sua conta.</p>
                    </div>
                </div>

                <div class="faq">
                    <div class="faq-question">Quais são os benefícios de ser um membro?</div>
                    <div class="faq-answer">
                        <p>Como membro do Clube de Heróis, você terá acesso a:</p>
                        <ul>
                            <li>Quadrinhos exclusivos mensais</li>
                            <li>Descontos em eventos e convenções</li>
                            <li>Acesso antecipado a lançamentos</li>
                            <li>Conteúdo digital exclusivo</li>
                            <li>Participação em sorteios e promoções</li>
                            <li>Comunidade exclusiva de fãs</li>
                        </ul>
                    </div>
                </div>

                <div class="faq">
                    <div class="faq-question">Posso cancelar minha assinatura a qualquer momento?</div>
                    <div class="faq-answer">
                        <p>Sim, você pode cancelar sua assinatura a qualquer momento através da sua área de membro. O cancelamento será efetivado ao final do período já pago, sem cobranças adicionais. Você continuará tendo acesso aos benefícios até o final do período contratado.</p>
                    </div>
                </div>

                <div class="faq">
                    <div class="faq-question">Como funciona a entrega dos quadrinhos físicos?</div>
                    <div class="faq-answer">
                        <p>Os quadrinhos físicos são enviados mensalmente para o endereço cadastrado em sua conta. O prazo de entrega varia de acordo com a sua localização, mas geralmente leva de 5 a 10 dias úteis após o processamento. Você receberá um código de rastreamento por e-mail assim que seu pacote for despachado.</p>
                    </div>
                </div>

                <div class="faq">
                    <div class="faq-question">Vocês enviam para todo o Brasil?</div>
                    <div class="faq-answer">
                        <p>Sim, realizamos entregas para todos os estados brasileiros. Para algumas localidades mais remotas, pode haver um prazo adicional de entrega e/ou uma taxa extra de frete, que será informada durante o processo de assinatura.</p>
                    </div>
                </div>

                <div class="faq">
                    <div class="faq-question">Quais formas de pagamento são aceitas?</div>
                    <div class="faq-answer">
                        <p>Aceitamos as seguintes formas de pagamento:</p>
                        <ul>
                            <li>Cartões de crédito (Visa, Mastercard, American Express, Elo)</li>
                            <li>Boleto bancário</li>
                            <li>PIX</li>
                            <li>PayPal</li>
                        </ul>
                        <p>Para assinaturas, recomendamos o uso de cartão de crédito para garantir a continuidade do serviço.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="faqs-not-found">
            <p>Não encontrou o que procurava?</p>
            <a href="<?= url("contato"); ?>" class="btn btn-primary">Entre em contato</a>
        </div>
    </div>
</section>
<?php
    $this->start("specific-script");
?>
<script type="module" src="<?= url("assets/js/web/faqs.js"); ?>"></script>
<?php
    $this->end();
?>
