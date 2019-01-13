<?php
namespace BxUp\CallList;

use Bitrix\Main,
    Bitrix\Main\SystemException,
	Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

class BusinessRules
{
    public static $callType = [
        1 => 'OUT_CALL',
        2 => 'IN_CALL',
        3 => 'IN_CALL_REDIRECT'
    ];

    public static $operatorDepartamentId = 90;
    public static $moduleId = "bxup.calllist";

    public static function getCallStatus($codeFailedCode)
    {
        switch($codeFailedCode)
        {
            case '200'   : return 'answer'     ; //Успешный звонок
            case '304'   : return 'new'        ; //Пропущенный звонок
            case '603'   : return 'not_answer' ; //Отклонено
            case '603-S' : return 'not_answer' ; //Вызов отменен
            case '403'   : return 'not_answer' ; //Запрещено
            case '404'   : return 'not_use'    ; //Неверный номер
            case '486'   : return 'busy'       ; //Занято
            case '484'   : return 'not_use'    ; //Данное направление не доступно
            case '503'   : return 'not_use'    ; //Данное направление не доступно
            case '480'   : return 'not_answer' ; //Временно не доступен
            case '402'   : return 'new'        ; //Недостаточно средств на счету
            case '423'   : return 'not_use'    ; //Заблокировано
            case 'OTHER' : return 'new'        ; //Не определен
            default      : return 'new'        ; //Другое
        }
    }

    public static function getAddressesPlacedOrder()
    {
        $contactsPlacedOrder = [];
        $contactsPlacedOrderExistedInCallList = [];
        
        $crmInvoiceList = \CCrmInvoice::GetList([], ['>=DATE_INSERT' => date('d.m.Y').' 00:00:00', 'CHECK_PERMISSIONS' => 'N'], false, false, ['UF_CONTACT_ID']);

        while($item = $crmInvoiceList->fetch())
        {
            $contactsPlacedOrder[] = $item['UF_CONTACT_ID'];
        }

        if(count($contactsPlacedOrder) <= 0) return [];
        
        $callListTableList = \BxUp\CallList\CallListTable::getList(['filter' => ['client_address_id' => $contactsPlacedOrder], 'select' => ['client_address_id']]);
        
        while($item = $callListTableList->fetch())
        {
            $contactsPlacedOrderExistedInCallList[] = $item['client_address_id'];
        }

        if(count($contactsPlacedOrderExistedInCallList) > 0 && intval(date('i')) === 0) self::log('contacts_placed_order_existed_in_calllist', $contactsPlacedOrderExistedInCallList);
        
        return $contactsPlacedOrderExistedInCallList;
    }

    public static function getAddressId($phone)
    {
        if(strlen($phone) >= 9 && strlen($phone) <= 12 && ctype_digit($phone))
        {
            $list = \BxUp\CallList\CallListTable::getList(
                [
                    'filter' => [
                        'phone' => $phone
                    ]
                ]
            );
            $i = 0;
    
            while($item=$list->fetch())
            {
                $items[] = $item;
                $i++;
            }
    
            if(count($items) > 0)
            {
                return $items[0]['client_address_id'];
            }
            else
            {
                return false;
            }
        }
        else
        {
            self::log('wrong_phone_format', ['phone' => $phone]);
        }
    }

    public static function updateStatusWherePhone($phone, $status)
    {
        $recallAttempts = \Bitrix\Main\Config\Option::get('main','calls_count_timer', '3');
        $recallInterval = \Bitrix\Main\Config\Option::get('main','calls_count_timer_interval', '90');

        $list = \BxUp\CallList\CallListTable::getList(
            [
                'filter' => [
                    'phone' => $phone
                ]
            ]
        );

        $isComplete = true;

        while($item=$list->fetch())
        {   
            if($item['calls_count'] >=  $recallAttempts) return true;
            $items[] = $item;
        }

        foreach($items as $item)
        {
            $fields['status'] = $status;
            $fields['calls_count'] = ++$item['calls_count'];
            $fields['last_call'] = (new \Bitrix\Main\Type\DateTime())->add("+ {$recallInterval} minutes");

            $result = \BxUp\CallList\CallListTable::update($item['id'], $fields);

            if(!$result->isSuccess())
            {
                self::log('not_updated', 
                    [
                        'phone' => $phone,
                        'error' => $result->getErrorMessages()
                    ]
                );

                $isComplete = false;
            }
        }

        return $isComplete;

    }

