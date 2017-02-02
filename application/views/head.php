<!DOCTYPE html>
<html lang="en">
<head>
	<title>Green Action Food Co-op</title>

<?if(isset($stylesheets))
foreach($stylesheets as $s):?>
<link rel="stylesheet" href="<?=$s;?>">
<?endforeach;?>

<link rel="stylesheet" href="/food/bootstrap.min.css">
<link rel="stylesheet" href="/food/main.css">

</head>
<body>
    <div class="topbar">
      <div class="fill">
        <div class="container">
          <a class="brand" href="/food">Food Co-op</a>
          <ul class="nav">
			<?if($this->auth->logged_in()):?>
			<li><a href="/food/request">Your Order</a>
			<li><a href="/food/settings">User Settings</a>
			<?endif;?>
			<?if($this->auth->get_udata("permissions")>0):?>
			<li><a href="/food/admin">Admin</a>
			<?endif;?>
          </ul>
		  <p class="pull-right">
			<?if(!$this->auth->logged_in()):?>
			<a href="/food/login">Log in</a>
			<?else:?>
			Logged in as <b><?=$this->auth->get_udata("member_name");?></b>. 
			<a href="/food/login/logout">Log out</a>
			<?endif;?>
		  </p>
		  </div>
        </div>
      </div>
    </div>