<?php
//TODO criar folha CSS e incluir adquadamente
?>

<style>
.cextoo-cards {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(200px, 4fr));
    grid-auto-rows: auto;
    object-fit: cover;
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

.cextoo-button.bg-success {
    background-image: linear-gradient(to right,
            #009e28,
            #0cc12f,
            #06b02b,
            #009e28);
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
        $countdown = date_diff(date_create($subscription->renew_at), date_create());
        if ($subscription->status && (int)$countdown->days == 1) {
            $status = 'border-left-color:  #a40a0a;';
            $button = [
                'text' => "Renovar",
                'class' => 'cextoo-button bg-success',
                'action' => 'https://checkout.defiverso.com/subscription/' . $subscription->external_id
            ];
        } else {
            $status = 'border-left-color: #90EE90;';
            $button = [
                'text' => "Cancelar",
                'class' => 'cextoo-button bg-cancel',
                'action' => "mailto:suporte@defiverso.com?subject=Cancelamento de Assinatura #"
                    . $subscription->external_id . "&body=Solicitação de cancelamento, "
                    . $subscription->product_name . " - adiqurido em: " . date('d/m/Y', strtotime($subscription->start_at))
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
                <?php endif; ?>
            </p>

            <p class="cextoo-card-text">
                <?php if ($subscription->expires_at != "0000-00-00 00:00:00") : ?>
                <strong>cancelado: </strong>
                <?php echo date("d/m/Y", strtotime($subscription->expires_at)) ?>
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