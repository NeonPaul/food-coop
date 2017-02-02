<div class="container-fluid">
	<?$this->load->view("orders/orders_box");?>
	
<div class="content">

	<h2>Popular Items</h2>
	<p>The items that our members buy the most.</p>

	<table>
	<tr>
		<th>Id</th>
		<th>Item name</th>
		<th>Price (each)</th>
		<th>Your order</th>
	</tr>
	<?
	foreach($results as $r):
	if(!($r->my_id)){
		$r->my_id=NULL;
		$r->my_quantity=0;
	}
	?>
	<tr>
		<td><?=$r->item_id;?></td>
		<td><?=$r->item_name." (".$r->unit_weight.$r->unit_weight_unit.")";?></td>
		<td><?=unit_price($r->wholesale_cost,$r->wholesale_qtty);?></td>
		<td>
			<?=form_open("request/update_add",array('style'=>"display:inline"));?>
			<input type="hidden" name="return" value="request/popular_items">
			<input type="hidden" name="item_id" value="<?=$r->item_id;?>">
			<input type="number" name="quantity" min="0" size=2 value="<?=$r->my_quantity;?>">
			<input type="submit" value="Update">
			</form>
		</td>
	</tr>
	<?
	endforeach;
	?>
	</table>
</div>