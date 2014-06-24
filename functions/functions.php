<?php
        /**********************************************
        *          functions.php                      *
        *                                             *
        * Holds all of the functions for the website  *
        *   Alphabetically ordered by function name   *
        ***********************************************/
    require_once $_SERVER['DOCUMENT_ROOT'] . '/classes/Bar.php';
        
    /*======================= A ========================*/


    /*======================= B ========================*/
    
    /* BarList : List all the bars in the database in HTML
     * PARAM:   ($conn) The database connection
     * RETURNS: ($html) String containing the HTML listing all the bars in the database
     */
    function BarList($conn)
    {
            $count_query = "SELECT count(*) FROM bar";
            $count_result = $conn->prepare($count_query);
            $count_result->execute();
            $total_rows_query = $count_result->fetch(PDO::FETCH_NUM);
            $total_rows = $total_rows_query[0];

            $query = "SELECT bar.id, bar.name, bar.city, bar.state, bar.address, bar.description FROM bar ORDER BY name ASC";
            $result = $conn->prepare($query);
            $result->execute();

            $html = "";

            for($i = 0; $i < $total_rows; $i++) {
                $row = $result->fetch(PDO::FETCH_BOTH);
                $html .= '<div class="search-result-box">
                            <h2><a href="/bar-view.php?id=' . sanitize($row['id']) . '"><em>' . $row['name'] . '</em> &ndash; ' . $row['city'] . ', ' . $row['state'] . '</a> </h2>
                            <p><span>Address: ' . $row['address'] . '</span></p>
                            <p>' . $row['description'] .'</p>
                          </div>';
            }

            $conn = NULL; // free conection
            return $html; 
    }
    
    function BarListByCity($conn, $city)
    {
            $query = "SELECT bar.id, bar.name, bar.city, bar.state, bar.address, bar.description FROM bar 
                INNER JOIN bar_to_city_state on bar_to_city_state.city_state_id = :city WHERE bar.id = bar_to_city_state.bar_id ORDER BY name ASC";
            $result = $conn->prepare($query);
            $result->execute(array(':city'=>  $city));

            $html = "";

                while($row = $result->fetch(PDO::FETCH_BOTH))
                {
                $html .= '<div class="search-result-box">
                            <h2><a href="/bar-view.php?id=' . sanitize($row['id']) . '"><em>' . $row['name'] . '</em> &ndash; ' . $row['city'] . ', ' . $row['state'] . '</a> </h2>
                            <p><span>Address: ' . $row['address'] . '</span></p>
                            <p>' . $row['description'] .'</p>
                          </div>';
                }
            

            $conn = NULL; // free conection
            return $html;   
    }
    
    /* BarSearchCountRows : Counts how results will be returned from a query
     * PARAM:   ($search_term) The user inputted search term
     *          ($conn) The database connection
     * RETURNS: ($rows[0]) The total number of rows resulting from the database query
     */
    function BarSearchCountRows($search_term, $conn) 
    {
        try
        {
            //remove all apostrophes
            $new_search = str_replace("'", "", $search_term); 

            /* Searches the bar_search table for the term then matches the bar_id 
             * field with id field in the bar table and gives returns the results
             */
            $query = "SELECT count(*) FROM bar_search, bar WHERE Match (keywords) AGAINST (:term IN BOOLEAN MODE) AND bar.id = bar_search.bar_id;";
            $result = $conn->prepare($query);
            $result->bindValue(":term",$new_search);
            $result->execute();
            $rows = $result->fetch(PDO::FETCH_NUM);

            $conn = NULL; // free conection
            return $rows[0]; // [0] holds the number of rows returned from the query
        }
        catch(PDOException $e) {
            echo ErrorDisplay($e->getMessage());
          return NULL;
        }
    }
    
    /* BarSearchBasicCountRows : Counts how many EXACT match results will be returned from a query
     * PARAM:   ($search_term) The user inputted search term
     *          ($conn) The database connection
     * RETURNS: ($rows[0]) The total number of rows resulting from the database query
     */
    function BarSearchBasicCountRows($search_term, $conn) 
    {

        /* Searches the bar table for an exact match using the bar's name */
        $query = "SELECT count(*) FROM bar WHERE name = :term";
        $result = $conn->prepare($query);
        $result->bindValue(":term",$search_term);
        $result->execute();
        $rows = $result->fetch(PDO::FETCH_NUM);
        
        $conn = NULL; // free conection
        return $rows[0]; // [0] holds the number of rows returned from the query
    }
        
    /* BarSearchBasic : Perfoms search using the $search_term and tries to match it against an exact name match
     * PARAM:   ($search_term) The user inputted search term
     *          ($conn) The database connection
     * RETURNS: ($result) The result from the database query
     */
    function BarSearchBasic($search_term, $conn) 
    {
        /* Searches the bar table for an exact match using the bar's name */
        $query = "SELECT bar.id, bar.name, bar.city, bar.state, bar.address, bar.description FROM bar WHERE name = :term";
        $result = $conn->prepare($query);
        $result->bindValue(":term",$search_term);
        $result->execute();
        
        $conn = NULL; // free conection
        return $result;
    }
    
    /* BarSearch : Perfoms an advanced search using keywords and the bar_search table in the database
     * PARAM:   ($search_term) The user inputted search term
     *          ($conn) The database connection
     * RETURNS: ($result) The result from the database query
     */
    function BarSearch($search_term, $conn) 
    {
        //remove all apostrophes
        $new_search = str_replace("'", "", $search_term); 

        /* Searches the bar_search table for the term then matches the bar_id 
         * field with id field in the bar table and gives returns the results
         */
        $query = "SELECT bar_search.bar_id, bar_search.keywords, bar.* FROM bar_search, bar WHERE Match (keywords) AGAINST (:term IN BOOLEAN MODE) AND bar.id = bar_search.bar_id;";
        $result = $conn->prepare($query);
        $result->bindValue(":term",$new_search);
        $result->execute();
        
        $conn = NULL; // free conection
        return $result;
    }
    
    /* BarSearchDisplayResults : Sets up the HTML for the returned search results
     * PARAM:   ($search_results) The results from a search by either BarSearch() or BarSearchBasic()
     *          ($total_rows) The total rows included in $search_results
     * RETURNS: ($html) string that holds all of the HTML for the search results
     */
    function BarSearchDisplayResults($search_results, $total_rows)
    {
        $html = "";
        
        for($i = 0; $i < $total_rows; $i++) {
            $row = $search_results->fetch(PDO::FETCH_BOTH);
            $html .= '<div class="search-result-box">
                        <h2><a href="/bar-view.php?id=' . $row['id'] . '"><em>' . $row['name'] . '</em> &ndash; ' . $row['city'] . ', ' . $row['state'] . '</a> </h2>
                        <p><span>Address: ' . $row['address'] . '</span><br />' . $row['description'] . '</p>
                      </div>';
        }
        
        return $html;
    }
    
     /* BuildAdminBarTable : Sets up the HTML for the bar table on admin-add-bar.php
     * PARAM: ($conn) the connection object to the database
     * RETURNS: ($table_html) string that holds all of the HTML for the table
     */
    function BuildAdminBarTable($conn)
    {
        if($conn)
        {
            // Set the number of columns for each row in the bar table
            $total_columns_result = DatabaseQuery("SELECT COUNT(*) FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = 'bar'", $conn)->fetch(PDO::FETCH_BOTH); 
            $total_columns = $total_columns_result[0];
            // Sets up the PDO object for the list of BARs
            $bar_list = DatabaseQuery("SELECT * FROM bar", $conn);

            //$table_html holds all the html for the admin-bar table
            $table_html = "<table id='admin-bars-list' border='1'><tr>"; 

            //Holds all the table header information for BAR
            $table_fields = DatabaseQuery("DESCRIBE bar", $conn)->fetchAll(PDO::FETCH_COLUMN);

            //Set up the table headers
            for($i = 0; $i < $total_columns; $i++) {
                $table_html .= "<th id='admin-bar-col-" . $i . "'>" . $table_fields[$i] . "</th>";
            }
            $table_html .= "</tr>";

            //Go through row by row and fill in the table data with the BAR data
            while ($row = $bar_list->fetch(PDO::FETCH_NUM)) {
                $table_html .= "<tr>";
                for ($i = 0; $i < $total_columns; $i++) {
                    $table_html .= "<td>". $row[$i] . "</td>";
                }
                $table_html .= "</tr>";
            } 

            //finish the table
            $table_html .= "</table>";

            //Free the connection
            $conn = NULL;

            //return the completed $table_html
            return $table_html;
        }
        else return NULL;
    }
    
    /* BuildAdminInsertBarForm : Sets up the HTML for the form on admin-add-bar.php
     * PARAM: none
     * RETURNS: ($form_html) string that holds all of the HTML for the form
     */
    function BuildAdminInsertBarForm($conn)
    {
        $query = "SELECT COUNT(*) FROM bar";
        $new_id_query = DatabaseQuery($query, $conn)->fetch(PDO::FETCH_BOTH);
        $new_id = $new_id_query[0] + 1;
        
        $conn = NULL;
        $form_html = "<form action='/scripts/admin-insert-bar.php'  method='post'><br />
                <label for='ui-id'>ID: </label><input type='text' disabled='disabled' name='ui-id' title='ID' value='" . $new_id . "' /><br />
                <label for='ui-name'>Name: </label><input type='text' name='ui-name' title='Name' /><br />
                <label for='ui-city'>City: </label><input type='text' name='ui-city' title='City' /><br />
                <label for='ui-state'>State: </label><input type='text' name='ui-state' title='State' /><br />
                <label for='ui-address'>Address: </label><input type='text' name='ui-address' title='Address' /><br />
                <label for='ui-phone_number'>Phone Number: </label><input type='text' name='ui-phone_number' pattern='^(1\s*[-\/\.]?)?(\((\d{3})\)|(\d{3}))\s*[-\/\.]?\s*(\d{3})\s*[-\/\.]?\s*(\d{4})\s*(([xX]|[eE][xX][tT])\.?\s*(\d+))*$' title='10 Digit Phone Number' /><br />
                <label for='ui-menu_file'>Menu File: </label><input type='text' name='ui-menu_file' title='Menu File' /><br />
                <label for='ui-image_file'>Image File: </label><input type='text' name='ui-image_file' title='Image File' /><br />
                <label for='ui-keywords'>Keywords: </label><input type='text' name='ui-keywords' title='Keywords' /><br />
                <input type='submit' name='submit_button' value='Submit' /></p></form>";
        return $form_html;
    }
    
    /* BuildHeaderHTML : Sets up the HTML for the header data on all pages
     * PARAM: ($page_title) string that contains the title for the page
     * RETURNS: ($header_html) string that holds all of the HTML for the header
     */
    function BuildHeaderHTML($page_title)
    {
        $header_html = "<!doctype html>
        <html lang='en'>
        <head>
          <meta charset='utf-8' />
          <title>Barmadillo | " . $page_title . "</title>
          <meta name='description' content='Barmadillo: Easily find local bar menus, prices, and specials!'> 
          <meta http-equiv='content-type' content='text/html;charset=UTF-8'>
          <script src='http://code.jquery.com/jquery-1.9.1.js'></script>";
        return $header_html;
    }
    
    /*======================= C ========================*/
    
    /* CityList : Builds an unordered list of the cities in the city_state
     * table in the database.
     * PARAM: ($conn) Connection to the database
     * RETURNS: ($html) String containing the unordered list
     */
    function CityList($conn)
    {
            $count_query = "SELECT count(*) FROM city_state";
            $count_result = $conn->prepare($count_query);
            $count_result->execute();
            $total_rows_query = $count_result->fetch(PDO::FETCH_NUM);
            $total_rows = $total_rows_query[0];
            
            $query = "SELECT id, city, state FROM city_state ORDER BY city ASC";
            $result = $conn->prepare($query);
            $result->execute();

            $html = '<ul class="city-list">';

            for($i = 0; $i < $total_rows; $i++) {
                $row = $result->fetch(PDO::FETCH_BOTH);
                $html .= '<li><a href="/cities/bars.php?city='. $row['id'] .'">' . $row['city'] . ', ' . $row['state'] . '</a></li>';
            }
            
            $html .= '</ul>';
            $conn = NULL; // free conection
            return $html; 
    }


    /*======================= D ========================*/
    
    /* DatabaseConnect : Wrapper to establish connection to database
     * PARAM: none
     * RETURNS: ($result) object that can be fetched
     */
    function DatabaseConnect()
    {
        // Live server connection credentials.
//        $dbhost = '10.16.112.123';
//        $dbuser = 'u1136517_root';
//        $dbpass = 'firestorm210x800CCC@';
//        $dbname = 'db1136517_barmadillo';
        
        $dbhost = 'localhost';
        $dbuser = 'root';
        $dbpass = 'firestorm21';
        $dbname = 'barmadillo';
        
        // First try the conenction using the live server credentials.
        try {
          $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
          return $conn; 
        }
        // If this fails, set the variables to the development server credentials.
        catch(PDOException $e) {
            $dbhost = 'localhost';
            $dbuser = 'root';
            $dbpass = 'firestorm21';
            $dbname = 'barmadillo';
            
            // Attempt the connection with the development credentials.
            try {
                $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);
                return $conn;
            }
            // If that fails, the error is legitimate and no connection could be made.
            catch(PDOException $e) {
                echo ErrorDisplay("Could not connect to database...");
                return NULL;
            }
          return NULL;
        }
    }
    
    /* DatabaseQuery : Wrapper to query database
     * PARAM: ($query) string that will be queried
     *        ($conn) the connection object to the database
     * RETURNS: ($result) object that can be fetched
     */
    function DatabaseQuery($query, $conn)
    {
        if($conn)
        {
            $result = $conn->prepare($query);
            $result->execute();
            
            //Free the connection
            $conn = NULL;
            
            return $result;
        }
        else return NULL;
    }
    
    function DrinkSearch($search_item, $search_price, $conn) {
        // Don't allow price higher than 100
        if($search_price > 101) $search_price = 100;
        // Create the string holding the column name
        $search_str = "drink_type." . sanitize($search_item);
        /* Search for the drinks that match the type of alchohol */
        $query = "SELECT bar.id, bar.name, specials.item, specials.price
                FROM specials
                INNER JOIN bar ON bar.id = specials.bar_id
                INNER JOIN drink_type ON drink_type.special_id = specials.id
                WHERE specials.price BETWEEN 0 AND (:priceLimit) AND ". $search_str ." = 1
                ORDER BY specials.price, bar.name ASC";
        
        $result = $conn->prepare($query);
        $result->bindValue(":priceLimit",$search_price);
        $result->execute();
        
        $conn = NULL; // free conection
        return $result;
    }
    
    function DrinkSearchCountRows($search_item, $search_price, $conn) {
        // Don't allow price higher than 100
        if($search_price > 101) $search_price = 100;
        // Create the string holding the column name
        $search_str = "drink_type." . sanitize($search_item);
        /* Search for the drinks that match the type of alchohol */
        $query = "SELECT bar.id, bar.name, specials.item, specials.price
                FROM specials
                INNER JOIN bar ON bar.id = specials.bar_id
                INNER JOIN drink_type ON drink_type.special_id = specials.id
                WHERE specials.price BETWEEN 0 AND (:priceLimit) AND ". $search_str ." = 1
                ORDER BY specials.price, bar.name ASC";
        $result = $conn->prepare($query);
        $result->bindValue(":priceLimit",$search_price);
        $result->execute();
        $rows = $result->fetch(PDO::FETCH_NUM);

        $conn = NULL; // free conection
        return $rows[0]; // [0] holds the number of rows returned from the query
    }
    
    function DrinkSearchDisplayResults($search_results, $total_rows)
    {
        $html = "";
        
        for($i = 0; $i < $total_rows; $i++) {
            $row = $search_results->fetch(PDO::FETCH_BOTH);
            $html .= '<div class="search-result-box">
                        <h2><a href="/bar-view.php?id=' . $row['id'] . '"><em>' . $row['name'] . '</em> &ndash; ' . $row['item'] . ', ' . $row['price'] . '</a> </h2>
                      </div>';
        }
        
        return $html;
    }
    
    
    /*======================= E ========================*/
    
    /* ErrorDisplay: Displays an error message with HTML elements
     * PARAM: ($error_message) the caught error message in string form 
     * RETURNS: ($error_html) string that holds all of the HTML for the error message
     */
    function ErrorDisplay($error_message)
    {
        $error_html = "<p>It seems there was an error. The error message displayed is: </p><p>" . sanitize($error_message) . "</p>";
        return $error_html;
    }
    
    
    /*======================= F ========================*/
    

    
    /*======================= G ========================*/
    
    function GoogleMapSetup($bar_instance)
    {
        $html =  "<script src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false'></script>
        <script>
            var marker;
            var map;
            var bar = new google.maps.LatLng(39.18295890, -96.58156799999999);
            var contentString = '<div id=\"content\">'+
              '<h1 id=\"firstHeading\" class=\"firstHeading\">" . $bar_instance->name . "</h1>'+
              '<div id=\"bodyContent\" style=\"font-size: 11px; height: 50px; \">'+
              '<p>Address: " . $bar_instance->address . ", " . $bar_instance->city . ", " . $bar_instance->state . "<br />' +
              'Phone: " . $bar_instance->phone . "</p> ' +
              '</div>'+
              '</div>';

              var infowindow = new google.maps.InfoWindow({
                content: contentString,
                maxWidth: 250
                });

            function initialize() {
              var mapOptions = {
                zoom: 17,
                center: new google.maps.LatLng(" . $bar_instance->lat_long . "),
                mapTypeId: google.maps.MapTypeId.HYBRID
              };
              map = new google.maps.Map(document.getElementById('map-canvas'),
                  mapOptions);

              marker = new google.maps.Marker({
                map:map,
                draggable:false,
                animation: google.maps.Animation.DROP,
                position: bar
                });

                google.maps.event.addListener(marker, 'click', function() {
                    infowindow.open(map,marker);
                });
            }

            google.maps.event.addDomListener(window, 'load', initialize);

    </script>";
        
        return $html;
    }
    
    /*======================= H ========================*/
    
    /*======================= I ========================*/
    
    
    /*======================= J ========================*/
    
    /*======================= K ========================*/

    
    /*======================= L ========================*/
    

    
    /*======================= M ========================*/
    
    /*======================= N ========================*/
    
    /*======================= O ========================*/
    
    /*======================= P ========================*/
    
    /*======================= Q ========================*/
    
    /*======================= R ========================*/
    
    /*======================= S ========================*/
    
    /* sanitze: cleans up the input for safer database entry
     * PARAM: ($dirty) The variable to be sanitized
     * RETURNS: ($safe) The sanitized input
     */
    function sanitize($dirty)
    {
        $safe = strip_tags($dirty);
        return $safe;
    }
    
    
    /* SecureSessionStart : Wrapper to start a secure PHP session
     * adapted from: http://www.wikihow.com/Create-a-Secure-Login-Script-in-PHP-and-MySQL
     * PARAM: none
     * RETURNS: none
     */
    function SecureSessionStart()
    {
        $session_name = 'BarmadilloSecureSession'; // Set a custom session name
        $secure = false; // Set to true if using https
        $httponly = true; // Stops javascript being able to access session
        session_name($session_name); // Sets the session name to the one set above
        ini_set('session.use_only_cookies', 1); // Forces session to use only cookies
        $cookieParams = session_get_cookie_params(); // Gets current cookie params
        session_set_cookie_params($cookieParams["lifetime"], '', $cookieParams["domain"], $secure, $httponly);
        session_start(); // Start the php session
        session_regenerate_id(1); // regenerated the session, delete old one
    }
    
    function SetUpBar($id)
    {
        if(is_int($id)) // make POST info is an int
        {
            
        }
    }
    
    /*======================= T ========================*/
    
    /*======================= U ========================*/
    
    /* UserLogin : Processes the inputted email and password and see's if it matches with a user in the database table USER
     * adapted from: http://www.wikihow.com/Create-a-Secure-Login-Script-in-PHP-and-MySQL
     * PARAM: ($email) User inputted email address that is used to find the correct database entry
     *        ($password) User inputted password to be hashed and matched against the user's password
     *        ($conn) The database connection
     * RETURNS: True if the email and password matches, False if it fails
     */
    function UserLogin($email, $password, $conn)
    {
        if($stmt = $conn->prepare("SELECT id, name, user_password, salt FROM user WHERE email = (:term) LIMIT 1"))
        {
            $stmt->bindValue(":term", $email); // bind email to term
            $stmt->execute(); // execute prepared query
            //$stmt->store_result();
            //$stmt->bind_result($user_id, $username, $db_password, $salt); // Get variables from result
            $result = $stmt->fetch(PDO::FETCH_BOTH);
            
            $user_id = $result['id'];
            $username = $result['name'];
            $db_password = $result['user_password'];
            $salt = $result['salt'];
            $password = hash('sha512', $password.$salt); // hash the password
            
            if(count($result) > 0) {
                if(CheckBrute($user_id, $conn) == true) {
                    //account is locked
                    //alert user account is locked
                                      
                    return false;
                } else {
                    if($db_password == $password) {
                        //password is correct
                        
                        /* set the $_SESSION variables to hold user's info */
                        $user_browser = $_SERVER['HTTP_USER_AGENT']; // Get the user-agent string of user
                        
                        $user_id = preg_replace("/[^0-9]+/", "", $user_id); // XSS protection as we might print this value
                        $_SESSION['user_id'] = $user_id;
                        $username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $username); // XSS protection as we might print this value
                        $_SESSION['username'] = $username;
                        $_SESSION['login_string'] = hash('sha512', $password.$user_browser);
                        // Login successful
                        return true;
                    } else {
                        // Password not correct
                        // Record attempt in database
                        $now = time();
                        DatabaseQuery("INSERT INTO login_attempts (user_id, time) VALUES ('$user_id', '$now')", $conn);
                        return false;
                    }
                }
            } else {
                // No user exists
                return false;
            }
        }
    }
    
    /*======================= V ========================*/
    
    /*======================= W ========================*/
    
    /*======================= X ========================*/
    
    
    
    /*======================= Y ========================*/
    
    /*======================= Z ========================*/
    
?>
