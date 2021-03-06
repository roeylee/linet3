<?php
/***********************************************************************************
 * The contents of this file are subject to the Mozilla Public License Version 2.0
 * ("License"); You may not use this file except in compliance with the Mozilla Public License Version 2.0
 * The Original Code is:  Linet 3.0 Open Source
 * The Initial Developer of the Original Code is Adam Ben Hur.
 * All portions are Copyright (C) Adam Ben Hur.
 * All Rights Reserved.
 ************************************************************************************/
class FormMprofloss extends CFormModel
{
    public $year;
    
    
    
    public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			
			array('to_date, from_date, year', 'safe'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('to_date, from_date, year', 'safe', 'on'=>'search'),
		);
	}
    
    private function calc($account_type){
        $criteria=new CDbCriteria;
        
        $criteria->condition="type = :type";
        $criteria->params=array(
            ':type' => $account_type,
          );

        $accounts= Accounts::model()->findAll($criteria);
        $sum=0;
        $data=array();
        $stime="00:00:01";
        $etime="23:59:59";
        $from_date="01/01/$this->year $stime";
        $to_date="31/12/$this->year $etime";
        foreach($accounts as $account){
                
                $sum=$account->getTotal($from_date,$to_date); 
                if($sum!=0){
                    $accounty=array(
                        'id'=>$account->id,
                        'name'=>$account->name,
                        'sum'=>$sum,
                        'id6111'=>$account->id6111
                            );

                    for ($x=1; $x<=12; $x++){
                        if($x<=9)
                            $a="0$x";
                        else
                             $a=$x;
                        
                        $last = 31;   
                        while(!checkdate($x, $last, $this->year)) {
                                $last--;
                        }
                        $accounty[$x]=$account->getTotal("01/$a/$this->year $stime","$last/$a/$this->year $etime");
                        
                    }

                    $data[]= $accounty;
                    
                }
        }
        return $data;
    }
    
    public function search(){
        
        
        $data=$this->calc(3);//incomes
        //$data=array_merge($data,$this->calc(2));//outcomes
        
        
        return new CArrayDataProvider($data,
                 array(
                                'pagination'=>array(
                                    'pageSize'=>100,
                            ),
                )             
          );
    }
    //put your code here
}
