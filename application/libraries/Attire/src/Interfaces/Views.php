<?php
namespace Attire\Interfaces;

interface Views
{
	public function init(\Twig_LoaderInterface $loader, $paths, $ext);
}
