<?php

/*
|------------------------------------------------------------------------
| Debug Helper Funcions
|------------------------------------------------------------------------
| 
|
*/

/**
 * Var dump Shortcut
 * 
 * @param mixed $var
 * @param mixed $return
 * @param boolean $use_xdebug
 * @return string
 * @author A. H. Abid <a_h_abid@hotmail.com>
 */
function dd($var, $return = FALSE, $use_xdebug = TRUE)
{
	$use_xdebug = $use_xdebug && function_exists('xdebug_var_dump');
	if ($return)
	{
		if ($use_xdebug && function_exists('ob_start'))
		{
			ob_start();
			xdebug_var_dump($var);
			$out = ob_get_contents();
			ob_end_clean();
		}
		else
			$out = "<pre class='quark-dump'>" . htmlentities(var_export($var, $return), ENT_QUOTES, "utf-8") . "</pre>";
		
		return $out;
	}
	else
	{
		if ($use_xdebug) {
			xdebug_var_dump($var);
		}
		else {
			echo "<pre class='quark-dump'>" . htmlentities(var_export($var, TRUE), ENT_QUOTES, "utf-8") . "</pre>";
		}
	}
}