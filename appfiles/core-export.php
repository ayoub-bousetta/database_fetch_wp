<?php if (is_admin()): ?><?php global $wpdb; ?>
<?php 

/*****Get Value From DataBase fetch_database*****/

$getdatabase_name=$wpdb->get_results("SELECT * FROM fetch_database ORDER BY ID DESC LIMIT 1");


if (!empty($getdatabase_name) ) {
    
/*****POST VALUE TO A VAR $DATABASE*****/
$database=$getdatabase_name[0]->database_name;
$sepa=$getdatabase_name[0]->separator_style;

$pagenum = isset( $_GET['pagenum'] ) ? absint( $_GET['pagenum'] ) : 1;
$limit = 20;
$offset = ( $pagenum - 1 ) * $limit;
}else{
    echo '<div class="error"><p>';
 echo "OPs !";
    echo "| The Databse Name input =>setting<= should not left empty | ";
    echo ":)";
echo ":)</p></div>";

        die();

}?>
<?php /*Check if table exist*/ ?>
<?php 
$charset_collate = $wpdb->get_charset_collate();

if($wpdb->get_var("SHOW TABLES LIKE '".$database."'") != $database){

echo'<div class="error"><p>';
    echo "OPs !";
    echo "| Your Table doesn't exist, Please use an available database or create one using this name => ' $database ' | ";
    echo ":(";
         echo ":)</p></div>";

        die();

}  ?>








<?php

/*Flag $checkmefirst used to check if data exist in this database*/
    $checkmefirst=false;


/*****GET DATABASE TABLE*****/
$result=$wpdb->get_results("SELECT * FROM $database");

$total = $wpdb->get_var( "SELECT COUNT('id') FROM $database ");
$num_of_pages = ceil( $total / $limit );

/**Check for data**/
if (count($result) >= 1 && !empty($result)) {

    $checkmefirst=true;
}else{
    $checkmefirst=false;

    echo'<div class="error"><p>';
    echo "OPs !";
    echo "| Your table is empty,Sorry No data to fetch | ";
    echo ":)</p></div>";
}
?>

<?php if ($checkmefirst==true): ?>
    <div class="subsubsub">
<div class="fetch_database_style">
<h3 style="color:#999" >Your Data list is ready ! <span class="admincount">(<?php echo $total ?> items)<span>&nbsp;was found</h3 >
    <small>.csv file will be generated and separated by <?php echo $sepa ?></small>

<input type="hidden" value="yoitme" name="role">
<a type='submit' id="export" >Yeah ! Download</a>

</div>
<?php endif ?>

        



<div class="hide" style="display:none">
    <div id="dvData">


<table class="widefat">



<thead>
<tr>

<?php $table_name = $database;

/*Get table cols */
$getcol=$wpdb->get_col( "DESC " . $table_name, 0 ) ;?>


<?php if (count($getcol) < 1 && empty($getcol)){
    echo'<div class="error"><p>';
    echo "OPs !";
    echo "| No field found ,Please create fields and try again |  ";
    echo ":)</p></div>";
        die();
} ?>


<?php foreach ($getcol as $key => $col): ?>
    

<th class='titleadminth'><?php echo $col ?> </th>

<?php endforeach ?>
</tr>
</thead>

<?php foreach ($result as $k => $val): ?>
<tr>
    <?php foreach ($getcol as $key => $col): ?>
<td ><?php echo $val->$col ?></td>
<?php endforeach ?>
</tr>


<?php endforeach ?>

</table >
    
</div>

</div>

<?php endif ?>
 <!-- Scripts  -->
        <script type='text/javascript' src='https://code.jquery.com/jquery-1.11.0.min.js'></script>
        <!-- If you want to use jquery 2+: https://code.jquery.com/jquery-2.1.0.min.js -->
        <script type='text/javascript'>
        $(document).ready(function () {

            var sep=$(this).find('.separator').text();


            function exportTableToCSV($table, filename) {
                var $headers = $table.find('tr:has(th)')
                    ,$rows = $table.find('tr:has(td)')

                    // Temporary delimiter characters unlikely to be typed by keyboard
                    // This is to avoid accidentally splitting the actual contents
                    ,tmpColDelim = String.fromCharCode(11) // vertical tab character
                    ,tmpRowDelim = String.fromCharCode(0) // null character

                    // actual delimiter characters for CSV format
                    ,colDelim = '"'+sep+'"'
                    ,rowDelim = '"\r\n"';

                    // Grab text from table into CSV formatted string
                    var csv = '"';
                    csv += formatRows($headers.map(grabRow));
                    csv += rowDelim;
                    csv += formatRows($rows.map(grabRow)) + '"';

                    // Data URI
                    var csvData = 'data:application/csv;charset=utf-8,%EF%BB%BF' + encodeURIComponent(csv);

                $(this)
                    .attr({
                    'download': filename
                        ,'href': csvData
                        //,'target' : '_blank' //if you want it to open in a new window
                });

                //------------------------------------------------------------
                // Helper Functions 
                //------------------------------------------------------------
                // Format the output so it has the appropriate delimiters
                function formatRows(rows){
                    return rows.get().join(tmpRowDelim)
                        .split(tmpRowDelim).join(rowDelim)
                        .split(tmpColDelim).join(colDelim);
                }
                // Grab and format a row from the table
                function grabRow(i,row){
                     
                    var $row = $(row);
                    //for some reason $cols = $row.find('td') || $row.find('th') won't work...
                    var $cols = $row.find('td'); 
                    if(!$cols.length) $cols = $row.find('th');  

                    return $cols.map(grabCol)
                                .get().join(tmpColDelim);
                }
                // Grab and format a column from the table 
                function grabCol(j,col){
                    var $col = $(col),
                        $text = $col.text();

                    return $text.replace('"', '""'); // escape double quotes

                }
            }


            // This must be a hyperlink
            $("#export").click(function (event) {
                // var outputFile = 'export'
                var outputFile = window.prompt("Nom du fichier excel") || 'Export';
                outputFile = outputFile.replace('.csv','') + '.csv'
                 
                // CSV
                exportTableToCSV.apply(this, [$('#dvData>table'), outputFile]);
                
                // IF CSV, don't do event.preventDefault() or return false
                // We actually need this to be a typical hyperlink
            });
        });
    </script>