<?php defined('B_PROLOG_INCLUDED') and (B_PROLOG_INCLUDED === true) or die();

if(!\Bitrix\Main\Loader::includeModule('crm'))
{
	throw new \Bitrix\Main\LoaderException('Invalid include crm module'); 
}

Bitrix\Main\Loader::registerAutoLoadClasses(
	'bxup.calllist', 
	[
		'BxUp\CallList\StatisticTable'        => 'lib/StatisticTable.php'       ,
		'BxUp\CallList\CallListTable'         => 'lib/CallListTable.php'        ,
		'BxUp\CallList\BusinessRules'         => 'lib/BusinessRules.php'        ,
		'BxUp\CallList\CCrmActivitiyCallList' => 'lib/CCrmActivityCallList.php'
	]
);