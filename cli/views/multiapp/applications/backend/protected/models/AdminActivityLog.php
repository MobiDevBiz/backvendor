<?php

/**
 * This is the model class for table "{{admin_activity_log}}".
 *
 * The followings are the available columns in table '{{admin_activity_log}}':
 * @property integer $id
 * @property string $date
 * @property string $ip
 * @property string $admin_name
 * @property string $action
 * @property string $entity
 * @property string $message
 * @property string $additional
 *
 * @author    MobiDev Corporation
 * @license   http://mobidev.biz/backvendor_license
 * @link      http://mobidev.biz/backvendor
 */
class AdminActivityLog extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return AdminActivityLog the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return '{{admin_activity_log}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('date, admin_name, message', 'required'),
			array('ip, action', 'length', 'max'=>20),
			array('entity', 'length', 'max'=>30),
			array('additional', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, date, ip, admin_name, action, entity, message, additional', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'date' => 'Date',
			'ip' => 'Ip',
			'admin_name' => 'Admin Name',
			'action' => 'Action',
			'entity' => 'Entity',
			'message' => 'Message',
			'additional' => 'Additional',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('t.id',$this->id);
		$criteria->compare('date',$this->date,true);
		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('admin_name',$this->admin_name,true);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('entity',$this->entity,true);
		$criteria->compare('message',$this->message,true);
		$criteria->compare('additional',$this->additional,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}