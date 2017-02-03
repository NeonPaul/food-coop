<div class="container">
	<h1>Orders</h1>

<?
  $member_id="";
  $r = null;
  $price=0;
  foreach($orders as $r):
    if($r->member_id!==$member_id):
      if($member_id!==""):
        ?>
        <tr><td colspan=5 align=right><b><?=price_format($price);?></b>
        <? if($r->deposit == 0): ?>
          + <b>£5 deposit</b> = <b><?=price_format($price + 5);?></b>
        <? endif; ?>
        </td></tr></table></div><hr>
      <?endif;?>
      <div class="order">
	<h3><?=$r->member_name;?></h3>
	<h4><?=$r->email;?></h4>
	<table>
          <tr>
		<th>Id</th>
		<th>Name</th>
		<th>Weight</th>
		<th>Qtty</th>
		<th>Cost</th>
	  </tr>
	  <?
            $member_id=$r->member_id;
	    $price=0;
	endif;?>

	<tr>
		<td><?=$r->item_id;?></td>
		<td><?=$r->item_name;?></td>
		<td><?=$r->unit_weight.$r->unit_weight_unit;?></td>
		<td><?=$r->order_qtty;?></td>
		<td><?=unit_price($r->wholesale_cost,$r->wholesale_qtty,$r->order_qtty);?></td>
	</tr>
	<?
	$price+=unit_price($r->wholesale_cost,$r->wholesale_qtty,$r->order_qtty,false,false);
	endforeach;?>
	<tr><td colspan=5 align=right><b><?=price_format($price);?></b>
  <? if($r && ($r->deposit == 0)): ?>
        + <b>£5 deposit</b> = <b><?=price_format($price + 5);?></b>
  <? endif; ?>
  </td></tr></table></div>

	<?if($phase=="collection"):?> 
	<?=form_open("admin/orders");?>
	<?=form_submit("open_orders","Reopen current orders","class=btn")?>
	<?=form_submit("reset_orders","Reset to blank","onclick='return confirm(\"Are you sure? This will update stock and blank all orders. It cannot be undone.\")' class='btn danger'");?>
	</form>
	<?endif;?>
</div>
