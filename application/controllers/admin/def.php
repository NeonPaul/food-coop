<?php
class Def extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->auth->require_login();
		$this->auth->require_permissions();
	}
	
	public function index()
	{
		$this->load->view("head");
		$this->load->view("admin/index");
		$this->load->view("foot");
	}
	
	public function login_as($mid){
		$q = $this->db
				->where('member_id',$mid)
				->limit(1)
				->get('members');
		if($q->num_rows() > 0){
			$this->auth->set_user_session($q->row());
		}
		redirect("orders");
	}
}
?>