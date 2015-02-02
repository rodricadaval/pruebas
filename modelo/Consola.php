<?php
class Consola {

/**
 * Send debug code to the Javascript console
 */

	function mostrar($data)
	{
		if (is_array($data) || is_object($data))
		{
			print_r(json_encode($data)."\n");
		}
		else
		{
			print_r($data."\n");
		}
	}

}?>