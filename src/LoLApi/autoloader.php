<?php

spl_autoload_register(
	function($a)
	{
		$b	= explode('\\', $a);
		if ($b[0] == 'LoLApi')
		{
			unset($b[0]);
			$b	= implode('/', $b);
			require_once $b .'.php';
		}
    });
