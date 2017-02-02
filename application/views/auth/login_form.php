<h1>Log in</h1>
<p>Sign in with your email address and password to get started.</p>
<p><a href="/food/login/reset_password">Forgotten your password?</a> We've got it covered.</p>
<?= form_open('login'); ?>
<p>
  <?php
	echo form_label('Email address:','email');
	echo form_input('email',set_value('email'),'email');
  ?>
</p>
<p>
  <?php
	echo form_label('Password:','password');
	echo form_password('password','','password');
  ?>
</p>
<p>
  <?php
	echo form_label('Remember me:','cookie');
	echo form_checkbox(array('name'=>'cookie','id'=>'cookie','value'=>1));
  ?>
</p>

<?
echo form_submit('submit','Login');
echo form_close();
?>

<div class="errors"> <?php echo validation_errors().$loginfail; ?></div>