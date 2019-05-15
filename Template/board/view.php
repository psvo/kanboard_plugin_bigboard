<?php
	// check if plugin "starred projects" is available
	try {
		if ($this->app->starredProjectsModel->find($project['id'], $this->user->getId())) {
						$fav = "<i class='fa fa-star' title='".t('Favorite project')."'></i>";
		} else $fav = "";
	} catch (Exception $e) {
		$fav = "";
	}

	if ($project['is_private']) {
        $priv = "<i class='fa fa-lock fa-fw' title='".t('Private project')."'></i>";
	} else $priv = "";

	if ($this->app->bigboardModel->collapseFind($project['id'], $this->user->getId())) {
		$open = "none";
		$close = "inline-block";
	} else {
		$open = "inline-block";
		$close = "none";
	}

?><section id="main">

	<span class='header btn BB'>
	<big>
	<span class='collapsible' data-project-id="<?= $this->text->e($project['id']) ?>" id="head-<?= $this->text->e($project['id']) ?>">
	<i id="open-<?= $this->text->e($project['id']) ?>" style="display:<?= $open ?>" class="fa fa-folder-open open"></i>
	<i id="close-<?= $this->text->e($project['id']) ?>" style="display:<?= $close ?>" class="fa fa-folder close"></i>
	<i id="wip-<?= $this->text->e($project['id']) ?>" style="display:none" class="fa fa-cog fa-spin fa-fw"></i>
	</span>
	<small>#<?= $this->text->e($project['id']) ?></small>
	<?= $fav ?>
	<?= $this->text->e($project['name']) ?>
    <?= $priv ?>
    <?php if (! empty($project['description'])): ?>
        <?= $this->app->tooltipMarkdown($project['description']) ?>
    <?php endif ?>
    
    
    </big></span>
    

    <?= $this->render('bigboard:board/table_container', array(
        'project' => $project,
        'swimlanes' => $swimlanes,
        'board_private_refresh_interval' => $board_private_refresh_interval,
        'board_highlight_period' => $board_highlight_period,
    )) ?>

</section>
