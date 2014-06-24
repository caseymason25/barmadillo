<?php
    require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/functions.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Bar.php';
    
    $conn = DatabaseConnect(); // create connection
    $bar_instance = Bar::BuildFromID((int) $bar_id, $conn); // Creates a Bar object with info based on the ID
    $conn = NULL; // free connection
    if($bar_instance == NULL) header("location: /bars/"); // IF there is no bar with the supplied ID redirect to the bars list
    
    
    require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/bar-header.php'; // put bar-header at the end
?>

<body id="bars">

    <div class="top-line">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/top-line.html'; ?> 
    </div>

    <div class="wrapper">



        <div class="top-search-bar">
            <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/top-search-bar.html'; ?> 
        </div>
        

        <div class="bar-html-wrapper">
            <div class="bar-html-box">
                <div class="bar-info-header">
                    <h2><?php echo $bar_instance->name; ?></h2>
                    <ul>
                        <li>Phone: <?php echo $bar_instance->phone; ?></li>
                        <li>Address: <?php echo $bar_instance->address . ", " . $bar_instance->city . ", " . $bar_instance->state; ?></li>
                    </ul>
                </div>
                <div class="bar-info-row-1">
                    <div class="bar-info-left-col">
                        <div class="bar-info-specials">
                            <?php echo $bar_instance->DisplaySpecials(); ?>
                        </div>
                    </div>

                    <div class="bar-info-right-col">
                        <div id="map-container"><div id="map-canvas"></div></div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>



    <div class="bottom-line">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/bottom-line.html'; ?> 
    </div>


</body>

</html>
