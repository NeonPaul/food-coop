<?=form_open('admin/stock',array('name'=>'new_item','id'=>'new_item'));?>
  <input type="hidden" name="item_id">
  <fieldset id="item_search"><input type="text" name="q"></fieldset>
  x<input type="number" name="quantity" size="2" style="width:50px;" value=1>
  <input type="submit" name="add" value="Add item">
 </form>
<hr>
<?=form_open("admin/stock");?>
<div>
<?foreach($stock AS $r):?>
		<div style="width:400px;padding:2px;margin:2px;clear:left;float:left;background:#eee;"><?=$r->item_id;?> <?=$r->item_name;?> <?=$r->unit_weight.$r->unit_weight_unit;?></div>
		<input style="float:left;display:block;" type="number" name="stock[<?=$r->item_id;?>]" value="<?=$r->quantity;?>">
		<br>
<?endforeach;?>
</div>
	<input type="submit" value="Update" style="position:fixed;bottom:1px;left:605px;">
	</form>

