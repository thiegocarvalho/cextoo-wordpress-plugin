<?php

class Cextoo_Database{

	protected int $ID;
	protected string $external_id;
	protected string $product_name;
	public int $status;
	public int $renew_count;
	public string $renew_at;
	protected string $start_at;
	public string $expires_at;
	protected int $user_id;
	protected string $created_at;
	protected string $updated_at;


	public function __construct()
	{
        $this->ID = 0;
        $this->external_id = '';
        $this->product_name = '';
        $this->status = 0;
        $this->renew_count = 0;
        $this->renew_at = '';
        $this->start_at = '';
        $this->expires_at = '';
        $this->user_id = 0;
        $this->created_at = '';
        $this->updated_at = ''; 
	}

    private function camelize($input, $separator = '_')
    {
        return str_replace($separator, '', ucwords($input, $separator));
    }

	public function set($data){
        
		foreach ($data as $key => $value){
            $function = 'set'.$this->camelize($key);
            if (method_exists(__CLASS__, $function)) {
                $this->$function($value);
            }
		}
	}

    public function get_by_user_id($user_id){
        global $wpdb;
        $table_name = $wpdb->prefix . 'cextoo';
        $sql = "SELECT * FROM $table_name WHERE user_id = $user_id";
        $result = $wpdb->get_results($sql);
        return $result;
    }

	public function get($external_id){
		global $wpdb;
		$database_result = $wpdb->get_row(
			"SELECT * FROM `{$wpdb->base_prefix}cextoo` WHERE external_id = {$external_id}"
		);
		if($database_result){
			$this->set($database_result);
            return true;
		}
        return false;
	}

    private function uptdateRenewCount()
    {
        if($this->getStatus() == 1){         
            if($this->getRenewCount()){
                $this->setRenewCount($this->getRenewCount() + 1);
            }else{
                $this->setRenewCount(0);
            }
        }
    }

	public function update(): void
    {
        if(!$this->getID()){
            throw new Exception("Dear friend, I can't do this...");
         }else{
            global $wpdb;
		    $this->updateTimestamp();
            $this->uptdateRenewCount();
			$wpdb->update($wpdb->base_prefix.'cextoo',[
                    'status' => $this->getStatus(),
                    'renew_count' => $this->getRenewCount(),							
                    'renew_at' => $this->getRenewAt(),							
                    'expires_at' => $this->getExpiresAt(),
                    'updated_at' => $this->getUpdatedAt()
                ], [
                    'ID' => $this->getID()
                ]
			);
        }
    }

	public function create()
    {     
        if($this->getID()){
           throw new Exception("Dear friend, I can't do this...");
        }else{
            global $wpdb;
            $this->updateTimestamp();
            $this->uptdateRenewCount();    
            
            $wpdb->insert($wpdb->base_prefix.'cextoo', [
                    'external_id' => $this->getExternalId(),	
                    'product_name' => $this->getProductName(),
                    'status' => $this->getStatus(),
                    'renew_count' => $this->getRenewCount(),							
                    'renew_at' => $this->getRenewAt(),
                    'start_at' => $this->getStartAt(),								
                    'expires_at' => $this->getExpiresAt(),
                    'user_id' => $this->getUserID(),
                    'created_at' => $this->getCreatedAt(),
                    'updated_at' => $this->getUpdatedAt()
                ]);
        }
	}

	private function updateTimestamp()
    {
		$this->updated_at = date("Y-m-d H:i:s");
		if(!$this->getCreatedAt()){
			$this->created_at = $this->updated_at;
		}
	}

    /**
     * @return int
     */
    public function getID(): int
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
     * @return string
     */
    public function getExternalId(): string
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
     * @return string
     */
    public function getProductName(): string
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
     * @return int
     */
    public function getRenewCount(): int
    {
        return $this->renew_count;
    }

    /**
     * @param int $renew_count
     */
    public function setRenewCount(int $renew_count): void
    {
        $this->renew_count = $renew_count;
    }

    /**
     * @return string
     */
    public function getRenewAt()
    {
        return $this->renew_at;
    }

    /**
     * @param string $renew_at
     */
    public function setRenewAt($renew_at): void
    {
        $timestamp = strtotime($renew_at);
        $this->renew_at = date("Y-m-d H:i:s", $timestamp);
    }

    /**
     * @return string
     */
    public function getStartAt()
    {
        return $this->start_at;
    }

    /**
     * @param string $start_at
     */
    public function setStartAt($start_at): void
    {
        $timestamp = strtotime($start_at);
        $this->start_at = date("Y-m-d H:i:s", $timestamp);
    }

    /**
     * @return string
     */
    public function getExpiresAt()
    {
        return $this->expires_at;
    }

    /**
     * @param string $expires_at
     */
    public function setExpiresAt($expires_at): void
    {
        $timestamp = strtotime($expires_at);
        $this->expires_at = date("Y-m-d H:i:s", $timestamp);
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    private function setUserEmail(string $user_email): void
    {
        $this->setUserId($user_email);
    }

    /**
     * @param $param
     */
    public function setUserId($param): void
    {
        if(is_int($param)){
            $this->user_id = $param;
        }else{
            $user = get_user_by('email', $param);
            if($user){
                $this->user_id = $user->ID;
            }
        }
    }

    /**
     * @return string
     */
    public function getCreatedAt()
    {
        return $this->created_at;
    }

    /**
     * @return string
     */
    public function getUpdatedAt()
    {
        return $this->updated_at;
    }

}