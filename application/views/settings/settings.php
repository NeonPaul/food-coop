<div class="container">
	<h1>Settings</h1>
	<?echo validation_errors();?>
	<?echo form_open("settings");?>
	<p>
		<?echo form_label('Name:','member_name');?>
		<?echo form_input('member_name',$this->auth->get_udata('member_name'),'id=member_name');?>
	</p>
	<p>
		<?echo form_label('Email address:','email');?>
		<?echo form_input('email',$this->auth->get_udata('email'),'id=email');?>
	</p>
	<p>
		<?echo form_label('Password:','password');?>
		<?echo form_password('password','','id=password');?>
	</p>
	
	<p>
		<?echo form_submit('update','Update','class="btn default"');?>
	</p>
	<?
	echo form_close();
	?>
</div>