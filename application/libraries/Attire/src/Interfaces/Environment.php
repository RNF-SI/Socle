<?php
namespace Attire\Interfaces;

interface Environment
{
	public function &init(\Twig_LoaderInterface $loader, array $options);
}