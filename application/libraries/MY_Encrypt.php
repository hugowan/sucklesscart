<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MY_Encrypt extends CI_Encrypt {

	const TD		= 'des';
	const IV		= 'ecb';
	const ECK		= 'yui567'; // u can find it. crv://other/lib_string.php - func g_k9_return
	
	private $_ci;
	private $_iv;
	private $_kkey;
	
	private function _customer_init()
	{
		$this->_ci = mcrypt_module_open(self::TD, '', self::IV, '');
		$this->_iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($this->_ci), MCRYPT_DEV_URANDOM);
		$this->_kkey = substr(md5(self::ECK), 0, mcrypt_enc_get_key_size($this->_ci));
	}
	
	function customer_encrypt($string)
	{
		$this->_customer_init();
		$string = trim($string);
		mcrypt_generic_init($this->_ci, $this->_kkey, $this->_iv);
		$string = base64_encode(mcrypt_generic($this->_ci, $string));
		mcrypt_generic_deinit($this->_ci);
		return trim($string);
	}
	
	function customer_decrypt($string)
	{
		$this->_customer_init();
		$string = trim($string);
		mcrypt_generic_init($this->_ci, $this->_kkey, $this->_iv);
		$string = mdecrypt_generic($this->_ci, base64_decode($string));
		mcrypt_generic_deinit($this->_ci);
		return trim($string);
	}
}