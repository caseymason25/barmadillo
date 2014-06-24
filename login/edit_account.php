<?php 
    require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/header.php';
    require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/functions.php'; 
      
    // First we execute our common code to connection to the database and start the session 
    require_once($_SERVER['DOCUMENT_ROOT'] . '/login/common.php');
    
     
    // At the top of the page we check to see whether the user is logged in or not 
    if(!$logged_in) 
    { 
        // If they are not, we redirect them to the login page. 
        header("Location: /login/login.php"); 
         
        die("Redirecting to login.php"); 
    } 
     
    // This if statement checks to determine whether the edit form has been submitted 
    // If it has, then the account updating code is run, otherwise the form is displayed 
    if(!empty($_POST)) 
    { 
        // Make sure the user entered a valid E-Mail address 
        if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) 
        { 
            die("Invalid E-Mail Address"); 
        } 
         
        // If the user is changing their E-Mail address, we need to make sure that 
        // the new value does not conflict with a value that is already in the system. 
        // If the user is not changing their E-Mail address this check is not needed. 
        if($_POST['email'] != $_SESSION['user']['email']) 
        { 
            // Define our SQL query 
            $query = " 
                SELECT 
                    1 
                FROM users 
                WHERE 
                    email = :email 
            "; 
             
            // Define our query parameter values 
            $query_params = array( 
                ':email' => $_POST['email'] 
            ); 
             
            try 
            { 
                // Execute the query 
                $stmt = $db->prepare($query); 
                $result = $stmt->execute($query_params); 
            } 
            catch(PDOException $ex) 
            { 
                // Note: On a production website, you should not output $ex->getMessage(). 
                // It may provide an attacker with helpful information about your code.  
                die("Failed to run query"); 
            } 
             
            // Retrieve results (if any) 
            $row = $stmt->fetch(); 
            if($row) 
            { 
                die("This E-Mail address is already in use"); 
            } 
        } 
         
        // If the user entered a new password, we need to hash it and generate a fresh salt 
        // for good measure. 
        if(!empty($_POST['password'])) 
        { 
            $salt = dechex(mt_rand(0, 2147483647)) . dechex(mt_rand(0, 2147483647)); 
            $password = hash('sha256', $_POST['password'] . $salt); 
            for($round = 0; $round < 65536; $round++) 
            { 
                $password = hash('sha256', $password . $salt); 
            } 
        } 
        else 
        { 
            // If the user did not enter a new password we will not update their old one. 
            $password = null; 
            $salt = null; 
        } 
         
        // Initial query parameter values 
        $query_params = array( 
            ':email' => $_POST['email'], 
            ':user_id' => $_SESSION['user']['id'], 
        ); 
         
        // If the user is changing their password, then we need parameter values 
        // for the new password hash and salt too. 
        if($password !== null) 
        { 
            $query_params[':password'] = $password; 
            $query_params[':salt'] = $salt; 
        } 
         
        // Note how this is only first half of the necessary update query.  We will dynamically 
        // construct the rest of it depending on whether or not the user is changing 
        // their password. 
        $query = " 
            UPDATE users 
            SET 
                email = :email 
        "; 
         
        // If the user is changing their password, then we extend the SQL query 
        // to include the password and salt columns and parameter tokens too. 
        if($password !== null) 
        { 
            $query .= " 
                , password = :password 
                , salt = :salt 
            "; 
        } 
         
        // Finally we finish the update query by specifying that we only wish 
        // to update the one record with for the current user. 
        $query .= " 
            WHERE 
                id = :user_id 
        "; 
         
        try 
        { 
            // Execute the query 
            $stmt = $db->prepare($query); 
            $result = $stmt->execute($query_params); 
        } 
        catch(PDOException $ex) 
        { 
            // Note: On a production website, you should not output $ex->getMessage(). 
            // It may provide an attacker with helpful information about your code.  
            die("Failed to run query"); 
        } 
         
        // Now that the user's E-Mail address has changed, the data stored in the $_SESSION 
        // array is stale; we need to update it so that it is accurate. 
        $_SESSION['user']['email'] = $_POST['email']; 
         
        // This redirects the user back to the my account page after submitting
        header("Location: /login/my_account.php"); 
        die("Redirecting to: my_account.php"); 
    } 
     
?> 
<body id="homepage">
    
    <div class="top-line">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/top-line.html'; ?> 
    </div>

    <div class="wrapper">
        <h1 style="font-size: 30px; margin: 70px auto 10px auto; width: 400px; text-align: center;">
            Edit Account:
        </h1>
        <div class="login-page-box">
        <form action="edit_account.php" method="post"> 
            Username:<br /> 
            <b><?php echo htmlentities($_SESSION['user']['username'], ENT_QUOTES, 'UTF-8'); ?></b> 
            <br /><br /> 
            E-Mail Address:<br /> 
            <input type="text" name="email" value="<?php echo htmlentities($_SESSION['user']['email'], ENT_QUOTES, 'UTF-8'); ?>" /> 
            <br /><br /> 
            Password:<br /> 
            <input type="password" name="password" value="" /><br /> 
            <i>(leave blank if you do not want to change your password)</i> 
            <br /><br /> 
            <input type="submit" value="Update Account" /> 
        </form>
        </div>
        
    </div>
    
    <div class="bottom-line">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/bottom-line.html'; ?> 
    </div>

</body>
</html>