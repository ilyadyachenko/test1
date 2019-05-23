<?
if (!empty($pagination))
{
	?>
	<nav>
		<ul class="pagination">
			<?
			if ($pagination['current_page'] > 1)
			{
				?>
				<li class="page-item">
					<a class="page-link" href="<?=$pagination['previous_page_url']?>" aria-label="Previous">
						<span aria-hidden="true">&laquo;</span>
						<span class="sr-only">Previous</span>
					</a>
				</li>
				<?
			}


			foreach ($pagination['pages'] as $page)
			{
				if ($page['page'] == $pagination['current_page'])
				{
					?>
					<li class="page-item active">
						<span class="page-link"><?=$page['page']?></span>
					</li>
					<?
				}
				else
				{
					?><li class="page-item"><a class="page-link" href="<?=$page['url']?>"><?=$page['page']?></a></li><?
				}
			}

			if ($pagination['current_page'] != $pagination['last_page'])
			{
				?>
				<li class="page-item">
					<a class="page-link" href="<?=$pagination['next_page_url']?>" aria-label="Next">
						<span aria-hidden="true">&raquo;</span>
						<span class="sr-only">Next</span>
					</a>
				</li>
				<?
			}
			?>
		</ul>
	</nav>
	<?
}
