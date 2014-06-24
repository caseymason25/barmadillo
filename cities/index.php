<?php
    
    require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/functions.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Bar.php';
    
    $conn = DatabaseConnect();

    //Database connection must be active to run
    if ($conn)
    {
        $city_list = CityList($conn);

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
            <?php echo $city_list;  ?>
        </div>

    </div>

    <div class="bottom-line">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/bottom-line.html'; ?> 
    </div>


</body>

</html>

