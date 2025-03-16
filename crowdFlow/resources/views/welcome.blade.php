<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>CrowdFlow | Real-Time Crowd Detection</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.css" />

    <!-- Particles.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@9/swiper-bundle.min.js"></script>

    <style>
        /* Full-screen particle background */
        #particles-js {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            z-index: -1;
        }

        /* Efect pulsant de glow subtil, cu gradient verde-mov, pentru buton */
        .glow-btn {
            position: relative;
            z-index: 1;
        }
        .glow-btn::before {
            content: "";
            position: absolute;
            top: -5px;
            left: -5px;
            right: -5px;
            bottom: -5px;
            background: linear-gradient(45deg, #16a34a, #8b5cf6);
            filter: blur(4px);
            opacity: 0.3;
            z-index: -1;
            border-radius: inherit;
            animation: pulseGlow 2s infinite;
        }
        @keyframes pulseGlow {
            0%, 100% {
                opacity: 0.3;
                transform: scale(1);
            }
            50% {
                opacity: 0.35;
                transform: scale(1.02);
            }
        }
    </style>
</head>
<body class="min-h-screen flex flex-col justify-between relative">
<!-- Particle Background Container -->
<div id="particles-js"></div>

<!-- Header -->
<header class="bg-gray-900 border-b border-gray-700 mb-8">
    <div class="container px-4 mx-auto sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Left side: Logo and Navigation -->
            <div class="flex items-center">
                <!-- Mobile menu button -->
                <div class="-m-2 xl:hidden">
                    <button type="button" class="inline-flex items-center justify-center p-2 text-white bg-gray-900 rounded-md hover:text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-offset-gray-900 focus:ring-indigo-500">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                </div>
                <!-- Logo and navigation links -->
                <div class="flex items-center ml-8">
                    <span class="text-white text-xl font-bold">CrowdFlow</span>
                    <div class="hidden sm:flex space-x-10 ml-8">
                        <a href="#" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-white transition-all duration-200 border-b-2 border-white">Welcome</a>
                        <a href="#" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-400 transition-all duration-200 border-b-2 border-transparent hover:border-gray-200 hover:text-white">Products</a>
                        <a href="#" class="inline-flex items-center px-1 pt-1 text-sm font-medium text-gray-400 transition-all duration-200 border-b-2 border-transparent hover:border-gray-200 hover:text-white">Customers</a>
                    </div>
                </div>
            </div>
            <!-- Right side: Buttons -->
            <div class="flex items-center space-x-4">
                <!-- Sign In button (outline) -->
                <a href="#"
                   class="inline-flex items-center px-4 py-2 border border-white text-white rounded-md hover:bg-gray-800 transition-colors duration-200">
                    Sign In
                </a>
                <!-- PRO button (filled) -->
                <a href="#"
                   class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700 transition-colors duration-200">
                    PRO
                </a>
            </div>
        </div>
    </div>
</header>

<!-- Main Section -->
<section class="py-12 bg-white sm:py-16 lg:py-20 xl:py-20 relative z-10 mb-8">
    <div class="px-4 mx-auto sm:px-6 lg:px-8 max-w-7xl">
        <div class="grid items-center grid-cols-1 gap-y-12 lg:grid-cols-2 lg:gap-x-16 xl:gap-x-24">
            <!-- Image Column (illustrative in this example) -->
            <div class="lg:order-2">
                <div class="grid gap-4 px-8 pt-12 bg-gray-200 rounded-3xl place-items-end sm:px-16 sm:pt-20">
                    <img class="w-full" src="images/hero.png" alt="CrowdFlow app mockup" />
                </div>
            </div>
            <!-- Content Column -->
            <div class="lg:order-1">
                <h2 class="text-3xl font-semibold tracking-tight text-gray-900 sm:text-4xl lg:text-5xl">
                    CrowdFlow – Real-Time Crowd Detection
                </h2>
                <p class="mt-4 text-base font-normal leading-7 text-gray-600 lg:text-lg lg:mt-6 lg:leading-8">
                    CrowdFlow is a revolutionary application that detects the presence of people in real time at various locations, enabling you to make the best decisions for your day.
                </p>
                <!-- Grid with Features -->
                <div class="inline-grid grid-cols-1 gap-6 mt-8 sm:grid-cols-2 sm:mt-12">
                    <!-- Real-time Tracking -->
                    <div class="flex flex-col">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-blue-600 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-lg font-medium text-gray-800 ml-2.5">Real-time Tracking</span>
                        </div>
                        <span class="ml-10 text-sm text-blue-500">34 people</span>
                    </div>
                    <!-- Exact Number of People -->
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-blue-600 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-lg font-medium text-gray-800 ml-2.5">Exact Number of People</span>
                    </div>
                    <!-- Location-based Information -->
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-blue-600 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-lg font-medium text-gray-800 ml-2.5">Location-based Information</span>
                    </div>
                </div>
                <!-- Crowd Codes Legend -->
                <div class="mt-8">
                    <div class="bg-gray-100 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Crowd Codes Legend</h3>
                        <div class="flex space-x-4">
                            <div class="flex items-center space-x-2">
                                <span class="block w-4 h-4 bg-green-500 rounded"></span>
                                <span class="text-sm text-gray-700">Not crowded</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="block w-4 h-4 bg-yellow-500 rounded"></span>
                                <span class="text-sm text-gray-700">Slightly crowded</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="block w-4 h-4 bg-orange-500 rounded"></span>
                                <span class="text-sm text-gray-700">Crowded</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="block w-4 h-4 bg-red-500 rounded"></span>
                                <span class="text-sm text-gray-700">Very crowded</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Buttons: "Continue with Google" & Analytics -->
                <div class="flex justify-start mt-8 space-x-4">
                    <a href="{{ route('login.google') }}"
                       class="flex items-center justify-center border border-gray-300 rounded-md py-3 px-5 text-lg font-medium text-gray-700 hover:bg-gray-50">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 488 512" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M488 261.8c0-15.2-1.2-30-3.6-44.2H249v83.8h134.2c-5.8 31.6-23.1 58.4-49.2 77.2v64.2h79.4c46.5-42.8 73.4-105.8 73.4-181z"/>
                            <path d="M249 504c66.8 0 123.1-22.4 164.1-60.8l-79.4-64.2c-22.1 14.8-50.4 23.4-84.7 23.4-65 0-120.1-43.8-139.9-102.8H30.5v64.4C71.6 447 153.6 504 249 504z"/>
                            <path d="M109.1 297.8c-6-18-9.4-37.3-9.4-57.8s3.4-39.8 9.4-57.8V117.8H30.5C11 145.2 0 178.1 0 242s11 96.8 30.5 124.3l78.6-68.5z"/>
                            <path d="M249 97.8c35.8 0 67.8 12.3 93 36.6l69.6-69.6C373.8 26.2 317.7 0 249 0 153.6 0 71.6 57 30.5 117.8l78.6 68.5C128.9 141.7 184 97.8 249 97.8z"/>
                        </svg>
                        Continue with Google
                    </a>
                    <a href="#"
                       class="glow-btn flex items-center justify-center bg-green-600 text-white rounded-md py-3 px-5 text-lg font-medium hover:bg-green-700 transition-colors duration-200">
                        $15/month for Analytics
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Image Slider -->
<section id="image-slider" class="py-8 bg-white relative z-10 mb-8">
    <div class="container mx-auto px-4">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <div class="swiper-slide">
                    <img src="images/slider1.jpg" alt="Slide 1" class="h-24 object-contain rounded" />
                </div>
                <div class="swiper-slide">
                    <img src="images/slider2.jpg" alt="Slide 2" class="h-24 object-contain rounded" />
                </div>
                <div class="swiper-slide">
                    <img src="images/slider3.jpg" alt="Slide 3" class="h-24 object-contain rounded" />
                </div>
                <div class="swiper-slide">
                    <img src="images/slider4.jpg" alt="Slide 4" class="h-24 object-contain rounded" />
                </div>
                <div class="swiper-slide">
                    <img src="images/slider5.jpg" alt="Slide 5" class="h-24 object-contain rounded" />
                </div>
                <div class="swiper-slide">
                    <img src="images/slider6.jpg" alt="Slide 6" class="h-24 object-contain rounded" />
                </div>
                <div class="swiper-slide">
                    <img src="images/slider7.jpg" alt="Slide 7" class="h-24 object-contain rounded" />
                </div>
                <div class="swiper-slide">
                    <img src="images/slider8.jpg" alt="Slide 8" class="h-24 object-contain rounded" />
                </div>
                <div class="swiper-slide">
                    <img src="images/slider9.jpg" alt="Slide 9" class="h-24 object-contain rounded" />
                </div>
                <div class="swiper-slide">
                    <img src="images/slider10.jpg" alt="Slide 10" class="h-24 object-contain rounded" />
                </div>
                <div class="swiper-slide">
                    <img src="images/slider11.jpg" alt="Slide 11" class="h-24 object-contain rounded" />
                </div>
                <div class="swiper-slide">
                    <img src="images/slider12.jpg" alt="Slide 12" class="h-24 object-contain rounded" />
                </div>
                <div class="swiper-slide">
                    <img src="images/slider13.jpg" alt="Slide 13" class="h-24 object-contain rounded" />
                </div>
                <div class="swiper-slide">
                    <img src="images/slider14.jpg" alt="Slide 14" class="h-24 object-contain rounded" />
                </div>
                <div class="swiper-slide">
                    <img src="images/slider15.jpg" alt="Slide 15" class="h-24 object-contain rounded" />
                </div>
            </div>
        </div>
    </div>
</section>

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

<!-- Swiper Initialization Script -->
<script>
    var swiper = new Swiper(".mySwiper", {
        loop: true,
        autoplay: {
            delay: 2000,
            disableOnInteraction: false
        },
        slidesPerView: 6,
        spaceBetween: 10,
        breakpoints: {
            1024: {
                slidesPerView: 8
            }
        }
    });
</script>

<!-- ParticlesJS Config -->
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
