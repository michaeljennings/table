<ul class="pagination">
	<li><?= $paginator->getPrev() ?></li>
	<?= $paginator->renderLinks() ?>
	<li><?= $paginator->getNext() ?></li>
</ul>