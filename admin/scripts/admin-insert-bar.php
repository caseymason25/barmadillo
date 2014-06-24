<?php
    
    require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/functions.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Bar.php';
    $conn = DatabaseConnect();

    if($conn)
    {

        /* Get the amount of bars in the table and add one to it for the ID */
        $query = "SELECT COUNT(*) FROM bar";
        $current_id_query = DatabaseQuery($query, $conn)->fetch(PDO::FETCH_BOTH);
        $current_id = $current_id_query[0] + 1;

        /* If Keywords is not set, set it to an empty string */
        if(isset($_POST['ui-keywords'])) { $keywords = $_POST['ui-keywords']; }
        else { $keywords = ""; }
        
        if(!isset($_POST['ui-phone_number'])) {
            $phone_number = "";
        } else {
            $phone_number = $_POST['ui-phone_number'];
        }

        /* Create the Bar object */
        $current_bar = Bar::BuildFromScratch(
                $current_id, //id
                ucwords($_POST['ui-name']), // capitalized name 
                ucwords($_POST['ui-city']), // capitalized city
                $_POST['ui-state'], // state
                $_POST['ui-address'],  // address
                $phone_number, // formatted phone number 
                $_POST['ui-menu_file'], // menu file
                0, // rating
                $_POST['ui-image_file'], // image file
                $_POST['ui-description'], // bar description
                $keywords // keywords
                );
        
        $e = $current_bar->AddNewBar($conn); // Holds either the error message or NULL
        if($e == NULL) 
        {
            // NULL if successfull
            
            // If they are not, we redirect them to the login page. 
        header("Location: /admin/"); 
         
        die("Success"); 
        }
        else
        {
            echo ErrorDisplay("Bar did not insert");
        }
        
        
    }
    else
    {
        ErrorDisplay("Could Not Connect To Database...");
    }
?>
