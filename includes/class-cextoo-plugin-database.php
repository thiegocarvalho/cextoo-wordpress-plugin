<?php

class Cextoo_Database{

	protected int $ID;
	protected string $Cextoo_external_id;
	protected string $Cextoo_product_name;
	public int $Cextoo_status;
	public int $Cextoo_renew_count;
	public datetime $Cextoo_renew_at;
	protected datetime $Cextoo_start_at;
	public datetime $Cextoo_expires_at;
	protected int $user_id;
	protected datetime $created_at;
	protected datetime $updated_at;


	public function __construct()
	{
	}


	private function set($data){
		foreach ($data as $key => $value){
				$this->$key = $value;
		}
	}

	public function get($external_id){
		global $wpdb;
		$database_result = $wpdb->get_row(
			"SELECT * FROM `{$wpdb->base_prefix}Cextoo` WHERE external_id = `$external_id)` LIMIT 0,1"
		);
		if($database_result){
			$this->set($database_result);
		}
	}

	public function update(): void
    {
		global $wpdb;
		$this->update_timestamp();
		if ($this->ID) {
			$wpdb->update( $wpdb->base_prefix.'Cextoo',[
				'Cextoo_product_name' => $this->Cextoo_product_name,
				'Cextoo_status' => $this->Cextoo_status,
				'Cextoo_renew_count' => $this->Cextoo_renew_count,							
				'Cextoo_renew_at' => $this->Cextoo_renew_at,							
				'Cextoo_expires_at' => $this->Cextoo_expires_at,
				'updated_at' => $this->updated_at
			], [
					'ID' => $this->ID
			]
			);
	}
}

	public function create(): void
    {
			global $wpdb;
			$this->update_timestamp();
			$wpdb->insert($wpdb->base_prefix.'Cextoo', [
				'Cextoo_external_id' => $this->Cextoo_external_id,
				'Cextoo_product_name' => $this->Cextoo_product_name,
				'Cextoo_status' => $this->Cextoo_status,
				'Cextoo_renew_count' => $this->Cextoo_renew_count,							
				'Cextoo_renew_at' => $this->Cextoo_renew_at,							
				'Cextoo_start_at' => $this->Cextoo_start_at,							
				'Cextoo_expires_at' => $this->Cextoo_expires_at,							
				'user_id' => $this->user_id,
				'created_at' => $this->created_at,
				'updated_at' => $this->updated_at
			]
			);
	}

	private function update_timestamp(): void
    {
		$this->updated_at = date();
		if(!$this->created_at){
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
    public function getCextooExternalId(): string
    {
        return $this->Cextoo_external_id;
    }

    /**
     * @param string $Cextoo_external_id
     */
    public function setCextooExternalId(string $Cextoo_external_id): void
    {
        $this->Cextoo_external_id = $Cextoo_external_id;
    }

    /**
     * @return string
     */
    public function getCextooProductName(): string
    {
        return $this->Cextoo_product_name;
    }

    /**
     * @param string $Cextoo_product_name
     */
    public function setCextooProductName(string $Cextoo_product_name): void
    {
        $this->Cextoo_product_name = $Cextoo_product_name;
    }

    /**
     * @return int
     */
    public function getCextooStatus(): int
    {
        return $this->Cextoo_status;
    }

    /**
     * @param int $Cextoo_status
     */
    public function setCextooStatus(int $Cextoo_status): void
    {
        $this->Cextoo_status = $Cextoo_status;
    }

    /**
     * @return int
     */
    public function getCextooRenewCount(): int
    {
        return $this->Cextoo_renew_count;
    }

    /**
     * @param int $Cextoo_renew_count
     */
    public function setCextooRenewCount(int $Cextoo_renew_count): void
    {
        $this->Cextoo_renew_count = $Cextoo_renew_count;
    }

    /**
     * @return datetime
     */
    public function getCextooRenewAt(): datetime
    {
        return $this->Cextoo_renew_at;
    }

    /**
     * @param datetime $Cextoo_renew_at
     */
    public function setCextooRenewAt(datetime $Cextoo_renew_at): void
    {
        $this->Cextoo_renew_at = $Cextoo_renew_at;
    }

    /**
     * @return datetime
     */
    public function getCextooStartAt(): datetime
    {
        return $this->Cextoo_start_at;
    }

    /**
     * @param datetime $Cextoo_start_at
     */
    public function setCextooStartAt(datetime $Cextoo_start_at): void
    {
        $this->Cextoo_start_at = $Cextoo_start_at;
    }

    /**
     * @return datetime
     */
    public function getCextooExpiresAt(): datetime
    {
        return $this->Cextoo_expires_at;
    }

    /**
     * @param datetime $Cextoo_expires_at
     */
    public function setCextooExpiresAt(datetime $Cextoo_expires_at): void
    {
        $this->Cextoo_expires_at = $Cextoo_expires_at;
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
     * @param datetime $created_at
     */
    public function setCreatedAt(datetime $created_at): void
    {
        $this->created_at = $created_at;
    }

    /**
     * @return datetime
     */
    public function getUpdatedAt(): datetime
    {
        return $this->updated_at;
    }

    /**
     * @param datetime $updated_at
     */
    public function setUpdatedAt(datetime $updated_at): void
    {
        $this->updated_at = $updated_at;
    }


}