<div class="table-container">
	<form method="post">
	<div class="table-header">
		<div class="col-sm-6">
			<h3><?= $table->getTitle() ?></h3>
		</div>
		<?php if ($table->hasActions('table')) { ?>
			<div class="table-actions col-sm-6">
				<?php foreach($table->getActions('table') as $action) { ?>
					<?= $action->render() ?>
				<?php } ?>
			</div>
		<?php } ?>
	</div>
	<table class="table">
		<thead>
			<tr>
				<?php foreach ($table->getColumns() as $column) { ?>
					<th <?php foreach ($column->getAttributes() as $attr => $val) { ?>
						<?=$attr?>="<?=$val?>"
						<?php } ?>>
						<a href="<?=$column->getHref()?>">
							<?=$column->label?>
							<?php if (isset($column->sort)) { ?>
								 <span class="glyphicon glyphicon-chevron-<?= $column->sort ?>"></span>
							<?php } ?>
						</a>
					</th>
				<?php } ?>
			</tr>
		</thead>
		<tbody>
			<?php if($table->hasRows()) { ?>
				<?php foreach ($table->getRows() as $row) { ?>
					<tr data-id="<?=$row->id?>">
						<?php foreach ($row->cells as $cell) { ?>
							<td>
								<?= $cell->renderSpreadsheetCell() ?>
							</td>
						<?php } ?>
					</tr>
				<?php } ?>
			<?php } else { ?>
				<tr>
					<td colspan="<?= count($table->getColumns()) ?>">No Results Found.</td>
				</tr>
			<?php } ?>
		</tbody>
	</table>
	</form>
	<div class="table-footer">
		<?php if ($table->hasLinks()) { ?>
			<?= $table->getLinks() ?>
		<?php } ?>
	</div>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script type="text/javascript" src="/packages/gmlconsulting/table/js/table.js"></script>
<script type="text/javascript">
	$(document).ready(function(){
		$('.table-container').tablejs();
	});
</script>