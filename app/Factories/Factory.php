<?php

namespace App\Factories;

abstract class Factory
{
	abstract public static function get(string $handler);
}
