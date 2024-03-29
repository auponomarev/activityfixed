<?php

declare(strict_types=1);
namespace Auponomarev\ActivityFixed\Events;

use Bitrix\Crm\DealTable;
use Bitrix\Crm\LeadTable;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\Loader;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime;
use CCrmDeal;
use CCrmLead;
use CCrmOwnerType;
use COption;

Loader::includeModule('crm');

class Activity
{
    
    /**
     * Событие отлавливается, при добавлении активности
     *
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function OnActivityAdd($id, $arrFields): bool
    {
        if ($arrFields['OWNER_TYPE_ID'] === CCrmOwnerType::Lead) {
            $FIRSTLEADTOUCH_FIELD = COption::GetOptionString(
                'auponomarev.activityfixed',
                "FIRSTLEADTOUCH_FIELD",
                'UF_CRM_FIRSTLEADTOUCH'
            );
            
            $LASTLEADTOUCH_FIELD = COption::GetOptionString(
                'auponomarev.activityfixed',
                "TIME_FIELD",
                'UF_CRM_LASTLEADTOUCH'
            );
            
            if (empty($arrFields['OWNER_ID'])) {
                return true;
            }
            
            $oldLead = LeadTable::getList([
                'select' => ['ID', $FIRSTLEADTOUCH_FIELD ],
                'filter' => ['=ID' => $arrFields['OWNER_ID']],
            ])->fetch();
            $arFields = [];
    
            if (empty($oldLead[$FIRSTLEADTOUCH_FIELD])) {
                $arFields[$FIRSTLEADTOUCH_FIELD] = (new DateTime())->format('d.m.Y H:i:s');
            }
            
            $lead = new CCrmLead(false);
            $arFields[$LASTLEADTOUCH_FIELD] = (new DateTime())->format('d.m.Y H:i:s');
            $lead->Update($arrFields['OWNER_ID'], $arFields);
        }
    
        if ($arrFields['OWNER_TYPE_ID'] === CCrmOwnerType::Deal) {
            $FIRSTDEALTOUCH_FIELD = COption::GetOptionString(
                'auponomarev.activityfixed',
                "FIRSTDEALTOUCH_FIELD",
                'UF_CRM_FIRSTDEALTOUCH'
            );
            
            $LASTDEALTOUCH_FIELD = COption::GetOptionString(
                'auponomarev.activityfixed',
                "LASTDEALTOUCH_FIELD",
                'UF_CRM_LASTDEALTOUCH'
            );
            
            if (empty($arrFields['OWNER_ID'])) {
                return true;
            }
            $oldLead = DealTable::getList([
                'select' => ['ID', $FIRSTDEALTOUCH_FIELD ],
                'filter' => ['=ID' => $arrFields['OWNER_ID']],
            ])->fetch();
            $arFields = [];
        
            if (empty($oldLead[$FIRSTDEALTOUCH_FIELD])) {
                $arFields[$FIRSTDEALTOUCH_FIELD] = (new DateTime())->format('d.m.Y H:i:s');
            }
        
            $deal = new CCrmDeal(false);
            $arFields[$LASTDEALTOUCH_FIELD] = (new DateTime())->format('d.m.Y H:i:s');
            $deal->Update($arrFields['OWNER_ID'], $arFields);
        }
        return true;
    }
}
