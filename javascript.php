<?php

if (empty($_GET['files']))
{
	die('/* błąd ładowania plików */');
}

define('PATH', 'js/'); // 1
define('EXT', '.js'); // 2
$files = $_GET['files'];
$f_md5 = md5($files); // 3
$file_path = createFilePath($f_md5);

if ( !file_exists($file_path))
{
	require_once 'jsmin.class.php';

	$code = includeJSFiles($files); // 4
	saveMinimizedCode($file_path, $code); // 5
}

echo file_get_contents($file_path); // 6

function includeJSFiles($files)
{
	$f_array = explode(',', $files);
	$n = count($f_array);
	$code = '';

	for($i = 0; $i < $n; $i++)
	{
		$file_path = createFilePath($f_array[$i]);
		if (file_exists($file_path))
		{
			$code .= JSMin::minify(file_get_contents($file_path));
		}
	}
	return $code;
}

function saveMinimizedCode($file_path, $code)
{
	$file = fopen($file_path, 'w+');
	fwrite($file, $code);
	fclose($file);
}

function createFilePath($name, $path = PATH, $ext = EXT)
{
	return $path . $name . $ext;
}





