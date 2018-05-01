<?php
function makeHeader($headerText){
	echo '<title>'.$headerText.'</title>';
	echo '<div style="background-color:purple;">';
	echo '<h1 style="color: white; margin-top:10px; padding-left:.1em;" class="display-2">'.$headerText.'</h1>';
	echo '</div>';
}
function renderInputForms($buttonName,$variables,$names,$formHeaders,$formTypes){
	echo '<form method = "get">';
	$numVariables = count($variables);
	for ( $x = 0; $x < $numVariables ; $x++){
		echo '<input type="hidden" name="'.$names[$x].'" value="'.$variables[$x].'">';
	}
	echo '<table class="table table-hover">';
	echo '<tbody>';
	$numVariables = count($formHeaders);
	for ( $x = 0; $x < $numVariables ; $x++){
		echo '<tr>';
		echo '<td>';
		echo '<p>'.$formHeaders[$x].'</p>';
		echo '</td>';
		echo '<td>';
		echo '<input type="text" name="'.$formTypes[$x].'" value="">';
		echo '</td>';
		echo '</tr>';
	}
	echo '<td>';
	echo '<tr>';
	echo '<input type="submit" name ="submit" value="'.$buttonName.'">';
	echo '</td>';
	echo '</tr>';
	echo '</form>';
	echo '</table>';
	echo '</tbody>';
}
function makeBackButton(){
	echo "<a href=\"javascript:history.go(-1)\" style=>Back</a>";
}
function renderButton($buttonName,$variables,$names){
	echo '<form method = "get">';
	$numVariables = count($variables);
	for ( $x = 0; $x < $numVariables ; $x++){
		echo '<input type="hidden" name="'.$names[$x].'" value="'.$variables[$x].'">';
	}
	echo '<input type="submit" name ="submit" value="'.$buttonName.'">
		</form>';
}
function renderText($text){
		echo '<h3>"'.$text.'"</h3>';
}
//this is essentially the same as previous, but allows for a style attribute to be set
function renderStyledButton($buttonName,$variables,$names, $style){
	echo '<form method = "get">';
	$numVariables = count($variables);
	for ( $x = 0; $x < $numVariables ; $x++){
		echo '<input type="hidden" name="'.$names[$x].'" value="'.$variables[$x].'">';
	}
	echo '<input type="submit" name ="submit" value="'.$buttonName.'" style="'.$style.'">
		</form>';
}
//renderTable takes in a table, and a columnName to render, and makes buttons or text for it
function renderTable($conn,$mysqlQuery,$columnNames,$variables,$names,$isRecipe){
	$query = mysqli_query($conn,$mysqlQuery);
	$count = count($columnNames);
	echo '<table class="table table-hover">';
	echo '<tbody>';
	while($row = mysqli_fetch_assoc($query)){
		//make the actual listing
		echo '<tr>';
		for ($x = 0 ; $x < $count ; $x++){
			echo '<td>';
			renderButton($row[$columnNames[$x]],$variables,$names);
			echo '</td>';
		}

		//make a delete button
		echo '<td>';
		if ($isRecipe){
			renderStyledButton("Delete",array("Delete Recipe",$row['name']),array("type","target"),"margin-left:5px;color:red");
		}
		else {
			renderStyledButton("Delete",array("Delete Ingredient",$row['ingredient']),array("type","target"),"margin-left:5px;color:red");
		}
		echo '</td>';
		echo '</tr>';
	}
	echo '</table>';
}
//similar to above, except renders only text, and not the buttons
function renderTextTable($conn,$mysqlQuery,$columnNames){
	$query = mysqli_query($conn,$mysqlQuery);
	$count = count($columnNames);
	echo '<table class="table table-hover">';
	echo '<thead>';
	echo '<tr>';
	for ($x = 0 ; $x<$count ; $x++){
			echo '<th>'.$columnNames[$x].'</th>';
	}
	echo '</tr>';
	echo '</thead>';
	echo '<tbody>';
	while($row = mysqli_fetch_assoc($query)){
		//make the actual listing
		echo '<tr>';
		for ($x = 0 ; $x < $count ; $x++){
			echo '<td>';
			renderText($row[$columnNames[$x]]);
			echo '</td>';
		}

		//make a delete button
		echo '<td>';
		renderStyledButton("Delete",array("Delete Ingredient",$row['ingredient']),array("type","target"),"margin-left:5px;color:red");
		echo '</td>';
		echo '</tr>';
	}
	echo '</table>';
}
?>
