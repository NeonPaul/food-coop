<?php
class Members extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->auth->require_login();
		$this->auth->require_permissions(ADMIN_MEMBERS);
		$this->load->model("admin/members_model");
		$this->load->library("form_validation");
	}
	
	public function index($all="active")
	{
		$members=$this->members_model->get_members(($all=="all")?"all":"active");
		$data['members']=$members;
		
		$this->load->view("head");
		$this->load->view("admin/members",$data);
		$this->load->view("foot");
	}
	
	public function edit($mid=null){
		if($mid===null)
			redirect("admin/members");
		
		$this->load->library("form_validation");
		//set validation rules
		$this->form_validation->set_rules('member_name','Member Name','required');
		$this->form_validation->set_rules('email','Email Address','required|valid_email');
		$this->form_validation->set_rules('permissions[]','Permissions','integer|is_natural|less_than[9]');
		if($this->form_validation->run()){
			$permissions=array_sum($this->input->post("permissions"));
			if($this->input->post("member_id")==$this->auth->get_udata("member_id")){
				$permissions=$permissions|ADMIN_MEMBERS;
			}
			$this->members_model->update_member($mid,array(
				"member_name"=>$this->input->post("member_name"),
				"email"=>$this->input->post("email"),
				"permissions"=>$permissions,
				"constitution"=>$this->input->post("constitution"),
				"notes"=>$this->input->post("notes"),
				"active"=>$this->input->post("active"),
				"deposit"=>$this->input->post("deposit")
			));
			redirect("admin/members");
		}

		$data['member']=$this->members_model->get_member($mid);
		$this->load->view("head");
		$this->load->view("admin/edit_member_form",$data);
		$this->load->view("foot");
	}
	
	public function add(){
		$this->load->library("form_validation");
		//set validation rules
		$this->form_validation->set_rules('member_name','Member Name','required');
		$this->form_validation->set_rules('email','Email Address','required|valid_email');
		if($this->form_validation->run()){
			$mid=$this->members_model->add_member(array(
				"member_name"=>$this->input->post("member_name"),
				"email"=>$this->input->post("email"),
				"permissions"=>array_sum($this->input->post("permissions")),
				"notes"=>$this->input->post("notes"),
				"active"=>1
			));
			redirect("admin/members/edit/$mid");
		}

		$this->load->view("head");
		$this->load->view("admin/add_member_form");
		$this->load->view("foot");
	}
	

	public function email($message="default",$user=null){
		//set validation rules
		$this->form_validation->set_rules("subject","Subject",'required');
		$this->form_validation->set_rules("body","Message body",'required');
		
		if($this->form_validation->run()){
			if(!is_numeric($to=$user))
				$to="active";
			$members=$this->members_model->get_members($to);
			$this->load->library("email");
			
			foreach($members as $member){
				//send email
				$this->email->to($member->email);
				//$this->email->to("neonpaul@gmail.com");
				$this->email->from("food@greenactionsouthampton.co.uk");
				$this->email->reply_to($this->auth->get_udata("email"));
				$this->email->subject($this->input->post("subject"));
				$this->email->message(str_replace(
						array(	"<member_name>",
							"<reset_password_url>",
							"<login_link>"),
						array(	$member->member_name,
							$this->config->item('base_url')."login/reset_password/".urlencode($member->email),
							$this->config->item('base_url')."login/by_link/".urlencode($member->email)."/".$member->password),
						$this->input->post("body")));
				$this->email->send();
				//die;
			}
			?>
			Emails sent! <a href="">Continue.</a>
			<?
			die;
		}
		
		$msgdir="/home/mrkiddle/green_action/www/food/application/views/admin/default_emails/";
		$msgext=".txt";
		if(preg_match("/^[a-z0-9_-]+$/i",$message)){
			$msgfile=$msgdir.$message.$msgext;
			if(file_exists($msgfile))
				$data['body']=file($msgfile);
		}
		if(!isset($data['body']))
			$data['body']=file($msgdir."default".$msgext);
			
		$data['subject']=array_shift($data['body']);
		$data['body']=implode($data['body']);
		
		$data['to']=(is_numeric($user)?"User ID# $user":"Active members");
		
		
		$this->load->view("head");
		$this->load->view("admin/email_members_form",$data);
		$this->load->view("foot");
	}
}
?>
