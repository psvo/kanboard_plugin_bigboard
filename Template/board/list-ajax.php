<section id="main">
	<style>
	div.item {
		margin: 6px;
	}
	div.item[check=checked] span.libelle {
		background-color: rgba(255,200,0,0.3);
		font-weight: bold;
		padding: 3px;
		border-radius: 5px;
	}
	</style>
    <div class='page-header'>
        <h2><?= t('BigBoard') ?> : <?= t('manage projects selection') ?></h2>
    </div>

	<div>
		<small><small>
			<span class="btn" id="listView"><i class="fa fa-align-left"></i> <?= t('list view') ?></span>
			<span class="btn" id="paragraphView"><i class="fa fa-align-justify"></i> <?= t('inline view') ?></span>
			<br><br>
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
		</small></small>
	</div>
<?php
foreach ($listechoix as $choix) { 
	
	try {
		if ($this->app->starredProjectsModel->find($choix['id'], $this->user->getId())) {
					$fav = "<i class='fa fa-star' title='".t('Favorite project')."'></i>";
					$class="fav";
		} else $fav = $class = "";
	} catch (Exception $e) {
		$fav = $class = "";
	}
	
	if ($choix['is_private']) {
        $priv = "<i class='fa fa-lock fa-fw' title='".t('Private project')."'></i>";
	} else $priv = "";
    
  ?>			
<div class="item bigbboard-select <?= $class ?>" check="<?php if($this->app->bigboardModel->find($choix['id'], $this->user->getId(),"SELECTED")): ?>checked<?php else: ?>unchecked<?php endif; ?>" data-project-id="<?= $choix['id'] ?>" style="display: block;">
	<span class="box">
                        <?php if($this->app->bigboardModel->find($choix['id'], $this->user->getId(),"SELECTED")): ?>
						<?php /* if(1 == 1): */ ?>
                            <i class="fa fa-check-square-o"></i>
                        <?php else: ?>
                            <i class="fa fa-square-o"></i>
                        <?php endif; ?>
						  
  </span>
<span class="libelle"> <?= $fav ?> <?= $choix['nom']?> <?= $priv ?> </span> 
</label>
</div>			
			
<?php } ?>
</section>	 
	
	