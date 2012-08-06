<?php

/**
 * This is the model class for table "{{admin}}".
 *
 * The followings are the available columns in table '{{admin}}':
 * @property integer $id
 * @property string $a_login
 * @property string $a_password
 * @property string $name
 * @property integer $super_admin
 *
 * @author    MobiDev Corporation
 * @license   http://mobidev.biz/backvendor_license
 * @link      http://mobidev.biz/backvendor
 */
class Admin extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @return Admin the static model class
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
		return '{{admin}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('a_login, a_password', 'required'),
			array('super_admin', 'numerical', 'integerOnly'=>true),
			array('a_login, a_password, name', 'length', 'max'=>45),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, a_login, a_password, name, super_admin', 'safe', 'on'=>'search'),
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
			'a_login' => 'Login',
			'a_password' => 'Password',
			'name' => 'Name',
			'super_admin' => 'Super Admin',
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

		$criteria->compare('id',$this->id);
		$criteria->compare('a_login',$this->a_login,true);
		$criteria->compare('a_password',$this->a_password,true);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('super_admin',$this->super_admin);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}