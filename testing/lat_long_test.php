<?php
    $page_title = "Bar Template";
    $extra_header = "
        <style>
        
    </style>
    <script src='https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false'></script>
    <script>
    var marker;
    var map;
    var bar = new google.maps.LatLng(39.18295890, -96.58156799999999);
    var contentString = '<div id=\"content\">'+
      '<h1 id=\"firstHeading\" class=\"firstHeading\">Bar Name</h1>'+
      '<div id=\"bodyContent\" style=\"font-size: 11px; height: 50px; \">'+
      '<p>Address: 1616 Osage Street, Manhattan, KS, 66502<br />' +
      'Phone: (913) 526-8704</p> ' +
      '</div>'+
      '</div>';
      
      var infowindow = new google.maps.InfoWindow({
        content: contentString,
        maxWidth: 250
        });
        
    function initialize() {
      var mapOptions = {
        zoom: 17,
        center: new google.maps.LatLng(39.18295890, -96.58156799999999),
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
?>
<?php require_once $_SERVER['DOCUMENT_ROOT'] . '/scripts/header.php';
      require_once $_SERVER['DOCUMENT_ROOT'] . '/functions/functions.php'; 

    //$conn = DatabaseConnect();
    ?>
</head>
<body>
 
    <?php      
        echo ConvertAddressToLatLong("15128 W 132nd Street Olathe Kansas");
    ?>

        

 
 
</body>
</html>

