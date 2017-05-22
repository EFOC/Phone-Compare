<?php


	//When the user submits the form, the program will start from here
	if ($_POST){
		$selectErr = $minErr = $maxErr = "";			

		//This gets the values from the HTML by name
		$phone = $_POST['phone'];
		$min = $_POST['min'];
		$max = $_POST['max'];

		$valid = true;
		
		//These validate the inputs to make sure something is inside them
		if(empty($phone)){
			$selectErr = "Error";
			$valid = false;
		}
		if(empty($min)){
			$minErr = "Error";
			$valid = false;
		}
		if(empty($max)){
			$maxErr = "Error";
			$valid = false;
		}
		if($max < $min){
			$maxErr = "Max can not be lower than min";
			$valid = false;
		}
		
		
		//This is making sure everything is valid in order to continue
		if($valid) {
				
			$lines = file("/home/int322_171d20/secret/topsecret.txt");
						
			/*
			$serverName = "db-mysql.zenit";
			$dbUsername = "int322_171d20";
			$password = "keYM6564";
			$database = "int322_171d20";
			*/
			$serverName = trim($lines[0]);
			$dbUsername = trim($lines[1]);
			$password = trim($lines[2]);
			$database = trim($lines[3]);
			
			$conn = mysqli_connect($serverName, $dbUsername, $password, $database) or die("Connection failed: " . mysql_error($conn));
			

			//This is getting the values from the sql table with a query	
			$sql = 'select itemName, model, os, price from phone where itemName="' . $phone . '" and (price between "' . $min . '" and "' . $max . '")' ;
			
		
			$result = mysqli_query($conn, $sql);
			//This if statement makes sure the query ran in order to print the table
			//else it will close the connection
			if($result){$table1 = true;} else {mysqli_close($conn);}
		}
	}

?>   
<DOCTYPE html>
   	<head>
   		<title>Assignment 1</title>
   	</head>
   	<body>
   	
   		<form method="POST">
   			
   			<h1>Hello World</h1>
   			<h2>Compare your Phone</h2>
   
			<?php	
				//Set the current time zone you want to display and then show the date 
				date_default_timezone_set('America/Toronto');
				echo "The current date is " . date("Y/m/d") . "<br><br>"; 
			?>   			
		
			<select name=phone>
				<option selected disabled value="">Choose Here</option>
				<option value=iPhone>iPhone</option>
				<option value=HTC>HTC</option>
			</select>	
			<?php echo $selectErr; ?>
	

			<p>Price</p>
			<p>min:</p><input type=number name=min> <?php echo $minErr; ?>
			<p>max:</p><input type=number name=max> <?php echo $maxErr; ?>
							
			<?php 
			//if the query ran successfully then the program will come here
			if ($table1){
				//Making the start of the table and storing it in the variable
				$table = '<table border=1><tr><th>Phone</th><th>Model</th><th>OS</th><th>Price</th></tr>';
			//print the first part of the table
			echo $table;
			//grabs the values of the table and prints them row by row
			while($row = mysqli_fetch_assoc($result)){
				echo '<tr>';
				echo '<th>' . $row['itemName'] . '</th>';
				echo '<th>' . $row['model'] . '</th>';
				echo '<th>' . $row['os'] . '</th>';
				echo '<th>' . $row['price'] . '</th>';
				echo '</tr>';
			}	
			echo '</table>';
	
			//Close the connection
			mysqli_close($conn);
			} 
			?>
	

			<br>
   			<br>
   			<input type='submit'>	
   		</form>
   
   	</body>
   
   </html>

