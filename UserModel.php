<?php

namespace App\RobiMvc\Core;

use App\RobiMvc\Core\DB\DbModel;

abstract class UserModel extends DbModel
{
    abstract public function getDisplayName(): string;
}