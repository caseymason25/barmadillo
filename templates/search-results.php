<?php
    
    require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/functions.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Bar.php';
    
    $conn = DatabaseConnect();

//Database connection must be active to run
if ($conn)
{
    // This will tell bar-search-display-results what type of search it is
    $search_type = sanitize($_POST['search_type']);
    
    // Search by bar name
    //Initial Search term provided by user must be present
    if($search_type == "name" && isset($_POST['bar_search']))
    {
        $search_term = $_POST['bar_search']; 
        $total_rows_exact = BarSearchBasicCountRows($search_term, $conn); // See if any results are returned when searching by the exact name
        if($total_rows_exact > 0) 
        {
            // There were results based on the name, so only show those
            $search_results_exact = BarSearchBasic($search_term, $conn);
        }
        else
        {
            // There were no exact matches to the name, so search by keywords
            $search_results = BarSearch($search_term, $conn); // The search results to display
            $total_rows = BarSearchCountRows($search_term, $conn); // Integer of number of rows returned
        }
    }
    
    // Search by price
    //Initial Search term provided by user must be present
    else if($search_type == "price" && isset($_POST['bar_search']))
    {
        
        // This array holds the options of items in order from 1 to N, "NULL" is in position 0
        // because the form passes values higher than 0. The text words (like "beer") must match
        // the database fields in TABLE drink_type
        $type_choices = array("NULL", "beer", "vodka", "whiskey", "gin", "tequila", "rum");
        
        // search_item will be 1 of the items from the above array
        $search_item = $type_choices[(int)sanitize($_POST['ui-item'])];
        
        $search_price = sanitize($_POST['ui-price']);
        $search_results = DrinkSearch($search_item, $search_price, $conn); // The search results to display
        $total_rows = DrinkSearchCountRows($search_item, $search_price, $conn); // Integer of number of rows returned
    }
    
    /* Free connection resources. */
    $conn = null; 
}
// No connection to Database
else 
{
    echo "Something went wrong, please try again later...";
}
    if(!empty($_POST['bar_search'])) $page_title  = 'Search: ' . trim(sanitize($_POST['bar_search']));
    else $page_title = "Search";
    require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/header.php';
            
?>

<body id="search-results">

    <div class="top-line">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/top-line.html'; ?> 
    </div>

    <div class="wrapper">

        <div class="top-search-bar">
            <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/top-search-bar.html'; ?> 
        </div>

        <div class="search-results-wrapper">
            <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/bar-search-display-results.php'; ?>
        </div>

    </div>

    <div class="bottom-line">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/bottom-line.html'; ?> 
    </div>


</body>

</html>

