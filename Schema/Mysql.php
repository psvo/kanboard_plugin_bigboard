<?php

namespace Kanboard\Plugin\Bigboard\Schema;

use PDO;

const VERSION = 4;

function version_4(PDO $pdo)
{
    $pdo->exec('
        CREATE TABLE IF NOT EXISTS bigboard_selected (
          id INT PRIMARY KEY AUTO_INCREMENT,
          user_id INT(11) NOT NULL,
          project_id INT(11) NOT NULL,
          UNIQUE(user_id, project_id)
        ) ENGINE=InnoDB CHARSET=utf8
    ');
    $pdo->exec('
        CREATE TABLE IF NOT EXISTS bigboard_collapsed (
          id INT PRIMARY KEY AUTO_INCREMENT,
          user_id INT(11) NOT NULL,
          project_id INT(11) NOT NULL,
          UNIQUE(user_id, project_id)
        ) ENGINE=InnoDB CHARSET=utf8
    ');	
}
