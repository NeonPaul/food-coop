<?
class Members_model extends CI_Model{
	function __construct()
	{
		parent::__construct();
	}
	
	function get_members($who="active"){
		if(is_numeric($who)){
			$who= (int) $who;
			$where="WHERE `member_id`='$who'";
		}else{
			switch($who){
				case "all":
					$where="";
					break;
				
				case "active":
				default:
					$where="WHERE `active`=1 AND `member_id`>0";
					break;
			}
		}
		
		$q=$this->db->query("SELECT `member_id`, `member_name`, `email`, `permissions`,`password` FROM `members` $where");
		return $q->result();
	}
	
	function get_member($mid){
		$q=$this->db->query("SELECT * FROM `members` WHERE `member_id`='$mid' LIMIT 1");
		return $q->row();
	}
	
	function update_member($mid,$array){
		$delim="SET ";
		$sql="UPDATE `members` ";
		foreach($array as $k => $v){
			$sql.=$delim."`$k`='$v'";
			$delim=", ";
		}
		$sql.=" WHERE `member_id`='".$mid."' LIMIT 1";
		
		$this->db->query($sql);
		
		if($mid==$this->auth->get_udata("member_id")){
			$this->auth->refresh_user();
		}
		
		return ($this->db->affected_rows()==1);	
	}
	
	function add_member($details){
		$this->load->helper("password");
		$pw=generate_password();
//		$pw=Auth::pw_encrypt($pw);
		$sql="INSERT INTO `members` (`member_name`,`email`,`permissions`,`password`) VALUES('${details['member_name']}','${details['email']}','${details['permissions']}','".$pw."')";
		
		$this->db->query($sql);
		
		return ($this->db->insert_id());	
	}
}
?>