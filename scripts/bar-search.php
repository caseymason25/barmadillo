<?php
/***********************************************
 *          bar-search.php                     *
 *                                             *
 * Use Fulltext Search in the bar_search table *
 *                                             *
 ***********************************************/

//Require the database connect script (returns $conn if successful)
require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/functions.php';

$conn = DatabaseConnect();

//Database connection must be active to run
if ($conn)
{
    //Initial Search term provided by user must be present
    if(isset($_POST['bar_search']))
    {
        $search_term = $_POST['bar_search']; 
        $total_rows_exact = BarSearchBasicCountRows($search_term, $conn); // See if any results are returned when searching by the exact name
        if($total_rows_exact > 0) 
        {
            // There were results based on the name, so only show those
            $search_results_exact = BarSearchBasic($search_term, $conn);
            BarSearchDisplayResults($search_results_exact, $total_rows_exact);
        }
        else
        {
            // There were no exact matches to the name, so search by keywords
            $search_results = BarSearch($search_term, $conn); // The search results to display
            $total_rows = BarSearchCountRows($search_term, $conn); // Integer of number of rows returned
            BarSearchDisplayResults($search_results, $total_rows); // Display the results
        }

    }
    /* Free connection resources. */
    $conn = null; 
}
// No connection to Database
else 
{
    echo "Something went wrong, please try again later...";
}


?>
