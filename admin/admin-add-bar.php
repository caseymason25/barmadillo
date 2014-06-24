<?php
    $page_title = "Admin | Add Bar";
    
    require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/header.php'; 
    require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/functions.php';
    $conn = DatabaseConnect();
?>

<body>
 
<?php 
if(strip_tags($_GET['pass']) == "0x800CCC!")
{
    echo BuildAdminBarTable($conn);
}
?>

 
 
</body>
</html>
