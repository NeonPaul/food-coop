<?
class Item_model extends CI_Model{
	
	function exists($id){
		$q=$this->db->query("SELECT * FROM `item` WHERE `item_id`='$id' AND `unavailable`=0");
		return $q->num_rows()>0;
	}
	
	function search($query){
		$q=mysql_real_escape_string($query);
		$q=$this->db->query("SELECT * FROM `item` WHERE (`item_id` LIKE '%$q%' OR `item_name` LIKE '%$q%') AND `unavailable`=0 LIMIT 10");
		$r=array();
		foreach($q->result_array() AS $row){
			$row['unit_price']=unit_price_array($row);
			$r[]=$row;
		}
		return $r;
	}
}
?>