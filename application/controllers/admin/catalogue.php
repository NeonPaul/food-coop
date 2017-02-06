<?php
class Catalogue extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->auth->require_login();
		$this->auth->require_permissions(ADMIN_CATALOGUE);
	}
	public function index(){
		self::upload();
	}

	public function upload(){
		$data=array();
		if($this->input->post("upload")){
			$this->config->load("file_locations");

			$upload['upload_path'] = $this->config->item("data_path");
			$upload['file_name'] = "temp_".$this->config->item("catalogue");
			$upload['allowed_types'] = "csv";
			$upload['overwrite'] = TRUE;

			$this->load->library("upload",$upload);

			if(!$this->upload->do_upload("file")){
				$data['error']=$this->upload->display_errors();
			}else{

				$result=$this->upload->data();
				$this->load->model("catalogue_model");
				if(!$this->catalogue_model->verify($result['full_path'])){
					$data['error']="The file may not be a valid catalogue. Please check you have uploaded the correct file.";
				}else{
					$catalogue=$this->config->item("data_path").$this->config->item("catalogue");
					@unlink($catalogue);
					rename($result['full_path'],$catalogue);
					$results=$this->catalogue_model->update();
					$data["success"]="$results[success_count] records updated successfully";
					if($results["error_count"]>0){
						$data["error"]="$results[error_count] errors:<ul>\n<li>".implode("\n<li>",$results["errors"])."</ul>";
					}
				}
			}

			// Upload and move to place
			// Extract contents & update database
		}
		$this->load->view("head");
		$this->load->view("admin/catalogue/upload",$data);
		$this->load->view("foot");
	}
}

?>
