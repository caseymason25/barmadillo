<?php
    $page_title = "Admin Insert Bar";
    if(strip_tags($_GET['pass']) !== "0x800CCC!")
    {
        header('location: /index.php');
        die();
    }

      require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/header.php';
      require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/functions.php'; 

?>

<body>
    <div class="top-line">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/top-line.html'; ?> 
    </div>
    <div style="padding: 10px;"></div>
    <div class="wrapper">
    <form action="/admin/scripts/admin-insert-bar.php" method="POST">
        <p><label for="ui-name">Name: </label><input placeholder="Enter Name..." type="text" name="ui-name" id="ui-name" /></p>
        <p><label for="ui-address">Address: </label><input placeholder="Enter Address..." type="text" name="ui-address" id="ui-address" /></p>
        <p><label for="ui-city">City: </label><input placeholder="Enter City..." type="text" name="ui-city" id="ui-city" /></p>
        <p><label for="ui-state">State: </label>
            <select name="ui-state" id="ui-state">
                <option value="AL">Alabama</option>
                <option value="AK">Alaska</option>
                <option value="AZ">Arizona</option>
                <option value="AR">Arkansas</option>
                <option value="CA">California</option>
                <option value="CO">Colorado</option>
                <option value="CT">Connecticut</option>
                <option value="DE">Delaware</option>
                <option value="DC">District Of Columbia</option>
                <option value="FL">Florida</option>
                <option value="GA">Georgia</option>
                <option value="HI">Hawaii</option>
                <option value="ID">Idaho</option>
                <option value="IL">Illinois</option>
                <option value="IN">Indiana</option>
                <option value="IA">Iowa</option>
                <option value="KS">Kansas</option>
                <option value="KY">Kentucky</option>
                <option value="LA">Louisiana</option>
                <option value="ME">Maine</option>
                <option value="MD">Maryland</option>
                <option value="MA">Massachusetts</option>
                <option value="MI">Michigan</option>
                <option value="MN">Minnesota</option>
                <option value="MS">Mississippi</option>
                <option value="MO">Missouri</option>
                <option value="MT">Montana</option>
                <option value="NE">Nebraska</option>
                <option value="NV">Nevada</option>
                <option value="NH">New Hampshire</option>
                <option value="NJ">New Jersey</option>
                <option value="NM">New Mexico</option>
                <option value="NY">New York</option>
                <option value="NC">North Carolina</option>
                <option value="ND">North Dakota</option>
                <option value="OH">Ohio</option>
                <option value="OK">Oklahoma</option>
                <option value="OR">Oregon</option>
                <option value="PA">Pennsylvania</option>
                <option value="RI">Rhode Island</option>
                <option value="SC">South Carolina</option>
                <option value="SD">South Dakota</option>
                <option value="TN">Tennessee</option>
                <option value="TX">Texas</option>
                <option value="UT">Utah</option>
                <option value="VT">Vermont</option>
                <option value="VA">Virginia</option>
                <option value="WA">Washington</option>
                <option value="WV">West Virginia</option>
                <option value="WI">Wisconsin</option>
                <option value="WY">Wyoming</option>
            </select>			
        </p>
        
        <p><label for="ui-phone_number">Phone: </label><input placeholder="Enter Phone Number..." type="text" name="ui-phone_number" id="ui-phone_number" /></p>
        
        <p><label for="ui-description" style="vertical-align: top;">Description: </label><textarea placeholder="Enter Description... (200 characters MAX)" rows="4" cols="50" name="ui-description" id="ui-description" maxlength="200" /></textarea></p>
        
        <p><label for="ui-menu_file">Menu File: </label><input type="text" name="ui-menu_file" id="ui-menu_file" /></p>
        <p><label for="ui-image_file">Image File: </label><input type="text" name="ui-image_file" id="ui-image_file" /></p>
        <p><label for="ui-keywords">Keywords: </label><input type="text" name="ui-keywords" id="ui-keywords" /></p>
        <p><input type="submit" value="submit" /></p>
    </form>
    </div>
    
    <div class="bottom-line">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/bottom-line.html'; ?> 
    </div>
</body>
</html>

