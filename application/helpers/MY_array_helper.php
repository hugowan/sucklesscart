<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function implode_assoc($array, $output = 'array')
{
	if (is_array($array) && count($array) > 0)
	{
		return array_map('implode', array_fill(0, count($array), ','), $array);
	}
}

function array_swap($key, $value, $array)
{
	if (is_array($array))
	{
		foreach ($array as $k => $v)
		{
			$return[$v[$key]] = $v[$value];
		}
		
		return $return;
	}
}