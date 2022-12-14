<div id="board-container" data-project-id='<?= $project['id'] ?>'>
	<?php if (empty($swimlanes) || empty($swimlanes[0]['nb_columns'])): ?>
		<p class="alert alert-error"><?= t('There is no column or swimlane activated in your project!') ?></p>
	<?php else: ?>

		<?php if (isset($not_editable)): ?>
			<table id="board" class="board-project-<?= $project['id'] ?>">
		<?php else: ?>
			<table id="board"
				   class="board-project"
				   data-project-id="<?= $project['id'] ?>"
				   data-check-interval="<?= $board_private_refresh_interval ?>"
				   data-save-url="<?= $this->url->href('BoardAjaxController', 'save', array('plugin' => "Bigboard", 'project_id' => $project['id'])) ?>"
				   data-reload-url="<?= $this->url->href('BoardAjaxController', 'reload', array('plugin' => "Bigboard", 'project_id' => $project['id'])) ?>"
				   data-check-url="<?= $this->url->href('BoardAjaxController', 'check', array('plugin' => "Bigboard", 'project_id' => $project['id'], 'timestamp' => time())) ?>"
				   data-task-creation-url="<?= $this->url->href('TaskCreationController', 'show', array('project_id' => $project['id'])) ?>"
			>
		<?php endif ?>

		<?php foreach ($swimlanes as $index => $swimlane): ?>
			<?php if (! ($swimlane['nb_tasks'] === 0 && isset($not_editable))): ?>

				<?php if ($index === 0 && $swimlane['nb_swimlanes'] > 1): ?>
		    			<!-- Render empty columns to setup the "grid" for collapsing columns (Only once and only if more than 1 swimlane in project) -->
					<?= $this->render('board/table_column_first', array(
					    'swimlane' => $swimlane,
					    'not_editable' => isset($not_editable),
					)) ?>
				<?php endif ?>

				<?php if ($swimlane['nb_swimlanes'] > 1): ?>
					<?= $this->render('board/table_swimlane', array(
						'project' => $project,
						'swimlane' => $swimlane,
						'not_editable' => isset($not_editable),
					)) ?>
				<?php endif ?>

				<?= $this->render('board/table_column', array(
					'swimlane' => $swimlane,
					'not_editable' => isset($not_editable),
				)) ?>

				<?= $this->render('bigboard:board/table_tasks', array(
					'project' => $project,
					'swimlane' => $swimlane,
					'not_editable' => isset($not_editable),
					'board_highlight_period' => $board_highlight_period,
				)) ?>

			<?php endif ?>
		<?php endforeach ?>

		</table>

	<?php endif ?>
</div>

