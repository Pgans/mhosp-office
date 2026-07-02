
<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
        <meta charset="utf-8">
        <title>Maps</title>
        <script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false&libraries=places"></script>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">

     

        <script type="text/javascript">
         
            var source, destination;
            var directionsDisplay;
            var directionsService = new google.maps.DirectionsService();
            google.maps.event.addDomListener(window, 'load', function () {
                new google.maps.places.SearchBox(document.getElementById('txtSource'));
                new google.maps.places.SearchBox(document.getElementById('txtDestination'));
                directionsDisplay = new google.maps.DirectionsRenderer({'draggable': true});
            });

            function GetRoute() {
                var origin = new google.maps.LatLng(13.81936, 100.5147896);
                var mapOptions = {
                    zoom: 7,
                    center: origin
                };
                map = new google.maps.Map(document.getElementById('dvMap'), mapOptions);
                directionsDisplay.setMap(map);
                directionsDisplay.setPanel(document.getElementById('dvPanel'));

                //*********DIRECTIONS AND ROUTE**********************//
                source = document.getElementById("txtSource").value;
                destination = document.getElementById("txtDestination").value;

                var request = {
                    origin: source,
                    destination: destination,
                    travelMode: google.maps.TravelMode.DRIVING
                };
                directionsService.route(request, function (response, status) {
                    if (status == google.maps.DirectionsStatus.OK) {
                        directionsDisplay.setDirections(response);
                    }
                });

                //*********DISTANCE AND DURATION**********************//
                var service = new google.maps.DistanceMatrixService();
                service.getDistanceMatrix({
                    origins: [source],
                    destinations: [destination],
                    travelMode: google.maps.TravelMode.DRIVING,
                    unitSystem: google.maps.UnitSystem.METRIC,
                    avoidHighways: false,
                    avoidTolls: false
                }, function (response, status) {
                    if (status == google.maps.DistanceMatrixStatus.OK && response.rows[0].elements[0].status != "ZERO_RESULTS") {
                        var distance = response.rows[0].elements[0].distance.text;
                        var duration = response.rows[0].elements[0].duration.text;
                        var dvDistance = document.getElementById("dvDistance");
                        dvDistance.innerHTML = "";
                        dvDistance.innerHTML += "ระยะทาง: " + distance + "<br />";
                        dvDistance.innerHTML += "ระยะเวลาเดินทางโดยประมาณ: " + duration;

                    } else {
                        alert("Unable to find the distance via road.");
                    }
                });
            }
        </script>
    </head>
    <body>
        <div class=" container-fluid">
        <div id="map-canvas" style="width: 100%; height: auto">
         
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title"><i class="fa fa-map"></i> ค้นหาเส้นทางแผนที่โดย Google Map</h3>
                </div>
                <div class="panel-body">
                    <div class="row-fluid">
                        <div class="span12">
                            จุดเริ่มต้น:<input type="text" id="txtSource" value="โรงพยาบาลม่วงสามสิบ อำเภอม่วงสามสิบ จังหวัดอุบลราชธานี ประเทศไทย" style="width: 30%" />&nbsp;
                            ที่หมาย:<input type="text" id="txtDestination" value="" style="width: 30%" />&nbsp;
                            <input type="button" value="ค้นหาเส้นทาง" onclick="GetRoute()" />
                            <div class="row-fluid">
                                <div id="dvDistance"></div>
                                <div id="dvMap" class="span8" style="width: 100%; height: 600px"></div>
                                <div id="dvPanel" class="span4" style="width: 100%; height: auto"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
    </body>
</html>



?>