<?
class Order_model extends CI_Model{

	function getCurrentOrders(){
		$u=$_SESSION['user'];

		$q = $this->db->query("SELECT *,(CEIL(`wholesale_cost`/(`wholesale_qtty`*5))*5) AS `price` FROM `orders` "
				      ."LEFT JOIN `item` USING(`item_id`) "
				      ."WHERE `member_id`='{$u->member_id}' "
				      ."AND `current`=1");

		$transaction = $q -> result();


		return $transaction;
	}

	function getTotal($orders=NULL,$string=TRUE){
		if($orders===NULL) $orders= self::getCurrentOrders();

		$total=0;
		foreach($orders as $order){
			$total+=unit_price($order->wholesale_cost,$order->wholesale_qtty,$order->quantity,0,0);
		}
		if($string)
			$total="£".number_format($total,2);
		return $total;
	}

	function getWeight($orders=NULL){
		if($orders===NULL) $orders= self::getCurrentOrders();

		$total=0;
		foreach($orders as $order){
			if(in_array($order->unit_weight_unit,array("kg","l"))){
				$multiple = 1;
			}else if(in_array($order->unit_weight_unit,array("g","ml"))){
				$multiple = 0.001;
			}else{
				continue;
			}
			$total+=$order->unit_weight * $multiple;
		}

		return $total;
	}

	function addItem($id,$qtty){
		if($qtty<1) $qtty=1;
		$mid=$this->auth->get_udata("member_id");
		$q = $this->db->query("SELECT * FROM `orders` WHERE `item_id`='$id' AND `member_id`='$mid' AND `current`=1");
		if($q->num_rows()>0){
			$r=$q->row();
			$row_id=$r->order_id;
			$this->db->query("UPDATE `orders` SET `quantity`=`quantity`+'$qtty' WHERE `order_id`='$row_id' AND `member_id`='$mid'");
		}else{
			$this->db->query("INSERT INTO `orders` (`item_id`,`quantity`,`member_id`,`current`) VALUES('$id','$qtty','$mid',1)");
		}
	}

	function updateItem($id,$qtty){
		if($qtty<0) $qtty=0;
		$mid=$this->auth->get_udata("member_id");
		if($qtty==0){
			$this->db->query("DELETE FROM `orders` WHERE `order_id`='$id' AND `member_id`='$mid'");
		}else{
			$this->db->query("UPDATE `orders` SET `quantity`='$qtty' WHERE `order_id`='$id' AND `member_id`='$mid'");
		}
	}

	function updateOrAddItem($item_id,$order_qtty){
		$mid=$this->auth->get_udata("member_id");
		if($order_qtty<=0){
			$this->db->query("DELETE FROM `orders` WHERE `item_id`='$item_id' AND `member_id`='$mid' AND `current`=1");
			return;
		}else
			$this->db->query("UPDATE `orders` SET `quantity`='$order_qtty' WHERE `item_id`='$item_id' AND `member_id`='$mid' AND `current`=1");

		if($this->db->affected_rows()==0)
			$this->db->query("INSERT INTO `orders` (`item_id`,`quantity`,`member_id`,`current`) VALUES('$item_id','$order_qtty','$mid',1)");
	}

	function compile_orders(){
		$u=$_SESSION['user'];
		$q= $this->db->query("SELECT stock.*,stock.quantity AS `stock_qtty`,`other_orders`.*,my_orders.order_id as `my_id`,my_orders.quantity as `my_quantity`  FROM (SELECT stock.quantity AS `stock`,item.item_name,item.wholesale_cost,item.unit_weight,item.unit_weight_unit,item.wholesale_qtty,SUM(orders.quantity) AS `order_qtty`,GROUP_CONCAT(`member_name`) AS `member_names`,orders.item_id FROM `orders` LEFT JOIN `item` USING(`item_id`) LEFT JOIN `members` USING(`member_id`) LEFT JOIN `stock` USING(`item_id`) WHERE orders.current=1 GROUP BY orders.item_id) AS `other_orders` LEFT JOIN (SELECT * FROM `orders` WHERE `current`=1 AND `member_id`={$u->member_id}) AS `my_orders` USING(`item_id`) LEFT JOIN `stock` USING(`item_id`)");
		return $q->result();
	}

	function member_orders(){
		$q= $this->db->query("SELECT * FROM `orders` LEFT JOIN `item` USING(`item_id`) WHERE `current`=1 AND `member_id`='".$this->auth->get_udata('member_id')."'");
		foreach($q->result() as $result){
			$r[$result->item_id]=$result;
		}
		return $r;
	}

	function all_orders(){
		$q= $this->db->query("SELECT *,SUM(`quantity`) AS `quantity` FROM `orders` LEFT JOIN `item USING(`item_id`)` WHERE `current`=1 GROUP BY `item_id`");
		foreach($q->result() as $result){
			$r[$result->item_id]=$result;
		}
		return $r;
	}

	function get_popular_items(){
		$q= $this->db->query("SELECT other_orders.*,my_orders.order_id as `my_id`,my_orders.quantity as `my_quantity`  FROM (SELECT `item_id`,`item_name`,`unit_weight`,`unit_weight_unit`,`wholesale_cost`,`wholesale_qtty`, SUM(orders.quantity) AS `popularity` FROM `orders` LEFT JOIN `item` USING(`item_id`) WHERE `unavailable`=0 GROUP BY `item_id`) AS `other_orders` LEFT JOIN (SELECT * FROM `orders` WHERE `current`=1 AND `member_id`={$this->auth->get_udata("member_id")}) AS `my_orders` USING(`item_id`) ORDER BY `popularity` DESC LIMIT 20");
		return $q->result();
	}

	function get_current_stock(){
		$q= $this->db->query("SELECT * FROM `stock` LEFT JOIN `item` USING(`item_id`)");
		foreach($q->result() as $result){
			$r[$result->item_id]=$result;
		}
		return $r;
	}
}
?>
