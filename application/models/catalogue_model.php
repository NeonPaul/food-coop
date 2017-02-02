<?php
class Catalogue_model extends CI_Model{
	function format_row($row){
		$result=array();
		
		
		$result['id']=$row[1];
		$result['price']=($row[8]+$row[10])*100;
		preg_match("/(?:([0-9]+)x)?([0-9.]+)([a-z]*)/",$row[2],$matches);
		if(!$matches)
			return null;
			
			
		$result['qtty']=($matches[1]?$matches[1]:1);
		$result['weight']=(float) $matches[2];
		$result['unit']=$matches[3];
		$result['brand']=$row[6];
		$result['name']=$row[4];
		$result['organic']=$row[3];
	
	
		
	 
		if((floor($result['weight'])!=$result['weight'])&&($result['unit']=="kg")){
			$result['weight']*=1000;
			$result['unit']="g";
		}
		
		return $result;
	}
	
	function search($query,$filter_imported=true){
		// Open the file
		$this->load->model("catalogue_model");
		$catalogue=$this->config->item("data_path").$this->config->item("catalogue");
		
		$catalogue = fopen($catalogue,"r");
		fgetcsv($catalogue); // skip first line
		
		$results = array();
		// Begin filling array
		while(($row = fgetcsv($catalogue))!==FALSE){
			if(stristr($row[1]." ".$row[6]." ".$row[4],$query)&&(((int) $row[1])<999990)){
				if($r=self::format_row($row)){
					$results[$r['id']]=$r;
				}
			}
		}
		$ids = array_keys($results);
		$ids = implode("' OR `item_id`='",$ids);
		$sql = "SELECT * FROM `item` WHERE `item_id`='$ids'";
		$q = $this->db->query($sql);
		if($filter_imported)
			foreach($q->result() as $r)
				unset($results[$r->item_id]);
		return $results;
	}
	
	function import($items){
		foreach($items as $id=>$item){
			$sql="INSERT INTO `item` (`item_id`,`item_name`,`unit_weight`,`unit_weight_unit`,`wholesale_qtty`,`wholesale_cost`)"
				."VALUES('$id','".$item['name']." (".$item['brand'].")','".$item['weight']."','".$item['unit']."','".$item['qtty']."','".$item['price']."')";
			$this->db->query($sql);
		}
	}
	
	function verify($file){
		$c=fopen($file,"r");
		$line=str_replace('"', '', rtrim(fgets($c)));
		$matchuq=',code,concatsize,organic?,concatprod,RRP?,brand,j,price,,,#VALUE!,case size,unit size,unit,VAT code';

		return ($line==$matchuq);
	}
	
	function update(){
		$sql="SELECT `item_id` FROM `item`";
		$q=$this->db->query($sql);
		foreach($q->result_array() as $r){
			$item_ids[]=$r['item_id'];
		}
		
		$this->load->model("catalogue_model");
		$catalogue=$this->config->item("data_path").$this->config->item("catalogue");
		
		$catalogue = fopen($catalogue,"r");
		fgetcsv($catalogue); // skip first line
		
		$success=0;
		$errorc=0;
		$errormsg=array();
		while(($row = fgetcsv($catalogue))!==FALSE){
			if(in_array($row[1],$item_ids)){
				if($r=self::format_row($row)){
					$sql= "UPDATE `item` SET `wholesale_cost`='$r[price]',`wholesale_qtty`='$r[qtty]',`unit_weight`='$r[weight]',`unit_weight_unit`='$r[unit]' WHERE `item_id`='$r[id]'";
					mysql_query($sql);
					if($m=mysql_error()){
						$errorc++;
						$errormsg[]=$m;
					}else{
						$success++;
					}
				}
			}
		}
		return array("success_count"=>$success,"error_count"=>$errorc,"errors"=>$errormsg);
	}	
}?>
