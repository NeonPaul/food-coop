<div class="container">
	<h1>Send email</h1>
	<?=form_open();?>
	<b>To:</b> <?=$to;?><br>
	<b>From:</b> food@greenactionsouthampton.co.uk<br>
	<b>Reply-to:</b> <?=$this->auth->get_udata("email");?><br>
	<b>Subject:</b> <?=form_input("subject",$subject);?><br>
	<b>Body:</b><br>
	<?=form_textarea("body",$body);?><br>
	<?=form_submit("send","Send","class=btn");?>

	<div>
	<h4>Tags:</h4>
	<ul>
	<li>&lt;member_name&gt; - Member's full name
	<li>&lt;reset_password_url&gt; - Link to allow the member to reset their password
	<ul>
	</div>

	<?=form_close();?>
</div>