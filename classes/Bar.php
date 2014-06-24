<?php


/**
 * Description of Bar
 *
 * @author Casey
 */
class Bar {
    private $_id;
    private $_name;
    private $_city;
    private $_state;
    private $_address;
    private $_phone;
    private $_menu; // File location on server of menu PDF
    private $_rating;
    private $_image; // File location on server of bar image
    private $_lat_long;
    private $_keywords;
    private $_specials; // array of the specials
    private $_description;
    
    /* Don't allow constructor to be called explicilty */
    private function __construct() {
        $this->_id = NULL;
        $this->_name = NULL;
        $this->_city = NULL;
        $this->_state = NULL;
        $this->_address = NULL;
        $this->_phone = NULL;
        $this->_menu = NULL;
        $this->_rating = NULL;
        $this->_image = NULL;
        $this->_lat_long = NULL;
        $this->_specials = array();
        $this->_description = NULL;
    }
    
    /* BuildFromID: Create a Bar instance using an ID to look up the information in the database
     * PARAM: ($id) the ID to be looked up
     *        ($conn) the connection object to the database
     * RETURNS: ($instance) An instance of the Bar with that ID if there is a bar with the ID
     */
    public static function BuildFromID($id, $conn)
    {
        $instance = new self();
        if($instance->PopulateByID($id, $conn) != FALSE) return $instance;
        else return NULL;
    }
    
    /* BuildFromScratch: Creates a new bar object with all the info inputted by the user 
     * PARAM: ($id...$lat_long) All of the information the bar object needs
     * RETURNS: ($instance) The instance of the newly created bar
     */
    public static function BuildFromScratch($id, $name, $city, $state, $address, $phone, $menu, $rating, $image, $link, $key)
    {
        $instance = new self();
        $instance->PopulateFromScratch($id, $name, $city, $state, $address, $phone, $menu, $rating, $image, $link, $key);        
        return $instance;
    }
    
    /* DisplaySpecials: Create and return the HTML for the bar specials lists
     * PARAM: none
     * RETURNS: ($html) String of the complated HTML of the specials list
     */
    public function DisplaySpecials()
    {
        $full_date = date("l, F j ");
        $day = date("l");
        $html = '<h3>Specials for ' . $full_date . '</h3><ul>'; // String holding all the HTML to display
        $everyday_drink_html = '<li style="list-style: none; margin-bottom: 10px;">Everyday Drink Prices:<ul>'; // String holding the everday drink specials list
        $drink_html = '<li style="list-style: none; margin-bottom: 10px;">Today&rsquo;s Drink Specials:<ul>'; // String holding the drink specials list
        $everyday_food_html = '<li style="list-style: none; margin-bottom: 10px;">Everyday Food Specials:<ul>'; // String holding the everyday food specials list
        $food_html = '<li style="list-style: none; margin-bottom: 10px;">Today&rsquo;s Food Specials:<ul>'; // String holding the food specials list
        $count = count($this->_specials); // Get the totals number of elements in the specials array
        for($i = 0; $i < $count; $i++)
        {
            if(strcmp($this->_specials[$i]['day'], "Everyday") == 0)
            {
                if(strcmp($this->_specials[$i]['item_type'], "drink") == 0) // If this item is a drink add it to the drink specials
                {
                    $everyday_drink_html .= '<li style="margin-top: 3px;">' . $this->_specials[$i]['item'] . ' &ndash; $' . $this->_specials[$i]['price'] . '</li>';
                }
                else if(strcmp($this->_specials[$i]['item_type'], "food") == 0) // If this item is a drink add it to the drink specials
                {
                    $everyday_food_html .= '<li style="margin-top: 3px;">' . $this->_specials[$i]['item'] . ' &ndash; $' . $this->_specials[$i]['price'] . '</li>';
                }
            }
            // Check the today's day and only show those specials, OR show everyday specials
             else if(strcmp($this->_specials[$i]['day'], $day) == 0)
            {
                if(strcmp($this->_specials[$i]['item_type'], "drink") == 0) // If this item is a drink add it to the drink specials
                {
                    $drink_html .= '<li>' . $this->_specials[$i]['item'] . ' &ndash; $' . $this->_specials[$i]['price'] . '</li>';
                }
                else if(strcmp($this->_specials[$i]['item_type'], "food") == 0) // If this item is food add it to the food specials
                {
                    $food_html .= '<li>' . $this->_specials[$i]['item'] . ' &ndash; $' . $this->_specials[$i]['price'] . '</li>';
                }
            }
        }
        $everyday_drink_html .= '</ul></li>';
        $everyday_food_html .= '</ul></li>';
        $drink_html .= '</ul></li>';
        $food_html .= '</ul></li>';
        $html .= $everyday_drink_html . $drink_html . $everyday_food_html. $food_html;
        $html .= '</ul>';
        return $html;
    }
    
