<?php ?>
<style>
.cextoo-cards {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    grid-auto-rows: auto;
    grid-gap: .5rem;
}

.cextoo-card {
    background: rgba(255, 255, 255, 0.45);
    border-radius: 16px;
    box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    backdrop-filter: blur(5px);
    -webkit-backdrop-filter: blur(5px);
    border: 1px solid rgba(255, 255, 255, 0.3);
    padding: .5rem;
    margin: .5rem;
}

.cextoo-card-title {
    font-size: .7rem;
    font-weight: bold;
    text-align: center;
}

.cextoo-card-text {
    text-align: center;
    font-size: .6rem;
}

.cextoo-button {
    width: 90px;
    font-size: 12px;
    font-weight: 400;
    color: #fff;
    cursor: pointer;
    margin: 10px;
    height: 30px;
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
        if ($subscription->status) {
            $status = '🟢';
            $button = [
                'text' => "cancelar",
                'class' => 'cextoo-button bg-cancel',
                #TODO mudar para configuração do plugins para setar URL da action
                'action' => 'https://checkout.defiverso.com/subsciption/' . $subscription->external_id
            ];
        } else {
            $status = '🔴';
            $button = [
                'text' => "<s>cancelar<s>",
                'class' => 'cextoo-button bg-desactivate',
                'action' => false
            ];
        }
    ?>
    <div class="cextoo-card">
        <div class="cextoo-card-body">
            <h5 class="cextoo-card-title">
                <span><?php echo $status ?></span>
                <span><?php echo $subscription->product_name ?>
            </h5>
            <p class="cextoo-card-text"><strong>inicio: </strong>
                <?php echo date("d/m/Y", strtotime($subscription->start_at)) ?></p>
            <p class="cextoo-card-text">
                <strong>renovação: </strong><?php echo date("d/m/Y", strtotime($subscription->renew_at)) ?>
            </p>
            <p class="cextoo-card-text"><strong>cancelamento: </strong>
                <?php echo date("d/m/Y", strtotime($subscription->expires_at)) ?>
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