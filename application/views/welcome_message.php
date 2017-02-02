<div class="container">
	<div class="hero-unit">
		<?if($logged_in) :?>
		<h1>Hullo, <?=$this->auth->get_udata("member_name");?>!</h1>
		<p>Welcome back! To start your shopping list, head to 
the <a href="<?=$this->config->item('base_url');?>request">Your 
Order</a> 
page.</p>
		<?else:?>
		<h1>Ahoy!</h1>
		<p>Welcome to the Southampton University Food Co&ouml;p!</p>
		<?endif;?>

		<p>This website is a prototype, hopefuly we'll be rolling out a better version in the future. If you want to get involved with the web development team or have any queries, please email <a href="mailto:neonpaul@gmail.com?subject=Food%20Co-op">neonpaul@gmail.com</a> or tweet <a href="http://www.twitter.com/neonpaul">@neonpaul</a>.</p>
	</div>
</div>
