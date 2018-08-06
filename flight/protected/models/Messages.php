<?php
class Messages extends CActiveRecord
{
	public $id;
	public $type;
	public $message;
	public $date;
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}