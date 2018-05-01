<html>
<?php
//This function makes the actual buttons
ini_set('display_errors', 1);
error_reporting(E_ALL);

//rendering.php holds different functions used to draw buttons and other information
include 'rendering.php';

//this is the "main function", so to speak
function run(){

	if (!isset($_GET['type'])){
		//by default, there is no type; Hence, the default page is created
		makeHeader("The Cookbook");
		echo '<div class="d-flex p-2">';
		renderButton("My Recipes",array("Request List"),array("type"));
		renderButton("Create New Recipe",array("New Recipe"),array("type"));
		echo '</div>';
		return;
	}
	else {
		//connect to the mysql server

		$servername = 'localhost';
		$username = 'root';
		$password = '';
		$database = 'Recipies';
		$conn =mysqli_connect($servername, $username, $password,$database);
		if ($conn->connect_error) {
			echo "FAILED TO CONNECT TO MYSQL SERVER!";
			return;

		}


		$dishes = 'dishes';
		$ingredients = 'ingredients';
		//change what happens based on what type of request was received
		switch( $_GET['type']){

			case 'Request List':
				//this represents getting the list of dishes we can make
				makeHeader("Recipe List");
				renderTable($conn,"SELECT * FROM ".$dishes." ORDER BY name",array('name'),array('Get Information'),array('type'),true);
				echo '<div class="d-flex p-2">';
				renderButton("Back",array(),array());
				echo '</div>';
				break;

			case 'Get Information':
			//this represents getting all the associated ingredients with the dish

				$dishName = $_GET['submit'];
				makeHeader("Ingredients for a ".$dishName);
				$dishData = mysqli_fetch_assoc(mysqli_query($conn,"SELECT * FROM ".$dishes." WHERE name = '".$dishName."'"));
				renderTextTable($conn,"SELECT * FROM ".$ingredients." WHERE dishid = '".$dishData['dishid']."'",array('ingredient','amount','unit'));
				renderInputForms("Add New Ingredient",array("Add Ingredient",$dishData['dishid']),array('type','dishid'),array('Ingredient Name','Amount','Unit'),array("name","amount","unit"));
				renderButton("Back",array(),array());
			break;
			case 'Add Ingredient':
				mysqli_query($conn,"INSERT INTO ".$ingredients." (dishid,ingredient,amount,unit) VALUES(".$_GET['dishid'].",'".$_GET['name']."',".$_GET['amount'].",'".$_GET['unit']."')");
				echo "<a href=\"javascript:history.go(-1)\">Back</a>";
				header( 'Location: index.php');
			case 'New Recipe':

				$currMaxId = mysqli_fetch_assoc(mysqli_query($conn,"SELECT MAX(dishid) AS dishid FROM ".$dishes));
				//assign a new id to the new dish!
				$newDishId = $currMaxId['dishid']+1;
				renderInputForms("Create Dish",array("Insert Dish Into Table",$newDishId),	array('type','dishid'),array('Dish Name:'),array('dishName'));

			break;
			case 'Insert Dish Into Table':
				mysqli_query($conn,"INSERT INTO ".$dishes." (name,dishid) VALUES ('".$_GET['dishName']."','".$_GET['dishid']."')");
				header( 'Location: index.php'); //redirect to main page
			break;
			case 'Delete Recipe':
				//removes the specified recipe from the table
				mysqli_query($conn,"DELETE FROM ".$dishes." WHERE name = '".$_GET['target']."'");
				header( 'Location: index.php'); //redirect to main page
			break;
			case 'Delete Ingredient':
				//removes the specified recipe from the table
				mysqli_query($conn,"DELETE FROM ".$ingredients." WHERE ingredient = '".$_GET['target']."'");
				header( 'Location: index.php'); //redirect to main page
			break;
			default:
			//by default, give the user an error message (invalid request)
						 echo "<body>Invalid request</body>";
						 break;
		}
	}
}
run();
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous">
<script src="https://code.jquery.com/jquery-3.1.1.slim.min.js" integrity="sha384-A7FZj7v+d/sdmMqp/nOQwliLvUsJfDHW+k9Omg/a/EheAdgtzNs3hpfag6Ed950n" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/tether/1.4.0/js/tether.min.js" integrity="sha384-DztdAPBWPRXSA/3eYEEUWrWCy7G5KFbe8fFjk5JAIxUYHKkDx6Qin1DkWx51bBrb" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/js/bootstrap.min.js" integrity="sha384-vBWWzlZJ8ea9aCX4pEW3rVHjgjt7zpkNpZk+02D9phzyeVkE+jo0ieGizqPLForn" crossorigin="anonymous"></script>
</html>
