<?php
    
    require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/functions.php';
    $conn = DatabaseConnect();

    if($conn)
    {
        $bar_id = NULL;
        $item_name = NULL;
        $item_day = NULL;
        $item_type = NULL;
        $item_price = NULL;
        $item_description = "none";
        
        if(!empty($_POST['ui-bar'])) // Bar needs to be chosen
        {
            $bar_id = (int)sanitize($_POST['ui-bar']);
            if(!empty($_POST['ui-name']) && !empty($_POST['ui-day']) && !empty($_POST['ui-type']) && !empty($_POST['ui-price'])) // These must have a value
            {
                // Sanitize and set the variables from the POST
                $item_name = sanitize($_POST['ui-name']);
                $item_day = (int)sanitize($_POST['ui-day']);
                $item_type = (int)sanitize($_POST['ui-type']);
                $item_price = (float)sanitize($_POST['ui-price']);
                
                // This sets all of the checkboxes to either 1 or 0
                // based on whether they were checked or not.
                $alcoholic = isset($_POST['alcoholic']) ? 1 : 0;
                $beer = isset($_POST['beer']) ? 1 : 0;
                $import = isset($_POST['import']) ? 1 : 0;
                $domestic = isset($_POST['domestic']) ? 1 : 0;
                $premium = isset($_POST['premium']) ? 1 : 0;
                $wine = isset($_POST['wine']) ? 1 : 0;
                $vodka = isset($_POST['vodka']) ? 1 : 0;
                $whiskey = isset($_POST['whiskey']) ? 1 : 0;
                $rum = isset($_POST['rum']) ? 1 : 0;
                $tequila = isset($_POST['tequila']) ? 1 : 0;
                $shot = isset($_POST['shot']) ? 1 : 0;
                $well = isset($_POST['well']) ? 1 : 0;
                $gin = isset($_POST['gin']) ? 1 : 0;
                $bomb = isset($_POST['bomb']) ? 1 : 0;
                $energy = isset($_POST['energy']) ? 1 : 0;
                
                
                // If there is a description then sanitize and add it into the 
                // $item_description variable
                if(!empty($_POST['ui-description']))
                {
                    $item_description = sanitize(trim($_POST['ui-description']));
                }
                
                // Enter the item information into the specials table
                $specials_query = $conn->prepare('INSERT INTO specials (bar_id, day, item, item_type, description, price) 
                                         VALUES (:bar_id, :day, :item, :item_type, :description, :price)');
                $specials_query->execute(array(':bar_id'=>  $bar_id, 
                    ':day'=>  $item_day, 
                    ':item'=> $item_name, 
                    ':item_type'=> $item_type, 
                    ':description'=> $item_description, 
                    ':price'=> $item_price));
                
                // If the item type is a drink then add the drink specs into the
                // drink_type table
                if($item_type == 2)
                {
                    // Get the ID of the special that was just added so it can be added
                    // into the drink_type table for relational purposes
                    $last_id = $conn->lastInsertId();

                    // Enter the special's ID and drink specs into the drink_type table
                    $drink_type_query = $conn->prepare('INSERT INTO drink_type (special_id, alcoholic, beer, import, domestic, premium, wine, vodka, whiskey, rum, tequila, shot, well, gin, bomb, energy)
                                             VALUES (:special_id, :alcoholic, :beer, :import, :domestic, :premium, :wine, :vodka, :whiskey, :rum, :tequila, :shot, :well, :gin, :bomb, :energy)');
                    $drink_type_query->execute(array(':special_id' => $last_id, 
                        ':alcoholic' => $alcoholic, 
                        ':beer' => $beer, 
                        ':import' => $import, 
                        ':domestic' => $domestic, 
                        ':premium' => $premium, 
                        ':wine' => $wine, 
                        ':vodka' => $vodka, 
                        ':whiskey' => $whiskey, 
                        ':rum' => $rum, 
                        ':tequila' => $tequila, 
                        ':shot' => $shot, 
                        ':well' => $well, 
                        ':gin' => $gin, 
                        ':bomb' => $bomb, 
                        ':energy' => $energy));
                }
            }
            else
            {
                ErrorDisplay("Name, Day, Price, or Type was not chosen.");
            }
        }
        else
        {
            ErrorDisplay("No bar was selected.");
        }
        
        
    }
    else
    {
        ErrorDisplay("Could Not Connect To Database...");
    }
    
    // Redirect at the end of script completion
        header("Location: /admin/insert-menu-items-ui.php?pass=0x800CCC!"); 
         
        die("Redirecting...");  
?>
