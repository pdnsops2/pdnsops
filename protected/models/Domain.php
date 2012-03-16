<?php

/**
 * This is the model class for table "domains".
 *
 * The followings are the available columns in table 'domains':
 * @property integer $id
 * @property string $name
 * @property string $master
 * @property integer $last_check
 * @property string $type
 * @property integer $notified_serial
 * @property string $account
 *
 * The followings are the available model relations:
 * @property User[] $users
 */
class Domain extends CActiveRecord
{
	public $ip_address;
	
	public $add_default_ns_records;
	
	public $add_default_mx_record;
	
	public $add_default_cname_records;
	
	public $copy_records;
	
	public $copy_permissions;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Domain the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	public function init()
	{
		$this->notified_serial = 1;
	}
	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'domains';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name', 'required'),
			array('name', 'length', 'max'=>255),
			array('name', 'unique', 'message' => 'Domain name already taken.'),
			array('ip_address', 'length', 'max'=>15),
			array('ip_address', 'ext.validators.FIpValidator', 'version' => 'ipv4'),
			array('type', 'length', 'max'=>6),
			array('master', 'length', 'max'=>15),
			array('master', 'ext.validators.FIpValidator', 'version' => 'ipv4'),
			array('add_default_ns_records, add_default_mx_record, add_default_cname_records, copy_records, copy_permissions', 'boolean'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, name, master, last_check, type, notified_serial, account', 'safe', 'on'=>'search'),
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
			'records'=>array(self::HAS_MANY, 'Record', 'domain_id'),
			'users' => array(self::MANY_MANY, 'User', 'domain_user(domain_id, user_id)'),
			);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>Yii::t('app','domain.id'),
			'name'=>Yii::t('app','domain.name'),
			'ip_address'=>Yii::t('app','domain.ipAddress'),
			'master'=>Yii::t('app','domain.masterIP'),
			'last_check'=>Yii::t('app','domain.lastCheck'),
			'type'=>Yii::t('app','domain.type'),
			'add_default_ns_records'=>Yii::t('app','domain.addDefaultNSRecords'),
			'add_default_mx_record'=>Yii::t('app','domain.addDefaultMXRecord'),
			'add_default_cname_records'=>Yii::t('app','domain.addDefaultCNAMERecords'),			
			'copy_records'=>Yii::t('app','domain.copyRecords'),
			'copy_permissions'=>Yii::t('app','domain.copyPermissions'),
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
		$criteria->compare('name',$this->name,true);
		$criteria->compare('master',$this->master,true);
		$criteria->compare('last_check',$this->last_check);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('notified_serial',$this->notified_serial);
		$criteria->compare('account',$this->account,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}