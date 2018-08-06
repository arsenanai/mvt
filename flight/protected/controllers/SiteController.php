<?php

class SiteController extends Controller
{
	public function actionMessageSave(){
		//header('Content-type: application/json');
		if(isset($_POST['message'])&&isset($_POST['type'])){
			$message = $_POST['message'];
			$message = trim($message);
			$type = $_POST['type'];
			$parsedMessage = $this->parseMessage($type,$message);
			$result = $this->saveMessage($type,json_encode($parsedMessage));
			if($result==='check database connectivity')
				$parsedMessage['error']='check database connectivity. see config/database.php';
			echo json_encode($parsedMessage); exit;
		}else{
			echo 'improper use of function';exit;
		}
	}

	private function parseMessage($type, $message){
		$lines = explode("\n", $message);
		$result = [];
		if(strlen($message)>0){
			if($type==='departure'){
				if(substr_count($lines[0],'/')===1
					&&substr_count($lines[0],'.')===2){
					$p = explode("/",$lines[0]);
					$p2 = explode(".",$p[1]);
					$result['airlineCode'] = substr($p[0],0,3);
					$result['flightNumber'] = substr($p[0],3,5);
					$result['date'] = $p2[0];
					$result['aircraftReg'] = $p2[1];
					$result['departureAirportCode'] = $p2[2];
				}else
					return "can not parse message line 1";
				if(substr_count($lines[1],'AD')===1
					&&substr_count($lines[1],'EA')===1
					&&substr_count($lines[1],'/')===1
					&&substr_count($lines[1],' ')===2){
					$p = explode("/",$lines[1]);
					$result['offblockTime'] = str_replace('AD','',$p[0]);
					$p2 = explode(" ",$p[1]);
					$result['airborneTime'] = $p2[0];
					$result['estimatedArrival'] = str_replace('EA','',$p2[1]);
					$result['destinationAirportCode'] = $p2[2];
				}else
					return "can not parse message line 2";
				if(substr_count($lines[2],'PX')===1){
					$result['passengerInformation'] = str_replace('PX','',$lines[2]);
				}else
					return "can not parse message line 3";
				if(substr_count($lines[3],'DL')===1
					&&substr_count($lines[3],'/')===3){
					$p = explode("/",$lines[3]);
					$result['delayCode1'] = str_replace('DL','',$p[0]);
					$result['delayCode2'] = $p[1];
					$result['delayTime1'] = $p[2];
					$result['delayTime2'] = $p[3];
				}else{
					$result['delayCode1'] = '';
					$result['delayCode2'] = '';
					$result['delayTime1'] = '';
					$result['delayTime2'] = '';
				}
				if(substr_count($message,'SI')===1){
					$result['supplementaryInformation']=trim(explode('SI',$message)[1]);
				}else
					$result['supplementaryInformation']='';
			}else if($type==='arrival'){
				if(substr_count($lines[0],'/')===1
					&&substr_count($lines[0],'.')===2){
					$p = explode("/",$lines[0]);
					$p2 = explode(".",$p[1]);
					$result['airlineCode'] = substr($p[0],0,3);
					$result['flightNumber'] = substr($p[0],3,5);
					$result['date'] = $p2[0];
					$result['aircraftReg'] = $p2[1];
					$result['arrivalAirportCode'] = $p2[2];
				}else
					return "can not parse message line 1";
				if(substr_count($lines[1],'AA')===1
					&&substr_count($lines[1],'/')===1){
					$p = explode("/",$lines[1]);
					$result['touchdownTime'] = str_replace('AA','',$p[0]);
					$result['onblockTime'] = $p[1];
				}else
					return "can not parse message line 2";
				if(substr_count($message,'SI')===1){
					$result['supplementaryInformation']=trim(explode('SI',$message)[1]);
				}else
					$result['supplementaryInformation']='';
			}else if($type==='delay'){
				if(substr_count($lines[0],'/')===1
					&&substr_count($lines[0],'.')===2){
					$p = explode("/",$lines[0]);
					$p2 = explode(".",$p[1]);
					$result['airlineCode'] = substr($p[0],0,3);
					$result['flightNumber'] = substr($p[0],3,5);
					$result['date'] = $p2[0];
					$result['aircraftReg'] = $p2[1];
					$result['departureAirportCode'] = $p2[2];
				}else
					return "can not parse message line 1";
				if(substr_count($lines[1],'ED')===1){
					$result['estimatedDepartureTime'] = str_replace('ED','',$lines[1]);
				}else
					return "can not parse message line 2";
				if(substr_count($lines[2],'DL')===1
					&&substr_count($lines[2],'/')===1){
					$p = explode('/',$lines[2]);
					$result['delayReasonCode1'] = str_replace('DL','',$p[0]);
					$result['delayReasonCode2'] = $p[1];
				}else
					return "can not parse message line 3";
				if(substr_count($message,'SI')===1){
					$result['supplementaryInformation']=trim(explode('SI',$message)[1]);
				}else
					$result['supplementaryInformation']='';
			}else if($type==='estimated time arrival'){
				if(substr_count($lines[0],'/')===1
					&&substr_count($lines[0],'.')===2){
					$p = explode("/",$lines[0]);
					$p2 = explode(".",$p[1]);
					$result['airlineCode'] = substr($p[0],0,3);
					$result['flightNumber'] = substr($p[0],3,5);
					$result['date'] = $p2[0];
					$result['aircraftReg'] = $p2[1];
					$result['departureAirportCode'] = $p2[2];
				}else
					return "can not parse message line 1";
				if(substr_count($lines[1],'EA')===1){
					$result['estimatedArrival']=str_replace('EA','',$lines[1]);
				}else
					return "can not parse message line 2";
				if(substr_count($lines[2],'EB')===1){
					$result['estimatedOnblockTime']=str_replace('EB','',$lines[2]);
				}else
					return "can not parse message line 3";
				if(substr_count($message,'SI')===1){
					$result['supplementaryInformation']=trim(explode('SI',$message)[1]);
				}else
					$result['supplementaryInformation']='';
			}else{
				return "undefined type";
			}
		}else{
			return "message is empty";
		}
		return $result;
	}