    /* PopulateByID: Populates the bar instance with the returned results from a database lookup based on the ID 
     * PARAM: ($id) the ID to be looked up
     *        ($conn) the connection object to the database
     * RETURNS: TRUE if there is a bar with the supplied ID, FALSE if there is not
     */
    protected function PopulateByID($id, $conn)
    {
        if($conn && is_int($id)) // Connection must be present AND $id must be an int
        {
            $result = $conn->prepare('SELECT * FROM bar WHERE id = :id');
            $result->execute(array(':id'=>  $id));
            $row = $result->fetch(PDO::FETCH_BOTH);

            if($row != FALSE) // Check to make sure there is something returned
            {
                $this->_id = $row['id'];
                $this->_name = $row['name'];
                $this->_city = $row['city'];
                $this->_state = $row['state'];
                $this->_address = $row['address'];
                $this->_phone = $row['phone_number'];
                $this->_menu = $row['menu_file'];
                $this->_rating = $row['average_rating'];
                $this->_image = $row['image_file'];
                $this->_lat_long = $row['lat_long'];
                $this->_description = $row['description'];
                
                $specials_result = $conn->prepare('SELECT * FROM specials WHERE bar_id = :id ORDER BY price ASC');
                $specials_result->execute(array(':id'=>  $id));
                while($specials_row = $specials_result->fetch(PDO::FETCH_BOTH))
                {
                    array_push($this->_specials, $specials_row);
                }
                return TRUE;
            }
            else
            {
                return FALSE;
            }
        }
    }
    
    /* PopulateFromScratch: Populates the bar instance with all the info inputted by the user  
     * PARAM: ($id...$lat_long) All of the information the bar object needs
     */
    protected function PopulateFromScratch($id, $name, $city, $state, $address, $phone, $menu, $rating, $image, $description, $key)
    {
            $this->_id = $id;
            $this->_name = $name;
            $this->_city = $city;
            $this->_state = $state;
            $this->_address = $address;
            $this->_phone = $this->FormatPhoneNumber($phone);
            $this->_menu = $menu;
            $this->_rating = $rating;
            $this->_image = $image;
            $this->_lat_long = $this->ConvertAddressToLatLong();
            $this->_description = $description;
            
            /* create the keywords */
            $keywords = $key;
            $keywords .= $this->KeywordsCreator($this->_name);
            
            $this->_keywords = $keywords;
    }
    
