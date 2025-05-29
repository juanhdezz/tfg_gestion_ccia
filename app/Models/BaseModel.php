<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class BaseModel extends Model
{
    public function getConnectionName()
    {
        return Config::get('database.default');
    }

    public function setConnection($name)
{
    $this->connection = $name;
    return $this;
}
}