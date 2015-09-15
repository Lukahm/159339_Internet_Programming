<?php

class Account{

    private $id;
    private $balance;
    
    //__get()
    public function __get($propertyName){ 
        if(isset($this->$propertyName)) { 
            return($this->$propertyName); 
        }else { 
            return(NULL); 
        }
    }
    //__set()
    public function __set($propertyName, $value){ 
        $this->$propertyName = $value; 
    } 

    //method of withdrawing money    
    public function withdraw($transactionAmount){
        if($transactionAmount<0 || $transactionAmount>($this->balance)){
            return false;                 
        } else {
            $this->balance-=$transactionAmount;
            //echo 'account '.$this->id.' withdraw amount:'.$transactionAmount.' balance: '.$this->balance.'<br>';
            return true;
        }
    }

    //method of depositinging money    
    public function deposit($transactionAmount){
    	if($transactionAmount<0){
            return false;            
        }else {
            $this->balance+=$transactionAmount;
            //echo 'account '.$this->id.' deposit amount:'.$transactionAmount.' balance: '.$this->balance.'<br>';
            return true;
        }        
    }
            
    //method of transaction validation
    public function transactionValidation($transactionType, $transactionAmount){
        // if transaction is succeed, return true
    	if($transactionType === 'W'){
            return $this->withdraw($transactionAmount);
    	}else if($transactionType === 'D'){
    		return $this->deposit($transactionAmount);
    	}else{
    		return false;
    	}
    }
}

?>




