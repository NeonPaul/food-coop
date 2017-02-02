<div class="container-fluid">
	<?$this->load->view("orders/orders_box");?>

	<div class="content">
		<h1>Place an order</h1>
		<p>Select items that you'd like to buy and their quantities using the form below. Use the links on the left for our suggestions.</p>

		<div id="place_order_interface">
			<?=form_open('request/search_items',array('name'=>'new_item','id'=>'new_item'));?>
				<input type="hidden" name="item_id">
				<span id="item_search">
					<input type="text" name="q">
				</span>
				<span id="quantity" style="display:none;">
					x<input type="number" name="quantity" value="1" size="2" style="width:50px;">
				</span>
				<input type="submit" value="Search" name="submit_b" class="btn">
			</form>
			<div id="request_contents">
			<?php foreach($request_items as $r):
				echo form_open('request/update',array('class'=>'request_contents_row'));
			?><input type="hidden" name="request_id" value="<?=$r->order_id;?>">
			<input type="hidden" name="item_id" value="<?=$r->item_id;?>">
			<span class="request_contents_item_details"><?=$r->item_id." - ".$r->item_name." ".$r->unit_weight.$r->unit_weight_unit." - £".number_format($r->price/100,2);?></span>
			<input type="number" name="quantity" value="<?=$r->quantity;?>"  size="2" style="width:50px;">
			<span class="request_contents_item_subtotal"><?=unit_price($r->wholesale_cost,$r->wholesale_qtty,$r->quantity,0,1);?></span>
			
			<input type="submit" value="Update" class="btn">
			<input type="button" class="btn danger" value="X" name="deleteButton">
		   </form>
		  <? endforeach; ?>
		 </div><!-- /request_contents -->
		 <span id="request_total"><?=$total;?> (<?=$weight;?>kg)</span>
		</div>
		</div>