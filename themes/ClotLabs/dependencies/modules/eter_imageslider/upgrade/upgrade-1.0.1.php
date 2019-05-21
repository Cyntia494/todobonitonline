<?php
if (!defined('_PS_VERSION_')) {
    exit;
}
//require_once dirname(dirname(__FILE__))."/src/autoload.php";
function upgrade_module_1_0_1($module)
{
	$res = $module->createTables();
	return $res;
}