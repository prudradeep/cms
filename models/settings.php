<?php
/*
*Author:	Pradeep Rajput
*Email:		prithviraj.rudraksh@gmail.com 
*Website:	----------
*Model:		Settings
*Class:		Settings
*/

if (! defined('PHINDART')) { die('Access denied'); }

class Settings extends Query{
	protected $fetchMode=PDO::FETCH_CLASS|PDO::FETCH_PROPS_LATE;

	public function defineVar(){
        if(!defined($this->field)) define($this->field, $this->value);
    }

    public function getValueByField($field){
    	$data = $this->select('value')->Eq('field', $field)->andEq('status', 1)->limit(1)->prepare()->execute()->fetch();
    	return $data->value;
    }

    public function getValue(){
    	return $this->value;
    }

    public function getField(){
    	return $this->field;
    }

    public function getAll(){
        return ['id'=>$this->id,'type'=>$this->type, 'field'=>$this->field, 'value'=>$this->value, 'status'=>$this->status];
    }

}