<?php

class Cextoo_Database{

	protected int $ID;
	protected string $external_id;
	protected string $product_name;
	public int $status;
	public int $renew_count;
	public datetime $renew_at;
	protected datetime $start_at;
	public datetime $expires_at;
	protected int $user_id;
	protected datetime $created_at;
	protected datetime $updated_at;


	public function __construct()
	{
        
	}

	private function set($data){
		foreach ($data as $key => $value){
            $function = 'set'.ucfirst($key);
            if (function_exists($this->$function)) {
                $this->$function($value);
            }
		}
	}

	public function get($external_id){
		global $wpdb;
		$database_result = $wpdb->get_row(
			"SELECT * FROM `{$wpdb->base_prefix}cextoo` WHERE external_id = `$external_id)` LIMIT 0,1"
		);
		if($database_result){
			$this->set($database_result);
		}
	}

    private function uptdateRenewCount()
    {
        if($this->getRenewCount()){
            $this->setRenewCount($this->getRenewCount() + 1);
        }else{
            $this->setRenewCount(1);
        }
    }

	public function update(): void
    {
        if(!$this->getID()){
            throw new Exception("Dear friend, I can't do this...");
         }else{
            global $wpdb;
		    $this->update_timestamp();
            $this->uptdateRenewCount();
			$wpdb->update($wpdb->base_prefix.'cextoo',[
                    'status' => $this->getStatus(),
                    'renew_count' => $this->getRenewCount(),							
                    'renew_at' => $this->getRenewAt(),							
                    'expires_at' => $this->getExpiresAt(),
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
            $this->update_timestamp();       
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

	private function update_timestamp()
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
     * @return datetime
     */
    public function getRenewAt(): datetime
    {
        return $this->renew_at;
    }

    /**
     * @param datetime $renew_at
     */
    public function setRenewAt(datetime $renew_at): void
    {
        $this->renew_at = $renew_at;
    }

    /**
     * @return datetime
     */
    public function getStartAt(): datetime
    {
        return $this->start_at;
    }

    /**
     * @param datetime $start_at
     */
    public function setStartAt(datetime $start_at): void
    {
        $this->start_at = $start_at;
    }

    /**
     * @return datetime
     */
    public function getExpiresAt(): datetime
    {
        return $this->expires_at;
    }

    /**
     * @param datetime $expires_at
     */
    public function setExpiresAt(datetime $expires_at): void
    {
        $this->expires_at = $expires_at;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    /**
     * @param int $user_id
     */
    public function setUserId(int $user_id): void
    {
        $this->user_id = $user_id;
    }

    /**
     * @return datetime
     */
    public function getCreatedAt(): datetime
    {
        return $this->created_at;
    }

    /**
     * @return datetime
     */
    public function getUpdatedAt(): datetime
    {
        return $this->updated_at;
    }

}