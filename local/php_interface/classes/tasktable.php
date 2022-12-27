<?php

namespace ORM;

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ORM\Data\DataManager,
    Bitrix\Main\ORM\Fields\FloatField,
    Bitrix\Main\ORM\Fields\IntegerField,
    Bitrix\Main\ORM\Fields\StringField,
    Bitrix\Main\ORM\Fields\Validators\LengthValidator;

Loc::loadMessages(__FILE__);
//echo __FILE__;

/**
 * Class TaskTable
 *
 * Fields:
 * <ul>
 * <li> ID int mandatory
 * <li> NAME string(255) mandatory
 * <li> PRICE double optional default 0
 * <li> CLIENT_ID int mandatory
 * <li> STATUS_ID int mandatory
 * </ul>
 *
 * @package ORM
 **/
class TaskTable extends DataManager
{
    /**
     * Returns DB table name for entity.
     *
     * @return string
     */
    public static function getTableName()
    {
        return 'px_task';
    }

    /**
     * Returns entity map definition.
     *
     * @return array
     */
    public static function getMap()
    {
        return [
            new IntegerField(
                'ID',
                [
                    'primary' => true,
                    'autocomplete' => true,
                    'title' => Loc::getMessage('TASK_ENTITY_ID_FIELD')
                ]
            ),
            new StringField(
                'NAME',
                [
                    'required' => true,
                    'validation' => [__CLASS__, 'validateName'],
                    'title' => Loc::getMessage('TASK_ENTITY_NAME_FIELD')
                ]
            ),
            new FloatField(
                'PRICE',
                [
                    'default' => 0,
                    'title' => Loc::getMessage('TASK_ENTITY_PRICE_FIELD')
                ]
            ),
            new IntegerField(
                'CLIENT_ID',
                [
                    'required' => true,
                    'title' => Loc::getMessage('TASK_ENTITY_CLIENT_ID_FIELD')
                ]
                /*,for getList()
                'runtime' => [
                    'PROPERTY_LIST' => [
                        'data_type' => $entityPropertyList->getDataClass(),
                        'reference' => [
                            '=this.ID' => 'ref.IBLOCK_ID',
                        ],
                        'join_type' => 'left'
                    ]
                ]*/
            ),
            new IntegerField(
                'STATUS_ID',
                [
                    'required' => true,
                    'title' => Loc::getMessage('TASK_ENTITY_STATUS_ID_FIELD')
                ]
            ),
        ];
    }

    /**
     * Returns validators for NAME field.
     *
     * @return array
     */
    public static function validateName()
    {
        return [
            new LengthValidator(null, 255),
        ];
    }
}
