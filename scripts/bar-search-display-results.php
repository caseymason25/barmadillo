<?php
// Needs functions.php to operate correctly
if($search_type === "name") {
    // $search_type set to 0 - regular bar search
    if(isset($search_results_exact)) { 
        // There is an exact match, display the exact results first
        echo BarSearchDisplayResults($search_results_exact, $total_rows_exact);

        if(isset($search_results) && isset($total_rows)) {
            if($total_rows > 0) {
                echo BarSearchDisplayResults($search_results, $total_rows);
            }
            else echo '<h2>Your search returned 0 results, try again or use the Bars List</h2>';
        }
    }
    else if(isset($search_results) && isset($total_rows)) {
        if($total_rows > 0) {
            echo BarSearchDisplayResults($search_results, $total_rows);
        }
        else echo '<h2>Your search returned 0 results, try again or use the Bars List</h2>';
    }
    else echo '<h2>You didn&rsquo;t enter a search term, try again</h2>';
} else if($search_type === "price") {
    // $search_type set to 1 - drink price search
    if(isset($search_results) && isset($total_rows)) {
        if($total_rows > 0) {
            echo DrinkSearchDisplayResults($search_results, $total_rows);
        }
        else echo '<h2>Your search returned 0 results, try again drinks</h2>';
    }
    else echo '<h2>You didn&rsquo;t enter a search term, try again</h2>';
} 
?>
