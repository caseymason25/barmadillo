<?php 
    /* If the ID is set and it is an integer less than 9999 */
    if(isset($_GET['id']))
    {
        if(((int) $_GET['id']) < 9999)
        {
            $bar_id = (int) $_GET['id'];
        }
        else
        {
            $bar_id = NULL;
        }
    }
    else
    {
        $bar_id = NULL;
    }
    require_once $_SERVER['DOCUMENT_ROOT'] . '/templates/bar-template.php'; 
?>