    /* The getter for the Bar class */
    public function __get($name) {
        switch($name) {
            case 'id':
                return $this->_id;
            case 'name':
                return $this->_name;
            case 'city':
                return $this->_city;
            case 'state':
                return $this->_state;
            case 'address':
                return $this->_address;
            case 'phone':
                return $this->_phone;
            case 'menu':
                return $this->_menu;
            case 'rating':
                return $this->_rating;
            case 'image':
                return $this->_image;
            case 'lat_long':
                return $this->_lat_long;   
            case 'keywords':
                return $this->_keywords;
            case 'specials':
                return $this->_specials;
            case 'description':
                return $this->_description;
            default:
                return NULL;
        }
    }
    
    
    /* AddInDatabase: Adds the Bars information into the 'bar' table in the database
     * PARAM: ($conn) the connection object to the database
     * RETURNS: NULL if successfull. Error string if failure
     */
    public function AddNewBar($conn)
    {
        if($conn) // check for connection to databse
        {
            if($this->CheckBarExists($conn) == false) // make sure the bar doesn't exist already
            {
                if($this->AddInDatabase($conn))
                {
                    //CreateBarFile and AddInDatabase were successful
                    return NULL;
                }
                else
                {
                    // failed
                    return "AddInDatabase was unsuccessful.";
                }
            }
            else
            {
                // Bar already exists
                return "Bar already exists.";
            }
        }
        else
        {
            // $conn is null
            return "No connection to database.";
        }
    }
    
