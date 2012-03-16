<?php

/**
 * This is the model class for table "records".
 *
 * The followings are the available columns in table 'records':
 * @property integer $id
 * @property integer $domain_id
 * @property string $name
 * @property string $type
 * @property string $content
 * @property integer $ttl
 * @property integer $prio
 * @property integer $change_date
 */
class Record extends CActiveRecord
{
	const TYPE_SOA = 'SOA';
	
	const TYPE_MX = 'MX';
	
	const TYPE_A = 'A';
	
	const TYPE_NS = 'NS';
	
	const TYPE_CNAME = 'CNAME';
		
	const PRIORITY_DEFAULT = 0;
	
	const PRIORITY_MX = 10;
		
	const PREFIX_MX = 'mail.';
	
	const PREFIX_CNAME = 'www.';
	
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Record the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Initializes the model.
	 */
	public function init()
	{
		parent::init();
		$this->ttl = Yii::app()->settings->defaultTTL;
		$this->prio = Record::PRIORITY_DEFAULT;
	}

	
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'records';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('domain_id, ttl, prio', 'numerical', 'integerOnly'=>true),
			array('name,ttl,prio', 'required'),
			array('name', 'length', 'max'=>255),
			array('type', 'length', 'max'=>10),
			array('content', 'length', 'max'=>4096),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, domain_id, name, type, content, ttl, prio', 'safe', 'on'=>'search'),
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
			'domain' => array(self::BELONGS_TO, 'Domain', 'domain_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => Yii::t('app', 'record.id'),
			'domain_id' => Yii::t('app', 'record.domain'),
			'name' => Yii::t('app', 'record.name'),
			'type' => Yii::t('app', 'record.type'),
			'content' => Yii::t('app', 'record.content'),
			'ttl' => Yii::t('app', 'record.ttl'),
			'prio' => Yii::t('app', 'record.prio'),
			'change_date' => Yii::t('app', 'record.change_date'),
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
		$criteria->compare('domain_id',$this->domain_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('content',$this->content,true);
		$criteria->compare('ttl',$this->ttl);
		$criteria->compare('prio',$this->prio);
		$criteria->compare('change_date',$this->change_date);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	public function getTypes()
	{
		return array(Record::TYPE_SOA => Record::TYPE_SOA, 
					 Record::TYPE_MX => Record::TYPE_MX, 
					 Record::TYPE_A => Record::TYPE_A, 
					 Record::TYPE_NS => Record::TYPE_NS, 
					 Record::TYPE_CNAME => Record::TYPE_CNAME);
	}
}