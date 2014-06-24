<?php
    
    require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/functions.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Bar.php';
    
    $conn = DatabaseConnect();

    //Database connection must be active to run
    if ($conn)
    {
        // Create the list of bars with city variable if it's set.
        if(!empty($_GET['city'])) $city_list = BarListByCity($conn, (int)sanitize($_GET['city']));
        /* Free connection resources. */
        $conn = null; 
    }
    // No connection to Database
    else 
    {
        echo "Something went wrong, please try again later...";
    }
    
    $page_title = "Cities";
    require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/header.php';
            
?>

<body id="cities-list">

    <div class="top-line">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/top-line.html'; ?> 
    </div>

    <div class="wrapper">

        <div class="top-search-bar">
            <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/top-search-bar.html'; ?> 
        </div>

        <div class="bar-list-wrapper">
            <?php if(!empty($city_list)) echo $city_list; else echo '<h2>Nothing is here, use the back button or return to the <a href="/">Home Page</a>.</h2>'; ?>
        </div>

    </div>

    <div class="bottom-line">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/bottom-line.html'; ?> 
    </div>


</body>

</html>