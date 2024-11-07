<?php

namespace schuetzenlust\brevo\webhooks\types;

class Sent {
	
	public $event;
	public $email;
	public $id;
	public $date;
	public $ts;
	public $message_id;
	public $ts_event;
	public $subject;
	public $X_Mailin_custom;
	public $sending_ip;
	public $ts_epoch;
	public $template_id;
	public $mirror_link;
	public $contact_id;
	public $tags;
	
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