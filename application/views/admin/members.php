<div class="container">
	<h1>Members</h1>
	<a href="/food/admin/members/add">Add member</a> | <a href="/food/admin/members/email">Email members</a>
	

	<?foreach($members as $member):?>
	<div class="row">
		<div class="span1"><?=$member->member_id;?></div>
		<div class="span3">
			<a href="/food/admin/members/edit/<?=$member->member_id;?>"><?=$member->member_name;?></a>
		</div>
		<div class="span1"><?=$member->email;?></div>
		<!--<li style="display:inline;"><?=$member->permissions;?>-->
	</div>
	<?endforeach;?>
	</ul>