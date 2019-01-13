<?php
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config\Option;
use Bitrix\Main\EventManager;
use Bitrix\Main\Application;
use Bitrix\Main\IO\Directory;

Loc::loadMessages(__FILE__);

Class bxup_calllist extends CModule
{
    /** @var string */
    public $MODULE_ID;

    /** @var string */
    public $MODULE_VERSION;

    /** @var string */
    public $MODULE_VERSION_DATE;

    /** @var string */
    public $MODULE_NAME;

    /** @var string */
    public $MODULE_DESCRIPTION;

    /** @var string */
    public $MODULE_GROUP_RIGHTS;

    /** @var string */
    public $PARTNER_NAME;

    /** @var string */
    public $PARTNER_URI;

    public static function getModuleId()
	{
		return basename(dirname(__DIR__));
    }

    public function __construct()
    {
        include_once(__DIR__."/version.php");

        $this->MODULE_ID = self::getModuleId();
        $this->MODULE_VERSION = $arModuleVersion["VERSION"] ;
        $this->MODULE_NAME = Loc::getMessage('BXUPCALLLIST_MODULE_NAME') ;
        $this->MODULE_DESCRIPTION = Loc::getMessage('BXUPCALLLIST_MODULE_DESCRIPTION') ;
        $this->PARTNER_NAME = Loc::getMessage('BXUPCALLLIST_PARTNER_NAME') ;
        $this->PARTNER_URI = Loc::getMessage('BXUPCALLLIST_PARTNER_URI') ;
    }

    
    public function DoInstall()
	{
		$this->InstallDB();
		$this->InstallFiles();
		$this->InstallEvents();
		$this->registerDependencies();
		RegisterModule(self::getModuleId());
	}

	public function DoUninstall()
	{
		UnRegisterModule(self::getModuleId());
		$this->unregisterDependencies();
		$this->UnInstallEvents();
		$this->UnInstallFiles();
		$this->UnInstallDB();
	}

    
	public function InstallDB()
	{
		return true;
	}

	public function UnInstallDB()
	{
		return true;
	}

	public function InstallFiles()
	{
		return true;
	}

	public function UnInstallFiles()
	{
		return true;
	}

	public function InstallEvents()
	{
		return true;
	}

	public function UnInstallEvents()
	{
		return true;
	}

    public function registerDependencies()
    {
        return true;
    }

    public function unregisterDependencies()
    {
        return true;
    }
}
