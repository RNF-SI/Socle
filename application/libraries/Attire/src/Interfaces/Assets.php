<?php
namespace Attire\Interfaces;

interface Assets 
{
	public function init($path, array $manifests_paths, array $external_paths, array $prefixes);
}