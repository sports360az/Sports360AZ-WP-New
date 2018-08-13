<?php
/**
 * Created by PhpStorm.
 * User: Fred
 * Date: 16/08/2016
 * Time: 00:16
 */

function __autoload ($Class) {
	$dirName = 'class';

	if (file_exists("{$dirName}/{$Class}.class.php")):
		require_once ("{$dirName}/{$Class}.class.php");
		else:
		die("Erro ao incluir");
		endif;
}