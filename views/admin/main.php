
<table class="table table-striped">
	<thead>
		<tr>
			<th><a href="/admin/?page=<?=$page?>&sort=id&type=<?=($sortType == 'asc' ? 'desc' : 'asc')?>">id<?=($sort == 'id' ? '&nbsp;<span class="glyphicon glyphicon-chevron-'.($sortType == 'asc' ? 'down' : 'up').'"></span>' : '')?></a></th>
			<th><a href="/admin/?page=<?=$page?>&sort=users.login&type=<?=($sortType == 'asc' ? 'desc' : 'asc')?>">user login<?=($sort == 'users.login' ? '&nbsp;<span class="glyphicon glyphicon-chevron-'.($sortType == 'asc' ? 'down' : 'up').'"></span>' : '')?></a></th>
			<th><a href="/admin/?page=<?=$page?>&sort=users.email&type=<?=($sortType == 'asc' ? 'desc' : 'asc')?>">user email<?=($sort == 'users.email' ? '&nbsp;<span class="glyphicon glyphicon-chevron-'.($sortType == 'asc' ? 'down' : 'up').'"></span>' : '')?></a></th>
			<th><a href="/admin/?page=<?=$page?>&sort=status&type=<?=($sortType == 'asc' ? 'desc' : 'asc')?>">status<?=($sort == 'status' ? '&nbsp;<span class="glyphicon glyphicon-chevron-'.($sortType == 'asc' ? 'down' : 'up').'"></span>' : '')?></a></th>
			<th>text</th>
			<th>&nbsp;</th>
		</tr>
	</thead>
	<tbody>
	<?
	if (!empty($tasks))
	{
		foreach ($tasks as $task)
		{
			?>
				<tr>
					<th scope="row"><?=intval($task['id'])?></th>
					<td><?=htmlspecialchars($task['user_login'])?></td>
					<td><?=htmlspecialchars($task['user_email'])?></td>
					<td><input type="checkbox" <?=($task['status'] == \app\models\Task::STATUS_DONE ? 'checked': '')?> onclick="window.location.href='/admin/?action=changestatus&id=<?=intval($task['id'])?>'"></td>
					<td><?=htmlspecialchars($task['text'])?></td>
					<td><a href="/admin/?action=edittask&id=<?=$task['id']?>" class="btn btn-sm btn-default">edit</a></td>
				</tr>
			<?
		}
	}
	?>
	</tbody>
</table>
<?

if (!empty($pagination))
{
	echo $pagination;
}
?>
<script type="text/javascript">

</script>
<?
