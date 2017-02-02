<div class="container">
	<h1>Change Password</h1>
	<?echo validation_errors();?>
	<?echo form_open("settings/change_password");?>
	<p>
		Great, you're all logged in! Just make sure to change your password to something that's a bit easier to remember:
	</p>
	<p>
		<?echo form_label('New password:','password');?>
		<?echo form_password('password','','id=password');?>
	</p>

	<p>
		<?echo form_submit('update','Change',"class='btn'");?>
	</p>
	<?
	echo form_close();
	?>
</div>