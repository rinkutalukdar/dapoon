<?php

/**
 * This is the model class for table "tbl_user".
 *
 * The followings are the available columns in table 'tbl_user':
 * @property integer $id
 * @property string $username
 * @property string $password
 * @property string $email
 */
class User extends TrackStarActiveRecord
{
	public $password_repeat;
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
		return 'tbl_user';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('email, username, password, password_repeat', 'required'),
			array('email, username, password', 'length', 'max'=>255),
			array('email, username', 'unique'),
			array('password', 'compare'),
			//array('password', 'compare', 'compareAttribute'=>'confirmPassword'),
			array('password_repeat', 'safe'),
			array('email', 'email'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, email, username, password, last_login_time, create_time, create_user_id, update_time, update_user_id', 'safe', 'on'=>'search'),
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
			'username' => 'Username',
			'password' => 'Password',
			'email' => 'Email',
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
		$criteria->compare('username',$this->username,true);
		$criteria->compare('password',$this->password,true);
		$criteria->compare('email',$this->email,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
	
	/**
	* apply a hash on the password before we store it in the database
	*/
	protected function afterValidate()
	{
		parent::afterValidate();
		if(!$this->hasErrors())
			$this->password = $this->hashPassword($this->password);
	}
	/**
	* Generates the password hash.
	* @param string password
	* @return string hash
	*/
	public function hashPassword($password)
	{
		return md5($password);
	}
	
	public function validatePassword($password)
	{
		return $this->hashPassword($password)===$this->password;
	}
	
	//public function behaviors()
	//{
	//	return array(
	//		'CTimestampBehavior' => array(
	//		'class' => 'zii.behaviors.CTimestampBehavior',
	//		'createAttribute' => 'create_time',
	//		'updateAttribute' => 'update_time',
	//		'setUpdateOnCreate' => true,
	//		),
	//	);
	//}
}