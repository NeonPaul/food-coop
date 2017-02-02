<div class="container">
	<h1>Add Member</h1>
	<?=validation_errors();?>
	<?=form_open();?>
		<p>Name: <?=form_input("member_name");?></p>
		<p>Email: <?=form_input("email");?></p>
		<p>Permissions: <?=form_hidden("permissions[]",0);?>
		<ul>
		<li>Members <?=form_checkbox("permissions[]",ADMIN_MEMBERS);?>
		<li>Orders <?=form_checkbox("permissions[]",ADMIN_ORDERS);?>
		<li>Stock <?=form_checkbox("permissions[]",ADMIN_STOCK);?>
		<li>Catalogue <?=form_checkbox("permissions[]",ADMIN_CATALOGUE);?>
		</ul></p>
		<p>Notes: <br><?=form_textarea("notes");?></p>
		<p><?=form_submit("add","Add","class='btn'");?></p>
	<?=form_close();?>
</div>