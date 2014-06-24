<?php

$admin_pass = "0x800CCC!";
if(!empty($_POST['pass']))
{
    $ui_pass = $_POST['pass'];
}

$pass_check = FALSE;
$error;

if(isset($_POST['submitted']))
{
    if($admin_pass == $ui_pass)
    {
        $pass_check = TRUE;
    }
    else
    {
        $error = "Password incorrect";
    }
}

?>

<?php
    $page_title = "Login";
?>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/header.php';
      require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/functions.php'; 

?>

<body id="homepage">
    
    <div class="top-line">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/top-line.html'; ?> 
    </div>

    <div class="wrapper">
        
        <h1 style="font-size: 30px; margin: 70px auto 10px auto; width: 400px; text-align: center;">Admin:</h1>
        <div class="login-page-box">
            <?php
            if(!isset($_POST['submitted']) || !empty($error))
            {
                if(!empty($error)) echo '<p>'.$error.'</p>';

                echo '<form method="post" action="' . $_SERVER['PHP_SELF'] . '">
                <table>
                    <tr>
                        <td><strong>Password:</strong></td> 
                        <td><input type="password" name="pass" id="pass" /></td>
                    </tr>
                    <tr>
                        <td colspan="2"><input class="green-button" style="width: 100%" type="submit" value="Sign In"/> </td>
                    </tr>
                    <tr>
                        <td><input type="hidden" name="submitted" value="TRUE" /></td>
                    </tr>
                </table>
            </form> ';
            }
            if(isset($_POST['submitted']) && $pass_check)
            {
                    echo '<p><a href="/admin/admin-insert-bar-ui.php?pass=' . $admin_pass . '">Insert Bar (good)</a></p>';
                    echo '<p><a href="/admin/insert-menu-items-ui.php?pass=' . $admin_pass . '">Insert Menu Items (good)</a></p>';
            }
            ?>
            
        </div>
    </div>
    
    <div class="bottom-line">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/bottom-line.html'; ?> 
    </div>

</body>
</html>