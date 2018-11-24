var geoA, geoB;
var map, behavior, ui, bubble, group, warehouse;
var callGeoB = false;

// set up containers for the map  + panel
var mapContainer = document.getElementById('map');

//Step 1: initialize communication with the platform
var platform = new H.service.Platform({
    app_id: '7qIkMTiarDbs6Rxn62aE',
    app_code: 'gTxGOlld1G2L9aMcW--uFg',
    useHTTPS: true
});
var pixelRatio = window.devicePixelRatio || 1;
var defaultLayers = platform.createDefaultLayers({
    tileSize: pixelRatio === 1 ? 256 : 512,
    ppi: pixelRatio === 1 ? undefined : 320
});

function InitMap(){
    mapContainer.innerHTML = '';
    map = new H.Map(mapContainer,
        defaultLayers.normal.map,{
        center: {lat:10.78282, lng:106.68414},
        zoom: 13,
        pixelRatio: pixelRatio
    });
    // Create a marker icon from an image URL:
    //var icon = new H.map.Icon('../html/assets/images/icon/maps-and-location.png');

    // Create a marker using the previously instantiated icon:
    var marker = new H.map.Marker({ lat: 10.78282, lng: 106.68414 });
    marker.instruction = 'Ebuild.vn';
    // Add the marker to the map:
    
    group = new  H.map.Group();
    group.addObject(marker);
    group.addEventListener('tap', function (evt) {
        map.setCenter(evt.target.getPosition());
        openBubble(
        evt.target.getPosition(), evt.target.instruction);
    }, false);

    // Add the maneuvers group to the map
    map.addObject(group);
    // Behavior implements default interactions for pan/zoom (also on mobile touch environments)
    behavior = new H.mapevents.Behavior(new H.mapevents.MapEvents(map));

    // Create the default UI components
    ui = H.ui.UI.createDefault(map, defaultLayers);
}
InitMap();

function geocodeA(platform) {
    if(!warehouse){
        return false;
    }
    $('#start_address').html(warehouse.name+': <b>'+warehouse.full_address+'</b>');
    $('#transport_info_from').val(warehouse.full_address);
    $('#address_from_to').show();
    geoA = false;
    var geocoder = platform.getGeocodingService(),
        geocodingParameters = {
        searchText: warehouse.full_address,
        jsonattributes : 1
    };

    geocoder.geocode(
        geocodingParameters,
        geocodeOnSuccessA,
        onError
    );
}
/**
 * This function will be called once the Geocoder REST API provides a response
 * @param  {Object} result          A JSONP object representing the  location(s) found.
 *
 * see: http://developer.here.com/rest-apis/documentation/geocoder/topics/resource-type-response-geocode.html
 */
function geocodeOnSuccessA(result) {
    var locations = result.response.view[0].result;
    geoA = locations[0].location.displayPosition;
    if(callGeoB){
        callGeocodeB();
    }
}

function geocodeB(platform,searchText) {
    geoB = false;
    callGeoB = false;
    if(!searchText) return false;
    $('#end_address').html('Đến: <b>'+searchText+'</b>');
    $('#transport_info_to').val(searchText);    
    var geocoder = platform.getGeocodingService(),
    geocodingParameters = {
        searchText: searchText,
        jsonattributes : 1
    };

    geocoder.geocode(
        geocodingParameters,
        geocodeOnSuccessB,
        onError
    );
}
/**
 * This function will be called once the Geocoder REST API provides a response
 * @param  {Object} result          A JSONP object representing the  location(s) found.
 *
 * see: http://developer.here.com/rest-apis/documentation/geocoder/topics/resource-type-response-geocode.html
 */
function geocodeOnSuccessB(result) {
    var locations = result.response.view[0].result;
    geoB = locations[0].location.displayPosition;
    calculateRouteFromAtoB (platform);
}


