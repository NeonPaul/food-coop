<?php
class Orders extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->auth->require_login();
		$this->auth->require_permissions(ADMIN_ORDERS);
		$this->load->library('table');
		$this->load->library('state');
		$this->load->model("admin/orders_model");
	}

	public function index()
	{
		$phase=$this->state->get("order_phase", 'open');

		if($phase=="collection"){
			self::member_orders();
			return;
		}

		if($phase=="open" AND $this->input->post("close_orders")){
			$this->state->set("order_phase","closed");
			redirect("admin/orders");
		}elseif($phase=="closed" AND $this->input->post("open_orders")){
			$this->state->set("order_phase","open");
			redirect("admin/orders");
		}elseif($phase=="closed" AND $this->input->post("place_orders")){
			$this->state->set("order_phase","collection");
			redirect("admin/orders");
		}

		$orders=$this->orders_model->compile_current_orders();
		$data['orders']=$orders;
		$data['order_phase']=$phase;

		$this->load->view("head");
		$this->load->view("admin/orders",$data);
		$this->load->view("foot");

	}

	public function member_orders($e=false){
		$phase=$this->state->get("order_phase", 'open');
		if($phase=="collection"){
			if($this->input->post("open_orders")){
				$this->state->set("order_phase","open");
				redirect("admin/orders");
			}elseif($this->input->post("reset_orders")){
				$this->orders_model->update_stock();
				$this->orders_model->archive_orders();
				$this->state->set("order_phase","open");
				redirect("admin/orders");
			}
		}
		$orders=$this->orders_model->current_orders_by_member();
		$data['orders']=$orders;
		$data['phase']=$phase;

		$this->load->view("head");
		$this->load->view("admin/member_orders".($e?"_foremail":""),$data);
		$this->load->view("foot");
	}

	public function delete($item_id){
		if($this->state->get("order_phase", 'open') == "closed"){
			$this->orders_model->delete_from_current($item_id);
		}
		redirect("admin/orders");
	}

	public function update(){
		if($this->state->get("order_phase", 'open') == "closed"){
			$this->orders_model->update_qtty($this->input->post("item_id"),$this->input->post("member_id"),$this->input->post("qtty"));

		}
		redirect("admin/orders");
	}
}
?>
