<?php
/***********************************************
 *          autocomplete-core.php              *
 *                                             *
 * Queries the database table 'bar' to suggest *
 * a search term to the user                   *
 *                                             *
 ***********************************************/

//Require the database connect script (returns $conn if successful)
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/functions.php';

$conn = DatabaseConnect();
// The array that will contain JSON data to send back to the caller
$return_arr = array();

// Needs connection to the database
if ($conn)
{
    //Prepare and execute the query
    $ac_term = "%".$_GET['term']."%"; // term is what the user is typing in the search box
    $query = "SELECT * FROM bar WHERE name LIKE :term";
    $result = $conn->prepare($query);
    $result->bindValue(":term",$ac_term);
    $result->execute();
     
    
    /* Retrieve and store in array the results of the query.*/
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $row_array['id'] = $row['id'];
        $row_array['value'] = $row['name'];
         
        array_push($return_arr,$row_array);
    }
 
     
}
/* Free connection resources. */
$conn = null; 
/* Toss back results as json encoded array. */
echo json_encode($return_arr);

?>
