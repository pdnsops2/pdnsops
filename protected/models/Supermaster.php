<?php

/**
 * This is the model class for table "supermasters".
 *
 * The followings are the available columns in table 'supermasters':
 * @property string $ip
 * @property string $nameserver
 * @property string $account
 */
class Supermaster extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Supermaster the static model class
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
		return 'supermasters';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('ip, nameserver', 'required'),
			array('ip', 'unique', 'message'=>Yii::t('app','supermaster.ipInUse')),
			array('ip', 'length', 'max'=>15),
			array('ip', 'ext.validators.FIpValidator', 'version' => 'ipv4'),
			array('nameserver', 'length', 'max'=>255),
			array('account', 'length', 'max'=>40),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('ip, nameserver, account', 'safe', 'on'=>'search'),
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
			'ip'=>Yii::t('app','supermaster.ip'),
			'nameserver'=>Yii::t('app','supermaster.nameserver'),
			'account'=>Yii::t('app','supermaster.account'),
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

		$criteria->compare('ip',$this->ip,true);
		$criteria->compare('nameserver',$this->nameserver,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getPrimaryKey()
	{
		return $this->ip;
	}
	
	public function getTableSchema()
	{
		$res = parent::getTableSchema();
		$res->primaryKey = 'ip';
		return $res;
	}
}