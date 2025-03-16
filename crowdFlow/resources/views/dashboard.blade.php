<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | CrowdFlow</title>

    <!-- Tailwind CSS CDN (for quick prototyping) -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Style for the map container -->
    <style>
        #map {
            height: 400px;
            width: 100%;
        }
    </style>

    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://cdn.jsdelivr.net/npm/leaflet@1.9.3/dist/leaflet.js"></script>

    <!-- jQuery (needed for dynamic content update) -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <!-- Bootstrap (optional, for styling leaflet plugins) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Leaflet Awesome Markers Plugin -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.awesome-markers/2.0.2/leaflet.awesome-markers.css" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Leaflet.awesome-markers/2.0.2/leaflet.awesome-markers.js"></script>

    <!-- Font Awesome (for icons) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.2.0/css/all.min.css" />
</head>
<body class="bg-gray-100">

<!-- Top Navigation -->
<nav class="bg-white shadow fixed top-0 inset-x-0 z-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex justify-between items-center py-4">
            <div class="flex items-center space-x-4">
                <span class="text-2xl font-bold text-gray-800">CrowdFlow</span>
            </div>
            <div class="flex items-center space-x-4">
                <span class="hidden sm:block text-gray-700">Welcome, {{ Auth::user()->name }}!</span>
                <!-- Logout link using POST by submitting a hidden form -->
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-red-500 hover:text-red-700 font-medium">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </div>
    </div>
</nav>

<!-- Main Content -->
<div class="pt-20">
    <div class="container mx-auto p-8">
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Dashboard</h1>
            <p class="text-gray-600">
                This is the CrowdFlow control panel, where you can monitor crowd levels in real time at various locations.
            </p>
        </div>

        <!-- Leaflet Map Section -->
        <div class="bg-white shadow rounded-lg p-6">
            <h2 class="text-2xl font-bold text-gray-800 mb-4">Location: Iulius UBC 0 Haufe Group!</h2>
            <div id="map"></div>
            <!-- Info Section (initially hidden, shown upon marker click) -->
            <div id="info-section" class="mt-4 p-6 bg-gray-50 shadow rounded-lg" style="display:none;">
                <p id="info-content" class="text-gray-800"></p>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-10">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 gap-x-5 gap-y-12 md:grid-cols-4 md:gap-x-12">
            <div>
                <p class="text-base text-gray-400">Company</p>
                <ul class="mt-8 space-y-4">
                    <li><a href="#" class="text-base text-white hover:opacity-80">About</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80">Features</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80">Works</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80">Career</a></li>
                </ul>
            </div>
            <div>
                <p class="text-base text-gray-400">Help</p>
                <ul class="mt-8 space-y-4">
                    <li><a href="#" class="text-base text-white hover:opacity-80">Customer Support</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80">Delivery Details</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80">Terms &amp; Conditions</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80">Privacy Policy</a></li>
                </ul>
            </div>
            <div>
                <p class="text-base text-gray-400">Resources</p>
                <ul class="mt-8 space-y-4">
                    <li><a href="#" class="text-base text-white hover:opacity-80">Free eBooks</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80">Development Tutorial</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80">How-to - Blog</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80">YouTube Playlist</a></li>
                </ul>
            </div>
            <div>
                <p class="text-base text-gray-400">Extra Links</p>
                <ul class="mt-8 space-y-4">
                    <li><a href="#" class="text-base text-white hover:opacity-80">Customer Support</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80">Delivery Details</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80">Terms &amp; Conditions</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80">Privacy Policy</a></li>
                </ul>
            </div>
        </div>
        <hr class="mt-16 border-gray-800" />
        <div class="flex flex-wrap items-center justify-between mt-8">
            <h2 class="text-3xl font-semibold tracking-tight">CrowdFlow</h2>
            <p class="text-sm">© Copyright 2025, All Rights Reserved by CrowdFlow</p>
        </div>
    </div>
</footer>

