<a href="/?action=addtask" class="btn btn-primary">New task</a>

<table class="table table-striped">
	<thead>
		<tr>
			<th><a href="/?page=<?=$page?>&sort=id&type=<?=($sortType == 'asc' ? 'desc' : 'asc')?>">id<?=($sort == 'id' ? '&nbsp;<span class="glyphicon glyphicon-chevron-'.($sortType == 'asc' ? 'down' : 'up').'"></span>' : '')?></a></th>
			<th><a href="/?page=<?=$page?>&sort=users.login&type=<?=($sortType == 'asc' ? 'desc' : 'asc')?>">user login<?=($sort == 'users.login' ? '&nbsp;<span class="glyphicon glyphicon-chevron-'.($sortType == 'asc' ? 'down' : 'up').'"></span>' : '')?></a></th>
			<th><a href="/?page=<?=$page?>&sort=users.email&type=<?=($sortType == 'asc' ? 'desc' : 'asc')?>">user email<?=($sort == 'users.email' ? '&nbsp;<span class="glyphicon glyphicon-chevron-'.($sortType == 'asc' ? 'down' : 'up').'"></span>' : '')?></a></th>
			<th><a href="/?page=<?=$page?>&sort=status&type=<?=($sortType == 'asc' ? 'desc' : 'asc')?>">status<?=($sort == 'status' ? '&nbsp;<span class="glyphicon glyphicon-chevron-'.($sortType == 'asc' ? 'down' : 'up').'"></span>' : '')?></a></th>
			<th>text</th>
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
					<th scope="row"><?=htmlspecialchars($task['id'])?></th>
					<td><?=htmlspecialchars($task['user_login'])?></td>
					<td><?=htmlspecialchars($task['user_email'])?></td>
					<td><?=($task['status'] == \app\models\Task::STATUS_DONE ? "Done" : "New")?></td>
					<td><?=htmlspecialchars($task['text'])?></td>
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

