<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
require_once(APPPATH . 'libraries/PHPMailer/class.phpmailer.php' );
class CI_phpMailer extends PHPMailer {
	
	public function __construct() {
		parent::__construct();
		
	}
}
/* End of file phpmailer.php */