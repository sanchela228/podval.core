<?
include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/urlrewrite.php');

include_once($_SERVER['DOCUMENT_ROOT'].'/local/routing_index.php');

if(file_exists($_SERVER['DOCUMENT_ROOT'].'/404.php'))
	include_once($_SERVER['DOCUMENT_ROOT'].'/404.php');
?>