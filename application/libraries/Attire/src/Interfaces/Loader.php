<?php
namespace Attire\Interfaces;

interface Loader
{
	public function &init($type, $params);
}