<?php

class Cextoo_API
{
    /**
     * @throws Exception
     */
    public function create_customer($data): bool
    {
        $password = wp_generate_password(6, false);
        $user_data = $this->format_wp_user($data, $password);
        $user_id = email_exists($user_data['user_email']);
        if (!$user_id) {
            $user_id =  wp_insert_user($user_data);
            $this->send_notification_wellcome_email($user_data, $user_id);
        }
        if ($data['status'] == 1) {
            $this->add_customer_role($data);
        }
        return $user_id;
    }

    public function remove_customer($data): bool
    {
        if (!email_exists($data['user_email'])) {
            $user =    get_user_by('email', $data['user_email']);
            wp_delete_user($user->ID);
            return true;
        }
        return false;
    }

    /**
     * @param $user_id
     *
     * @return string
     */
    private function generate_password_link($user_id)
    {
        $user = new WP_User((int) $user_id);
        $reset_key = get_password_reset_key($user);
        $user_login = $user->user_login;
        return wp_login_url(get_permalink()) . "?action=rp&key=$reset_key&login=" . rawurlencode($user_login);
    }

    /**
     * @throws Exception
     */
    private function send_notification_wellcome_email($user_data, $user_id, $template = 'cextoo-base-email-template.php')
    {
        try {
            $engine = new Cextoo_Template(
                WP_PLUGIN_DIR  . '/' . plugin_basename(__DIR__) . '/../public/partials/emails/'
            );

            $render =  $engine->render(
                $template,
                [
                    'link' => $this->generate_password_link($user_id),
                    'email' => $user_data['user_email']
                ]
            );

            wp_mail(
                $user_data['user_email'],
                'Seja bem-vindo ao Defiverso',
                $render
            );
        } catch (Exception $exception) {
            throw $exception;
        }
    }

    private function format_wp_user($data, $password)
    {
        return [
            'user_pass' =>  $password,
            'user_login' => $data['user_email'],
            'user_email' => $data['user_email']
        ];
    }

    public function find_or_create_role($rule_name, $rule_slug)
    {
        $rule_slug = sanitize_title($rule_slug);
        add_role($rule_slug, $rule_name, ['read' => true, 'level_0' => true]);
        return $rule_slug;
    }


    public function add_customer_role($data)
    {
        $user = get_user_by('email', $data['user_email']);
        if ($user) {
            $user->add_role($this->find_or_create_role($data['rule_name'], $data['rule_slug']));
            return true;
        }
        return false;
    }

    public function remove_customer_role($data)
    {
        $user = get_user_by('email', $data['user_email']);
        if ($user) {
            $user->remove_role($this->find_or_create_role($data['rule_name'], $data['rule_slug']));
            return true;
        }
        return false;
    }

    private function validate_token($token)
    {
        return get_option('cextoo_token') == $token;
    }

    private function subscription_handler($data)
    {
        $this->create_customer($data);
        $database = new Cextoo_Database();
        if ($database->get($data['external_id'])) {
            $database->set($data);
            $database->update();
        } else {
            $database->set($data);
            $database->create();
        }

        if ($data['status'] == 0 && !$database->haveOtherActiveSubscription()) {
            $this->remove_customer_role($data);
        }
    }

    public function webhook_handler(WP_REST_Request $request)
    {
        try {
            $data = $request->get_json_params();

            if (!$this->validate_token($data['token'])) {
                throw new Exception('What Day is Today?');
            }

            switch ($data['event']) {
                case 'CREATE_RULE':
                    return new WP_REST_Response(
                        $this->find_or_create_role($data['body']['rule_name'], $data['body']['rule_slug'])
                    );
                    break;
                case 'UPDATE_RULE':
                    return new WP_REST_Response(
                        $this->find_or_create_role($data['body']['rule_name'], $data['body']['rule_slug'])
                    );
                    break;
                case 'CREATE_CUSTOMER':
                    return new WP_REST_Response(
                        $this->create_customer($data['body'])
                    );
                    break;
                case 'REMOVE_CUSTOMER':
                    return new WP_REST_Response(
                        $this->remove_customer($data['body'])
                    );
                    break;
                case 'ADD_CUSTOMER_ROLE':
                    return new WP_REST_Response(
                        $this->add_customer_role($data['body'])
                    );
                    break;
                case 'REMOVE_CUSTOMER_ROLE':
                    return new WP_REST_Response(
                        $this->remove_customer_role($data['body'])
                    );
                    break;
                case 'SUBSCRIPTION':
                    return new WP_REST_Response(
                        $this->subscription_handler($data['body'])
                    );
                    break;
            }
        } catch (Exception $exception) {
            return new WP_REST_Response(
                $exception->getMessage(),
                403
            );
        }
    }

    public function set_endpoints()
    {
        register_rest_route('cextoo/v1', 'webhook', array(
            'methods'  => 'POST',
            'callback' => [$this, 'webhook_handler']
        ));
    }
}