<!-- Leaflet Map Initialization with Custom Marker Colors and Info Section Update -->
<script>
    // Initialize the map in the "map" element
    var map = L.map("map", {
        center: [45.753959, 21.225967],
        zoom: 14,
        zoomControl: true,
        preferCanvas: false,
        crs: L.CRS.EPSG3857
    });

    // Add tile layer from CARTO (light theme)
    var tile_layer = L.tileLayer("https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png", {
        minZoom: 0,
        maxZoom: 20,
        maxNativeZoom: 20,
        noWrap: false,
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/attributions">CARTO</a>',
        subdomains: "abcd",
        detectRetina: false,
        tms: false,
        opacity: 1
    });
    tile_layer.addTo(map);

    /**
     * Helper function to add a marker using AwesomeMarkers.
     * The optional parameter mongoId is used to make an AJAX request
     * to the backend endpoint for fetching details from MongoDB.
     *
     * @param {String} mongoId - The MongoDB document ID.
     */
    function addMarker(mongoId) {
        $.ajax({
            url: '/locations/' + mongoId,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // Expected data object properties:
                // { _id, capacity, count, description, lat, locationName, lon }
                let locationName = data.locationName;
                let capacity = parseFloat(data.capacity);
                let lat = parseFloat(data.lat);
                let lon = parseFloat(data.lon);
                let description = data.description;
                let count = data.count;
                let percentage = (count / capacity) * 100;
                let markerColor, congestionDesc;

                if (percentage < 40) {
                    markerColor = "green";
                    congestionDesc = "Green - Not crowded";
                } else if (percentage < 50) {
                    markerColor = "yellow";
                    congestionDesc = "Yellow - Slightly crowded";
                } else if (percentage < 75) {
                    markerColor = "orange";
                    congestionDesc = "Orange - Crowded";
                } else {
                    markerColor = "red";
                    congestionDesc = "Red - Very crowded";
                }

                // Create the awesome marker icon with the computed marker color
                let awesomeIcon = L.AwesomeMarkers.icon({
                    markerColor: markerColor,
                    iconColor: "white",
                    icon: "info-sign",
                    prefix: "glyphicon",
                    extraClasses: "fa-rotate-0"
                });

                let coords = [lat, lon];
                let marker = L.marker(coords, { icon: awesomeIcon }).addTo(map);

                // Bind a permanent tooltip displaying the location name
                marker.bindTooltip("<div>" + locationName + "</div>", {
                    permanent: true,
                    direction: "top",
                    offset: [0, -20]
                });

                // On marker click, update the info section with details
                marker.on("click", function() {
                    let content =
                        "<h3>" + locationName + "</h3>" +
                        "<p><strong>Description:</strong> " + description + "</p>" +
                        "<p><strong>Capacity:</strong> " + capacity + "</p>" +
                        "<p><strong>Current Count:</strong> " + count + " (" + percentage.toFixed(1) + "%)</p>" +
                        "<div style='margin-top:5px;'><strong>Congestion Level:</strong> " +
                        "<span style='display:inline-block; width:15px; height:15px; background-color:" + markerColor +
                        "; margin:0 5px; vertical-align:middle;'></span>" + congestionDesc + "</div>";

                    $("#info-content").html(content);
                    $("#info-section").show();
                });
            },
            error: function(err) {
                console.error("Error fetching location data:", err);
                $("#info-content").html("Failed to load location data from MongoDB.");
                $("#info-section").show();
            }
        });
    }

    // Usage Example:
    addMarker("67d60b8c6544c169f73bbd89");
    addMarker("67d56cc288ff8426b0a9d87e");
    addMarker("67d60f146544c169f73bbd8a");
    addMarker("67d6111c6544c169f73bbd8b");
    addMarker("67d611786544c169f73bbd8c");
    addMarker("67d611de6544c169f73bbd8d");
    addMarker("67d612496544c169f73bbd8e");
    addMarker("67d6129a6544c169f73bbd8f");
    addMarker("67d613166544c169f73bbd90");
    addMarker("67d6135a6544c169f73bbd91");
    addMarker("67d613a16544c169f73bbd92");
    addMarker("67d613df6544c169f73bbd93");
    addMarker("67d614286544c169f73bbd94");
</script>
</body>
</html>
