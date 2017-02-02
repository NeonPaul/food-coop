<div class="container-fluid">
	<?$this->load->view("orders/orders_box");?>
	<div class="content">
		<H1>Item Search</h1>
		<p>Select an item and a quantity to add it to your shopping list.</p>
		
		<hr>
	
		<?foreach($search as $r):?>
		<div class="row">
			<div class="span2"><?=$r['item_id'];?></div>
			<div class="span5"><?=$r['item_name'];?> (<?=$r['unit_weight'].$r['unit_weight_unit']?>)</div>
			<div class="span1"><?=price_format($r['unit_price']);?></div>
			<div class="span3"><?=form_open('request/update_add');?>
			<?=form_hidden('item_id',$r['item_id']);?>
			<input type="number" name='quantity' value=0>
			<?=form_submit('submit',"Add","class='btn'");?>
			</form></div>
		</div>
		<?endforeach;?>
	</div>
</div>