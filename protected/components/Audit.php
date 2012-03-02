<?php

class Audit extends CApplicationComponent
{
	public function log($message)
	{
		$model=new Log;
		$model->user_id=Yii::app()->user->getId();
		$model->action=$message;
		$model->created=date('Y-m-d H:i:s');
		$model->save();
	}
}