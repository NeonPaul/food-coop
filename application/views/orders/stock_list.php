<div class="container-fluid">
	<?$this->load->view("orders/orders_box");?>
	
	<div class="content">
		<h1>Current Stock</h1>
		<p>The items we currently have in stock - if you ask for some of these you'll probably get them!</p>

		<table>
		<tr>
			<th>Id</th>
			<th>Item name</th>
			<th>Quantity available</th>
			<th>Price (each)</th>
			<th>Your order</th>
		</tr>
		<?
		$total=0;
		foreach($stock as $iid=>$r):

		//$total+=unit_price($r->wholesale_cost,$r->wholesale_qtty,$user_orders[$iid]->quantity,false,false);
		?>
		<tr>
			<td><?=$r->item_id;?></td>
			<td><?=$r->item_name." (".$r->unit_weight.$r->unit_weight_unit.")";?></td>
			<td><?=$r->quantity;?></td>
			<td><?=unit_price($r->wholesale_cost,$r->wholesale_qtty);?></td>
			<td>
				<?=form_open("request/update_add",array('style'=>"display:inline"));?>
				<input type="hidden" name="return" value="request/current_stock">
				<input type="hidden" name="item_id" value="<?=$r->item_id;?>">
				<input type="number" name="quantity" min="0" value="<?=isset($user_orders[$iid])?$user_orders[$iid]->quantity:0;?>">
				<input type="submit" value="Update" class="btn">
				</form>
			</td>
		</tr>
		<?
		endforeach;
		//echo price_format($total);
		?>
		</table>
	</div>
</div>