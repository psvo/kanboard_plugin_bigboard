<?php
    $routerController = $this->app->getRouterController();
    $routerPlugin = $this->app->getPluginName();

    $active = $routerController == 'Bigboard' && $routerPlugin == 'Bigboard';
?>
 <li class="<?= $active ? 'active' : '' ?>">
	<big><i class="fa fa-th-large fa-fw"></i></big> 
    <?= $this->url->link(
        t('BigBoard'),
        'Bigboard',
        'index',
        ['plugin' => 'Bigboard', ]
    ) ?>
</li>
