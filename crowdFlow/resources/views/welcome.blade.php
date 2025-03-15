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

    <!-- Particles.js Library -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/particles.js/2.0.0/particles.min.js"></script>

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
    </style>
</head>
<body class="min-h-screen flex items-center justify-center relative">
<!-- Particle Background Container -->
<div id="particles-js"></div>

<section class="py-12 bg-white sm:py-16 lg:py-20 xl:py-20 relative z-10">
    <div class="px-4 mx-auto sm:px-6 lg:px-8 max-w-7xl">
        <div class="grid items-center grid-cols-1 gap-y-12 lg:grid-cols-2 lg:gap-x-16 xl:gap-x-24">

            <!-- Image Column -->
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
                    CrowdFlow is a revolutionary application that detects the presence of people in real time across various locations. Instantly learn how crowded a specific area is and make the best decisions for your daily schedule.
                </p>

                <!-- Features Grid -->
                <div class="inline-grid grid-cols-1 gap-6 mt-8 sm:grid-cols-2 sm:mt-12">

                    <!-- Real Time Human Tracking with Number -->
                    <div class="flex flex-col">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-blue-600 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                            </svg>
                            <span class="text-lg font-medium text-gray-800 ml-2.5">Real time human tracking</span>
                        </div>
                        <span class="ml-10 text-sm text-blue-500">34 People</span>
                    </div>

                    <!-- Exact Count of People -->
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-blue-600 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-lg font-medium text-gray-800 ml-2.5">Exact Count of People</span>
                    </div>

                    <!-- Location-based Information -->
                    <div class="flex items-center">
                        <svg class="w-6 h-6 text-blue-600 shrink-0" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                        <span class="text-lg font-medium text-gray-800 ml-2.5">Information based on a specific location</span>
                    </div>
                </div>

                <!-- Congestion Codes Legend -->
                <div class="mt-8">
                    <div class="bg-gray-100 rounded-lg p-4">
                        <h3 class="text-lg font-semibold text-gray-800 mb-2">Congestion Codes</h3>
                        <div class="flex space-x-4">
                            <div class="flex items-center space-x-2">
                                <span class="block w-4 h-4 bg-green-500 rounded"></span>
                                <span class="text-sm text-gray-700">Green (Low)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="block w-4 h-4 bg-yellow-500 rounded"></span>
                                <span class="text-sm text-gray-700">Yellow (Moderate)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="block w-4 h-4 bg-orange-500 rounded"></span>
                                <span class="text-sm text-gray-700">Orange (High)</span>
                            </div>
                            <div class="flex items-center space-x-2">
                                <span class="block w-4 h-4 bg-red-500 rounded"></span>
                                <span class="text-sm text-gray-700">Red (Severe)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- "Continue with Google" Button -->
                <div class="flex justify-start mt-8">
                    <a href="{{ route('login.google') }}" class="flex items-center justify-center border border-gray-300 rounded-md py-3 px-5 text-lg font-medium text-gray-700 hover:bg-gray-50">
                        <svg class="w-5 h-5 mr-2" viewBox="0 0 488 512" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path d="M488 261.8c0-15.2-1.2-30-3.6-44.2H249v83.8h134.2c-5.8 31.6-23.1 58.4-49.2 77.2v64.2h79.4c46.5-42.8 73.4-105.8 73.4-181z"/>
                            <path d="M249 504c66.8 0 123.1-22.4 164.1-60.8l-79.4-64.2c-22.1 14.8-50.4 23.4-84.7 23.4-65 0-120.1-43.8-139.9-102.8H30.5v64.4C71.6 447 153.6 504 249 504z"/>
                            <path d="M109.1 297.8c-6-18-9.4-37.3-9.4-57.8s3.4-39.8 9.4-57.8V117.8H30.5C11 145.2 0 178.1 0 242s11 96.8 30.5 124.3l78.6-68.5z"/>
                            <path d="M249 97.8c35.8 0 67.8 12.3 93 36.6l69.6-69.6C373.8 26.2 317.7 0 249 0 153.6 0 71.6 57 30.5 117.8l78.6 68.5C128.9 141.7 184 97.8 249 97.8z"/>
                        </svg>
                        Continue with Google
                    </a>
                </div>

            </div>
        </div>
    </div>
</section>

<script>
    /* ParticlesJS Config */
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