    /* CheckBarExists : Checks the database for an identical bar
     * PARAM: ($conn) the connection object to the database
     * RETURNS: TRUE if bar does exist
     */
    public function CheckBarExists($conn)
    {
        //Insert bar info into bar table
        $result = $conn->prepare('SELECT COUNT(*) FROM bar WHERE (name, address, city, state) = (:name, :address, :city, :state)');
        $result->execute(array(':name'=>  $this->_name, 
            ':address'=> $this->_address, 
            ':city'=> $this->_city, 
            ':state'=> $this->_state));
        $count_query = $result->fetch(PDO::FETCH_NUM);
        $count = $count_query[0];
        
        if($count >= 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    
    
    
    
    
    /*========================* PRIVATE FUNCTIONS *============================*/
    
    
    /* AddInDatabase: Adds the Bars information into the 'bar', 'bar_search', 'city_state', and 'bar_to_city_state' tables in the database
     * PARAM: ($conn) the connection object to the database
     * RETURNS: TRUE if successful
     */
    private function AddInDatabase($conn)
    {
        if($conn)
        {
            try
            {
                //Insert bar info into bar table in database
                $bar_query = $conn->prepare('INSERT INTO bar (id, name, address, city, state, phone_number, menu_file, average_rating, image_file, description, lat_long) 
                                         VALUES (:id, :name, :address, :city, :state, :phone_number, :menu_file, :average_rating, :image_file, :description, :lat_long)');
                $bar_query->execute(array(':id'=>  $this->_id, 
                    ':name'=>  $this->_name, 
                    ':address'=> $this->_address, 
                    ':city'=> $this->_city, 
                    ':state'=> $this->_state, 
                    ':phone_number'=> $this->_phone, 
                    ':menu_file'=> $this->_menu, 
                    ':average_rating'=> $this->_rating, 
                    ':image_file'=> $this->_image,
                    ':lat_long'=> $this->_lat_long,
                    ':description'=> $this->description));
 
                //Insert keywords into bar_search table in database
                $keywords_query = $conn->prepare('INSERT INTO bar_search (bar_id, keywords) VALUES (:id, :keywords)');
                $keywords_query->execute(array(':id'=>  $this->_id,
                    ':keywords'=>  $this->_keywords));
                
                //Find out if the city and state are already in the 'city_state' table
                $exists_query = $conn->prepare('SELECT * FROM city_state WHERE city = :city AND state = :state');
                $exists_query->execute(array(':city'=>  $this->_city,
                    ':state'=>  $this->_state));
                $exists_result = $exists_query->fetch(PDO::FETCH_BOTH); // returns false if nothing is found 
                
                if(!$exists_result)
                {
                    // City and State does not exist in table, must add all the info
                    
                    //Insert city and state into 'city_state' table in database
                    $insert_cs_query = $conn->prepare('INSERT INTO city_state (city, state) VALUES (:city, :state)');
                    $insert_cs_query->execute(array(':city'=>  $this->_city,
                        ':state'=>  $this->_state));
                    
                    //Find the ID of the newly entered city_state
                    $cs_id_query = $conn->prepare('SELECT id FROM city_state WHERE city = :city AND state = :state');
                    $cs_id_query->execute(array(':city'=>  $this->_city,
                        ':state'=>  $this->_state));
                    $cs_id_result = $cs_id_query->fetch(PDO::FETCH_NUM);
                    
                    $city_state_id = $cs_id_result[0]; // This is the city_state ID we want
                }
                else
                {
                    // City and State exists in table, city_state ID is still in @var $exists_result[0]
                    $city_state_id = $exists_result[0]; // This is the city_state ID we want
                }
                
                //Connect the city_state_id and the bar_id in the 'bar_to_city_state' table
                $bar_to_cs_query = $conn->prepare('INSERT INTO bar_to_city_state (bar_id, city_state_id) VALUES (:bar_id, :city_state_id)');
                $bar_to_cs_query->execute(array(':bar_id'=>  $this->_id,
                    ':city_state_id'=>  $city_state_id));
                
                //Free the connection
                $conn = NULL;
                
                return true;
            }
            catch(PDOException $e)
            {
                //Free the connection
                $conn = NULL;
                echo ErrorDisplay("AddInDatabase Failed");
                return false;
            }
        }
        else return false;
    }
    
    /* ConvertAddressToLatLong : Uses google geocoding API to get the latitude and longitude of an address
     * PARAM: none
     * RETURNS: string that holds the latitude and longitude of the address
     */
    private function ConvertAddressToLatLong()
    {
        $address = $this->_address . "," . $this->_city . "," . $this->_state;
        //Format the address to have '+' instead of a space
        $formatted_address = str_replace(" ", "+", $address);
        
        //Set up the google request url
        $google_request = "http://maps.googleapis.com/maps/api/geocode/json?address=" . $formatted_address . "&sensor=false";
        
        //Send the request to google
        $google_json = file_get_contents($google_request);
        
        //convert google response into associative array
        $data = json_decode($google_json, true);
        
        //Get the latitude and longitude from the array
        $lat = $data['results'][0]['geometry']['location']['lat'];
        $long = $data['results'][0]['geometry']['location']['lng'];
        
        //Combine the two for database insertion
        $lat_long = $lat . ", " . $long;
        
        return $lat_long;
    }
    
    /* FormatPhoneNumbers : Formats the phone number to before inserting it into database
     * PARAM: ($num) the phone number to be formatted
     * RETURNS: ($formatted_num) returns number if successful, NULL if it fails
     */
    private function FormatPhoneNumber($num)
    {
        $phone_chars = array("(", ")", " ", "-");
        $insert_pos = array(0, 4, 5, 9);

        //Find everything that is NOT a digit and remove it
        $formatted_num = preg_replace('/\D/','', $num);
        if(strlen($formatted_num) == 10)
        {
            for($i = 0; $i < 4; $i++) {
                $formatted_num = substr_replace($formatted_num, $phone_chars[$i], $insert_pos[$i], 0);
            }
            return $formatted_num;
        }
        else return NULL;

    }
    
    /* KeywordsCreator: Adds pieces of the bar name into the keywords for better searching
     * PARAM: none
     * RETURNS: ($keywords) String with the finished keywords
     */
    private function KeywordsCreator()
    {
        
        /* Intitial keyword addittions */
        $keywords = " " . $this->_name;
        $keywords .= " " . $this->_address;
        $keywords .= " " . $this->_city;
        $keywords .= " " . $this->_state;
        $keywords .= " " . $this->_phone;
        
        $length = strlen($this->_name); // The length of the name
        
        /* Iterate through the name and string it together piece by piece for better searching */
        for($i = 0; $i < $length; $i++)
        {
            $keywords .= " " . substr($this->_name, $i);
            $keywords .= " " . substr($this->_name, 0, $i);
        }
        
        return $keywords;
    }
 
}

?>
