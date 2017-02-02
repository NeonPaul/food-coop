<div class="container">
<h1>Admin</h1>

<ul>
<?if($this->auth->permissions(ADMIN_ORDERS)):?><li><?=anchor("admin/orders","Orders");?><?endif;?>
<?if($this->auth->permissions(ADMIN_MEMBERS)):?><li><?=anchor("admin/members","Members");?><?endif;?>
<?if($this->auth->permissions(ADMIN_STOCK)):?><li><?=anchor("admin/stock","Stock");?><?endif;?>
<?if($this->auth->permissions(ADMIN_CATALOGUE)):?><li><?=anchor("admin/catalogue","Catalogue");?><?endif;?>
</ul>

</div>