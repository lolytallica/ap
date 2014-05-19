<?php


namespace Classes;

class Directory {
	
	/**
	 * Provide a list of directory contents minus the top directory
	 * @param  string $path
	 * @return array
	 */
	public static function listContents($path)
	{
		return array_diff(scandir($path), array('.', '..'));
	}

}