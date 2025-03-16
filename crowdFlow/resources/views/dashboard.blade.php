<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard | CrowdFlow</title>

    <!-- Tailwind CSS CDN (for quick prototyping) -->
    <script src="https://cdn.tailwindcss.com"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Custom Styles -->
    <style>
        /* Map container style */
        #map {
            height: 400px;
            width: 100%;
        }
        /* Full-screen white loader overlay with high z-index and centered content */
        #global-loader {
            position: fixed;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
            z-index: 9999; /* higher than all components */
            background-color: #ffffff;
            display: none; /* initially hidden */
            display: flex;
            justify-content: center;
            align-items: center;
        }
        /* Loader spinner style */
        .loader {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #2563eb; /* blue-colored spinner */
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        /* Fade-in animation for info section */
        .fade-in {
            animation: fadeIn 500ms ease-in-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        /* Particles background container */
        #particles-js {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
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

    <!-- Particles.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>
</head>
<body class="bg-white relative">
<!-- Particles Background Container -->
<div id="particles-js"></div>

<!-- Global Loader Overlay -->
<div id="global-loader">
    <div class="loader"></div>
</div>

<!-- Header: New Top Navigation Integrated with Text Logo -->
<header class="fixed top-0 inset-x-0" style="z-index: 1000;">
    <div class="py-3 bg-gray-900">
        <div class="container px-4 mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between">
                <div class="block -m-2 lg:hidden">
                    <button type="button" class="inline-flex items-center justify-center p-2 text-white bg-gray-900 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
                <div class="flex-shrink-0 ml-4 lg:ml-0">
                    <a href="#" title="CrowdFlow" class="flex items-center">
                        <span class="text-2xl font-bold text-white">CrowdFlow</span>
                    </a>
                </div>
                <div class="flex-1 max-w-xs ml-8 mr-auto">
                    <label for="search" class="sr-only">Search</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <svg class="w-5 h-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                        <input type="search" id="search" class="block w-full py-2 pl-10 text-white placeholder-gray-400 bg-gray-900 border border-gray-600 rounded-lg focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" placeholder="Search here" />
                    </div>
                </div>
                <div class="flex items-center ml-4 lg:ml-0">
                    <button type="button" class="rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 focus:ring-offset-gray-900" id="options-menu-button" aria-expanded="false" aria-haspopup="true">
              <span class="flex items-center justify-between w-full">
                <span class="flex items-center justify-between min-w-0 space-x-3">
                  <img class="flex-shrink-0 object-cover bg-gray-700 rounded-full w-7 h-7" src="https://landingfoliocom.imgix.net/store/collection/clarity-dashboard/images/horizontal-menu/2/avatar-female.png" alt="" />
                  <span class="flex-1 hidden min-w-0 md:flex">
                    <span class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</span>
                  </span>
                </span>
                <svg class="flex-shrink-0 w-4 h-4 ml-2 text-gray-400 group-hover:text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                  <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                </svg>
              </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div class="hidden py-3 bg-gray-900 border-t border-gray-700 lg:block">
        <div class="container px-4 mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center space-x-4">
                <a href="#" title="" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white transition-all duration-200 bg-indigo-600 rounded-lg">
                    <svg class="w-6 h-6 mr-2 -ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                    </svg>
                    Dashboard
                </a>
                <a href="#" title="" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white transition-all duration-200 bg-gray-900 rounded-lg hover:bg-gray-700">
                    <svg class="w-6 h-6 mr-2 -ml-1 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                    Analytics
                    <svg class="w-5 h-5 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </a>
                <a href="#" title="" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white transition-all duration-200 bg-gray-900 rounded-lg hover:bg-gray-700">
                    <svg class="w-6 h-6 mr-2 -ml-1 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                    </svg>
                    Products
                    <svg class="w-5 h-5 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </a>
                <a href="#" title="" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white transition-all duration-200 bg-gray-900 rounded-lg hover:bg-gray-700">
                    <svg class="w-6 h-6 mr-2 -ml-1 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                    </svg>
                    Customers
                    <svg class="w-5 h-5 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </a>
                <a href="#" title="" class="inline-flex items-center px-3 py-2 text-sm font-medium text-white transition-all duration-200 bg-gray-900 rounded-lg hover:bg-gray-700">
                    <svg class="w-6 h-6 mr-2 -ml-1 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z" />
                    </svg>
                    Support
                    <svg class="w-5 h-5 ml-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Hidden Logout Form -->
<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
    @csrf
</form>

<!-- Main Content -->
<div class="pt-24">
    <div class="container mx-auto p-8">
        <div class="bg-white shadow rounded-lg p-6 mb-8 transition-all duration-500 transform hover:scale-105">
            <h1 class="text-3xl font-bold text-gray-800 mb-4">Dashboard</h1>
            <p class="text-gray-600">
                This is the CrowdFlow control panel, where you can monitor crowd levels in real time at various locations.
            </p>
        </div>

        <!-- Leaflet Map Section -->
        <div class="bg-white shadow rounded-lg p-6 transition-all duration-500 hover:shadow-xl">
            <h2 class="text-2xl font-bold text-gray-800 mb-4 animate-pulse">Location: Iulius UBC 0 Haufe Group!</h2>
            <div id="map" class="rounded-lg"></div>
            <!-- Info Section (initially hidden, animated when shown) -->
            <div id="info-section" class="mt-4 p-6 bg-gray-50 shadow rounded-lg transition-opacity duration-500 hidden">
                <p id="info-content" class="text-gray-800"></p>
            </div>
        </div>
    </div>
</div>

<!-- Footer -->
<footer class="bg-gray-900 text-white py-10 mt-12">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 gap-x-5 gap-y-12 md:grid-cols-4 md:gap-x-12">
            <div>
                <p class="text-base text-gray-400">Company</p>
                <ul class="mt-8 space-y-4">
                    <li><a href="#" class="text-base text-white hover:opacity-80 transition-colors">About</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80 transition-colors">Features</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80 transition-colors">Works</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80 transition-colors">Career</a></li>
                </ul>
            </div>
            <div>
                <p class="text-base text-gray-400">Help</p>
                <ul class="mt-8 space-y-4">
                    <li><a href="#" class="text-base text-white hover:opacity-80 transition-colors">Customer Support</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80 transition-colors">Delivery Details</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80 transition-colors">Terms &amp; Conditions</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80 transition-colors">Privacy Policy</a></li>
                </ul>
            </div>
            <div>
                <p class="text-base text-gray-400">Resources</p>
                <ul class="mt-8 space-y-4">
                    <li><a href="#" class="text-base text-white hover:opacity-80 transition-colors">Free eBooks</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80 transition-colors">Development Tutorial</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80 transition-colors">How-to - Blog</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80 transition-colors">YouTube Playlist</a></li>
                </ul>
            </div>
            <div>
                <p class="text-base text-gray-400">Extra Links</p>
                <ul class="mt-8 space-y-4">
                    <li><a href="#" class="text-base text-white hover:opacity-80 transition-colors">Customer Support</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80 transition-colors">Delivery Details</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80 transition-colors">Terms &amp; Conditions</a></li>
                    <li><a href="#" class="text-base text-white hover:opacity-80 transition-colors">Privacy Policy</a></li>
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
    // Ensure spinner is hidden on page refresh
    $(document).ready(function(){
        $("#global-loader").hide();
    });

    // Initialize the map in the "map" element
    var map = L.map("map", {
        center: [45.753959, 21.225967],
        zoom: 14,
        zoomControl: true,
        preferCanvas: false,
        crs: L.CRS.EPSG3857
    });
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
     * The parameter mongoId is used to fetch data from the backend via AJAX.
     *
     * @param {String} mongoId - The MongoDB document ID.
     */
    function addMarker(mongoId) {
        $.ajax({
            url: '/locations/' + mongoId,
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // Expected data: { _id, capacity, count, description, lat, locationName, lon }
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

                // When the marker is clicked: show loader for 500ms, update info section, scroll to it
                marker.on("click", function() {
                    let content =
                        "<h3 class='text-2xl font-bold mb-2'>" + locationName + "</h3>" +
                        "<p class='mb-2'><strong>Description:</strong> " + description + "</p>" +
                        "<p class='mb-2'><strong>Capacity:</strong> " + capacity + "</p>" +
                        "<p class='mb-2'><strong>Current Count:</strong> " + count + " (" + percentage.toFixed(1) + "%)</p>" +
                        "<div class='mt-2'><strong>Congestion Level:</strong> " +
                        "<span class='inline-block w-4 h-4 rounded-sm mr-2' style='background-color:" + markerColor + ";'></span>" + congestionDesc + "</div>";

                    // Show the full-screen white loader immediately
                    $("#global-loader").fadeIn(0);

                    // After 500ms, hide the loader, update the info section, and scroll to it
                    setTimeout(function(){
                        $("#global-loader").fadeOut(0);
                        $("#info-content").html(content);
                        $("#info-section").fadeIn(500).addClass("fade-in");
                        document.getElementById('info-section').scrollIntoView({ behavior: 'smooth' });
                    }, 500);
                });
            },
            error: function(err) {
                console.error("Error fetching location data:", err);
                $("#info-content").html("Failed to load location data from MongoDB.");
                $("#info-section").fadeIn(500).addClass("fade-in");
            }
        });
    }

    // Usage Examples:
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

<!-- ParticlesJS Initialization -->
<script>
    particlesJS("particles-js", {
        "particles": {
            "number": {
                "value": 80,
                "density": {
                    "enable": true,
                    "value_area": 800
                }
            },
            "color": {
                "value": "#000000"
            },
            "shape": {
                "type": "circle",
                "stroke": {
                    "width": 0,
                    "color": "#000000"
                },
                "polygon": {
                    "nb_sides": 5
                }
            },
            "opacity": {
                "value": 0.5,
                "random": false,
                "anim": {
                    "enable": false,
                    "speed": 1,
                    "opacity_min": 0.1,
                    "sync": false
                }
            },
            "size": {
                "value": 3,
                "random": true,
                "anim": {
                    "enable": false,
                    "speed": 40,
                    "size_min": 0.1,
                    "sync": false
                }
            },
            "line_linked": {
                "enable": true,
                "distance": 150,
                "color": "#000000",
                "opacity": 0.4,
                "width": 1
            },
            "move": {
                "enable": true,
                "speed": 6,
                "direction": "none",
                "random": false,
                "straight": false,
                "out_mode": "out",
                "bounce": false,
                "attract": {
                    "enable": false,
                    "rotateX": 600,
                    "rotateY": 1200
                }
            }
        },
        "interactivity": {
            "detect_on": "canvas",
            "events": {
                "onhover": {
                    "enable": true,
                    "mode": "grab"
                },
                "onclick": {
                    "enable": true,
                    "mode": "push"
                },
                "resize": true
            },
            "modes": {
                "grab": {
                    "distance": 140,
                    "line_linked": {
                        "opacity": 1
                    }
                },
                "bubble": {
                    "distance": 400,
                    "size": 40,
                    "duration": 2,
                    "opacity": 8,
                    "speed": 3
                },
                "repulse": {
                    "distance": 200,
                    "duration": 0.4
                },
                "push": {
                    "particles_nb": 4
                },
                "remove": {
                    "particles_nb": 2
                }
            }
        },
        "retina_detect": true
    });
</script>
</body>
</html>
