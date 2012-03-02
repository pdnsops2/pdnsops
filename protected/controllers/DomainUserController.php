<?php

class DomainUserController extends Controller
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
				'actions'=>array('create','delete'),
				'roles'=>array('admin'),
			),
			array('deny',
                'users'=>array('*'),
            ),
		);
	}

	public function actionCreate()
	{
		$model = new DomainUser;
	
		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['DomainUser']))
		{
			$model->attributes=$_POST['DomainUser'];
			
			// TODO: check if link already exists before inserting
						
			if($model->save())
			{
				Yii::app()->audit->log('Linked domain ' . $model->domain_id . ' to user ' . $model->user_id);
			}

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			if(!isset($_GET['ajax']))
				$this->redirect($_GET['returnUrl']);
		}
		else
		{
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		}		
	}
	
	/**
	 * Unlinks a connection between a user and a domain.
	 */
	public function actionDelete($domain_id, $user_id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			$perm = DomainUser::model()->findByAttributes(array('domain_id'=>$domain_id, 'user_id'=>$user_id));			
			if ($perm != null)
			{
				$perm->delete();
				Yii::app()->audit->log('Unlinked domain ' . $domain_id . ' from user ' . $user_id);
			}			

			// if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
			//if(!isset($_GET['ajax'])) // TODO: where to redirect?
			//	$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('update','id'=>$id));
		}
		else
		{
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
		}

	}
}