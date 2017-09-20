<?php
namespace Attire\Interfaces;

interface Theme
{
	public function init($name, $template, $layout, $path, array $external_paths, $ext);
}