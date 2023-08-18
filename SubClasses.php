<?php

	require("BankAccount.php");

	class ISA extends BankAccount{

		public $TimePeroid = 28;

		public $AdditionalServices;

		public function __construct($time, $service)
		{
			  $this->TimePeroid = $time;
				$this->AdditionalServices = $service;

		}

		//Methods

		public function WithDraw($amount){  //this method overwrite parent method, in bankaccount class

			$transDate = new DateTime();  //when this function is invoked it will create a new Time object

			$lastTransaction = null;

			$length = count($this->Audit);

			for( $i = $length; $i > 0; $i-- ){

				$element = $this->Audit[$i - 1];

				if( $element[0] === "WITHDRAW ACCEPTED" ){

					$days = new DateTime( $element[3] );

					$lastTransaction = $days->diff($transDate)->format("%a");

					break;

				}

			}

			if( $lastTransaction === null && $this->Locked === false || $this->Locked === false && $lastTransaction > $this->TimePeroid ){

				$this->Balance -= $amount;

				array_push( $this->Audit, array( "WITHDRAW ACCEPTED", $amount, $this->Balance, $transDate->format('c') ) );
				

			} else {

				if( $this->Locked === false ) {

					$this->Balance -= $amount;

					array_push( $this->Audit, array( "WITHDRAW ACCEPTED WITH PENALTY", $amount, $this->Balance, $transDate->format('c') ) );

					$this->Penalty();

				} else {

					array_push( $this->Audit, array( "WITHDRAW DENIED", $amount, $this->Balance, $transDate->format('c') ) );

				}

			}

		}

		private function Penalty(){

			$transDate = new DateTime();

			$this->Balance -= 10;

			array_push( $this->Audit, array( "WITHDRAW PENALTY", 10, $this->Balance, $transDate->format('c') ) );

		}

	}

	class Savings extends BankAccount implements AccountPlus, Savers{

		use SavingsPlus;  //use code in traits in Savings class
		
		public $PocketBook = array();

		public $DepositBook = array();

		//constructor

		public function __construct($fee, $package)
		{
			$this->MonthlyFee = $fee;
			$this->Package = $package;
		}

		//Methods

		public function OrderNewBook(){

			$orderTime = new DateTime();

			array_push( $this->PocketBook, "Ordered new pocket book on: ". $orderTime->format('c') );

		}

		public function OrderNewDepositBook(){

			$orderTime = new DateTime();

			array_push( $this->DepositBook, "Ordered new deposit book on: ". $orderTime->format('c') );

		}

	}

	class Debit extends BankAccount implements AccountPlus{

		use SavingsPlus;  //use code in traits in Savings class
		
		private $CardNumber;

		private $SecuirtyCode;

		private $PinNumber;

		public function __construct($fee, $package, $pin)
		{
			$this->MonthlyFee = $fee;
			$this->Package = $package;
			$this->PinNumber = $pin;
			$this->Validate();
		}


		//Methods

		private function Validate(){

			$valDate = new DateTime();

			$this->CardNumber = rand(1000, 9999) ."-". rand(1000, 9999) ."-". rand(1000, 9999) ."-". rand(1000, 9999);

			$this->SecuirtyCode = rand(100, 999);

			array_push( $this->Audit, array( "VALIDATED CARD", $valDate->format('c'), $this->CardNumber, $this->SecuirtyCode, $this->PinNumber ) );
			
		}

		public function ChangePin( $newPin ){

			$pinChange = new DateTime();

			$this->PinNumber = $newPin;

			array_push( $this->Audit, array( "PIN CHANGED", $pinChange->format('c'), $this->PinNumber ) );

		}

	}

	trait SavingsPlus{

		private $MonthlyFee = 20;

		public $Package = "holiday insurance";

		//Method...

		public function AddedBonus(){

			echo "Hello ". $this->FirstName ." ". $this->LastName ." for &pound;". $this->MonthlyFee ." a month you get ". $this->Package;

		}

	}

	interface AccountPlus{

		public function AddedBonus();

	}

	interface Savers{

		public function OrderNewBook();
		public function OrderNewDepositBook();

	}

?>