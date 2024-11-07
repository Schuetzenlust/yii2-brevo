<?php

namespace schuetzenlust\brevo\webhooks\types;

class Opened {
	
	public $event;
	public $email;
	public $id;
	public $date;
	public $message_id;
	public $subject;
	public $sending_ip;
	public $ts_epoch;
	public $template_id;
	public $tag;
	
	function __construct(array $data) {
		foreach($data as $key => $val) {
			if(property_exists(__CLASS__, str_replace("-", "_", $key))) {
				$field = str_replace("-", "_", $key);
				$this->$field = $val;
			}
		}
	}
	
}


?>