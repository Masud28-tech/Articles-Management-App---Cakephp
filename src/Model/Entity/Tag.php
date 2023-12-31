<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

class Tag extends Entity
{
  
    protected $_accessible = [
        'title' => true,
        'created' => true,
        'modified' => true,
        'articles' => true,
    ];
}
