<?php
class Orders extends CI_Controller{
	public $user;

	function __construct(){
		parent::__construct();
		$this->auth->require_login();

		$this->load->model('item_model');
		$this->load->model('order_model');
		$this->load->library('state');

		if(!$this->auth->get_udata("constitution")){
			redirect("settings/constitution");
		}

		if($this->state->get("order_phase", 'collection') != "open"){
			$this->load->view("head");
			$this->load->view("orders/orders_processing");
			$this->load->view("foot");
			echo $this->output->get_output();
			die;
		}
	}

	public function index()
	{
		// Fetch the user's open (transaction? order collection?)
		$data['request_items']=$this->order_model->getCurrentOrders();
		$data['total']=$this->order_model->getTotal($data['request_items']);
		$data['weight']=$this->order_model->getWeight();
		$hdata['stylesheets']=array("request.css");
		$fdata['scripts']=array("request.js");
		$this->load->view("head",$hdata);
		$this->load->view("orders/your_order",$data);
		$this->load->view("foot",$fdata);
	}

	function add()
	{
		$id=$this->input->post('item_id');
		$qtty=$this->input->post('quantity');
		if($this->item_model->exists($id))
			$this->order_model->addItem($id,$qtty);
		redirect("orders");
	}
	
	function update()
	{
		$return=$this->input->post('return') OR $return='orders';
		$id=$this->input->post('request_id');
		$qtty=$this->input->post('quantity');
		$this->order_model->updateItem($id,$qtty);
		redirect($return);	
	}
	
	function update_add(){
		$return=$this->input->post('return') OR $return='orders';
		$id=$this->input->post('item_id');
		$qtty=$this->input->post('quantity');
		$this->order_model->updateOrAddItem($id,$qtty);
		redirect($return);
	}

	function all_orders(){
		$fdata['scripts']=array("jquery.js");
		$this->load->helper('money');
		$data['results']=$this->order_model->compile_orders();
		$data['total']=$this->order_model->getTotal();
		$this->load->view("head");
		$this->load->view("orders/all_orders",$data);
		$this->load->view("foot",$fdata);
	}

	function popular_items(){
		$data['results']=$this->order_model->get_popular_items();
		$data['total']=$this->order_model->getTotal();
		$this->load->view("head");
		$this->load->view("orders/popular_items",$data);
		$this->load->view("foot");		
	}
	
	function current_stock(){
		$data['stock']=$this->order_model->get_current_stock();
		$data['user_orders']=$this->order_model->member_orders();
		$data['total']=$this->order_model->getTotal();
		$this->load->view("head");
		$this->load->view("orders/stock_list",$data);
		$this->load->view("foot");			
	}
	
	function search_catalogue(){
		$term=$_GET['q'];
		$this->load->model("catalogue_model");
		$data['results']=$this->catalogue_model->search($term);
		$data['total']=$this->order_model->getTotal();
		$_SESSION['catalogue_search_results']=$data['results'];
		$this->load->view("head");
		$this->load->view("orders/search_catalogue",$data);
		$this->load->view("foot");
	}
	
	function catalogue_import(){
		$q=array_filter($this->input->post("qtty"),function($v){return $v>0;});
		$this->load->model("catalogue_model");

		if(($q)&&($results=$_SESSION['catalogue_search_results'])){	
			$import_items=array_intersect_key($results,$q);		
			$this->catalogue_model->import($import_items);

			foreach($q as $id => $qtty){
				$this->order_model->addItem($id,$qtty);
			}
		}
		redirect("orders");
	}
	
	function search_items($type="html"){
		$q=isset($_REQUEST['q'])?$_REQUEST['q']:"";
		$data['search']=$this->item_model->search($q);
		$data['total']=$this->order_model->getTotal();
		if($type=="js"){
			array_unshift($data['search'],$q);
			echo json_encode($data['search']);
		}else{
			$this->load->view("head");
			$this->load->view("orders/search_items",$data);
			$this->load->view("foot");
		}
	}
}
?>
