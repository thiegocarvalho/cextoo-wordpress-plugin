<?php
//TODO criar folha CSS e incluir adquadamente
?>

<style>
.cextoo-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-auto-rows: auto;
    grid-gap: .5rem;
}

.cextoo-card {
    background-color: #F5F5F5;
    border-radius: 16px;
    border: .5px solid #E0E0E0;
    border-top-left-radius: 0;
    border-bottom-left-radius: 0;
    border-left: 12px solid #00a0d2;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    padding: .8rem;
    margin: .5rem;
}

.cextoo-card-title {
    color: #708090;
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    font-size: .9rem;
    font-weight: bold;
}

.cextoo-card-text {
    color: #708090;
    font-family: Verdana, Geneva, Tahoma, sans-serif;
    font-size: .7rem;
}

.cextoo-button {
    width: 90px;
    height: 32px;
    font-weight: 400;
    color: #fff;
    cursor: pointer;
    text-align: center;
    border: none;
    background-size: 300% 100%;
    border-radius: 50px;
    moz-transition: all .4s ease-in-out;
    -o-transition: all .4s ease-in-out;
    -webkit-transition: all .4s ease-in-out;
    transition: all .4s ease-in-out;
}

.cextoo-button.bg-cancel {
    background-image: linear-gradient(to right,
            #501f2c,
            #d0396a,
            #a33155,
            #792840);
}

.cextoo-button.bg-desactivate {
    background-image: linear-gradient(to right,
            #EEEEEE,
            #CCCCCC,
            #999999,
            #666666);
}

.cextoo-button:focus {
    outline: none;
}

.cextoo-button:hover {
    background-position: 100% 0;
    moz-transition: all .4s ease-in-out;
    -o-transition: all .4s ease-in-out;
    -webkit-transition: all .4s ease-in-out;
    transition: all .4s ease-in-out;
}
</style>
<div class="cextoo-cards">
    <?php foreach ($subscriptions as $subscription) :
        if ($subscription->status && !empty($subscription->renew_at)) {
            $status = 'border-left-color: #90EE90;';
            $button = [
                'text' => "cancelar",
                'class' => 'cextoo-button bg-cancel',
                #TODO mudar para configuração do plugins para setar URL da action
                'action' => 'https://checkout.defiverso.com/subscription/' . $subscription->external_id
            ];
        } else {
            $status = 'border-left-color: #999999;';
            $button = [
                'text' => "<s>cancelar</s>",
                'class' => 'cextoo-button bg-desactivate',
                'action' => false
            ];
        }
    ?>
    <div class="cextoo-card" style="<?php echo $status; ?>">
        <div class="cextoo-card-body">
            <p class="cextoo-card-title">
                <?php echo $subscription->product_name ?>
            </p>
            <p class="cextoo-card-text"><strong>adquirido: </strong>
                <?php echo date("d/m/Y", strtotime($subscription->start_at)) ?>
            </p>


            <p class="cextoo-card-text">
                <?php if ($subscription->renew_at) : ?>
                <strong>renovação: </strong>
                <?php echo date("d/m/Y", strtotime($subscription->renew_at)) ?>
                <?php else : ?>
                --
                <?php endif; ?>
            </p>

            <p class="cextoo-card-text">
                <?php if ($subscription->expires_at) : ?>
                <strong>cancelado: </strong>
                <?php echo date("d/m/Y", strtotime($subscription->expires_at)) ?>
                <?php else : ?>
                --
                <?php endif; ?>
            </p>


            <p class="cextoo-card-text">
                <?php if ($button['action']) : ?>
                <a href="<?php echo $button['action']; ?>" target="_blank">
                    <button class="<?php echo $button['class']; ?>">
                        <?php echo $button['text']; ?>
                    </button>
                </a>
                <?php else : ?>
                <button class="<?php echo $button['class']; ?>">
                    <?php echo $button['text']; ?>
                </button>
                <?php endif; ?>
            </p>
        </div>
    </div>
    <?php endforeach; ?>
</div>