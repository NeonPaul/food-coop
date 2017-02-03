<div class="container">
	<hi>Edit Member</h1>
	<?=validation_errors();?>
	<?=form_open();?>
		<?=form_hidden("member_id",$member->member_id);?>
		<p>Id: <?=$member->member_id;?></p>
		<p>Name: <?=form_input("member_name",$member->member_name);?></p>
		<p>Email: <?=form_input("email",$member->email);?></p>
		<p>Active: <?=form_checkbox("active",1,$member->active);?></p>
		<p>Agrees to constitution: <?=form_checkbox("constitution",1,$member->constitution);?></p>
		<p>Deposit ammount: <?=form_input("deposit",$member->deposit);?></p>
		<p>Permissions: <?=form_hidden("permissions[]",0);?>
		<ul>
		<li>Members <?=form_checkbox("permissions[]",ADMIN_MEMBERS,!!($member->permissions&ADMIN_MEMBERS),($member->member_id==$this->auth->get_udata("member_id")?"DISABLED":""));?>
		<li>Orders <?=form_checkbox("permissions[]",ADMIN_ORDERS,!!($member->permissions&ADMIN_ORDERS));?>
		<li>Stock <?=form_checkbox("permissions[]",ADMIN_STOCK,!!($member->permissions&ADMIN_STOCK));?>
		<li>Catalogue <?=form_checkbox("permissions[]",ADMIN_CATALOGUE,!!($member->permissions&ADMIN_CATALOGUE));?>
		</ul></p>
		<p>Notes: <br><?=form_textarea("notes",$member->notes);?></p>
		<p><?=form_submit("edit","Edit","class=btn");?></p>
	<?=form_close();?>
	<p><a href="admin/members/email/new_member/<?=$member->member_id;?>">Send new user welcome email.</a></p>
</div>	
