<?php
class Stock extends CI_Controller{
	function __construct(){
		parent::__construct();
		$this->auth->require_login();
		$this->auth->require_permissions(ADMIN_STOCK);
		$this->load->library("form_validation");
	}
	
	public function index()
	{
		if($this->input->post('add')){
			mysql_query("INSERT INTO `stock` (item_id,quantity) VALUES('".$this->input->post("item_id")."','".$this->input->post("quantity")."') ON DUPLICATE KEY UPDATE `quantity`=`quantity`+'".$this->input->post('quantity')."'");
			
		}
		if($stock=$this->input->post('stock')){
			foreach($stock as $item_id=>$quantity){
				if($quantity>0){
					$this->db->query("UPDATE `stock` SET `quantity`='$quantity' WHERE `item_id`='$item_id' LIMIT 1");
				}else{
					$this->db->query("DELETE FROM `stock` WHERE `item_id`='$item_id' LIMIT 1");	
				}
			}
		}
		
		$stock=$this->db->query("SELECT * FROM `stock` LEFT JOIN `item` USING(`item_id`)");
		$data['stock']=$stock->result();
		$hdata['stylesheets']=array("/food/request.css");
		$fdata['scripts']=array("/food/request.js");
		
		
		$this->load->view("head",$hdata);
		$this->load->view("admin/stock",$data);
		$this->load->view("foot",$fdata);
	}
	

}
?>