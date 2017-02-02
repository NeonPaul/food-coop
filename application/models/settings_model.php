<?php
class settings_model extends CI_Model{
	function reset_password(){
		$this->load->helper("password");
		$newpass = generate_password();
		$this->db->query("UPDATE `members` SET `password`='".$this->auth->pw_encrypt($newpass)."' WHERE `email`='".$this->input->post('email')."' LIMIT 1");
		
		if($this->db->affected_rows()!=1)
			return false;
			
		$q=$this->db->query("SELECT * FROM `members` WHERE `email`='".$this->input->post('email')."' LIMIT 1");
		$user=$q->row();
		
		$this->load->library("email");
		
		$this->email->from("food@greenactionsouthampton.co.uk","Food Co-op");
		$this->email->reply_to("neonpaul@gmail.com","Paul Kiddle (Food Co-Op)");
		$this->email->to($user->email);
		
		$this->email->subject("New Food Co-op Password");
		$this->email->message("Hi, ".$user->member_name."!\n\nThis is just a message to let you know that your password to access the food co-op has been reset. Your new password is ".$newpass.". Use the link below to log in (and then change your password to something more memorable!)\n\n".$this->config->item('base_url')."login/by_link/".urlencode($user->email)."/$newpass");
		
		if($this->email->send())
			return true;
		
		echo $this->email->print_debugger();
		die;
		
	}
	
	function update($array){
		$this->load->model("admin/members_model");
		if($this->members_model->update_member($this->auth->get_udata('member_id'),$array)){
			$this->auth->refresh_user();
			return true;
		}
		return false;
	}
}

?>
