	<?php
	// Enter your Host, username, password, database below.
	// I left password empty because i do not set password on localhost.
	$con = mysqli_connect("localhost","root","","dharaniseeddb");
	// Check connection
	if (mysqli_connect_errno())
	{
	    echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	$con1 = mysqli_connect("localhost","root","","dharaniseeddb");
	// Check connection
	if (mysqli_connect_errno())
	{
	    echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	$con2 = mysqli_connect("localhost","root","","dharaniseeddb");
	// Check connection
	if (mysqli_connect_errno())
	{
	    echo "Failed to connect to MySQL: " . mysqli_connect_error();
	}
	?>
