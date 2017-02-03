<!DOCTYPE html>
<html lang="en">
<head>
	<title>Green Action Food Co-op</title>

<?if(isset($stylesheets))
foreach($stylesheets as $s):?>
<link rel="stylesheet" href="<?=$s;?>">
<?endforeach;?>

<base href="<?=$this->config->item('base_url');?>">
<link rel="stylesheet" href="bootstrap.min.css">
<link rel="stylesheet" href="main.css">

</head>
<body>
    <div class="topbar">
      <div class="fill">
        <div class="container">
          <a class="brand" href=".">Food Co-op</a>
          <ul class="nav">
			<?if($this->auth->logged_in()):?>
			<li><a href="request">Your Order</a>
			<li><a href="settings">User Settings</a>
			<?endif;?>
			<?if($this->auth->get_udata("permissions")>0):?>
			<li><a href="admin">Admin</a>
			<?endif;?>
          </ul>
		  <p class="pull-right">
			<?if(!$this->auth->logged_in()):?>
			<a href="login">Log in</a>
			<?else:?>
			Logged in as <b><?=$this->auth->get_udata("member_name");?></b>. 
			<a href="login/logout">Log out</a>
			<?endif;?>
		  </p>
		  </div>
        </div>
      </div>
    </div>
