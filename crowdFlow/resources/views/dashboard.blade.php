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
                <a href="{{ route('logout') }}" class="text-red-500 hover:text-red-700 font-medium">Logout</a>
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
            <!-- Info Section: Ascuns inițial, se afișează la click -->
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
    // Inițializare harta în elementul "map"
    var map = L.map("map", {
        center: [45.753959, 21.225967],
        zoom: 14,
        zoomControl: true,
        preferCanvas: false,
        crs: L.CRS.EPSG3857
    });

    // Adăugare strat de plăcuțe de la CARTO (temă light)
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
     * Funcție helper pentru adăugarea unui marker folosind AwesomeMarkers.
     * Parametrul suplimentar mongoId (opțional) este folosit pentru a face o cerere AJAX
     * la endpoint-ul backend-ului pentru a prelua detalii din MongoDB.
     *
     * @param {Array} coords - [lat, lng]
     * @param {String} infoText - Textul informativ pentru secțiunea de sub hartă
     * @param {String} tooltipText - Numele locației, afișat permanent deasupra markerului
     * @param {String} statusColor - Culoarea markerului (ex.: "red", "yellow", "orange", "green")
     * @param {String|null} mongoId - (Opțional) Id-ul folosit pentru preluarea datelor din MongoDB
     */
    function addMarker(coords, infoText, tooltipText, statusColor, mongoId = null) {
        var awesomeIcon = L.AwesomeMarkers.icon({
            markerColor: statusColor,
            iconColor: "white",
            icon: "info-sign",
            prefix: "glyphicon",
            extraClasses: "fa-rotate-0"
        });
        var marker = L.marker(coords, { icon: awesomeIcon }).addTo(map);

        // Tooltip permanent, poziționat deasupra markerului
        marker.bindTooltip("<div>" + tooltipText + "</div>", {
            permanent: true,
            direction: "top",
            offset: [0, -20]
        });

        // La click, dacă mongoId este specificat, se face o cerere AJAX către API,
        // altfel se afișează textul static și nivelul de aglomerație.
        marker.on("click", function() {
            if (mongoId) {
                $.ajax({
                    url: '/locations/' + mongoId,
                    method: 'GET',
                    dataType: 'json',
                    success: function(data) {
                        // Răspunsul de la MongoDB se așteaptă sub forma:
                        // { "_id": "...", "capacity": 54, "count": 10 }
                        var content = "<h3>" + tooltipText + "</h3>" +
                            "<p><strong>ID:</strong> " + data._id + "</p>" +
                            "<p><strong>Capacitate:</strong> " + data.capacity + "</p>" +
                            "<p><strong>Număr curent:</strong> " + data.count + "</p>";
                        $("#info-content").html(content);
                        $("#info-section").show();
                    },
                    error: function(err) {
                        console.error("Eroare la preluarea datelor:", err);
                        $("#info-content").html("Nu s-au putut prelua datele din MongoDB.");
                        $("#info-section").show();
                    }
                });
            } else {
                var statusDesc = "";
                if (statusColor === "green") {
                    statusDesc = "Verde - nu este aglomerat";
                } else if (statusColor === "yellow") {
                    statusDesc = "Galben - puțin aglomerat";
                } else if (statusColor === "orange") {
                    statusDesc = "Portocaliu - aglomerat";
                } else if (statusColor === "red") {
                    statusDesc = "Roșu - Foarte aglomerat";
                }
                var levelHTML = "<div style='margin-top:5px;'><strong>Nivel Aglomeratie:</strong> " +
                    "<span style='display:inline-block; width:15px; height:15px; background-color:" + statusColor +
                    "; margin:0 5px; vertical-align:middle;'></span>" + statusDesc + "</div>";
                var content = infoText + levelHTML;
                $("#info-content").html(content);
                $("#info-section").show();
            }
        });
        return marker;
    }

    // Adăugare marker pentru "Haufe Group" cu id-ul MongoDB hardcodat
    addMarker(
        [45.765629, 21.230695],
        "Informații despre Haufe Group.",
        "Haufe Group",
        "red",
        "67d56cc288ff8426b0a9d87e"
    );

    // Adăugare markeri pentru celelalte locații (fără AJAX specific)
    addMarker([45.74895, 21.23951], "Informații despre GymOne 3.", "GymOne 3", "green");
    addMarker([45.707757, 21.232807], "Informații despre Lidl.", "Lidl", "yellow");
    addMarker([45.753986, 21.249482], "Informații despre Curtea Berarilor La Fabrica.", "Curtea Berarilor La Fabrica", "orange");
    addMarker([45.757099, 21.227967], "Informații despre Jack's Bistro.", "Jack's Bistro", "red");
    addMarker([45.73716, 21.24157], "Informații despre Spitalul Clinic Județean de Urgență.", "Spitalul Clinic Județean de Urgență", "green");
    addMarker([45.755422, 21.233204], "Informații despre Hotel Continental.", "Hotel Continental", "orange");
    addMarker([45.73716, 21.219815], "Informații despre Kaufland.", "Kaufland", "green");
    addMarker([45.766586, 21.236062], "Informații despre Dedeman.", "Dedeman", "yellow");
    addMarker([45.755225, 21.227778], "Informații despre La Focacceria.", "La Focaccerias", "orange");
    addMarker([45.757842, 21.229859], "Informații despre Pepper - Steak & Shake.", "Pepper - Steak & Shake", "red");
    addMarker([45.754119, 21.225484], "Informații despre Starbucks.", "Starbucks", "green");
</script>
</body>
</html>
