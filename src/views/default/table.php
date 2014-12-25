<div class="table-container">
	<form action="<?= $table->getFormAction() ?>" method="post">
		<div class="table-header">
			<div class="col-md-8">
				<h3><?= $table->getTitle() ?></h3>
				<?php if ($table->isSearchable()) { ?>
					<div class="search-group">
						<div class="input-group input-group-sm">
							<input type="text" name="terms" placeholder="Search" value="<?= $table->getSearchTerms() ?>" class="form-control">
							<span class="input-group-btn">
						        <button class="btn btn-default" name="search" value="search" type="submit"><span class="fa fa-search"></span></button>
						    </span>
						    <span class="input-group-btn">
						        <button class="btn btn-default" name="reset" value="reset" type="submit"><span class="fa fa-times-circle"></span></button>
						    </span>
						</div>
					</div>
					<?php if ($table->hasSearchTerms()) { ?>
						<p id="search-results"><strong><?= $table->getSearchResultsTotal(); ?></strong> <?= Str::plural('result', $table->getSearchResultsTotal()); ?> found for <strong><?= $table->getSearchTerms() ?></strong></p>
					<?php } ?>
				<?php } ?>
			</div>
			<?php if ($table->hasActions('table')) { ?>
				<div class="table-actions col-sm-4">
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
							<?php if ($table->isSortable()) { ?>
								<a href="<?=$column->getHref()?>">
									<?=$column->label?>
									<?php if (isset($column->sort)) { ?>
										 <span class="glyphicon glyphicon-chevron-<?= $column->sort ?>"></span>
									<?php } ?>
								</a>
							<?php } else { ?>
								<?=$column->label?>
							<?php } ?>
						</th>
					<?php } ?>
				</tr>
			</thead>
			<tbody>
				<?php if($table->hasRows()) { ?>
					<?php foreach ($table->getRows() as $row) { ?>
						<tr data-id="<?=$row->id?>">
							<?php foreach ($row->cells as $cell) { ?>
								<td><?= $cell->value ?></td>
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