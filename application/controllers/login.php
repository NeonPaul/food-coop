<?php
class Login extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->load->library('form_validation');
	}
	
	public function index()
	{
	$data['loginfail']="";	
		$this->form_validation->set_rules('email','Email address','required');
		$this->form_validation->set_rules('password','Password','required');
		
		if($this->form_validation->run() !== false){
			$auth=$this->auth->authenticate(
						$this->input->post('email'),
						$this->input->post('password'),
						'manual',
						$this->input->post('cookie')
					);
			if($auth == true){
				if(($r=$_SESSION['login_redir'])=="/login"){
					redirect('');
				}else{
					redirect($_SESSION['login_redir']);
				}
			}
			$data["loginfail"]="Log in failed, please check that your email and password are correct and try again.";
		}
		
		$this->load->view('head');
		$this->load->view('auth/login_form',$data);
		$this->load->view('foot');
	}
	
	public function logout()
	{
		$this->auth->unset_session();
		redirect('login');
	}
	
	public function reset_password($email=""){
		$this->load->model("settings_model");
		$data['email']=$email;
		
		$this->form_validation->set_rules('email','Email Address','required|email');
		
		if($this->form_validation->run() !== false){
			if($this->settings_model->reset_password()==true){
				redirect("login/reset_password_success");
				die;
			}else{
				$data["errors"]="We couldn't find that email in our members list - please check that it's spelt correctly and try again.";
			}
		}else{
			$data["errors"]=validation_errors();
		}
		
		$this->load->view('head');
		$this->load->view('auth/reset_password_form',$data);
		$this->load->view('foot');
	}
	
	public function reset_password_success(){
		$this->load->view('head');
		$this->load->view('auth/reset_password_success');
		$this->load->view('foot');
	}
	
	public function by_link($email="",$password=""){
		if(!$email || !$password){
			redirect("login");
			return;
		}
		
		$auth=$this->auth->authenticate2(urldecode($email),$password);
		
		
		
		if($auth==true){
			redirect('settings/change_password');
		}
		redirect('');
	}
}
?>