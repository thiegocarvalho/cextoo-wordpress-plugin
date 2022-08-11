<?php

class Cextoo_Shortcode
{

    public function register_shortcodes()
    {
        add_shortcode('my-cextoo-subscriptions', [$this, 'my_cextoo_subscriptions_table']);
    }

    public function my_cextoo_subscriptions_table()
    {
        $database = new Cextoo_Database();
        $subscriptions = $database->get_by_user_id(get_current_user_id());

        $engine = new Cextoo_Template(
            WP_PLUGIN_DIR  . '/' . plugin_basename(__DIR__) . '/../public/partials/'
        );

        return $engine->render(
            'cextoo-subscriptions-table.php',
            [
                'subscriptions' => $subscriptions
            ]
        );
    }
}