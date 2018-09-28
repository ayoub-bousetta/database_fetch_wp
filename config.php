<?php 

/*Check the action Create or Update*/
if (!empty($_POST['action'])) {

	/*Start Create */
	if ($_POST['action']=='create') {

	
if (!empty($_POST['database_name']) && !empty($_POST['separator'])) {


$databaseName=$_POST['database_name'];
$separa=$_POST['separator'];
$date = date_default_timezone_get();
    $date = date('Y-m-d H:i:s', time());


if (strlen((string)$separa)>1) {
	echo "<div class='error'><p>Sperator must be only one character ';' or ',' are recommended</p></div>";
}else{

$separa=substr($separa,0,1);
if ($wpdb->insert('fetch_database',array('database_name'=>$databaseName,'separator_style'=>$separa,'created_time'=>$date))===FALSE) {
	echo "<div class='error'><p>Mm !: Must be something wrong with your input fields,please try again</p></div>";

}else{echo '<div id="message" class="updated"><p>Yeah <strong>Done</strong> Successfully...</p></div>';}}


}else{

echo '<div class="error"><p><strong>DataBase Name </strong> & <strong>Separator</strong> are requiered fields </p></div>';

}



	/*End Create - Begin Update*/
	}else{

if (!empty($_POST['database_name']) && !empty($_POST['separator']) && !empty($_POST['id_update'])) {




$databaseName=$_POST['database_name'];
$separa=$_POST['separator'];
$date = date_default_timezone_get();
    $date = date('Y-m-d H:i:s', time());


if (strlen((string)$separa)>1) {
	echo '<div class="error"><p>Sperator must be only one character ";" or "," are recommended</p></div>';
}else{



$separa=substr($separa,0,1);
if ($wpdb->update('fetch_database',array('database_name'=>$databaseName,'separator_style'=>$separa,'created_time'=>$date) ,array( 'id' => $_POST['id_update'] ))===FALSE) {
	echo "<div class='error'><p>Mm !: Must be something wrong with your input fields,please try again</p></div>";

	/*Update is not working*/

}
else{

	echo '<div id="message" class="updated"><p>Yeah <strong>Updated</strong> Successfully...</p></div>';

/*Update work great*/
}

}


}	else{

echo '<div class="error"><p><strong>DataBase Name </strong> & <strong>Separator</strong> are requiered fields </p></div>';

}	





	}
	




}


 ?>




<?php 
	$result=$wpdb->get_results("SELECT * FROM fetch_database ORDER BY ID DESC LIMIT 1");

	$basename=$result[0]->database_name;
	$separat=$result[0]->separator_style; 
	$id_update=$result[0]->id; 
	?>
<?php 

$base=$wpdb->dbname;
$getit=$wpdb->get_results("SHOW TABLES from $base");


 ?>
<div class="fetch_database_style">
<h2>Configuration Data-fetch</h2>


	<?php if (!empty($basename) && !empty($separat)): ?>


 <form action="?page=database-fetch/config.php" method="POST">
 	<div id="inputup">
 		<span>DataBase Name : </span>
 	<select name="database_name" class="widthup">
<option value='' disabled selected style='display:none;'>Please Select a table</option>
<optgroup label="Table from <?php echo $base ?>">
<?php foreach ($getit as $key => $tablename): ?>
		<?php /*get data from class table*/ ?>


		<?php foreach ($tablename as $key => $valbase): ?>
		<?php if ($basename ==$valbase): ?>
			<option selected value="<?php echo $valbase; ?>"><?php echo $valbase; ?></option>
		<?php else: ?>
		<option value="<?php echo $valbase; ?>"><?php echo $valbase; ?></option>
		<?php endif ?>
			
		<?php endforeach ?>
	
<?php endforeach ?>

</optgroup>

</select>


 	</div>




	<div id="inputup"><span>Separateur : (for the excel file)</span>
<input name="separator" class="widthup" value="<?php echo (!empty($separat)) ? $separat : "" ; ?>" placeholder="use only (,) or (;)">
 	</div>

 	<input type="hidden" name="action" class="widthup" value="update">
 	<input type="hidden" name="id_update" value="<?php echo (!empty($id_update)) ? $id_update : "" ; ?>" class="widthup" >

<button type="submit" class="acf_edit_field acf-button grey" >Update Settings</button>

 </form>





<?php else: ?>
	
 <form action="?page=database-fetch/config.php" method="POST">
 	<div id="inputup"><span>DataBase Name : </span>





<select name="database_name" class="widthup">
<option value='' disabled selected style='display:none;'>Please Select a table</option>
<optgroup label="Table from <?php echo $base ?>">
<?php foreach ($getit as $key => $tablename): ?>
		<?php /*get data from class table*/ ?>


		<?php foreach ($tablename as $key => $valbase): ?>
			<option value="<?php echo $valbase; ?>"><?php echo $valbase; ?></option>
		<?php endforeach ?>
	
<?php endforeach ?>

</optgroup>

</select>
 	</div>
	<div id="inputup"><span>Separateur : (for the excel file)</span>
<input name="separator" class="widthup" placeholder="use only (,) or (;)">
 	</div>
 	<input type="hidden" name="action" class="widthup" value="create">
<button type="submit" class="acf_edit_field acf-button grey" >Save Settings</button>
 </form>


	<?php endif ?>
</div>