<?php
namespace BxUp\CallList;

use Bitrix\Main,
	Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

/**
 * Class StatisticTable
 * 
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> ACCOUNT_ID int optional
 * <li> APPLICATION_ID int optional
 * <li> APPLICATION_NAME string(80) optional
 * <li> PORTAL_USER_ID int optional
 * <li> PORTAL_NUMBER string(20) optional
 * <li> PHONE_NUMBER string(20) mandatory
 * <li> INCOMING string(50) mandatory default 1
 * <li> CALL_ID string(255) mandatory
 * <li> CALL_CATEGORY string(20) optional default 'external'
 * <li> CALL_LOG string(2000) optional
 * <li> CALL_DIRECTION string(255) optional
 * <li> CALL_DURATION int mandatory
 * <li> CALL_START_DATE datetime mandatory
 * <li> CALL_STATUS int optional
 * <li> CALL_FAILED_CODE string(255) optional
 * <li> CALL_FAILED_REASON string(255) optional
 * <li> CALL_RECORD_ID int optional
 * <li> CALL_WEBDAV_ID int optional
 * <li> CALL_VOTE int optional
 * <li> COST double optional default 0.0000
 * <li> COST_CURRENCY string(50) optional
 * <li> CRM_ENTITY_TYPE string(50) optional
 * <li> CRM_ENTITY_ID int optional
 * <li> CRM_ACTIVITY_ID int optional
 * <li> REST_APP_ID int optional
 * <li> REST_APP_NAME string(255) optional
 * <li> SESSION_ID int optional
 * <li> TRANSCRIPT_PENDING string(1) optional
 * <li> TRANSCRIPT_ID int optional
 * <li> REDIAL_ATTEMPT int optional
 * <li> COMMENT string optional
 * <li> PORTAL_USER reference to {@link \Bitrix\User\UserTable}
 * </ul>
 *
 * @package Bitrix\Voximplant
 **/

