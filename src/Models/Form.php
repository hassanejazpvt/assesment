<?php

declare(strict_types=1);

namespace Hassan\Assesment\Models;

use Hassan\Assesment\Core\Model;

class Form extends Model
{
    protected $table = 'forms';

    protected $columns = ['name', 'title', 'created_at', 'updated_at'];
}
