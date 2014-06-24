$(document).ready(function(){
    $("#price-search").click(function(){
      $(".main-search-bar").hide();
      $(".price-search-options").show();
      document.getElementById("search-type").setAttribute("value","price");
    });
    
    $("#name-search").click(function(){
      $(".price-search-options").hide();
      $(".main-search-bar").show();
      document.getElementById("search-type").setAttribute("value","name");
    });
  });


