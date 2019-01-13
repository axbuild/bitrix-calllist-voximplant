<?php
namespace BxUp\CallList;

use Bitrix\Main,
	Bitrix\Main\Localization\Loc;
Loc::loadMessages(__FILE__);

/**
 * Class CallListTable
 * 
 * Fields:
 * <ul>
 * <li> id int mandatory
 * <li> client_address_id int optional
 * <li> client_bitrix_id int optional
 * <li> uid string(255) optional
 * <li> template unknown mandatory default 'timer'
 * <li> phone int mandatory
 * <li> name string(255) mandatory
 * <li> priority int optional
 * <li> calls_attempt int mandatory
 * <li> calls_delay_between int optional
 * <li> calls_count int mandatory
 * <li> status unknown mandatory default 'new'
 * <li> atime int optional
 * <li> agent string(255) optional
 * <li> last_call datetime optional
 * </ul>
 *
 * @package BxUp\CallListTable
 **/

class CallListTable extends Main\Entity\DataManager
{
	/**
	 * Returns DB table name for entity.
	 *
	 * @return string
	 */
	public static function getTableName()
	{
		return 'bdialer_oktell';
	}

	/**
	 * Returns entity map definition.
	 *
	 * @return array
	 */
	public static function getMap()
	{
		return array(
			'id' => array(
				'data_type' => 'integer',
				'primary' => true,
				'autocomplete' => true,
				'title' => Loc::getMessage('OKTELL_ENTITY_ID_FIELD'),
			),
			'client_address_id' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('OKTELL_ENTITY_CLIENT_ADDRESS_ID_FIELD'),
			),
			'client_bitrix_id' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('OKTELL_ENTITY_CLIENT_BITRIX_ID_FIELD'),
			),
			'uid' => array(
				'data_type' => 'string',
				'validation' => array(__CLASS__, 'validateUid'),
				'title' => Loc::getMessage('OKTELL_ENTITY_UID_FIELD'),
			),
			'template' => array(
				'data_type' => 'string',
				'required' => true,
				'title' => Loc::getMessage('OKTELL_ENTITY_TEMPLATE_FIELD'),
			),
			'phone' => array(
				'data_type' => 'integer',
				'required' => true,
				'title' => Loc::getMessage('OKTELL_ENTITY_PHONE_FIELD'),
			),
			'name' => array(
				'data_type' => 'string',
				'required' => true,
				'validation' => array(__CLASS__, 'validateName'),
				'title' => Loc::getMessage('OKTELL_ENTITY_NAME_FIELD'),
			),
			'priority' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('OKTELL_ENTITY_PRIORITY_FIELD'),
			),
			'calls_attempt' => array(
				'data_type' => 'integer',
				'required' => true,
				'title' => Loc::getMessage('OKTELL_ENTITY_CALLS_ATTEMPT_FIELD'),
			),
			'calls_delay_between' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('OKTELL_ENTITY_CALLS_DELAY_BETWEEN_FIELD'),
			),
			'calls_count' => array(
				'data_type' => 'integer',
				'required' => true,
				'title' => Loc::getMessage('OKTELL_ENTITY_CALLS_COUNT_FIELD'),
			),
			'status' => array(
				'data_type' => 'string',
				'required' => true,
				'title' => Loc::getMessage('OKTELL_ENTITY_STATUS_FIELD'),
			),
			'atime' => array(
				'data_type' => 'integer',
				'title' => Loc::getMessage('OKTELL_ENTITY_ATIME_FIELD'),
			),
			'agent' => array(
				'data_type' => 'string',
				'validation' => array(__CLASS__, 'validateAgent'),
				'title' => Loc::getMessage('OKTELL_ENTITY_AGENT_FIELD'),
			),
			'last_call' => array(
				'data_type' => 'datetime',
				'title' => Loc::getMessage('OKTELL_ENTITY_LAST_CALL_FIELD'),
			),
		);
	}
	/**
	 * Returns validators for uid field.
	 *
	 * @return array
	 */
	public static function validateUid()
	{
		return array(
			new Main\Entity\Validator\Length(null, 255),
		);
	}
	/**
	 * Returns validators for name field.
	 *
	 * @return array
	 */
	public static function validateName()
	{
		return array(
			new Main\Entity\Validator\Length(null, 255),
		);
	}
	/**
	 * Returns validators for agent field.
	 *
	 * @return array
	 */
	public static function validateAgent()
	{
		return array(
			new Main\Entity\Validator\Length(null, 255),
		);
	}
}