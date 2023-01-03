<?php 

	abstract class BankAccount
  {

		protected $Balance = 0;

		public $APR;

		public $SortCode;

		public $FirstName;

		public $LastName;

		public $Audit = array(); //define lika an array so that we can push in it

		protected $Locked = false;

		//Methods

		public function WithDraw( $amount ){

			$transDate = new DateTime(); //when this function is invoked it will create a new Time object

			if( $this->Locked === false ){

				$this->Balance -= $amount; //take away amount from balance 

				array_push( $this->Audit, array("WITHDRAW ACCEPTED", $amount, $this->Balance, $transDate->format('c') ) ); //push in$Auudit array, nest another array with $amount,balance after withdraw and date

			} else {

				array_push( $this->Audit, array("WITHDRAW DENIED", $amount, $this->Balance, $transDate->format('c') ) ); //nothin happen is LOCKED account

			}

		}

		public function Deposit( $amount ){

			$transDate = new DateTime();

			if( $this->Locked === false ){

				$this->Balance += $amount;  //adding to existing account certain amount

				array_push( $this->Audit, array("DEPOSIT ACCEPTED", $amount, $this->Balance, $transDate->format('c') ) );

			} else {

				array_push( $this->Audit, array("DEPOSIT DENIED", $amount, $this->Balance, $transDate->format('c') ) );

			}

		}

		public function Lock(){

			$this->Locked = true;

			$lockedDate = new DateTime();

			array_push( $this->Audit, array("Account Locked", $lockedDate->format('c') ) );

		}

		public function Unlock(){

			$this->Locked = false;

			$unlockedDate = new DateTime();

			array_push( $this->Audit, array("Account Unlocked", $unlockedDate->format('c') ) );

		}

	}

?>