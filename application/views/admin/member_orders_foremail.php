<div class="container">
	<h1>Orders</h1>

	<?
	$member_id="";
	$n=0;
	$nr=$orders[0];
	while($r=$nr):
 
	$nr=(++$n<sizeof($orders)?$orders[$n]:null);
	
	?>
	
	

	<?if($r->member_id!==$member_id):
			$str="";
		
			
		
		$str.="<p>Hello ".$r->member_name."!</p>".
		"<p>Here's your order summary for this month:</p>".
		"<table>".
	"<tr>".
		"<th>Id</th>".
		"<th>Name</th>".
		"<th>Weight</th>".
		"<th>Qtty</th>".
		"<th>Cost</th>".
	"</tr>";
	
	$price=0;
	endif;

	$str.="<tr>".
		"<td>".$r->item_id."</td>".
		"<td>".$r->item_name."</td>".
		"<td>".$r->unit_weight.$r->unit_weight_unit."</td>".
		"<td>".$r->order_qtty."</td>".
		"<td>".unit_price($r->wholesale_cost,$r->wholesale_qtty,$r->order_qtty)."</td>".
	"</tr>";
	
	
	$price+=unit_price($r->wholesale_cost,$r->wholesale_qtty,$r->order_qtty,false,false);

	
	if($r->member_id!==$nr->member_id){
				$str.="<tr><td colspan=5 align=right><b>".price_format($price)."</b></td></tr></table>".
			"<p>Please note the following changes we've had to make:</p>".
			"<p>What time will you be available on Friday to collect your order?".
			"</p>";
			
			
			echo "<h4><a href=\"mailto:".$r->email."?subject=Your+food+co-op+order\">Email</a></h4>";
			echo "<div contenteditable style='border:1px solid black;'>".$str."</div>";
			echo "<hr>";
	}
	
	$member_id=$r->member_id;
	
	endwhile;
	?>
	
</div>