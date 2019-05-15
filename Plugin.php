<?php

namespace Kanboard\Plugin\Bigboard;

use DateTime;
use Kanboard\Core\Plugin\Base;
use Kanboard\Core\Translator;
use Kanboard\Core\Security\Role;

class Plugin extends Base
{
    public function initialize()
    {
      $this->template->hook->attach('template:project-list:menu:before', 'bigboard:Bigboard');
	  $this->template->hook->attach('template:project-header:view-switcher', 'bigboard:BigboardNoOpts');
      $this->template->setTemplateOverride('board/table_container','bigboard:board/table_container');
      $this->template->setTemplateOverride('board/table_tasks','bigboard:board/table_tasks');
      $this->template->setTemplateOverride('board/table_private','bigboard:board/table_private');
      $this->hook->on('template:layout:js', array('template' => 'plugins/Bigboard/Asset/js/BoardDragAndDrop.js'));
      $this->hook->on('template:layout:js', array('template' => 'plugins/Bigboard/Asset/js/BoardPolling.js'));      
	  $this->hook->on('template:layout:js', array('template' => 'plugins/Bigboard/Asset/js/bigboard-selected.js'));
	  $this->hook->on('template:layout:js', array('template' => 'plugins/Bigboard/Asset/js/bigboard-collapsed.js'));
      $this->hook->on('template:layout:css', array('template' => 'plugins/Bigboard/Asset/css/bigboard.css'));	  
    }
    
	public function onStartup()
    {
        Translator::load($this->languageModel->getCurrentLanguage(), __DIR__.'/Locale');
    }
	
    public function getClasses()
    {
      return array(
        'Plugin\Bigboard' => array(
          'UserSession'
        ),
        'Plugin\Bigboard\Controller' => array(
          'Bigboard',
          'BoardAjaxController'
        ),
		'Plugin\Bigboard\Model' => array(
                'BigboardModel',
		),
      );
    }

    public function getPluginName()
    {
        return 'Bigboard';
    }

    public function getPluginDescription()
    {
        return t('BigBoard improved (projects selection, db stored selection) : a Kanboard that displays selected multiple projects - based on BigBoard 1.0.5 by Thomas Stinner');
    }

    public function getPluginAuthor()
    {
        return 'Pierre Cadeot';
    }

    public function getPluginVersion()
    {
        return '1.2.0';
    }

    public function getPluginHomepage()
    {
        return 'https://github.com/stinnux/kanboard-bigboard';
    }

    public function getCompatibleVersion()
    {
        return '>=1.2.4';
    }

}
