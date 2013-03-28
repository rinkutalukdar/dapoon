<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	//public function authenticate()
	//{
	//	$users=array(
	//		// username => password
	//		'demo'=>'demo',
	//		'admin'=>'admin',
	//	);
	//	if(!isset($users[$this->username]))
	//		$this->errorCode=self::ERROR_USERNAME_INVALID;
	//	elseif($users[$this->username]!==$this->password)
	//		$this->errorCode=self::ERROR_PASSWORD_INVALID;
	//	else
	//		$this->errorCode=self::ERROR_NONE;
	//	return !$this->errorCode;
	//}
	
	
	private $_id;
	/**
	* Logs in the user using the given username and password in the
	model.
	* @return boolean whether login is successful
	*/
	public function login()
	{       
		if($this->_identity===null)
		{
			$this->_identity=new UserIdentity($this->username,$this->password);
			$this->_identity->authenticate();
		}
		if($this->_identity->errorCode===UserIdentity::ERROR_NONE)
		{
			$duration=$this->rememberMe ? 3600*24*30 : 0; // 30 days
			Yii::app()->user->login($this->_identity,$duration);
			return true;
		}
		else
			return false;
	}
	public function getId()
	{
		return $this->_id;
	}
	

	
	public function authenticate()
	{
		$user=User::model()->find('LOWER(username)=?',array(strtolower($this->username)));
		//print_r($user, 1);
	//die();
		if($user===null)
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if(!$user->validatePassword($this->password))
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else
		{
			$this->_id=$user->id;
			$this->username=$user->username;
			$this->setState('lastLogin', date("m/d/y g:i A", strtotime($user->last_login_time)));
			$user->saveAttributes(array(
				'last_login_time'=>date("Y-m-d H:i:s", time()),
			));
			$this->errorCode=self::ERROR_NONE;
		}
		return $this->errorCode==self::ERROR_NONE;
	}
	
	public function rules()
	{
		return array(
			// username and password are required
			array('username, password', 'required'),
			// rememberMe needs to be a boolean
			array('rememberMe', 'boolean'),
			// password needs to be authenticated
			array('password', 'authenticate'),
		);
	}

}