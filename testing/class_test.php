<?php
    $page_title = "Bar Template";

    require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/header.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/functions.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Bar.php';
    $conn = DatabaseConnect();

    ?>
</head>
<body>
 
    <?php      
        $bar_object = Bar::BuildFromID(3, $conn);
        echo '<br />';
        echo $bar_object->id;
        echo '<br />';
        echo $bar_object->name;
        echo '<br />';
        echo $bar_object->lat_long;
        echo '<br />';
        echo $bar_object->address;
        echo '<br />';
        echo $bar_object->phone;
        echo '<br />';
        echo $bar_object->city;
        echo '<br />';
        echo 'End';
        ?>

        

 
 
</body>
</html>

