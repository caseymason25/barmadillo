<?php
    $page_title = NULL;
?>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/header.php';
      require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/functions.php'; 

?>

<body id="homepage">
    
    <div class="top-line">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/top-line.html'; ?> 
    </div>

    <div class="wrapper">
        <div class="sign-in-top">
            <div class="sign-in-box">
                <?php 
                    if($logged_in)
                    {
                        echo '<a href="login/my_account.php" class="green-button">My Account</a>';
                    }
                    else
                    {
                        echo '<a href="login/login.php" class="green-button">Sign In</a>';
                    }
                 ?>
            </div>
        </div>

        <div class="main-search-area">

            <img src="images/barmadillo-logo-large.png" alt="Barmadillo Logo" class="main-logo-large" />

            <form action="/templates/search-results.php"  method="post" class="main-search-box">
                <p class="ui-widget" style="height: 50px;">
                    
                    <input type="text" id="bar_search"  name="bar_search" class="main-search-bar" placeholder="Start your search" />
                    
                    <span class="price-search-options">I want some</span>
                    <select name="ui-item" id="ui-item" class="price-search-options">
                        <option value="1" SELECTED>Beer</option>
                        <option value="2">Vodka</option>
                        <option value="3">Whiskey</option>
                        <option value="4">Gin</option>
                        <option value="5">Tequila</option>
                        <option value="6">Rum</option>
                    </select>
                    <span class="price-search-options">for less than:</span>
                    <select name="ui-price" id="ui-price" class="price-search-options">
                        <option value="1" SELECTED>$1.00</option>
                        <option value="2">$2.00</option>
                        <option value="3">$3.00</option>
                        <option value="4">$4.00</option>
                        <option value="5">$5.00</option>
                        <option value="10">$10.00</option>
                        <option value="20">$20.00</option>
                        <option value="100">$100.00</option>
                    </select>
                </p>
                <p>
                    <ul class="main-search-options">
                        <li style="padding-right: 5px;"><label id="radio-main-label">Search by: </label></li>
                        <li><a href="#" style="vertical-align: baseline; margin-left: -10px;" id="name-search">Name</a></li>
                        <li><a href="#" style="vertical-align: baseline; margin-left: 20px;" id="price-search">Price</a></li>
                    </ul>
                </p>
                
                <input type="hidden" name="search_type" id="search-type" value="name">
                <p><input type="submit" name="submit_button" value="Search" class="green-button"></p>
            </form>
        </div>
    </div>
    
    <div class="bottom-line">
        <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/bottom-line.html'; ?> 
    </div>

    <script src="/js/main-search.js"></script>
</body>
</html>
