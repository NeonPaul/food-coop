<div class="container-fluid">
	<?$this->load->view("orders/orders_box");?>
	
	<div class="content">
		<h1>Catalogue Search</h1>
		<p>Some items aren't in our database yet because nobody has wanted to order them before. We can pull them from the Infinity catalogue, though please only use this option if you can't find what you're looking for in the normal search.</p>
		<p>Select the items you'd like to import from the catalogue and hit "Add to order" at the bottom</p>
		<hr>
		<?
		echo form_open("request/catalogue_import");
		foreach($results as $r){
		$brand=$r['brand'];
		?>
		<div class="row">
			<div class="span1"><?=$r['id'];?></div>
			<div class="span8"><?=$r['name'].($brand?" ($brand)":"");?> -
			<?=$r['weight'].$r['unit'];?></div>
			<div class="span2"><?=unit_price($r['price'],$r['qtty'],1,0,1);?> each</div>
			<div class="span1"><input type="number" name="qtty[<?=$r['id'];?>]" value="0"></div>
		</div>
		<?}?>
		<div class="row">
			<div class="offset10 span2">
				<input class="btn primary" type="submit" value="Add to order">
			</div>
		</div>
		</form>
	</div>
</div>