/**
 * Calculates and displays a car route from the Brandenburg Gate in the centre of Berlin
 * to Friedrichstraße Railway Station.
 *
 * A full list of available request parameters can be found in the Routing API documentation.
 * see:  http://developer.here.com/rest-apis/documentation/routing/topics/resource-calculate-route.html
 *
 * @param   {H.service.Platform} platform    A stub class to access HERE services
 */
function calculateRouteFromAtoB (platform) {
    if(!geoA || !geoB)
        return false;
    InitMap();
    addMarker(geoA.latitude,geoA.longitude,warehouse.name);
    addMarker(geoB.latitude,geoB.longitude);
    
    var waypoint0 = geoA.latitude+','+geoA.longitude;
    var waypoint1 = geoB.latitude+','+geoB.longitude;
    var router = platform.getRoutingService(),
    routeRequestParams = {
        mode: 'fastest;car',
        representation: 'display',
        routeattributes : 'waypoints,summary,shape,legs',
        maneuverattributes: 'direction,action',
        waypoint0: waypoint0,
        waypoint1: waypoint1,
        language:'vi_VN'
    };

    router.calculateRoute(
        routeRequestParams,
        onSuccess,
        onError
    );
}
/**
 * This function will be called once the Routing REST API provides a response
 * @param  {Object} result          A JSONP object representing the calculated route
 *
 * see: http://developer.here.com/rest-apis/documentation/routing/topics/resource-type-calculate-route.html
 */
function onSuccess(result) {
    var route = result.response.route[0];
 
    addRouteShapeToMap(route);
    addManueversToMap(route);
    addSummaryToPanel(route.summary);
    
}

/**
 * This function will be called if a communication error occurs during the JSON-P request
 * @param  {Object} error  The error message received.
 */
function onError(error) {
    console.log('Ooops!');
    callGeoB = false;
}

/**
 * Opens/Closes a infobubble
 * @param  {H.geo.Point} position     The location on the map.
 * @param  {String} text              The contents of the infobubble.
 */
function openBubble(position, text){
    bubble =  new H.ui.InfoBubble(
    position,
    // The FO property holds the province name.
    {content: text});
    ui.addBubble(bubble);
    // if(!bubble){
    //     bubble =  new H.ui.InfoBubble(
    //     position,
    //     // The FO property holds the province name.
    //     {content: text});
    //     ui.addBubble(bubble);
    // } else {
    //     bubble.setPosition(position);
    //     bubble.setContent(text);
    //     bubble.open();
    // }
}


/**
 * Creates a H.map.Polyline from the shape of the route and adds it to the map.
 * @param {Object} route A route as received from the H.service.RoutingService
 */
function addRouteShapeToMap(route){
    var lineString = new H.geo.LineString(),
        routeShape = route.shape,
        polyline;

    routeShape.forEach(function(point) {
        var parts = point.split(',');
        lineString.pushLatLngAlt(parts[0], parts[1]);
    });

    polyline = new H.map.Polyline(lineString, {
        style: {
            lineWidth: 4,
            strokeColor: 'rgba(0, 128, 255, 0.7)'
        }
    });
    // Add the polyline to the map
    map.addObject(polyline);
    // And zoom to its bounding rectangle
    map.setViewBounds(polyline.getBounds(), true);
}


/**
 * Creates a series of H.map.Marker points from the route and adds them to the map.
 * @param {Object} route  A route as received from the H.service.RoutingService
 */
function addManueversToMap(route){
    var svgMarkup = '<svg width="18" height="18" ' +
        'xmlns="http://www.w3.org/2000/svg">' +
        '<circle cx="8" cy="8" r="8" ' +
        'fill="#1b468d" stroke="white" stroke-width="1"  />' +
        '</svg>',
        dotIcon = new H.map.Icon(svgMarkup, {anchor: {x:8, y:8}}),
        group = new  H.map.Group(),
        i,
        j;

    // Add a marker for each maneuver
    for (i = 0;  i < route.leg.length; i += 1) {
        for (j = 0;  j < route.leg[i].maneuver.length; j += 1) {
            // Get the next maneuver.
            maneuver = route.leg[i].maneuver[j];
            // Add a marker to the maneuvers group
            var marker =  new H.map.Marker({
            lat: maneuver.position.latitude,
            lng: maneuver.position.longitude} ,
            {icon: dotIcon});
            marker.instruction = maneuver.instruction;
            group.addObject(marker);
        }
    }

    group.addEventListener('tap', function (evt) {
        map.setCenter(evt.target.getPosition());
        openBubble(
        evt.target.getPosition(), evt.target.instruction);
    }, false);

    // Add the maneuvers group to the map
    map.addObject(group);
}

