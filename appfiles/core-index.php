<?php if (is_admin()): ?><?php global $wpdb; ?>



<?php 

/*****Get Value From DataBase fetch_database*****/

$getdatabase_name=$wpdb->get_results("SELECT * FROM fetch_database ORDER BY ID DESC LIMIT 1");


if (!empty($getdatabase_name) ) {
	
/*****POST VALUE TO A VAR $DATABASE*****/
$database=$getdatabase_name[0]->database_name;
$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
$limit = 20;
$offset = ( $pagenum - 1 ) * $limit;
}else{
	echo '<div class="error"><p>';
 	echo "OPs !";
    echo "| The Databse Name input =>setting<= should not left empty | ";
 	echo ":) </p></div>";
	die();

}?>




<?php /*Check if table exist*/ ?>


<?php 
$charset_collate = $wpdb->get_charset_collate();

if($wpdb->get_var("SHOW TABLES LIKE '".$database."'") != $database){
	echo '<div class="error"><p>';
	echo "OPs !";
	echo "| Your Table doesn't exist, Please use an available database or create one using this name => <strong>' $database '</strong> | ";
	echo ":( </p></div>";
	die();

}  ?>


<?php

/*Flag $checkmefirst used to check if data exist in this database*/
	$checkmefirst=false;


/*****GET DATABASE TABLE*****/



/*Order by the first col*/
$getcol=$wpdb->get_col( "DESC " . $database, 0 ) ;
$orderby=$getcol[0];


$result=$wpdb->get_results("SELECT * FROM $database ORDER BY $orderby desc LIMIT $offset, $limit ");

$total = $wpdb->get_var( "SELECT COUNT('id') FROM $database ");
$num_of_pages = ceil( $total / $limit );


if (count($result) >= 1 && !empty($result)) {
	
	$checkmefirst=true;
}else{
	$checkmefirst=false;
	echo '<div class="error"><p>';
	echo "OPs !";
	echo "| Your table is empty,Sorry No data to fetch | ";
	echo ":) </p></div>";
}
?>

	
<h2 class="admintitle">Data Fetched &nbsp;<span class="admincount">(<?php echo (!empty($total)) ? $total : 0 ; ?>)<span></span></span></h2>




 <!--//****************FETCH DATA ROWS******************//-->

 	<?php if ($checkmefirst===true): ?>
 	<?php $cnt=count($result); ?>
 <h2 class="admintitle"> <span class="admincount"><?php echo $cnt; ?><span>&nbsp;items</h2>	
 	

 	<div class="tabelContainer">
 		
 		
 
<table class="widefat">



<thead>
<tr>

<?php $table_name = $database;

/*Get table cols */
$getcol=$wpdb->get_col( "DESC " . $table_name, 0 ) ;?>


<?php if (count($getcol) < 1 && empty($getcol)){
	echo '<div class="error"><p>';
	echo "OPs !";
	echo "| No field found ,Please create fields and try again | :) ";
	echo ":) </p></div>";
	die();
} ?>


<?php foreach ($getcol as $key => $col): ?>
	
<?php if ($col != 'post_content'): ?>
	<th class='titleadminth'><?php echo $col ?> </th>
<?php endif ?>


<?php endforeach ?>
</tr>
</thead>

<?php foreach ($result as $k => $val): ?>
<tr>
	<?php foreach ($getcol as $key => $col): ?>

		<?php if ($col != 'post_content'): ?>
<td ><?php echo $val->$col ?></td>
<?php endif ?>
<?php endforeach ?>
</tr>


<?php endforeach ?>

</table >
 	
	</div>
<?php 

$page_links = paginate_links( array(
	'base' => add_query_arg( 'pagenum', '%#%' ),
	'format' => '',
	'prev_text' => __( '&laquo;', 'aag' ),
	'next_text' => __( '&raquo;', 'aag' ),
	'total' => $num_of_pages,
	'current' => $pagenum
) );


 ?>

<?php if ($page_links): ?>
	
<div class="tablenav">
	<?php echo $total ?> items<div class="tablenav-pages" style="margin-right: 20px;"> <?php echo $page_links ?></div>
</div>

<?php endif ?>


 		
 	<?php else: ?>
 		
 	<?php endif ?>

  <!--//****************************************************************//-->


	
<?php else: ?>
	
<?php endif ?>