	private function saveMessage($type, $parsedMessage){
		try{
			$command = Yii::app()->db->createCommand();
			$count = $command
		    ->select('count(*)')
		    ->from('messages m')
		    //->join('tbl_profile p', 'u.id=p.user_id')
		    ->where('m.type=:type', array(':type'=>$type))
		    ->andWhere('m.message=:message', array(':message'=>$parsedMessage))
		    ->queryColumn();
	    if($count[0]==='0'){
	    	$command->insert('messages', array(
				    'type'=>$type,
				    'message'=>$parsedMessage,
				    'date'=>date("Y-m-d H:i:s")
				));
	    }
	    return true;
  	}catch(\Exception $e){return "check database connectivity";}
	}
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	/**
	 * This is the default 'index' action that is invoked
	 * when an action is not explicitly requested by users.
	 */
	public function actionIndex()
	{
		// renders the view file 'protected/views/site/index.php'
		// using the default layout 'protected/views/layouts/main.php'
		$this->render('index');
	}

	/**
	 * This is the action to handle external exceptions.
	 */
	public function actionError()
	{
		if($error=Yii::app()->errorHandler->error)
		{
			if(Yii::app()->request->isAjaxRequest)
				echo $error['message'];
			else
				$this->render('error', $error);
		}
	}

	/**
	 * Displays the contact page
	 */
	public function actionContact()
	{
		$model=new ContactForm;
		if(isset($_POST['ContactForm']))
		{
			$model->attributes=$_POST['ContactForm'];
			if($model->validate())
			{
				$name='=?UTF-8?B?'.base64_encode($model->name).'?=';
				$subject='=?UTF-8?B?'.base64_encode($model->subject).'?=';
				$headers="From: $name <{$model->email}>\r\n".
					"Reply-To: {$model->email}\r\n".
					"MIME-Version: 1.0\r\n".
					"Content-Type: text/plain; charset=UTF-8";

				mail(Yii::app()->params['adminEmail'],$subject,$model->body,$headers);
				Yii::app()->user->setFlash('contact','Thank you for contacting us. We will respond to you as soon as possible.');
				$this->refresh();
			}
		}
		$this->render('contact',array('model'=>$model));
	}

	/**
	 * Displays the login page
	 */
	public function actionLogin()
	{
		$model=new LoginForm;

		// if it is ajax validation request
		if(isset($_POST['ajax']) && $_POST['ajax']==='login-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}

		// collect user input data
		if(isset($_POST['LoginForm']))
		{
			$model->attributes=$_POST['LoginForm'];
			// validate user input and redirect to the previous page if valid
			if($model->validate() && $model->login())
				$this->redirect(Yii::app()->user->returnUrl);
		}
		// display the login form
		$this->render('login',array('model'=>$model));
	}

	/**
	 * Logs out the current user and redirect to homepage.
	 */
	public function actionLogout()
	{
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->homeUrl);
	}
}