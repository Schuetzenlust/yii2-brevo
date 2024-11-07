<?php

namespace schuetzenlust\brevo\webhooks\types;

class Delivered {
	
	public $event;
	public $email;
	public $brevo_id;
	public $date;
	public $ts;
	public $message_id;
	public $ts_event;
	public $subject;
	public $x_mailincustom;
	public $sending_ip;
	public $template_id;
	public $tags;
	
	function __construct(array $data) {
		foreach($data as $key => $val) {
			$key = str_replace("-", "_", $key);
			if ($key == "id") {
				$key = "brevo_id";
			}
			if(property_exists(__CLASS__, $key)) {
				$field = str_replace("-", "_", $key);
				$this->$field = $val;
			}
		}
	}
}


?>