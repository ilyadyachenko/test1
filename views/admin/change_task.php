<a href="/admin/" class="btn btn-primary">Back</a>
<form action="/admin/?action=edittask&id=<?=$id?>" method="post">
	<?
	if (!empty($errors))
	{
		?>
		<div class="alert-danger">
			<?
			foreach ($errors as $errorField => $errorValues)
			{
				foreach ($errorValues as $errorText)
				{
					echo $errorText. "<br>";
				}
			}
			?>
		</div>
		<?
	}
	?>

	<div class="form-group">
		<label for="login">Login: <?=htmlspecialchars($user_login)?></label>
	</div>
	<div class="form-group">
		<label for="login">Email: <?=htmlspecialchars($user_email)?></label>
	</div>

	<div class="form-group">
		<label for="text">text</label>
		<textarea class="form-control" id="text" name="text" placeholder="Enter text" required><?=(!empty($text) ? htmlspecialchars($text) : '')?></textarea>
	</div>
	<button type="submit" class="btn btn-primary" name="save">Submit</button>
</form>