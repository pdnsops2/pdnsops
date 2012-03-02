<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	const ROLE_ADMIN = 'admin';
	
	const ROLE_USER = 'user';

	private $id;
		
	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$user = User::model()->findByAttributes(array('username'=>$this->username));
		if($user === null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if($user->password !== SHA1($this->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
		{
			$this->errorCode=self::ERROR_NONE;
			$this->id = $user->id;
			$this->setState('isAdmin', $user->admin == 1);
			
            $auth=Yii::app()->authManager; // Initialize Auth Manager
            
            // Clear all previously set roles (from previous logins w/o logout)
            foreach ($auth->getAuthItems(2, $this->id) as $authItem) 
			{
				$auth->revoke($authItem->name, $this->id);
            }

			if ($user->admin == 1)
			{
				$auth->assign(UserIdentity::ROLE_ADMIN, $this->id);
            }
			else
			{
				$auth->assign(UserIdentity::ROLE_USER, $this->id);			
			}
           
			// Save new roles to auth manager
			Yii::app()->authManager->save();
		}
		return !$this->errorCode;
	}

	public function getId()
	{
		return $this->id;
	}
}