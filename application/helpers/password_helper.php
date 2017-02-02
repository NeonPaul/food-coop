<?
# Not cryptographically secure...
	function generate_password(){
		$newpass=time().rand();
		$newpass=substr(base64_encode(md5($newpass)),0,8);
		return $newpass;
	}
	?>
