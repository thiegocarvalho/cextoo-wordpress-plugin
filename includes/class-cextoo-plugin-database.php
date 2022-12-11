<?php

class Cextoo_Database
{
    protected ?int $ID = null;
    protected ?string $external_id = null;
    protected ?string $product_name = null;
    public int $status = 0;
    public ?string $renew_at = null;
    protected ?string $start_at = null;
    public ?string $expires_at = null;
    protected ?int $user_id = null;
    protected ?string $rule_name = null;
    protected ?string $rule_slug = null;
    protected ?string $created_at = null;
    protected ?string $updated_at = null;


    public function __construct()
    {
    }

    private function camelize($input, $separator = '_')
    {
        return str_replace($separator, '', ucwords($input, $separator));
    }

    public function set($data)
    {
        foreach ($data as $key => $value) {
            $function = 'set' . $this->camelize($key);
            if (method_exists(__CLASS__, $function)) {
                $this->$function($value);
            }
        }

        return $this;
    }

    public function get_by_user_id($user_id)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'cextoo';
        $sql = "SELECT * FROM `{$table_name}` WHERE user_id = {$user_id}";
        return $wpdb->get_results($sql);
    }

    public function get($external_id)
    {
        global $wpdb;
        $database_result = $wpdb->get_row(
            "SELECT * FROM `{$wpdb->base_prefix}cextoo` WHERE external_id = {$external_id}"
        );
        if ($database_result) {
            $this->set($database_result);
            return true;
        }
        return false;
    }

    public function update(): void
    {
        if (!$this->getID()) {
            throw new Exception("Dear friend, I can't do this...");
        } else {
            global $wpdb;
            $this->updateTimestamp();
            $wpdb->update(
                $wpdb->base_prefix . 'cextoo',
                [
                    'status' => $this->getStatus(),
                    'renew_at' => $this->getRenewAt(),
                    'expires_at' => $this->getExpiresAt(),
                    'updated_at' => $this->getUpdatedAt()
                ],
                [
                    'ID' => $this->getID()
                ]
            );
        }
    }

    public function create()
    {
        if ($this->getID()) {
            throw new Exception("Dear friend, I can't do this...");
        } else {
            global $wpdb;
            $this->updateTimestamp();

            $wpdb->insert($wpdb->base_prefix . 'cextoo', [
                'external_id' => $this->getExternalId(),
                'product_name' => $this->getProductName(),
                'status' => $this->getStatus(),
                'renew_at' => $this->getRenewAt(),
                'start_at' => $this->getStartAt(),
                'expires_at' => $this->getExpiresAt(),
                'user_id' => $this->getUserID(),
                'rule_name' => $this->getRuleName(),
                'rule_slug' => $this->getRuleSlug(),
                'created_at' => $this->getCreatedAt(),
                'updated_at' => $this->getUpdatedAt()
            ]);
        }
    }

    public function haveOtherActiveSubscription()
    {
        global $wpdb;

        $sql = "SELECT * FROM `{$wpdb->base_prefix}cextoo`
                WHERE user_id = {$this->getUserId()} 
                AND external_id != {$this->getExternalId()} 
                AND rule_slug = '{$this->getRuleSlug()}'
                AND status = 1";

        $database_result = $wpdb->get_results($sql);
        if ($database_result) {
            return true;
        }
        return false;
    }

    /**
     * @param int $days
     *
     * @return void
     * @throws Exception
     */
    public function renewSubscriptions(int $days): void
    {
        global $wpdb;

        $sql = "SELECT * FROM `{$wpdb->base_prefix}cextoo` WHERE renew_at IS NOT NULL AND renew_at = DATE_ADD(CURDATE(), INTERVAL {$days} DAY) AND STATUS = 1";
        $database_result = $wpdb->get_results($sql);
        if ($database_result) {
            foreach ($database_result as $subscription) {

                $subscriptionObject = $this->set((array) $subscription);

                if ($subscriptionObject->haveOtherActiveSubscription()) {
                    continue;
                }

                if ($days == 1) {
                    $template = 'cextoo-renew-email-1-day.php';
                    $subject = 'Sua Renovação ' . $subscriptionObject->getRuleName() . ' precisa ser amanhã';
                }

                if ($days == 0) {
                    $template = 'cextoo-renew-email-last-day.php';
                    $subject = 'Hoje é o último dia para renovar sua Assinatura' . $subscriptionObject->getRuleName();
                }

                if (isset($template) && isset($subject)) {
                    $engine = new Cextoo_Template(
                        WP_PLUGIN_DIR  . '/' . plugin_basename(__DIR__) . '/../public/partials/emails/'
                    );

                    $render =  $engine->render($template);

                    $user = get_user_by('id', $subscriptionObject->getUserId());

                    wp_mail(
                        $user->get('user_email'),
                        $subject,
                        $render
                    );
                }
            }
        }
    }

    public function desactiveExpiredSubscriptions()
    {
        global $wpdb;

        $database_result = $wpdb->get_results(
            "SELECT * FROM `{$wpdb->base_prefix}cextoo` WHERE renew_at IS NOT NULL AND renew_at < DATE_SUB(CURDATE(), INTERVAL 1 DAY) AND STATUS = 1"
        );

        if ($database_result) {
            foreach ($database_result as $subscription) {
                $subscriptionObject = $this->set((array)$subscription);
                $subscriptionObject->setExpiresAt(date('d-m-y h:i:s'));
                $subscriptionObject->setStatus(0);
                $subscriptionObject->update();

                if (!$subscriptionObject->haveOtherActiveSubscription()) {
                    $user = get_user_by('id', $subscriptionObject->getUserId());
                    if ($user) {
                        $user->remove_role($subscriptionObject->getRuleSlug());
                    }
                }
                $subscriptionObject->unsetAllAtributes();
            }
        }
    }

    private function unsetAllAtributes()
    {
        $this->ID = null;
        $this->external_id = null;
        $this->product_name = null;
        $this->status = 0;
        $this->renew_at = null;
        $this->start_at = null;
        $this->expires_at = null;
        $this->user_id = null;
        $this->rule_name = null;
        $this->rule_slug = null;
        $this->created_at = null;
        $this->updated_at = null;
    }

    private function updateTimestamp()
    {
        $this->updated_at = date("Y-m-d H:i:s");
        if (!$this->getCreatedAt()) {
            $this->created_at = $this->updated_at;
        }
    }

    /**
     * @return int|null
     */
    public function getID(): ?int
    {
        return $this->ID;
    }

    /**
     * @param int $ID
     */
    public function setID(int $ID): void
    {
        $this->ID = $ID;
    }

    /**
     * @return string|null
     */
    public function getExternalId(): ?string
    {
        return $this->external_id;
    }

    /**
     * @param string $external_id
     */
    public function setExternalId(string $external_id): void
    {
        $this->external_id = $external_id;
    }

    /**
     * @return string|null
     */
    public function getRuleName(): ?string
    {
        return $this->rule_name;
    }

    /**
     * @param string $rule_name
     */
    public function setRuleName(string $rule_name): void
    {
        $this->rule_name = $rule_name;
    }

    /**
     * @return string|null
     */
    public function getRuleSlug(): ?string
    {
        return $this->rule_slug;
    }

    /**
     * @param string $rule_slug
     */
    public function setRuleSlug(string $rule_slug): void
    {
        $this->rule_slug = $rule_slug;
    }

    /**
     * @return string|null
     */
    public function getProductName(): ?string
    {
        return $this->product_name;
    }

    /**
     * @param string $product_name
     */
    public function setProductName(string $product_name): void
    {
        $this->product_name = $product_name;
    }

    /**
     * @return int
     */
    public function getStatus(): int
    {
        return $this->status;
    }

    /**
     * @param int $status
     */
    public function setStatus(int $status): void
    {
        $this->status = $status;
    }

    /**
     * @return string|null
     */
    public function getRenewAt(): ?string
    {
        return $this->renew_at;
    }

    /**
     * @param string $renew_at
     */
    public function setRenewAt($renew_at): void
    {
        if ($renew_at) {
            $timestamp = strtotime($renew_at);
            $this->renew_at = date("Y-m-d H:i:s", $timestamp);
        }
    }

    /**
     * @return string|null
     */
    public function getStartAt(): ?string
    {
        return $this->start_at;
    }

    /**
     * @param string $start_at
     */
    public function setStartAt($start_at): void
    {
        if ($start_at) {
            $timestamp = strtotime($start_at);
            $this->start_at = date("Y-m-d H:i:s", $timestamp);
        }
    }

    /**
     * @return string|null
     */
    public function getExpiresAt(): ?string
    {
        return $this->expires_at;
    }

    /**
     * @param string $expires_at
     */
    public function setExpiresAt($expires_at): void
    {
        if ($expires_at) {
            $timestamp = strtotime($expires_at);
            $this->expires_at = date("Y-m-d H:i:s", $timestamp);
        }
    }

    /**
     * @return int|null
     */
    public function getUserId(): ?int
    {
        return $this->user_id;
    }

    /**
     * @param $param
     */
    public function setUserId($param): void
    {
        $this->user_id = $param;
    }

    /**
     * Set User ID by email address
     * @param string $user_email
     * @return void
     */
    private function setUserEmail(string $user_email): void
    {
        $user = get_user_by('email', $user_email);
        if ($user) {
            $this->setUserId($user->ID);
        }
    }

    /**
     * @return string|null
     */
    public function getCreatedAt(): ?string
    {
        return $this->created_at;
    }

    /**
     * @return string|null
     */
    public function getUpdatedAt(): ?string
    {
        return $this->updated_at;
    }

    /**
     * 
     */
    public function createDatabase()
    {
        global $wpdb;
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE `{$wpdb->base_prefix}cextoo` (
			ID bigint(20) unsigned NOT NULL auto_increment,
			external_id varchar(250) NOT NULL,
			product_name varchar(250) NOT NULL,
			status int(11) NOT NULL default '0',
			renew_at datetime NULL,
			start_at datetime NOT NULL,
			expires_at datetime NULL,
			user_id bigint(20) UNSIGNED NOT NULL,
            rule_name varchar(250) NOT NULL,
            rule_slug varchar(250) NOT NULL,
			created_at datetime NOT NULL,
			updated_at datetime NOT NULL,
			PRIMARY KEY  (ID)
		  ) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}