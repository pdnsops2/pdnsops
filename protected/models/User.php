<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 * @property integer $admin
 *
 * The followings are the available model relations:
 * @property Domains[] $domains
 */
class User extends CActiveRecord
{
	public $newPassword;
	
	public $newPasswordRepeat;

	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return User the static model class
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
		return 'user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('username', 'required'),
			array('username', 'unique', 'message'=>Yii::t('app','user.usernameTaken')),
			array('email', 'required'),
			array('email', 'email'),
			array('password', 'required'),
			array('newPassword', 'ext.validators.EPasswordStrength', 'min'=>8),
			array('newPassword', 'compare', 'compareAttribute'=>'newPasswordRepeat'),
			array('admin', 'numerical', 'integerOnly'=>true),
			array('username, newPassword, newPasswordRepeat, password, email', 'length', 'max'=>128),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, username, email, admin', 'safe', 'on'=>'search'),
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
			'domains'=>array(self::MANY_MANY, 'Domain', 'domain_user(user_id, domain_id)'),
			'logs'=>array(self::HAS_MANY, 'Log', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id'=>Yii::t('app','user.id'),
			'username'=>Yii::t('app','user.username'),
			'password'=>Yii::t('app','user.password'),
			'email'=>Yii::t('app','user.email'),
			'admin'=>Yii::t('app','user.admin'),
			'newPassword'=>Yii::t('app','user.password') ,
			'newPasswordRepeat'=>Yii::t('app','user.passwordRepeat'),
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);
		$criteria->compare('admin',$this->admin);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}