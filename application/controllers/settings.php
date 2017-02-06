<?
class settings extends CI_Controller{

function __construct(){
	parent::__construct();
	
	$this->auth->require_login();
	$this->load->model("settings_model");
}

function index(){
	$this->load->library("form_validation");
	$this->form_validation->set_rules('member_name','Name','required');
	$this->form_validation->set_rules('email','Email address','required|valid_email');
	$this->form_validation->set_rules('password','Password','min_length[5]');

	
	if($this->form_validation->run()!=FALSE){
		$settings=array(
			'member_name'=>$this->input->post('member_name'),
			'email'=>$this->input->post('email')
		);
		if(($p=$this->input->post('password'))!==''){
			$settings['password']=$this->auth->pw_encrypt($p);
		}
		
		if($this->settings_model->update($settings))		
			redirect("settings");
	}

	$this->load->view("head");
	$this->load->view("settings/settings");
	$this->load->view("foot");
}

function change_password(){
	$this->load->library("form_validation");

	$this->form_validation->set_rules('password','Password','min_length[5]');
	
	if($this->form_validation->run()!=FALSE){
		$settings['password']=$this->auth->pw_encrypt($this->input->post('password'));
		
		if($this->settings_model->update($settings))
			redirect($this->auth->get_udata("constitution")?"":"settings/constitution"); 
	}

	$this->load->view("head");
	$this->load->view("settings/changepw");
	$this->load->view("foot");
}

function constitution(){
	
	if($this->input->post("submit")){
		$agree=!!$this->input->post("agree");
		$this->settings_model->update(array("constitution"=>$agree));
		redirect($agree?"":"settings/constitution");
	}
	
	include "../libraries/rtfclass.php";
	$this->load->helper("clean_close_tags");
	$this->config->load("file_locations");
	$con=file_get_contents($this->config->item("data_path").$this->config->item("constitution"));
	$con=new rtf($con);
	$con->output("html");
	$con->parse();
	$con=$con->out;
	
	$data['constitution']=clean_close_tags($con);
	$data['agreed']=$this->auth->get_udata("constitution");

	$this->load->view("head");
	$this->load->view("settings/constitution",$data);
	$this->load->view("foot");
}
}
?>
