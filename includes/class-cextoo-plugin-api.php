<?php

class Cextoo_Plugin_API{
    public function create_customer($data): bool
    {
         $password = wp_generate_password( 6, false );
         $user_data = $this->format_wp_user($data, $password);
         if(!email_exists($user_data['user_email'])){
             $user_id =  wp_insert_user($user_data);
             if($user_id){
                 $this->send_notification_email($user_data, $password);
             }
             return true;
         }
        return false;
    }

    private function send_notification_email($user_data, $data, $template = 'cextoo-base-email-template'){
        $template_path = plugin_dir_path('/cextoo-wordpress-plugin/admin/partials/emails/'.$template.'.html');
        wp_mail( $user_data['user_email'], 'ACTIVATION SUBJECT', Cextoo_Plugin_Template::view($template_path, $data));
    }



    private function format_wp_user($data, $password){
        $role = $this->find_or_create_role($data['product_name']);
        return [
            'user_pass' =>  $password,
            'user_login' => $data['email'],
            'user_email' => $data['email'],
            'role' => $role
        ];
    }

    private function find_role($sanitize_product_name)
    {
        global $wp_roles;
        $all_roles = $wp_roles->roles;
        $editable_roles = apply_filters('editable_roles', $all_roles);
        return array_key_exists($sanitize_product_name, $editable_roles);
    }

    public function find_or_create_role($product_name)
    {
        $sanitize = sanitize_title($product_name);
        if(!$this->find_role($sanitize)){
            add_role( $sanitize, $product_name, ['read' => true, 'level_0' => true ]);
        }

        return $sanitize;
    }


    public function add_customer_role($data){
        $user = get_user_by( 'email',$data['user_email']);
        if($user){
            $user->add_role( $this->find_or_create_role($data['product_name']));
            return true;
        }
        return false;
    }

    public function remove_customer_role($data){
        $user = get_user_by( 'email',$data['user_email']);
        if($user){
            $user->remove_role( $this->find_or_create_role($data['product_name']));
            return true;
        }
        return false;
    }

    private function validate_token($token){
        return get_option('Cextoo_token') == $token;
    }
    
    public function webhook_handler(WP_REST_Request $request)
    {
        $data = $request->get_json_params();

        if(!$this->validate_token($data['token'])){
            $error = new WP_REST_Response(
                "<img src='https://c.tenor.com/R6safQu3LPQAAAAM/nacho-libre-forbidden.gif' />"
            );
            $error->set_status(403);
            return $error;
        }

        switch($data['event']) {
            case 'CREATE_RULE':
                return new WP_REST_Response(
                    $this->find_or_create_role($data['body'])
                );
                break;
            case 'CREATE_CUSTOMER':
                return new WP_REST_Response(
                    $this->create_customer($data['body'])
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
        }
    }

    public function set_endpoints()
    {
      register_rest_route( 'cextoo/v1', 'webhook',array(
        'methods'  => 'POST',
            'callback' => [$this,'webhook_handler']
     ));

    }
}