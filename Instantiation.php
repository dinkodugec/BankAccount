<?php

	require("SubClasses.php");

	$Account1 = new ISA;

	$Account1->APR = 5.0;
	$Account1->SortCode = "20-20-20";
	$Account1->FirstName = "Lawrence";
	$Account1->LastName = "Turton";
	$Account1->AdditionalServices = "holiday package";

	/* echo serialize($Account1); */

	$Account1->Deposit(1000);
	$Account1->WithDraw(200);
	$Account1->WithDraw(159);

	echo json_encode($Account1, JSON_PRETTY_PRINT);

?>