<?php
?>


<div class="card" style="width: 100%; display: block; margin: auto; margin-top: 50px;">

    <div class="card-body">

        <h2 class="card-title"><?php esc_html_e( 'API Config', 'Cextoo' ); ?></h2>
        <hr>
        <p class="card-title"><?php esc_html_e( 'Cextoo Webhook Url:', 'Cextoo' ); ?></p>
        <input type="text" value="<?php echo get_rest_url( null , "cextoo/v1/webhook")?>" id="weebhook_url" readonly style="
    width: 100%;
"><br>
        <span onclick="CopyWebHookURL()" style="color:#fff; display:inline-block;background-color: #098d34; margin-top:10px;padding: 7px;border-radius: 7px;cursor: pointer;font-weight: 700;">Copy</span>
        <p><?php esc_html_e( 'Cextoo Token:', 'Cextoo' ); ?></p>
        <form method="post" action="options.php">
            <?php settings_fields( 'cextoo' );?>
            <input type="text" name="Cextoo_token" value="<?php echo get_option('Cextoo_token')?>" id="Cextoo_token" readonly style="
    width: 100%;
"><br>
            <span onclick="CopyToken()" style="color:#fff;display: inline-block;background-color: #098d34; margin-top:10px;padding: 7px;border-radius: 7px;cursor: pointer;font-weight: 700;">Copy</span>
            <button style="margin-left: 15px;background-color: #000000;padding: 7px;border-radius: 7px;cursor: pointer;font-weight: 700;color: #fff;">Gerentare new token</button>
        </form>
        <br>
        <br>
        <br>

        <div style="display: block; margin: auto;">
        <span class="dashicons dashicons-sos"></span>
        <a href="#" class="btn btn-primary">Support</a>
        </div>
        <hr>
        <img class="card-img-top" src="<?php echo  plugins_url( 'cextoo-wordpress-plugin/admin/images/cextoo-logo.png' )?>" alt="Cextoo" style="
    padding-top: 40px;
    display: block;
    margin: auto;
">

    </div>
</div>




    <script>
        function CopyWebHookURL() {
            var copyText = document.getElementById('weebhook_url')
            copyText.select();
            document.execCommand('copy')
            alert('Webhook Url Copied!')
        }

        function CopyToken() {
            var copyText = document.getElementById('Cextoo_token')
            copyText.select();
            document.execCommand('copy')
            alert('Token Copied!')
        }
    </script>