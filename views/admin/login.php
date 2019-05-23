<form action="/admin/?action=login" method="post">
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
		<input type="text" class="form-control" id="login" placeholder="Enter login" name="login" value="<?=(!empty($login) ? htmlspecialchars($login) : '')?>">
	</div>
	<div class="form-group">
		<label for="password">Password</label>
		<input type="password" class="form-control" id="password" name="password" value="">
	</div>

	<button type="submit" class="btn btn-primary" name="submit">Login</button>
</form>