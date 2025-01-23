<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
/**
 * @var $APPLICATION
 */
$APPLICATION->SetTitle("Страница с формой");
?>

<?php
$APPLICATION->IncludeComponent(
	"test:form", 
	"", 
	[
		"BLOCK_ID" => "1",
	],
	false
); ?>

<?php
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");
