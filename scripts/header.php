
<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8" />
  <title>Barmadillo <?php if (isset($page_title)) echo " | " . $page_title; ?></title>
  <meta name="description" content="Barmadillo: Easily find local bar menus, prices, and specials!"> 
  <meta http-equiv="content-type" content="text/html;charset=UTF-8">
  <link rel="icon" type="image/png" href="/favicon.ico">
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <link rel="stylesheet" type="text/css" href="/css/css-reset.css">
  <link rel="stylesheet" type="text/css" href="/css/core.css">
  <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/autocomplete-ini.html'; ?>
  
  <?php 
      // This tells us whether a user is logged in or not, it will be checked using the session variable
      $logged_in = false;
      
      if(isset($extra_header)) {
          echo $extra_header;
      }
      session_start(); 
      if(!empty($_SESSION['user'])) 
      { 
          $logged_in = true;
      }
  ?>
</head>