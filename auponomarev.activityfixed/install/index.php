<?php

use Bitrix\Main\EventManager;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class Auponomarev_ActivityFixed extends CModule
{
    public $MODULE_ID = "auponomarev.activityfixed";

    public $MODULE_NAME;
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_DESCRIPTION;
    public $PARTNER_NAME;
    public $PARTNER_URI;
    public $MODULE_PATH;

    public $MODULE_GROUP_RIGHTS = "Y";

    public function __construct()
    {
        $arModuleVersion = [];
        include(__DIR__ . "/version.php");
        $this->MODULE_VERSION = $arModuleVersion["VERSION"];
        $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];

        $this->MODULE_NAME = Loc::getMessage("ricc.activityfixed_MODULE_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("ricc.activityfixed_MODULE_DESCRIPTION");
        $this->PARTNER_NAME = Loc::getMessage("ricc.activityfixed_MODULE_PARTNER_NAME");
        $this->PARTNER_URI = Loc::getMessage("ricc.activityfixed_MODULE_PARTNER_URL");
    }
    
    public function DoInstall()
    {
        $this->registerModule();
        $this->registerEvents();
        $this->InstallUserFields();
    }
    public function registerModule(): void
    {
        RegisterModule($this->MODULE_ID);
    }    
    public function registerEvents(): void
    {
        EventManager::getInstance()->registerEventHandler(
            'crm',
            'OnActivityAdd',
            $this->MODULE_ID,
            '\Auponomarev\ActivityFixed\Events\Activity',
            'OnActivityAdd'
        );
    
    
    }
    public function DoUninstall()
    {
        $this->unregisterEvents();
        $this->unRegisterModule();
    }
    public function unRegisterModule()
    {
        UnRegisterModule($this->MODULE_ID);
    }
    public function unregisterEvents(): void
    {
        EventManager::getInstance()->unRegisterEventHandler(
            'crm',
            'OnActivityAdd',
            $this->MODULE_ID,
            '\Auponomarev\ActivityFixed\Events\Activity',
            'OnActivityAdd'
        );
    
    }
    protected function InstallUserFields (  ) {
        global $APPLICATION;
        
        // add CRM userfield for Custom_Comment
        $rsUserType = CUserTypeEntity::GetList(
            [],
            [
                'ENTITY_ID' => 'CRM_LEAD',
                'FIELD_NAME' => 'UF_CRM_LASTLEADTOUCH',
            ]
        );
        if (!$rsUserType->Fetch())
        {
            $arFields = [];
            $arFields['ENTITY_ID'] = 'CRM_LEAD';
            $arFields['FIELD_NAME'] = 'UF_CRM_LASTLEADTOUCH';
            $arFields['USER_TYPE_ID'] = 'datetime';
            $arFields["MULTIPLE"] = "N";
            $arFields["MANDATORY"] = "N";
            $arFields["SHOW_FILTER"] = "E";
            $arFields["SHOW_IN_LIST"] = "Y";
            $arFields["EDIT_IN_LIST"] = "N";
            $arFields["IS_SEARCHABLE"] = "N";
            $arFields["SETTINGS"] = [
                "DEFAULT_VALUE" => [
                    "TYPE" => "NONE",
                    "VALUE" => "",
                ],
                "USE_SECOND" => "Y",
                "USE_TIMEZONE" => "N",
            ];
            
            $arFields['EDIT_FORM_LABEL'] = [
                'en' => 'Last comment date',
                'ru' => 'Дата последнего касания',
            ];
            
            
            $CAllUserTypeEntity = new CUserTypeEntity();
            $intID = $CAllUserTypeEntity->Add($arFields, false);
            if (false == $intID)
            {
                if ($strEx = $APPLICATION->GetException())
                {
                    $errors[] = $strEx->GetString();
                }
            }
        }
    
        $rsUserType = CUserTypeEntity::GetList(
            [],
            [
                'ENTITY_ID' => 'CRM_LEAD',
                'FIELD_NAME' => 'UF_CRM_FIRSTLEADTOUCH',
            ]
        );
        if (!$rsUserType->Fetch())
        {
            $arFields = [];
            $arFields['ENTITY_ID'] = 'CRM_LEAD';
            $arFields['FIELD_NAME'] = 'UF_CRM_FIRSTLEADTOUCH';
            $arFields['USER_TYPE_ID'] = 'datetime';
            $arFields["MULTIPLE"] = "N";
            $arFields["MANDATORY"] = "N";
            $arFields["SHOW_FILTER"] = "E";
            $arFields["SHOW_IN_LIST"] = "Y";
            $arFields["EDIT_IN_LIST"] = "N";
            $arFields["IS_SEARCHABLE"] = "N";
            $arFields["SETTINGS"] = [
                "DEFAULT_VALUE" => [
                    "TYPE" => "NONE",
                    "VALUE" => "",
                ],
                "USE_SECOND" => "Y",
                "USE_TIMEZONE" => "N",
            ];
        
            $arFields['EDIT_FORM_LABEL'] = [
                'en' => 'First comment date',
                'ru' => 'Дата первого касания',
            ];
        
        
            $CAllUserTypeEntity = new CUserTypeEntity();
            $intID = $CAllUserTypeEntity->Add($arFields, false);
            if (false == $intID)
            {
                if ($strEx = $APPLICATION->GetException())
                {
                    $errors[] = $strEx->GetString();
                }
            }
        }
        //================ DEAL
    
        $rsUserType = CUserTypeEntity::GetList(
            [],
            [
                'ENTITY_ID' => 'CRM_DEAL',
                'FIELD_NAME' => 'UF_CRM_LASTDEALTOUCH',
            ]
        );
        if (!$rsUserType->Fetch())
        {
            $arFields = [];
            $arFields['ENTITY_ID'] = 'CRM_DEAL';
            $arFields['FIELD_NAME'] = 'UF_CRM_LASTDEALTOUCH';
            $arFields['USER_TYPE_ID'] = 'datetime';
            $arFields["MULTIPLE"] = "N";
            $arFields["MANDATORY"] = "N";
            $arFields["SHOW_FILTER"] = "E";
            $arFields["SHOW_IN_LIST"] = "Y";
            $arFields["EDIT_IN_LIST"] = "N";
            $arFields["IS_SEARCHABLE"] = "N";
            $arFields["SETTINGS"] = [
                "DEFAULT_VALUE" => [
                    "TYPE" => "NONE",
                    "VALUE" => "",
                ],
                "USE_SECOND" => "Y",
                "USE_TIMEZONE" => "N",
            ];
        
            $arFields['EDIT_FORM_LABEL'] = [
                'en' => 'Last comment date',
                'ru' => 'Дата последнего касания',
            ];
        
        
            $CAllUserTypeEntity = new CUserTypeEntity();
            $intID = $CAllUserTypeEntity->Add($arFields, false);
            if (false == $intID)
            {
                if ($strEx = $APPLICATION->GetException())
                {
                    $errors[] = $strEx->GetString();
                }
            }
        }
    
        $rsUserType = CUserTypeEntity::GetList(
            [],
            [
                'ENTITY_ID' => 'CRM_DEAL',
                'FIELD_NAME' => 'UF_CRM_FIRSTDEALTOUCH',
            ]
        );
        if (!$rsUserType->Fetch())
        {
            $arFields = [];
            $arFields['ENTITY_ID'] = 'CRM_DEAL';
            $arFields['FIELD_NAME'] = 'UF_CRM_FIRSTDEALTOUCH';
            $arFields['USER_TYPE_ID'] = 'datetime';
            $arFields["MULTIPLE"] = "N";
            $arFields["MANDATORY"] = "N";
            $arFields["SHOW_FILTER"] = "E";
            $arFields["SHOW_IN_LIST"] = "Y";
            $arFields["EDIT_IN_LIST"] = "N";
            $arFields["IS_SEARCHABLE"] = "N";
            $arFields["SETTINGS"] = [
                "DEFAULT_VALUE" => [
                    "TYPE" => "NONE",
                    "VALUE" => "",
                ],
                "USE_SECOND" => "Y",
                "USE_TIMEZONE" => "N",
            ];
        
            $arFields['EDIT_FORM_LABEL'] = [
                'en' => 'First comment date',
                'ru' => 'Дата первого касания',
            ];
        
        
            $CAllUserTypeEntity = new CUserTypeEntity();
            $intID = $CAllUserTypeEntity->Add($arFields, false);
            if (false == $intID)
            {
                if ($strEx = $APPLICATION->GetException())
                {
                    $errors[] = $strEx->GetString();
                }
            }
        }
    }
}