
<!doctype html>

<html lang="en">
<head>
  <meta charset="utf-8" />
  <title><?php echo $bar_instance->name; ?> | Barmadillo</title>
  <meta name="description" content="Barmadillo: Easily find local bar menus, prices, and specials!"> 
  <meta http-equiv="content-type" content="text/html;charset=UTF-8">
  <link rel="icon" type="image/png" href="/favicon.ico">
  <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
  <link rel="stylesheet" type="text/css" href="/css/css-reset.css">
  <link rel="stylesheet" type="text/css" href="/css/core.css">
  <?php include_once $_SERVER['DOCUMENT_ROOT'] . '/html/autocomplete-ini.html'; ?>
  
  <?php 
      if(isset($extra_header)) {
          echo $extra_header;
      }
  ?>
  <script src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false'></script>
        <script type="text/javascript">
            
            var marker;
            var map;
            var bar = new google.maps.LatLng(<?php echo $bar_instance->lat_long; ?>);
            var contentString = '<div id="content">'+
              '<h1 id="firstHeading" class="firstHeading"><?php echo str_replace("'", "", $bar_instance->name); ?></h1>'+
              '<div id="bodyContent" style="font-size: 11px; height: 50px; ">'+
              '<p>Address: <?php echo $bar_instance->address; ?>, <?php echo $bar_instance->city; ?>, <?php echo $bar_instance->state; ?><br />' +
              'Phone: <?php echo $bar_instance->phone; ?></p> ' +
              '</div>'+
              '</div>';

              var infowindow = new google.maps.InfoWindow({
                content: contentString,
                maxWidth: 250
                });

            function initialize() {
              var mapOptions = {
                zoom: 17,
                center: new google.maps.LatLng(<?php echo $bar_instance->lat_long; ?>),
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
            
  </script>
</head>