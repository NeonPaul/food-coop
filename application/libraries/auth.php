<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

require 'phpass/PasswordHash.php';

class Auth {
  public static $phpass;

	public function __construct(){
          self::$phpass = new PasswordHash(8, false);

		session_start();
		//echo session_id();
		//print_r($_SESSION);
		//$_SESSION['test']=1;die;
		$this->CI =& get_instance();
		if( ! isset($_SESSION['user'])){
			$_SESSION["login_redir"]="/".$this->CI->uri->uri_string();
			////echo "Checking session:"; //print_r($_SESSION);
			self::session_from_cookie();
		}
	}

    public function require_login()
    {
		if( ! isset($_SESSION['user'])){
			$_SESSION["login_redir"]="/".$this->CI->uri->uri_string();
			if(!self::session_from_cookie()){
				redirect("login");
			}
		}
    }

	public function logged_in(){
		return isset($_SESSION['user']);
	}

	public function verify_user($user_id,$password,$method="manual"){
		$field_names = array(
			"manual"=>array(	"id"=>"email",
							"pass"=>"password" ),
			"auto"=>array("id"=>"member_id",
							 "pass"=>"machine_pass")
						);

		//print_r(array($field_names[$method]["id"],$user_id,	$field_names[$method]["pass"],$password));

		$q = $this->CI->db
				->where($field_names[$method]["id"],$user_id)
				->limit(1)
				->get('members');


		if($q->num_rows() > 0){
		  $row = $q->row();
                  if ($method == 'manual') {
                    $ver = self::verify_password($password, $row->password);
                    if (!$ver) {
                      return false;
                    }
                    if ($ver === 1) {
                      $this->load->model("admin/members_model");
                      $this->members_model->update_member(
                        $row->member_id,
                        array("password"=>self::$phpass->hashPassword($password))
                      );
                    }
                  } else {
                    if ($password != $row->machine_pass) {
                      return false;
                    }
                  }
                  return $row;
		}
		return false;
	}

	public function verify_user2($user_id,$password){
		$q = $this->CI->db
				->where('email',$user_id)
				->where('password',$password)
				->limit(1)
				->get('members');

		if($q->num_rows() > 0){
			return $q->row();
		}
		return false;
	}

	function new_machine_pass($member_id){
                // Very bad...
		$pass=self::pw_encrypt(time());
		$this->CI->db->query("UPDATE `members` SET `machine_pass`='$pass' WHERE `member_id`='$member_id'");
		return $pass;
	}

	function refresh_user(){
		$q = $this->CI->db
				->where('member_id',self::get_udata('member_id'))
				->limit(1)
				->get('members');
		if($q->num_rows() > 0){
			self::set_user_session($q->row());
			return true;
		}
		return false;
	}

	public function set_user_session($data){
		$_SESSION['user']=$data;
		//echo "Setting session: "; print_r($_SESSION);
		$this->CI->user=$data;

	}

	public function unset_session(){
		unset($_SESSION['user']);
		unset($_COOKIE['login']);
		setcookie("login","",0,"/");
		echo "<script>window.location.replace('/')</script><a href='/'>You are now logged out, click here to continue.</a>";
		die;
	}

	public function authenticate($id,$password,$method="manual",$cookie=false){
          $result = self::verify_user($id,$password,$method);
            if($result !== false){
              self::set_user_session($result);
              if($cookie || $method=="auto"){
                self::create_cookie($result->member_id);
                echo "<script>window.location.replace('/".$_SESSION['login_redir']."')</script><a href='/".$_SESSION['login_redir']."'>You are now logged in, click here to continue.</a>";
                die;
              }
              return true;
            }
          return false;
	}

	public function authenticate2($id,$password){
		$result = self::verify_user2($id,$password);
		if($result !== false){
				self::set_user_session($result);
				return true;
		}
		return false;
	}

	public function create_cookie($member_id){
		$machine_pass = self::new_machine_pass($member_id);
		setcookie("login",$member_id.":".$machine_pass,time()+60*60*24*30*3,'/');
	}

	public function session_from_cookie(){
		//echo "Cookie stuff\n";
		if(!isset($_COOKIE['login']))
			return false;
		//echo "Login cookie found\n";
		$c=explode(":",$_COOKIE['login']);
		//echo "About to authenticate\n";
		return self::authenticate($c[0],$c[1],"auto",true);
	}

	public function pw_encrypt($input, $deprecated=false){
          if ($deprecated) {
		$salt=getenv('DEP_PW_SALT');
		return md5($salt.md5($input).$salt);
          }
          return self::$phpass->hashPassword($input);
	}

        public function verify_password($password, $stored) {
          if (strpos($stored, '$') == 0) {
            return self::$phpass->checkPassword($password, $stored) && true;
          } else {
            return (self::pw_encrypt($password, true) == $stored) && 1;
          }
        }

	public function get_udata($what=NULL){
		if(!isset($_SESSION['user']))
			return;
		if($what===NULL)
			return $_SESSION['user'];

		if(!isset($_SESSION['user']->$what)){
			die("Could not get user data: ".$what);
		}
		return $_SESSION['user']->$what;
	}
	
	public function permissions($p=null){
		if(is_null($p)){
			return self::get_udata("permissions")>0;
		}else{
			return (self::get_udata("permissions") & $p) == $p;
		}
	}
	
	public function require_permissions($permissions=null){
		if(!self::permissions($permissions)){
			//echo "Sorry, you do not have permission to be here.<br>";
			//echo self::get_udata("permissions"). "<br>". $permissions."<br>".(self::get_udata("permissions") & $permissions);
			die;
		}
	}
}
?>
