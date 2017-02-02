<?
class Orders_model extends CI_Model{
	function compile_current_orders(){
		$q= $this->db->query("SELECT stock.quantity AS `stock`,item.*,SUM(orders.quantity) AS `order_qtty`,GROUP_CONCAT(`member_name`) AS `member_names`,GROUP_CONCAT(`member_id`) AS `member_ids`,GROUP_CONCAT(`order_id`) AS `order_ids`,GROUP_CONCAT(orders.quantity) AS `order_quantities`,GROUP_CONCAT(orders.date_created) AS `dates_created`,orders.item_id FROM `orders` LEFT JOIN `item` USING(`item_id`) LEFT JOIN `members` USING(`member_id`) LEFT JOIN `stock` USING(`item_id`) WHERE orders.current=1 GROUP BY orders.item_id");
		return $q->result();
	}
	
	function current_orders_by_member(){
		$q= $this->db->query("SELECT members.*,item.*,orders.item_id,orders.quantity AS `order_qtty` FROM `orders` LEFT JOIN `item` USING(`item_id`) LEFT JOIN `members` USING(`member_id`) WHERE orders.current=1 ORDER BY `member_id`");
		return $q->result();
	}
	
	function delete_from_current($item_id,$user_id=null){
		$user="";
		if(!is_null($user_id)){
			$user=" AND `member_id`='$user_id'";
		}
		
			$this->db->query("DELETE FROM `orders` WHERE `item_id`=$item_id AND `current`=1$user");
	}
	
	function update_qtty($item_id,$user_id,$qtty)
	{
		if($qtty<1){
			self::delete_from_current($item_id,$user_id);
		}else{
			$this->db->query("UPDATE `orders` SET `quantity`='$qtty' WHERE `item_id`='$item_id' AND `member_id`='$user_id'");
		}
	}
	
	function update_stock(){
		$stock=array();
		$orders=array();
		$q = $this->db->query("SELECT `item_id`,IF(ISNULL(stock.quantity),0,stock.quantity) AS `stock_quantity`,SUM(orders.quantity) as `order_quantity`,`wholesale_qtty` FROM `orders` LEFT JOIN `stock` USING(`item_id`) LEFT JOIN `item` USING(`item_id`) WHERE `current`=1 GROUP BY `item_id`");
		foreach($q->result_array() as $r){
			$stock[$r['item_id']]=$r['stock_quantity']-$r['order_quantity'];
			if($stock[$r['item_id']]<0){
				$orders[$r['item_id']]=-$stock[$r['item_id']];
				$stock[$r['item_id']]=0;
			}else{
				$orders[$r['item_id']]=0;
			}
			$ileft=$orders[$r['item_id']] % $r['wholesale_qtty'];
			
			$stock[$r['item_id']]+=($ileft==0? 0 : ($r['wholesale_qtty'] - $ileft));
			
			if($stock[$r['item_id']]==0){
				$sql="DELETE FROM `stock` WHERE `item_id`='$r[item_id]'";
			}else{
				$sql="INSERT INTO `stock` (`item_id`,`quantity`) VALUES('$r[item_id]','".$stock[$r['item_id']]."') ON DUPLICATE KEY UPDATE `quantity`='".$stock[$r['item_id']]."'";
			}
			$this->db->query($sql);
		}
		
	}
	
	function archive_orders(){
			$sql="UPDATE `orders` SET `current`=0 WHERE `current`=1";
			$this->db->query($sql);
	}
}
?>