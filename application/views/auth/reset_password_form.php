<h1>Reset Password</h1>
<p>Enter your email address in the form below and we'll send you a new password.</p>

<?= form_open('login/reset_password'); ?>
<p>
  <?php
	echo form_label('Email address:','email');
	echo form_input('email',($email?urldecode($email):set_value('email')),'email');
  ?>
</p>

<?
echo form_submit('submit','Reset');
echo form_close();
?>

<div class="errors"> <?php if(isset($errors)){echo $errors;} ?></div>