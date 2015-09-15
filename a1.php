<!DOCTYPE html>
<html>
    <!----head---->
    <head>
    <title>Simple ATM(Automatic Teller Machine)</title>
    </head>
    <!----body of php file operation--->    
    <body>
    <?php
    require 'account.php';
    //transaction class for all transaction related operations
    class Transaction{
        private $line;
        private $id;
        private $type;
        private $amount;
        private $isValid = true;
        
        //constructer
        public function __construct($line,$id,$type,$amount) {
            $this->line = $line;
            $this->id = $id;
            $this->type = $type;
            $this->amount = $amount;        
            //echo 'constructor '.$this->line.' '.$this->id.' '.$this->type.' '.$this->amount.' ';
            //echo '<br>';
        }
        
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
    }

    $invalidCounter = 0;
    $tranzCounter=0;
    $invalidTransArray = array();
    $transArray = array();
    $accountArray=array();

    //--------------open acct.txt------------------
    $fp = fopen("acct.txt", "r");
    if ($fp) {
        //read file line by line
        while (($accountLineRead = fgets($fp)) !== false) {

            //split the account line read by regular expression
            $accountInfo =preg_split("/[\s,]+/", $accountLineRead);
            
            //store information to account instance
            $account=new Account();
            $account->id=intval($accountInfo[0]);
            $account->balance=floatval($accountInfo[1]);
            //echo $account->id.' '.$account->balance.'<br/>';
            //store account instance into account array
            $accountArray[$account->id] = $account;
        }
        fclose($fp);
    } else {
        // error opening the file.
    } 
    //echo '<br><br>';
    //print_r($accountArray);

    //---------------open tranz.txt-----------------------
    $file=fopen("tranz.txt","r")or die("Unable to open file!");
    //ignore first line
    $transLineRead = fgets($file);
    while (($transLineRead = fgets($file)) !== false) {
        //transaction counter
        $tranzCounter++;
    
        //split the transaction line read by regular expression
        $transInfo =preg_split("/[\s,]+/", $transLineRead);
                
        //store transaction info into transaction instance
        $trans = new Transaction(intval($tranzCounter),intval($transInfo[0]),$transInfo[1],floatval($transInfo[2]));
        
        //store transaction instance into transaction array
        $transArray[intval($tranzCounter)] = $trans;

    }
    fclose($file);
    //account object array
    // print_r($accountArray);

    //main transaciton operation logic, loop through transArray
    foreach ($transArray as &$trans) {
        //if account id of current transation exists in account array 
        if (array_key_exists($trans->id , $accountArray)){
            $isSucceed = $accountArray[$trans->id]->transactionValidation($trans->type,$trans->amount);    
            if(!$isSucceed){
                $invalidTransArray[$trans->line] = $trans;
                $invalidCounter++;
            }
        }else{
            
            $invalidTransArray[$trans->line] = $trans;
            $invalidCounter++;
        }
    }
   //print_r($accountArray);
    $validCounter=$tranzCounter-$invalidCounter;

    //---------------open update.txt-----------------------
    $file = 'update.txt';
    foreach($accountArray as &$accounts){
    $current = $accounts->id.' '.$accounts->balance.PHP_EOL;
    file_put_contents($file, $current, FILE_APPEND | LOCK_EX);   
    }

    ?>

    <?php
    //print out total transactions and valid transactions
    echo '<p><strong>There were '.$tranzCounter.' transaction in total.</strong></p>';
    echo '<p><strong>There were '.$validCounter.' valid transaction in total.</strong></p>';
    ?>
     <!----Invalid Transactions table---->
    <table border="2" style="text-align:center;">
        <caption>Invalid Transactions</caption>
        <tr>
            <th>Line #</th>
            <th>ID</th> 
            <th>Type</th>
            <th>Amount</th>
        </tr>
    <?php
    //print out invalid transacitons in table format
    foreach ($invalidTransArray as $trans) {
        echo "<tr>";
        echo '<td>'.$trans->line.'</td>';
        echo '<td>'.$trans->id.'</td>';
        echo '<td>'.$trans->type.'</td>';
        echo '<td>$'.$trans->amount.'</td>';

        echo "</tr>";
    }
    ?>
    </table>

    </body>
 
</html> 
