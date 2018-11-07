<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        
        <link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
        <script src="https://code.jquery.com/jquery-1.11.3.min.js"></script>
        <script src="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.js"></script>
        
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    </head>
    
    <body style="text-align: center; background-color: white !important;">
        
        <div class="row">
            <button class="col-md-6" onclick='getLocation(); $(".cidade").hide();'; style="background-color: black; color: white; text-shadow: none;">Local</button>
            
            <button class="col-md-6" onclick='$(".cidade").show(); $(".local").hide();'>Cidade</button>
        </div><br>
        
       <!-- <iframe class="local" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3657.987995171444!2d-47.136203585620265!3d-23.53293418469701!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x94cf0d54817a1725%3A0x3dcff522a27b9b96!2sS%C3%A3o+Roque+Technology+College!5e0!3m2!1sen!2sbr!4v1541354067740" width="300" height="300" frameborder="0" style="border:0" allowfullscreen></iframe> -->
       
       <?php
            $lat = isset($_GET['lat']) ? $_GET['lat']:"0";
            $lon = isset($_GET['lon']) ? $_GET['lon']:"0";
       ?>
       
       <script src='https://maps.googleapis.com/maps/api/js?v=3.exp&key=AIzaSyDi3zZmXLyQTcegg3MboiFMLQMtw_DAdFo'></script><div class="row justify-content-center" style='overflow:hidden;'><div id='gmap_canvas' style='height:400px;width:520px;'></div><style>#gmap_canvas img{max-width:none!important;background:none!important}</style></div> <a href='http://maps-generator.com/pt'>Adicione o Google Maps</a> <script type='text/javascript' src='https://embedmaps.com/google-maps-authorization/script.js?id=cdd9b280e42bfacc4a61a99c23bd33c621cc352e'></script>
       <?php 
            echo "<script type='text/javascript'>
           function init_map(){var myOptions = {zoom:12,center:new google.maps.LatLng($lat,$lon),mapTypeId: google.maps.MapTypeId.ROADMAP};map = new google.maps.Map(document.getElementById('gmap_canvas'), myOptions);marker = new google.maps.Marker({map: map,position: new google.maps.LatLng($lat,$lon)});infowindow = new google.maps.InfoWindow({content:'<strong></strong><br><br>Você está aqui<br>'});google.maps.event.addListener(marker, 'click', function(){infowindow.open(map,marker);});infowindow.open(map,marker);}google.maps.event.addDomListener(window, 'load', init_map);</script>";
          ?>
           
       <br>
       <h2>Previsão do Tempo</h2>
        
        <div class="row justify-content-center cidade">

            <input type="text" id="local" name="local" placeholder="Insira a local" size="23">
            <input type="button" onclick="getCidade(local.value);" value="Ver Clima">
        </div>       
        
        <script>
            function getLocation() {
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(showPosition);

                } else { 
                    alert("Geolocation is not supported by this browser.");
                }
            }

            function showPosition(position) {
                //recarregando a página com os valroes de latitude e longitude na URL
                window.location.assign("index.php?lat=" + position.coords.latitude + 
                "&lon=" + position.coords.longitude+"&do=yes");
            }
            
            function getCidade(cidade) {
                window.location.assign("index.php?local="+cidade);
            }
            
            
            $(document).on("pagecreate",function(event){
                $(window).on("orientationchange",function(){
                    if(window.orientation == 0){
                        alert("Sou um retrato!");
                    }else{
                        alert("Sou uma paisagem!");
                    }
                });                   
            });

        </script>
        
    </body>
</html>

<?php

//fazer alguma coisa que receba a geolocalização do usuário

if(isset($_GET['do'])&&$_GET['do']=="yes"){
    
    $lat = isset($_GET['lat']) ? $_GET['lat']:"0";
    $lon = isset($_GET['lon']) ? $_GET['lon']:"0";
    
    //Apresentando o mapa
    
    //Criando link para a API com os valores de geolocalização
    @$json= file_get_contents("http://api.openweathermap.org/data/2.5/weather?lat=$lat&lon=$lon&appid=09b5819f52b743d182cb9ad5aaf6613b");
    
    //Convertendo resultado(json) do link em um objeto
    @$obj = json_decode($json);
    
    //Aqui separo o que seria um vetor dentro de outro
    @$main = $obj->main;
    
    //Aqui também separo o que seria um vetor dentro de outro
    @$wind = $obj->wind;
    
    //Escrevendo resultados
    echo "<div class='local'>";
    echo "<h3>Local</h3>";
    echo "<br>Cidade: ".@$obj->name;
    echo "<br>Temperatura: ".@($main->temp-273.15)." ºC";
    echo "<br>Umidade: ".@$main->humidity." %";
    echo "<br>Velolocal do Vento: ".@$wind->speed." km/h";    
    echo "</div>";
}

if(isset($_GET['local'])&&isset($_GET['local'])!=""){
    
    echo '<script>$(".local").hide();</script>';
    
    $city= $_GET['local'];
    

    @$json= file_get_contents("http://api.openweathermap.org/data/2.5/weather?q=$city,br&appid=09b5819f52b743d182cb9ad5aaf6613b");

    //Convertendo resultado(json) do link em um objeto
       @$obj = json_decode($json);

        //Aqui separo o que seria um vetor dentro de outro
        @$main = $obj->main;

        //Aqui também separo o que seria um vetor dentro de outro
       @$wind = $obj->wind;

     //Escrevendo resultados
      echo "<div class='cidade'>";
      echo "<br>Cidade: ".@$obj->name;
      echo "<br>Temperatura: ".@($main->temp-273.15)." ºC";
      echo "<br>Umidade: ".@$main->humidity." %";
      echo "<br>Velolocal do Vento: ".@$wind->speed." km/h";       
      echo "</div>";
}else{
    echo '<script>$(".cidade").hide();</script>';
}

if(!isset($_GET['local'])&&!isset($_GET['do'])){
    echo '<script>$(".cidade").hide(); getLocation(); </script>';
}


?>

