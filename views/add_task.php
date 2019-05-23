<a href="/" class="btn btn-primary">Back</a>
<form action="/?action=addtask" method="post">
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
		<label for="login">Login</label>
		<input type="text" class="form-control" id="login" placeholder="Enter login" name="login" value="<?=(!empty($login) ? htmlspecialchars($login) : '')?>" required>
	</div>
	<div class="form-group">
		<label for="email">Email</label>
		<input type="email" class="form-control" id="email" placeholder="Enter email" name="email" value="<?=(!empty($email) ? htmlspecialchars($email) : '')?>" required>
	</div>

	<div class="form-group">
		<label for="text">text</label>
		<textarea class="form-control" id="text" name="text" placeholder="Enter text" required><?=(!empty($text) ? htmlspecialchars($text) : '')?></textarea>
	</div>
	<button type="submit" class="btn btn-primary" name="save">Submit</button>
</form>