<?php
    $page_title = "Admin Insert Menu Items";
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
       
    <form action="/admin/scripts/insert-menu-items.php" method="POST">
        <h2>Bar Info</h2>
        <p><label for="ui-bar">Select Bar: </label>
            <select name="ui-bar" id="ui-bar">
                <option value="none" SELECTED>Select a Bar</option>
                <?php 
                    $conn = DatabaseConnect();
                    $list_bars_query = DatabaseQuery("SELECT * FROM bar ORDER BY name ASC", $conn);
                    var_dump($list_bars_query);
                    while($row = $list_bars_query->fetch(PDO::FETCH_BOTH))
                    {
                        echo '<option value="' . $row['id'] . '">' . $row['name'] . ' - ' . $row['city'] . ', ' . $row['state'] . ' - ' . $row['address'] . '</option>';
                    }
                    $conn = NULL;
                ?>
            </select>			
        </p>
        <hr />
        <h2>Item Information:</h2>
        <p><label for="ui-name">*Item Name: </label><input placeholder="Enter Name..." type="text" name="ui-name" id="ui-name" /></p>
        <p><label for="ui-day">*Day of Week: </label>
            <select name="ui-day" id="ui-day">
                <option value="none" SELECTED>Select a Day</option>
                <option value="1">Monday</option>
                <option value="2">Tuesday</option>
                <option value="3">Wednesday</option>
                <option value="4">Thursday</option>
                <option value="5">Friday</option>
                <option value="6">Saturday</option>
                <option value="7">Sunday</option>
                <option value="8">Everyday</option>
            </select>
        </p>
            
        <p><label for="ui-type">*Type of Item: </label>
            <select name="ui-type" id="ui-type">
                <option value="none" SELECTED>Select a type</option>
                <option value="1">Food</option>
                <option value="2">Drink</option>
                <option value="3">Other</option>
            </select>
        </p>
        
        <h2>Drinks Specs</h2>
        <p>
            <table>
                <tr>
                    <td><input type="checkbox" name="alcoholic" />Alcoholic</td>
                </tr>
                
                <tr>
                    <td><input type="checkbox" name="beer" />Beer</td>
                    <td><input type="checkbox" name="import" />Import</td>
                    <td><input type="checkbox" name="domestic" />Domestic</td>
                </tr>
                
                <tr>
                    <td><input type="checkbox" name="wine" />Wine</td>
                </tr>
                
                <tr>
                    <td><input type="checkbox" name="shot" />Shot</td>
                    <td><input type="checkbox" name="well" />Well</td>
                </tr>
                
                <tr>
                    <td><input type="checkbox" name="vodka" />Vodka</td>
                    <td><input type="checkbox" name="whiskey" />Whiskey</td>
                    <td><input type="checkbox" name="rum" />Rum</td>
                    <td><input type="checkbox" name="tequila" />Tequila</td>
                    <td><input type="checkbox" name="gin" />Gin</td>
                </tr>
                
                <tr>
                    <td><input type="checkbox" name="bomb" />Bomb</td>
                    <td><input type="checkbox" name="energy" />Energy</td>
                </tr>
            </table>
                        
                        
            
            
            
            
            
            
        </p>
        
        <p><label for="ui-price">*Item Price: </label><input placeholder="Enter price..." type="text" name="ui-price" id="ui-price" /></p>
        
        <p><label for="ui-description" style="vertical-align: top;">Description: </label><textarea placeholder="Enter Description... (200 characters MAX)" rows="4" cols="50" name="ui-description" id="ui-description" maxlength="200" /></textarea></p>
        
        
        <p><input type="submit" value="submit" /></p>
    </form>
    </div>
    
    <div class="bottom-line">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/bottom-line.html'; ?> 
    </div>
</body>
</html>