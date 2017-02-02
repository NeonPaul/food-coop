<div class="container-fluid">	<?$this->load->view("orders/orders_box");?>	<div class="content">
<h1>All Orders</h1>
<p>The full list of what you and everyone else have ordered this month.</p>
<p>Because we're buying wholesale we have to get things in specific quantities - which means if there isn't enough demand for a particular product we might not be able to get it at all.</p>
<p>In short, ask for a higher quantity and you're more likely to receive!</p>

<table>
<tr>
	<th>Id</th>
	<th>Item name</th>
	<th>Price (each)</th>
	<th colspan=2>Minimum Order Threshold</th>
	<th>Your order</th>
</tr>
<?
foreach($results as $r):
if(!($r->my_id)){
	$r->my_id=NULL;
	$r->my_quantity=0;
}
$buy_prob=$r->stock_qtty>0?1:min(1,max($r->order_qtty-$r->stock_qtty,0)/$r->wholesale_qtty);

?>
<tr>
	<td><?=$r->item_id;?></td>
	<td><?=$r->item_name." (".$r->unit_weight.$r->unit_weight_unit.")";?></td>
	<td><?=unit_price($r->wholesale_cost,$r->wholesale_qtty);?></td>
	<td><?=floor($buy_prob*100);?>%</td>
	<td style="font-size:75%;"><?=$r->order_qtty;?> requested<br><?=$r->wholesale_qtty;?> min order<br><?=$r->stock_qtty?$r->stock_qtty:0;?> in stock</td>
	<td>
		<?=form_open("request/update_add",array('style'=>"display:inline"));?>
		<input type="hidden" name="return" value="request/all_orders">
		<input type="hidden" name="item_id" value="<?=$r->item_id;?>">
		<input type="number" name="quantity" min="0" size=2 style="width:50px;" value="<?=$r->my_quantity;?>">
		<input type="submit" value="Update" class="btn">
		</form>
	</td>
</tr>
<?
endforeach;
?>
</table>
</div>