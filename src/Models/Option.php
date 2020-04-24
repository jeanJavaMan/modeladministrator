<?php

namespace Jeanderson\modeladministrator\Models;

use Eloquent;

/**
 * Class Option
 * @property string value
 * @property integer id
 * @package Jeanderson\modeladministrator\Models
 */
class Option extends Eloquent
{
    protected $fillable = ["value"];
    protected $table = "options";
    public $timestamps = false;
}
