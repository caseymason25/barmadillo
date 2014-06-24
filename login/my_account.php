<?php
    $page_title = "Login";
?>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/header.php';
      require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/functions.php'; 
    
    // At the top of the page we check to see whether the user is logged in or not 
    if(!$logged_in) 
    { 
        // If they are not, we redirect them to the login page. 
        header("Location: /login/login.php"); 
         
        // Remember that this die statement is absolutely critical.  Without it, 
        // people can view your members-only content without logging in. 
        die("Redirecting to login.php"); 
    } 
?>

<body id="homepage">
    
    <div class="top-line">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/top-line.html'; ?> 
    </div>

    <div class="wrapper">
        
        <h1 style="font-size: 30px; margin: 70px auto 10px auto; width: 400px; text-align: center;">
            My Account:
        </h1>
        <div class="login-page-box">
            <table class="myaccount-box">
                <tr>
                    <th></th>
                    <th></th>
                </tr>
                    <td class="left-td">
                        <strong>User Name:</strong>
                    </td>
                    <td class="right-td">
                        <?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?>
                    </td>
                </tr>
                <tr>
                    <td class="left-td">
                        <strong><strong>Email:</strong></strong>
                    </td>
                    <td class="right-td">
                        <?php echo htmlentities($_SESSION['user']['email'], ENT_QUOTES, 'UTF-8'); ?>
                    </td>
                </tr>
            </table>
            <p style="float:right; margin-right: 75px;"><a href="/login/edit_account.php">Edit account</a></p>
        </div>
    </div>
    
    <div class="bottom-line">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/bottom-line.html'; ?> 
    </div>

</body>
</html>

