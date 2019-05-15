<section id="main">
    <div class='page-header'>
        <h2><?= t('BigBoard') ?> : <?= t('manage projects selection') ?></h2>
    </div>
	<form id="project-creation-form" method="post" action="<?= $this->url->href('Bigboard', 'saveList', [ 'plugin' => 'bigboard',  ]) ?>" autocomplete="off">
	<div class="">
		<small><small>
			<span class="btn" id="listView"><i class="fa fa-align-left"></i> <?= t('list view') ?></span>
			<span class="btn" id="paragraphView"><i class="fa fa-align-justify"></i> <?= t('inline view') ?></span>
		<hr>
			<span class="btn" id="selectAll"><?= t('check') ?> <b><?= t('everything') ?></b></span>
			<span class="btn" id="clearAll"><?= t('uncheck') ?> <b><?= t('everything') ?></b></span>
			<?php 
				// try in case plugin starred projects is not available
				try {
					if (method_exists($this->app->starredProjectsModel,"find")) { ?>
			<span class="btn" id="addStar"><?= t('add') ?> <b><?= t('favorites') ?></b></span>
			<span class="btn" id="onlyStar"><?= t('check') ?> <u><?= t('only') ?></u> <b><?= t('favorites') ?></b></span>
			<?php   } 
				} catch (Exception $e) {} 
			?> 
			<hr>
		</small></small>
	</div>
<?php
if (isset($_GET["boardview"])) {

echo "<input type=hidden name='boardview' value=1>";

}

$storedList = $this->app->bigboardModel->selectFindAllProjectsById($this->user->getId());
(count($storedList)>0)?sort($storedList):null;
sort($projectList);
foreach ($projectList as $project) { 
	
	if ( ( $storedList != null ) && ( in_array($project['id'], $storedList) ) )
		$stored = "checked";
	else
		$stored = "";
	
	try {
		if ($this->app->starredProjectsModel->find($project['id'], $this->user->getId())) {
					$fav = "<i class='fa fa-star' title='".t('Favorite project')."'></i>";
					$class="fav";
		} else $fav = $class = "";
	} catch (Exception $e) {
		$fav = $class = "";
	}
	
	if ($project['is_private']) {
        $priv = "<i class='fa fa-lock fa-fw' title='".t('Private project')."'></i>";
	} else $priv = "";
    
  ?>			
<div class="selitem" style="display: block;">
<label class="sel">
<input type="checkbox" name="selection[]" class="<?= $class ?>" value="<?= $project['id']?>" <?= $stored ?>>
  <span> <small>#<?= $this->text->e($project['id']) ?></small> <?= $fav ?> <?= $project['nom']?> <?= $priv ?> </span> 
</label>
</div>			
			
<?php } ?>
        <?= $this->modal->submitButtons() ?>
    </form>
</section>	 