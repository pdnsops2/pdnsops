<?php

class DomainController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column2';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',
				'actions'=>array('index','create','createReverse','update','delete','copy'),
				'roles'=>array('user','admin'),
			),
			array('deny',
                'users'=>array('*'),
            ),
		);
	}

	private function createRecord($name, $type, $content)
	{
		$record = new Record;
		$record->name = $name;
		$record->type = $type;
		$record->content = $content;
		$record->change_date = time();
		return $record;
	}
	
	/**
	 * Creates new SOA record.
	 */
	private function createSOA($model)
	{
		$settings = Yii::app()->settings;
		return $this->createRecord($model->name, Record::TYPE_SOA, $settings->ns1 . ' ' . 'ADMIN_INCOMING' . ' ' . date('Ymd00') . ' ' . 
					$settings->soaRefresh . ' ' . $settings->soaRetry . ' ' . $settings->soaExpire . ' ' . $settings->defaultTTL);
	}

	/**
	 * Creates a new A record.
	 */
	private function createA($model)
	{
		return $this->createRecord($model->name, Record::TYPE_A, $model->ip_address);
	}
	
	/**
	 * Creates new NS record.
	 */
	private function createNS($model, $ns)
	{
		return $this->createRecord($model->name, Record::TYPE_NS, $ns);
	}
	
	/**
	 * Creates new MX record.
	 */
	private function createMX($model)
	{
		$record = $this->createRecord($model->name, Record::TYPE_MX, Record::PREFIX_MX . $model->name);
		$record->prio = Record::PRIORITY_MX;
		return $record;
	}
	
	private function createCNAME($model)
	{
		return $this->createRecord(Record::PREFIX_CNAME . $model->name, Record::TYPE_CNAME, $model->name);
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Domain;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Domain']))
		{		
			$model->attributes=$_POST['Domain'];			

			if ($model->validate()) 
			{
				$valid = true;
				$records = array();

				// Only add the SOA and A records if the domain is not a SLAVE. Slave domains should be pulling this from their regular updates.
				if ($model->type != 'SLAVE') 
				{
					// Master IP is not needed
					$model->master = '';
				
					$records[] = $this->createSOA($model);
					$records[] = $this->createA($model);
				}
				
				// NS records
				if ($model->add_default_ns_records == 1)
				{
					$settings = Yii::app()->settings;
					for ($i = 1; $i <= $settings::MAX_NAMESERVERS; $i++)
					{
						$ns = $settings->{"ns$i"};
						if ($ns === null || $ns == '')
							break;

						$records[] = $this->createNS($model, $ns);
					}
				}
						
				// MX record
				if ($model->add_default_mx_record == 1)
				{
					$records[] = $this->createMX($model);
					// Insert the mail.domain A record for the new domain
					$records[] = $this->createRecord(Record::PREFIX_MX . $model->name, Record::TYPE_A, $model->ip_address);
				}
						
				// CNAME records
				if ($model->add_default_cname_records == 1)
				{
					$records[] = $this->createCNAME($model);
				}
				
				// validate all
				foreach ($records as $record)
				{
					$valid = $record->validate() && $valid;
				}
				
				if ($valid === true)
				{
					$model->save(false);
					
					Yii::app()->audit->log('Created domain: ' . $model->id);
					
					foreach ($records as $record)
					{
						$record->domain_id = $model->id;
						$record->save(false);
					}
					
					// finally, create permission record
					$perm = new DomainUser;
					$perm->domain_id = $model->id;
					$perm->user_id = Yii::app()->user->getId();
					$perm->save();
					
					$this->redirect(array('update','id'=>$model->id));
				}
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}
	
	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreateReverse()
	{
		$model=new Domain;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Domain']))
		{
			$model->attributes=$_POST['Domain'];
			$settings = Yii::app()->settings;
			
			if ($model->type == 'SLAVE')
			{
				$model->master = $settings->domainMasterIP;
			}
						
			if ($model->validate())
			{
				$valid = true;
				$records = array();

				// Only add the SOA record if the domain is not a SLAVE.
				if ($model->type != 'SLAVE') 
				{
					$records[] = $this->createSOA($model);
				}
				
				// NS records
				if ($model->add_default_ns_records == 1)
				{
					for ($i = 1; $i <= $settings::MAX_NAMESERVERS; $i++)
					{
						$ns = $settings->{"ns$i"};
						if ($ns === null || $ns == '')
							break;

						$records[] = $this->createNS($model, $ns);
					}
				}				

				// validate all
				foreach ($records as $record)
				{
					$valid = $record->validate() && $valid;
				}
				
				if ($valid === true)
				{
					$model->save(false);

					Yii::app()->audit->log('Created reverse domain: ' . $model->id);
					
					foreach ($records as $record)
					{
						$record->domain_id = $model->id;
						$record->save(false);
					}
					
					// finally, create permission record
					$perm = new DomainUser;
					$perm->domain_id = $model->id;
					$perm->user_id = Yii::app()->user->getId();
					$perm->save();
					
					$this->redirect(array('update','id'=>$model->id));
				}				
			}
		}

		$this->render('createReverse',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Domain']))
		{
			$model->attributes=$_POST['Domain'];
			if($model->save())
			{
				Yii::app()->audit->log('Updated domain: ' . $model->id);
				$this->redirect(array('update','id'=>$model->id));
			}
		}
			$this->render('update',array(
				'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'admin' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// delete all permissions
			$models=DomainUser::model()->findAllByAttributes(array('domain_id'=>$id));
			foreach ($models as $model)
			{
				$model->delete();
			}
		
			// we only allow deletion via POST request
			$model = $this->loadModel($id);
			
			foreach ($model->records as $record)
			{
				$record->delete();
			}
						
			$model->delete();

			Yii::app()->audit->log('Deleted domain: ' . $model->id);

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{	
		$model=new Domain('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Domain']))
			$model->attributes=$_GET['Domain'];

		$userId = Yii::app()->user->getId();
		$user=User::model()->findByPK($userId);
		
		$this->render('index',array(
			'model'=>$model,
			'user'=>$user,
		));
	}

	/**
	 * Creates a deep copy of a domain and it's associated records.
	 */
	public function actionCopy($id)
	{
		$oldmodel=$this->loadModel($id);

		$model=new Domain;
		$model->name=$oldmodel->name;
		$model->master=$oldmodel->master;
		$model->last_check=$oldmodel->last_check;
		$model->type=$oldmodel->type;
		$model->notified_serial=$oldmodel->notified_serial;
		$model->account=$oldmodel->account;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Domain']))
		{
			$model->attributes=$_POST['Domain'];
			
			if($model->save())
			{
				// copy records
				if ($model->copy_records == 1)
				{
					foreach ($oldmodel->records as $or)
					{
						$record=new Record;
						$record->domain_id=$model->id;
						$record->name=$or->name;
						$record->type=$or->type;
						$record->content=$or->content;
						$record->ttl=$or->ttl;
						$record->prio=$or->prio;
						$record->change_date=$or->change_date;
						$record->save();
					}
				}
				
				// copy permissions
				if ($model->copy_permissions == 1)
				{
					foreach ($oldmodel->users as $user)
					{
						$perm=new DomainUser;
						$perm->domain_id=$model->id;
						$perm->user_id=$user->id;
						$perm->save();
					}
				}
			
				Yii::app()->audit->log('Copied domain: ' . $model->id);
				$this->redirect(array('update','id'=>$model->id));
			}
		}
			
		$this->render('copy',array(
				'model'=>$model,
		));
	}
	
	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Domain::model()->findByPk($id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='domain-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}