    public static function updateStatusWhereAddressId($id)
    {   
        $list = \BxUp\CallList\CallListTable::getList(
            [
                'filter' => [
                    'client_address_id' => $id
                ]
            ]
        );

        $isComplete = true;

        while($item=$list->fetch())
        {
            $fields['status'] = "answer";
            $fields['last_call'] = new \Bitrix\Main\Type\DateTime();
            $fields['calls_count'] = ++$item['calls_count'];
            $result = \BxUp\CallList\CallListTable::update($item['id'], $fields);

            if(!$result->isSuccess())
            {
                self::log('not_updated', 
                    [
                        'phone' => $phone,
                        'error' => $result->getErrorMessages()
                    ]
                );

                $isComplete = false;
            }
        }
  
        return $isComplete;
    }

    public static function addEventToStack($command)
    {
        try
        {
            if(!\Bitrix\Main\Loader::includeModule('pull')) throw new \Bitrix\Main\LoaderException('Invalid include pull module'); 

            \CPullWatch::AddToStack( self::$moduleId,
                [
                    'module_id' => self::$moduleId,
                    'command' => $command,
                    'params' => []
                ]
            );

            return true;
        }
        catch (SystemException $e)
        {
            self::log('exception', [$e->getMessage()]);
            return false;
        }
    }

    public static function onCallInit($param)
    {
        if($param['CALL_TYPE'] == 2)
        {
            return self::addEventToStack('ON_CALL_INIT_TYPE_2');
        }
        else
        {
            return false;
        }
    }

    public static function onCallStart($param)
    {   
        if($param['CALL_TYPE'] == 2)
        {
            return self::addEventToStack('ON_CALL_START_TYPE_2');
        }
        else
        {
            return false;
        }
    }

    public static function onCallEnd($param)
    {
        try
        {
            if($param['CALL_TYPE'] == 2)
            {
                self::addEventToStack('ON_CALL_END_TYPE_2');
            }

            $status = self::getCallStatus($param['CALL_FAILED_CODE']);

            if($status === 'answer')
            {
                if($addressId = self::getAddressId($param['PHONE_NUMBER']))
                {
                    $result = self::updateStatusWhereAddressId($addressId);
                }
                else
                {
                    self::log('not_found', $param);
                    $result = false;
                }
            }
            else
            {
                $result = self::updateStatusWherePhone($param['PHONE_NUMBER'], $status);
            }

            return $result;
        }
        catch (SystemException $e)
        {
            self::log('exception', [$e->getMessage()]);
            return false;
        } 
    }

    public static function OnBeforeProlog()
    {
        global $APPLICATION, $USER;
        
        if(!\Bitrix\Main\Loader::includeModule('pull')) throw new \Bitrix\Main\LoaderException('Invalid include pull module'); 

        $departmentEmployees = \CIntranetUtils::GetDepartmentEmployees(self::$operatorDepartamentId, false, false, 'Y');

        while($employee  = $departmentEmployees->fetch())
        {
            $workers[] = $employee['ID'];
        }
       
        if(in_array($USER->GetID(), $workers))
        {
            $subscribers = \CPullWatch::GetUserList(self::$moduleId);

            if(in_array($USER->GetID(), $subscribers))
            {
                \CPullWatch::Extend($USER->GetId(), self::$moduleId);
            }
            else
            {
                \CPullWatch::Add($USER->GetID(), self::$moduleId);
            }
        }
    }

    public static function log($event, $data)
    {
        $path = "/log/bxup.calllist/{$event}-" . date('d.m.y');  
      
        $array = $data;
        
        array_walk($array, function(&$value, $key) {
            $value = "[{$key}:{$value}]";
        });
        
        $row = implode(', ', $array);

        \Bitrix\Main\Diag\Debug::writeToFile($row, date('H:i:s'), $path);

        \CEventLog::Add(
            [
                "ITEM_ID"       => __CLASS__      ,
                "SEVERITY"      => "WARNING"      ,
                "MODULE_ID"     => "bxup.calllist",
                "DESCRIPTION"   => $row           ,
                "AUDIT_TYPE_ID" => $event         ,
            ]
        );
    }

    public static function bindEvents()
    {
        try
        {
            
            \Bitrix\Main\EventManager::getInstance()->addEventHandlerCompatible('main'      , 'OnBeforeProlog' , [__CLASS__, 'OnBeforeProlog' ]);
            \Bitrix\Main\EventManager::getInstance()->addEventHandlerCompatible('voximplant', 'onCallInit'     , [__CLASS__, 'onCallInit'     ]);
            \Bitrix\Main\EventManager::getInstance()->addEventHandlerCompatible('voximplant', 'onCallStart'    , [__CLASS__, 'onCallStart'    ]);
            \Bitrix\Main\EventManager::getInstance()->addEventHandlerCompatible('voximplant', 'onCallEnd'      , [__CLASS__, 'onCallEnd'      ]);
        }
        catch (SystemException $e)
        {
            self::log('exception', [$e->getMessage()]);
            return false;
        } 
    }
}