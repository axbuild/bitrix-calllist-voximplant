<?php
namespace BxUp\CallList;

use Bitrix\Main,
    Bitrix\Main\SystemException,
	Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

class CCrmActivitiyCallList extends \CCrmActivity
{
	public static function isContactExsit($id, $date=false, $status=1, $completed='N')
	{
        if(!isset($id))
        {
            throw new ArgumentTypeException('Invalid type argument');
        }

        if(intval($id) <= 0)
        {
            throw new ArgumentNullException('Invalid argument value');
        }

		$activities = self::getList(
			['ID' => 'DESC'],
			[
				'PROVIDER_TYPE_ID'  => 'CALL_LIST',
				'STATUS'            => $status,
				'COMPLETED'         => $completed,
				'>=CREATED'         => ($date) ? $date : date('d.m.Y'),
				'CHECK_PERMISSIONS' => 'N' 
			],
			false,
			false,
			[]
		);

        $items = [];

        while($activity = $activities->fetch())
        {
            $contacts = \Bitrix\Crm\CallList\CallList::createWithId((int)$activity['ASSOCIATED_ENTITY_ID'], true);

            $arContacts = $contacts->toArray();

            foreach($arContacts['ITEMS'] as $key => $value)
            {	
                if($id == $value['ELEMENT_ID']) return true;
            }

        }
		return false;

    }

    public static function getByContactId($id, $date=false, $status=1, $completed='N')  
    {
        if(!isset($id))
        {
            throw new ArgumentTypeException('Invalid type argument');
        }

        if(intval($id) <= 0)
        {
            throw new ArgumentNullException('Invalid argument value');
        }

		$activities = self::getList(
			['ID' => 'DESC'],
			[
				'PROVIDER_TYPE_ID'  => 'CALL_LIST',
				'STATUS'            => $status,
				'COMPLETED'         => $completed,
				'>=CREATED'         => ($date) ? $date : date('d.m.Y'),
				'CHECK_PERMISSIONS' => 'N' 
			],
			false,
			false,
			[]
		);

        $items = [];

        while($activity = $activities->fetch())
        {
            $contacts = \Bitrix\Crm\CallList\CallList::createWithId((int)$activity['ASSOCIATED_ENTITY_ID'], true);

            $arContacts = $contacts->toArray();

            foreach($arContacts['ITEMS'] as $key => $value)
            {	
                if($id == $value['ELEMENT_ID'])
                {
                    $items[] = $activity;
                }
            }

        }
		return $items;
    }
}