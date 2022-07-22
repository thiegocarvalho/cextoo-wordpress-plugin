<?php

class Cextoo_Plugin_API{
    public function create_customer($data): bool
    {
         $password = wp_generate_password( 6, false );
         $user_data = $this->format_wp_user($data, $password);
         if(!email_exists($user_data['user_email'])){
             $user_id =  wp_insert_user($user_data);
             if($user_id){
                 $this->send_notification_wellcome_email($user_data, $user_id);
             }
             return true;
         }
        $this->send_notification_wellcome_email($user_data, $user_id);
        return false;
    }
	
	private function generatePasswordLink($user_id)
	{
		$user = new WP_User( (int) $user_id );
		$reset_key = get_password_reset_key( $user );
		$user_login = $user->user_login;
 
		return network_site_url("wp-login.php?action=rp&key=$reset_key&login=" . rawurlencode($user_login), 'login');

	}

    /**
     * @throws Exception
     */
    private function send_notification_wellcome_email($user_data, $user_id, $template = 'cextoo-base-email-template.php'){
        try{
            $engine = new Cextoo_Plugin_Template(
				WP_PLUGIN_DIR  . '/'. plugin_basename(__DIR__).'/../admin/partials/emails/'
            );

            $render =  $engine->render(
                $template,
                [
                    'password' => $this->generatePasswordLink($user_id),
                    'email' => $user_data['user_email']
                ]
            );
		
            wp_mail(
                $user_data['user_email'],
                'Seja bem-vindo ao Defiverso',
                $render
            );


        }catch (Exception $exception){
            throw $exception;
        }
    }



    private function format_wp_user($data, $password){
        $role = $this->find_or_create_role($data['product_name']);
        return [
            'user_pass' =>  $password,
            'user_login' => $data['user_email'],
            'user_email' => $data['user_email'],
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
        return get_option('cextoo_token') == $token;
    }
    
    public function webhook_handler(WP_REST_Request $request)
    {
		try {
			$data = $request->get_json_params();

			if ( ! $this->validate_token( $data['token'] ) ) {
				throw new Exception('What Day is Today?');
			}



			switch ( $data['event'] ) {
				case 'CREATE_RULE':
					return new WP_REST_Response(
						$this->find_or_create_role( $data['body']['product_name'] )
					);
					break;
				case 'CREATE_CUSTOMER':
					return new WP_REST_Response(
						$this->create_customer( $data['body'] )
					);
					break;
				case 'ADD_CUSTOMER_ROLE':
					return new WP_REST_Response(
						$this->add_customer_role( $data['body'] )
					);
					break;
				case 'REMOVE_CUSTOMER_ROLE':
					return new WP_REST_Response(
						$this->remove_customer_role( $data['body'] )
					);
					break;
			}
		}catch (Exception $exception){
			return new WP_REST_Response(
				$exception->getMessage(), 403
			);
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