/**
 * Creates a series of H.map.Marker points from the route and adds them to the map.
 * @param {Object} route  A route as received from the H.service.RoutingService
 */
function addSummaryToPanel(summary){
    var distance    = summary.distance.toKMM();
    var travelTime  = summary.travelTime.toMMSS();
    
    setShippingCode(summary.distance / 1000);

    //document.getElementById('summary_distance_time').innerHTML = '- <b class="color">'+distance+'</b> - Dự kiến: <b class="color">'+travelTime+'</b>';
    $('#transport_info').show();

    if(document.getElementById('transport_info_distance') != null)
        document.getElementById('transport_info_distance').value    = distance;
    
    if(document.getElementById('transport_info_time') != null)
        document.getElementById('transport_info_time').value        = travelTime;
}

Number.prototype.toKMM = function () {
    if(this > 1000){
        var num = this / 1000;
        return   num.toFixed(1)+ ' km'; 
    }
    return  this + ' m';
}

Number.prototype.toMMSS = function () {
    var min =  Math.floor(this / 60);
    if(min > 60){
        return Math.floor(min / 60) + ' giờ ' + min % 60 +' phút';
    }
    if(min == 60)
        return '1 giờ';
    return min + ' phút';
}

function addMarker(latitude,longitude,instruction){
    var marker = new H.map.Marker({ lat: latitude, lng: longitude });

    group = new  H.map.Group();    
    if(instruction){
        marker.instruction = instruction;
        group.addEventListener('tap', function (evt) {
            map.setCenter(evt.target.getPosition());
            openBubble(evt.target.getPosition(), evt.target.instruction);
        }, false);
    }
    
    group.addObject(marker);
    // Add the maneuvers group to the map
    map.addObject(group);
}
function resetTransportInfo(){
    $('.alert').hide();
    $submit = true;
    $('#address_from_to').hide();
    $('#start_address').html('');
    $('#end_address').html('');
    $('#transport_info').hide('');
    $('#summary_distance_time').html('');    
    
    $('#transport_info_warehouse').val('');
    $('#transport_info_from').val('');
    $('#transport_info_to').val('');
    $('#transport_info_distance').val('');
    $('#transport_info_time').val('');
    $('.btn_smt').prop('disabled',false);
    $('#shipping_cost').val(0);
    InitMap();
}

function mapByAddressDelivery($this){
    $.get('/cart/get-warehouse',{address_delivery:$this.val()},function(res){            
        if(res.rs){
            warehouse = res.data
            $('#transport_info_warehouse').val(warehouse.id);            
            geocodeA(platform);
            var searchText = $this.closest('li').find('p.ad-address').text();
            geocodeB(platform,searchText);
        }else{
            // Tạm ẩn check nhà kho giao hàng chờ hướng xử lý
            // $('.alert').show();
            // $submit = false;
        }
    });
}
function setShippingCode(distance){
    var distance        = distance || 0;
    var warehouse_id    = warehouse.id || 0;
    ajax_loading(true);
    $.post('/order/add-product', {distance: distance,warehouse_id:warehouse_id}, function (res) {
        ajax_loading(false);
        if (res.status) {
            set_total_price(res.data);
        }
    });
}

function callGeocodeB(){
    var searchText = $('#c_address').val();
    searchText += ', '+$('#c_ward_id option:selected').text();
    searchText += ', '+$('#c_district_id option:selected').text();
    searchText += ', '+$('#c_province_id option:selected').text();          
    geocodeB(platform,searchText);
}