class StatisticTable extends Main\Entity\DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'b_voximplant_statistic';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return array(
			'ID' => array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true,
				'title' => Loc::getMessage('STATISTIC_ENTITY_ID_FIELD'),
			),
			'ACCOUNT_ID' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('STATISTIC_ENTITY_ACCOUNT_ID_FIELD'),
			),
			'APPLICATION_ID' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('STATISTIC_ENTITY_APPLICATION_ID_FIELD'),
			),
			'APPLICATION_NAME' => array(
				'data_type' => 'string',
				'validation' => array(__CLASS__, 'validateApplicationName'),
				'title' => Loc::getMessage('STATISTIC_ENTITY_APPLICATION_NAME_FIELD'),
			),
			'PORTAL_USER_ID' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('STATISTIC_ENTITY_PORTAL_USER_ID_FIELD'),
			),
			'PORTAL_NUMBER' => array(
				'data_type' => 'string',
				'validation' => array(__CLASS__, 'validatePortalNumber'),
				'title' => Loc::getMessage('STATISTIC_ENTITY_PORTAL_NUMBER_FIELD'),
			),
			'PHONE_NUMBER' => array(
				'data_type' => 'string',
				'required' => true,
				'validation' => array(__CLASS__, 'validatePhoneNumber'),
				'title' => Loc::getMessage('STATISTIC_ENTITY_PHONE_NUMBER_FIELD'),
			),
			'INCOMING' => array(
				'data_type' => 'string',
				'required' => true,
				'validation' => array(__CLASS__, 'validateIncoming'),
				'title' => Loc::getMessage('STATISTIC_ENTITY_INCOMING_FIELD'),
			),
			'CALL_ID' => array(
				'data_type' => 'string',
				'required' => true,
				'validation' => array(__CLASS__, 'validateCallId'),
				'title' => Loc::getMessage('STATISTIC_ENTITY_CALL_ID_FIELD'),
			),
			'CALL_CATEGORY' => array(
				'data_type' => 'string',
				'validation' => array(__CLASS__, 'validateCallCategory'),
				'title' => Loc::getMessage('STATISTIC_ENTITY_CALL_CATEGORY_FIELD'),
			),
			'CALL_LOG' => array(
				'data_type' => 'string',
				'validation' => array(__CLASS__, 'validateCallLog'),
				'title' => Loc::getMessage('STATISTIC_ENTITY_CALL_LOG_FIELD'),
			),
			'CALL_DIRECTION' => array(
				'data_type' => 'string',
				'validation' => array(__CLASS__, 'validateCallDirection'),
				'title' => Loc::getMessage('STATISTIC_ENTITY_CALL_DIRECTION_FIELD'),
			),
			'CALL_DURATION' => array(
				'data_type' => 'integer',
				'required' => true,
				'title' => Loc::getMessage('STATISTIC_ENTITY_CALL_DURATION_FIELD'),
			),
			'CALL_START_DATE' => array(
				'data_type' => 'datetime',
				'required' => true,
				'title' => Loc::getMessage('STATISTIC_ENTITY_CALL_START_DATE_FIELD'),
			),
			'CALL_STATUS' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('STATISTIC_ENTITY_CALL_STATUS_FIELD'),
			),
			'CALL_FAILED_CODE' => array(
				'data_type' => 'string',
				'validation' => array(__CLASS__, 'validateCallFailedCode'),
				'title' => Loc::getMessage('STATISTIC_ENTITY_CALL_FAILED_CODE_FIELD'),
			),
			'CALL_FAILED_REASON' => array(
				'data_type' => 'string',
				'validation' => array(__CLASS__, 'validateCallFailedReason'),
				'title' => Loc::getMessage('STATISTIC_ENTITY_CALL_FAILED_REASON_FIELD'),
			),
			'CALL_RECORD_ID' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('STATISTIC_ENTITY_CALL_RECORD_ID_FIELD'),
			),
			'CALL_WEBDAV_ID' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('STATISTIC_ENTITY_CALL_WEBDAV_ID_FIELD'),
			),
			'CALL_VOTE' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('STATISTIC_ENTITY_CALL_VOTE_FIELD'),
			),
			'COST' => array(
				'data_type' => 'float',
				'title' => Loc::getMessage('STATISTIC_ENTITY_COST_FIELD'),
			),
			'COST_CURRENCY' => array(
				'data_type' => 'string',
				'validation' => array(__CLASS__, 'validateCostCurrency'),
				'title' => Loc::getMessage('STATISTIC_ENTITY_COST_CURRENCY_FIELD'),
			),
			'CRM_ENTITY_TYPE' => array(
				'data_type' => 'string',
				'validation' => array(__CLASS__, 'validateCrmEntityType'),
				'title' => Loc::getMessage('STATISTIC_ENTITY_CRM_ENTITY_TYPE_FIELD'),
			),
			'CRM_ENTITY_ID' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('STATISTIC_ENTITY_CRM_ENTITY_ID_FIELD'),
			),
			'CRM_ACTIVITY_ID' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('STATISTIC_ENTITY_CRM_ACTIVITY_ID_FIELD'),
			),
			'REST_APP_ID' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('STATISTIC_ENTITY_REST_APP_ID_FIELD'),
			),
			'REST_APP_NAME' => array(
				'data_type' => 'string',
				'validation' => array(__CLASS__, 'validateRestAppName'),
				'title' => Loc::getMessage('STATISTIC_ENTITY_REST_APP_NAME_FIELD'),
			),
			'SESSION_ID' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('STATISTIC_ENTITY_SESSION_ID_FIELD'),
			),
			'TRANSCRIPT_PENDING' => array(
				'data_type' => 'string',
				'validation' => array(__CLASS__, 'validateTranscriptPending'),
				'title' => Loc::getMessage('STATISTIC_ENTITY_TRANSCRIPT_PENDING_FIELD'),
			),
			'TRANSCRIPT_ID' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('STATISTIC_ENTITY_TRANSCRIPT_ID_FIELD'),
			),
			'REDIAL_ATTEMPT' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('STATISTIC_ENTITY_REDIAL_ATTEMPT_FIELD'),
			),
			'COMMENT' => array(
				'data_type' => 'text',
				'title' => Loc::getMessage('STATISTIC_ENTITY_COMMENT_FIELD'),
			),
			'PORTAL_USER' => array(
				'data_type' => 'Bitrix\User\User',
				'reference' => array('=this.PORTAL_USER_ID' => 'ref.ID'),
			),
		);
	}
	/**
	 * Returns validators for APPLICATION_NAME field.
	 *
	 * @return array
	 */
	public static function validateApplicationName()
	{
		return array(
			new Main\Entity\Validator\Length(null, 80),
		);
	}
	/**
	 * Returns validators for PORTAL_NUMBER field.
	 *
	 * @return array
	 */
	public static function validatePortalNumber()
	{
		return array(
			new Main\Entity\Validator\Length(null, 20),
		);
	}
	/**
	 * Returns validators for PHONE_NUMBER field.
	 *
	 * @return array
	 */
	public static function validatePhoneNumber()
	{
		return array(
			new Main\Entity\Validator\Length(null, 20),
		);
	}
	/**
	 * Returns validators for INCOMING field.
	 *
	 * @return array
	 */
	public static function validateIncoming()
	{
		return array(
			new Main\Entity\Validator\Length(null, 50),
		);
	}
	/**
	 * Returns validators for CALL_ID field.
	 *
	 * @return array
	 */
	public static function validateCallId()
	{
		return array(
			new Main\Entity\Validator\Length(null, 255),
		);
	}
	/**
	 * Returns validators for CALL_CATEGORY field.
	 *
	 * @return array
	 */
	public static function validateCallCategory()
	{
		return array(
			new Main\Entity\Validator\Length(null, 20),
		);
	}
	/**
	 * Returns validators for CALL_LOG field.
	 *
	 * @return array
	 */
	public static function validateCallLog()
	{
		return array(
			new Main\Entity\Validator\Length(null, 2000),
		);
	}
	/**
	 * Returns validators for CALL_DIRECTION field.
	 *
	 * @return array
	 */
	public static function validateCallDirection()
	{
		return array(
			new Main\Entity\Validator\Length(null, 255),
		);
	}
	/**
	 * Returns validators for CALL_FAILED_CODE field.
	 *
	 * @return array
	 */
	public static function validateCallFailedCode()
	{
		return array(
			new Main\Entity\Validator\Length(null, 255),
		);
	}
	/**
	 * Returns validators for CALL_FAILED_REASON field.
	 *
	 * @return array
	 */
	public static function validateCallFailedReason()
	{
		return array(
			new Main\Entity\Validator\Length(null, 255),
		);
	}
	/**
	 * Returns validators for COST_CURRENCY field.
	 *
	 * @return array
	 */
	public static function validateCostCurrency()
	{
		return array(
			new Main\Entity\Validator\Length(null, 50),
		);
	}
	/**
	 * Returns validators for CRM_ENTITY_TYPE field.
	 *
	 * @return array
	 */
	public static function validateCrmEntityType()
	{
		return array(
			new Main\Entity\Validator\Length(null, 50),
		);
	}
	/**
	 * Returns validators for REST_APP_NAME field.
	 *
	 * @return array
	 */
	public static function validateRestAppName()
	{
		return array(
			new Main\Entity\Validator\Length(null, 255),
		);
	}
	/**
	 * Returns validators for TRANSCRIPT_PENDING field.
	 *
	 * @return array
	 */
	public static function validateTranscriptPending()
	{
		return array(
			new Main\Entity\Validator\Length(null, 1),
		);
	}
}