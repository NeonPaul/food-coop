<div class="container">
	<h1>Orders</h1>

	<?=form_open("admin/orders");?>
		<?if($order_phase=="open"):?>
		<?=form_submit("close_orders","Close orders","class=btn");?>
		<p>You need to close orders to be able to edit them.</p>
		<?endif;?>
		<?if($order_phase=="closed"):?>
		<?=form_submit("open_orders","Open orders",'class="btn"');?>
		<?=form_submit("place_orders","Place orders",'class="btn"');?>
		<p>Only click the "Place orders" button <strong>after</strong> you have placed the order with infinity.</p>
		<?endif;?>
	</form>


	<div class="row th">
		<div class="span1">&nbsp;</div>
		<div class="span1">Id</div>
		<div class="span4">Name</div>
		<div class="span1">W. Qtty</div>
		<div class="span1">O. Qtty</div>
		<div class="span1">Current Stock</div>
		<div class="span1">Leftover</div>
		<div class="span1">Leftover price</div>
		<div class="span1">Order price</div>
		<div class="span3">Members</div>
	</div>
	<?
	$stotal=0;
	$total=0;
	foreach($orders as $r):
	$dnom=($r->splittable?2:1);


	if($r->wholesale_qtty){
		$o=$r->order_qtty-$r->stock; // Order qtty less stock
		if($o<0) $o=0;				// Qtty we actually need to buy in
		//echo "{$r->item_id}: \$left=$o % ({$r->wholesale_qtty}/$dnom);<br>";
		
		$left=$o % ($r->wholesale_qtty/$dnom);
		$left=($left==0? 0 : ($r->wholesale_qtty/$dnom)-$left);
		
		$price=unit_price($r->wholesale_cost,$r->wholesale_qtty,$left,false,false);
		$stotal+=$price;
		$price=price_format($price);
	}else{
		$o=0;
		$left=$r->order_qtty;
		$price="";
	}

$q=ceil($dnom*$o/$r->wholesale_qtty)/$dnom;
$total+=$q*$r->wholesale_cost/100;


	?>
	<hr>
	<div class="row">
		<div class="span1"><?=$q;?>x </div>
		<div class="span1"><?=$r->item_id;?></div>
		<div class="span4"><?=$r->item_name;?> <?=$r->unit_weight.$r->unit_weight_unit;?></div>
		<div class="span1"><?=$r->wholesale_qtty;?></div>
		<div class="span1"><?=$r->order_qtty;?>&nbsp;</div>
		<div class="span1"><?=$r->stock;?>&nbsp;</div>
		<div class="span1"><?=$left;?>&nbsp;</div>
		<div class="span1"><?=$price;?>&nbsp;</div>
		<div class="span1"><?=price_format($q*$r->wholesale_cost/100);?>&nbsp;</div>
		<div class="span4">
		<?$members=explode(",",$r->member_names);
		$ids=explode(",",$r->member_ids);
		$qttys=explode(",",$r->order_quantities);
		$cdates=explode(",",$r->dates_created);
		foreach($members as $k=>$member){?>
			<div class="row">
				<div class="span1"><a title="<?=$cdates[$k];?>"><?=$member;?></a></div>
				<div class="span3">
					<?if($order_phase=="closed"):?>
						<?=form_open("admin/orders/update",array("style"=>"display:inline")).form_hidden("member_id",$ids[$k]).form_hidden("item_id",$r->item_id);?>
							<input type="number" name='qtty' value="<?=$qttys[$k];?>">
							<?=form_submit('go',"Go",'class="btn"');?>
						</form>
					<?else:?>
						x <?=$qttys[$k];?>
					<?endif;?>
				</div>
			</div>
		<?}?>
		</div>
		<?if($order_phase=="closed"):?>
		<div class="span1">[<a href="/food/admin/orders/delete/<?=$r->item_id;?>" onclick="return confirm('Delete ALL occurences of this item?')">X</a>]</div>
		<?endif;?>
	</div>
	<?endforeach;?>
	<hr>
	<div class="row alert-message">
		<div class="span1">Totals</div>
		<div class="span1 offset9"><?=$stotal;?></div>
		<div class="span1"><?=$total;?></div>
	</div>
</div>