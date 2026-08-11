<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Welcome to our OBGYN Clinic. We provide comprehensive gynecological and obstetric care.">
    <title>OBGYN Clinic</title>

    <!-- Correct Favicon Link (with absolute path or root placement) -->
    <link rel="icon" href="<?php echo base_url('assets/logo/favicon.ico'); ?>" type="image/x-icon">
    <!-- Or use the absolute path if it's in the root directory -->
    <!-- <link rel="icon" href="/favicon.ico" type="image/x-icon"> -->

    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script src="https://unpkg.com/unlazy@0.11.3/dist/unlazy.with-hashing.iife.js" defer init></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.css" rel="stylesheet">
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.10.2/fullcalendar.min.js"></script>
</head>


<style type="text/tailwindcss">
    @layer base {
        body, html {
            font-family: 'Arial', sans-serif;
            background-color: #f7fafc;
            height: 100%;
            margin: 0;
        }

        /* Fullscreen container for centering content */
        .full-screen {
            width: 100vw;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* Hero Section with Image */
        .hero {
            position: relative;
            background-image: url('<?php echo base_url("upload/obgy.jpg"); ?>');
            background-size: cover;
            background-position: center;
            color: white;
            padding: 100px 20px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
            overflow: hidden;
            opacity: 0;
            transform: translateY(-20px);
            animation: fadeIn 1s ease-out forwards; /* Fade-in effect */
        }

        /* Fade-in animation for hero section */
        @keyframes fadeIn {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Hero image blur effect */
        .hero::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: inherit;
            background-size: cover;
            background-position: center;
            filter: blur(8px);
            z-index: -1;
        }

        /* Service Card Styling */
        .service-card {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            padding: 20px;
            margin: 20px;
        }

        .service-card:hover {
            transform: translateY(-5px); /* Lift effect on hover */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Enhanced shadow on hover */
        }

        /* Doctor Card Styling */
        .doctor-card {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            padding: 20px;
            margin: 20px;
        }

        .doctor-card:hover {
            transform: translateY(-5px); /* Lift effect on hover */
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2); /* Enhanced shadow on hover */
        }

        /* Contact Info Card Styling */
        .contact-info {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            padding: 35px;
            margin-bottom: 20px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
        }
        

        /* Custom Green Text */
        .text-green-custom {
            color: #6dbf8c;
        }

        /* Light Green Background */
        .bg-green-custom {
            background-color: #b2e1b2;
        }

        /* Dark Green Background */
        .bg-green-custom-dark {
            background-color: #8fbf8f;
        }

        /* Animation for fade-in of all cards */
        .fadeIn {
            animation: fadeIn 1s ease-out forwards;
        }
    }

    /* Font Size Custom Class */
    .fc {
        font-size: 14px;
    }
</style>

<style type="text/tailwindcss">
    @layer base {
        /* Header Animation */
        .header {
            opacity: 0;
            transform: translateY(-20px);
            animation: fadeInDown 1s ease-out forwards; /* Fade-in and slide down animation */
        }

        @keyframes fadeInDown {
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Navigation Links Hover Effect */
        .nav-link {
            transition: color 0.3s ease, transform 0.3s ease;
        }

        .nav-link:hover {
            color: #a8d08d; /* Lighter green on hover */
            transform: translateY(-2px); /* Slight lift effect */
        }
    }
</style>
</head>

<body>
    <div class="container mx-auto p-4">
        <header class="bg-green-custom-dark text-green-100 py-4 px-6 rounded-lg shadow-md header">
            <h1 class="text-3xl font-bold text-center">OBGYN CLINIC</h1>
            <nav class="flex justify-center mt-4">
                <a href="<?= base_url('clinic/index'); ?>" class="nav-link text-green-100 hover:text-green-200 mx-3">Home</a>
                <a href="#services" class="nav-link text-green-100 hover:text-green-200 mx-3">Services</a>
                <a href="#about" class="nav-link text-green-100 hover:text-green-200 mx-3">About</a>
                <a href="#contact" class="nav-link text-green-100 hover:text-green-200 mx-3">Contact</a>
            </nav>
        </header>

        <main class="bg-gray-100 text-gray-800 py-8 rounded-lg mt-4 shadow-md">
            <!-- Hero Section-->
            <section id="home" class="hero text-center">
                <h2 class="text -4xl font-bold mb-4">Welcome to our OBGYN Clinic</h2>
                <p class="mb-6">We provide gyncological and obstetric care with a compassionate touch.</p>
                <a href="#book-appointment" class="bg-green-custom text-white py-2 px-4 rounded-lg hover:bg-green-700">Book Appointment</a>
            </section>
            <br>
            <!-- Success Message -->
            <?php if ($this->session->flashdata('success')): ?>
                <div class="bg-green-200 text-green-800 p-3 rounded-lg mb-4">
                    <p class="font-medium"><?= $this->session->flashdata('success'); ?></p>
                    <p class="mt-1">Please wait for the approval confirmation email. Once you receive an email of the approved message, you can reply to cancel your appointment via reply to the email.</p>
                </div>
            <?php endif; ?>

            <!-- Error Messages -->
            <?php if ($this->session->flashdata('error')): ?>
                <div class="bg-red-200 text-red-800 p-3 rounded-lg mb-4">
                    <p class="font-medium"><?= $this->session->flashdata('error'); ?></p>
                    <?php
                    $error_type = $this->session->flashdata('error');
                    if (strpos($error_type, 'appointment every 5 minutes') !== false): ?>
                        <p class="mt-1">You can only create one appointment every 5 minutes. Please wait before booking again.</p>
                    <?php elseif (strpos($error_type, 'time slot is already booked') !== false): ?>
                        <p class="mt-1">You can’t book this time; it’s already booked. Please choose a different time slot.</p>
                    <?php endif; ?>
                </div>
            <?php endif; ?>


            <!-- Our Services -->
            <section id="services" class="max-w-4xl mx-auto mt-8">
                <h2 class="text-3xl font-bold mb-4 text-center text-green-custom">Our Services</h2>
                <p class="mb-4 text-center">Explore the range of services we offer at our OBGYN Clinic.</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="service-card p-4">
                        <h3 class="text-lg font-semibold mb-2">Parental Check Up and Delivery</h3>
                        <p>Comprehensive care throughout pregnancy, including delivery services.</p>
                    </div>
                    <div class="service-card p-4">
                        <h3 class="text-lg font-semibold mb-2">Gyn-cologic Check Up</h3>
                        <p>Routine check-ups to ensure gynecological health and address any concerns.</p>
                    </div>
                    <div class="service-card p-4">
                        <h3 class="text-lg font-semibold mb-2">Basic Infertility Work Up</h3>
                        <p>Initial assessments to diagnose and address potential causes of infertility.</p>
                    </div>
                    <div class="service-card p-4">
                        <h3 class="text-lg font-semibold mb-2">OBGYN Ultrasound</h3>
                        <p>Ultrasound imaging to monitor pregnancy and evaluate gynecological conditions.</p>
                    </div>
                </div>
            </section>

            <!-- About Us Section -->
            <section id="about" class="max-w-4xl mx-auto mt-8 text-center">
                <div class="container mx-auto px-4">
                    <h2 class="text-3xl font-bold mb-4 text-green-custom">About Us</h2>
                    <p class="mb-4">Established in 1968, Mendoza General Hospital is a recognized healthcare institution in Sta. Maria, Bulacan, Philippines. Services: Surgery, OBGYN, Pediatrics, Internal Medicine, Ophthalmology, EENT, Orthopedics, Urology, Medical Oncology, etc.</p>

                </div>
            </section>
            <div class="doctor-card p-6 mt-6 bg-white rounded-lg shadow-lg transform transition duration-300 ease-in-out hover:scale-105 hover:shadow-2xl">
                <div class="flex items-center mb-4">
                    <!-- Placeholder Image for the Physician -->
                    <div class="w-20 h-20 bg-gray-300 rounded-full overflow-hidden mr-4">
                        <img src="<?php echo base_url('upload/drachona.png'); ?>" alt="Doctor Photo" class="w-full h-full object-cover">
                    </div>
                    <!-- Doctor Info -->
                    <div>
                        <h2 class="text-3xl font-bold text-green-custom mb-2">Physician</h2>
                        <h3 class="text-lg font-semibold text-gray-800">Dra. Ma. Chona M. Mendoza MD, FPOGS, FPSUOG</h3>
                    </div>
                </div>
                <hr class="border-t-2 border-green-custom mb-4">
                <div class="text-center">
                    <p class="text-sm text-gray-600">Experienced in Obstetrics and Gynecology with years of service in the healthcare field.</p>
                </div>
            </div>


            <!-- Doctor Section -->
            <section id="doctor" class="max-w-4xl mx-auto mt-16 text-center">
                <h2 class="text-3xl font-bold mb-4 text-green-custom">Meet Our Doctor</h2>
                <div class="flex justify-center items-center mt-8">
                    <div class="doctor-card p-6 rounded-lg text-center max-w-lg">
                        <img src="<?php echo base_url('upload/drachona.png'); ?>" alt="Dr. Ma. Chona M. Mendoza" class="w-40 h-40 rounded-full mx-auto mb-4">
                        <h3 class="text-2xl font-semibold">Dra. Ma. Chona M. Mendoza MD, FPOGS, FPSUOG</h3>
                        <p class="mt-2 text-gray-600">OB-GYN Specialist</p>
                        <hr class="my-4">
                        <p class="text-gray-700">Dr. Mendoza has over 20 years of experience in Obstetrics and Gynecology. She is a Fellow of the Philippine Obstetrical and Gynecological Society (FPOGS) and the Philippine Society of Ultrasound in Obstetrics and Gynecology (FPSUOG). She specializes in prenatal care, gynecologic surgeries, and ultrasound diagnostics. With a compassionate approach, she ensures every patient receives personalized and attentive care.</p>
                    </div>
                </div>
            </section>

            <!-- Contact Section -->
            <section id="contact" class="max-w-4xl mx-auto mt-8 text-center">
                <h2 class="text-3xl font-bold mb-4 text-green-custom">Contact Us</h2>
                <div class="contact-info">
                    <p class="mb-2">Phone: <a href="tel:+639952302499" class="text-green -custom">+63 995 230 2499</a></p>
                    <p class="mb-2">Email: <a href="mailto:info@mendozagenhospital.com" class="text-green-custom">info@mendozagenhospital.com</a></p>
                    <p class="mb-2">Location: <a href="https://www.google.com/maps/place/Mendoza+General+Hospital,+A+Morales+St,+Santa+Maria,+Bulacan,+Philippines/@14.8531229,120.9627468,14z/data=!4m8!4m7!1m0!1m5!1m1!1s0x33b14e6bd105b5bb:0x2d5880b2df9d4307!2m2!1d120.9760535!2d14.853124" target="_blank" class="text-green-custom">Get Directions</a></p>
                </div>
            </section>

            <!-- Appointment Section -->
            <!-- <div class="formcontainer mx-auto p-6">
                <section id="book-appointment" class="bg-green-custom text-white p-6 rounded-lg mt-6 shadow-md">
                    <h2 class="text-2xl font-bold mb-4 text-center">Book an Appointment</h2>
                    <p class="text-2xl font-bold mb-4 text-center">Please book an appointment 3 - 4 business days</p>
                    <form action="<?= base_url('onlineappointments/onlinestore'); ?>" method="post" class="space-y-4">

                       
                        <div class="flex flex-col mb-3">
                            <label for="email" class="text-sm font-medium mb-1">Email:</label>
                            <input type="email" class="bg-gray-100 border border-gray-300 rounded-lg p-2 text-black focus:outline-none focus:ring-2 focus:ring-green-500" id="email" name="email" placeholder="Enter email" aria-label="Email" required>
                        </div>

                        <div class="flex flex-col mb-3">
                            <label for="firstname" class="text-sm font-medium mb-1">First Name:</label>
                            <input type="text" class="bg-gray-100 border border-gray-300 rounded-lg p-2 text-black focus:outline-none focus:ring-2 focus:ring-green-500" id="firstname" name="firstname" placeholder="Enter first name" aria-label="First Name" required>
                        </div>

                        <div class="flex flex-col mb-3">
                            <label for="lastname" class="text-sm font-medium mb-1">Last Name:</label>
                            <input type="text" class="bg-gray-100 border border-gray-300 rounded-lg p-2 text-black focus:outline-none focus:ring-2 focus:ring-green-500" id="lastname" name="lastname" placeholder="Enter last name" aria-label="Last Name" required>
                        </div>

                        <div class="flex flex-col mb-3">
                            <label for="contact_number" class="text-sm font-medium mb-1">Contact Number:</label>
                            <input type="tel" class="bg-gray-100 border border-gray-300 rounded-lg p-2 text-black focus:outline-none focus:ring-2 focus:ring-green-500" id="contact_number" name="contact_number" placeholder="Enter your contact number" aria-label="Contact Number" required>
                        </div>

                        <div class="flex flex-col mb-3">
                            <label for="appointment_date" class="text-sm font-medium mb-1">Appointment Date:</label>
                            <input
                                type="date"
                                class="bg-gray-100 border border-gray-300 rounded-lg p-2 text-black focus:outline-none focus:ring-2 focus:ring-green-500"
                                id="appointment_date"
                                name="appointment_date"
                                aria-label="Appointment Date"
                                required>
                        </div>
                        
                                              
                        <script>
                            const appointmentDateInput = document.getElementById('appointment_date');

                            appointmentDateInput.addEventListener('input', () => {
                                const selectedDate = new Date(appointmentDateInput.value);
                                const day = selectedDate.getDay(); // 0 = Sunday

                                if (day === 0) {
                                    // If Sunday, clear the input and alert the user
                                    appointmentDateInput.value = '';
                                    alert('Appointments cannot be scheduled on Sundays. Please select another day.');
                                }
                            });
                        </script>



                        <div class="flex flex-col mb-3">
                            <label for="appointment_time" class="text-sm font-medium mb-1">Appointment Time:</label>
                            <select class="bg-gray-100 border border-gray-300 rounded-lg p-2 text-black focus:outline-none focus:ring-2 focus:ring-green-500" id="appointment_time" name="appointment_time" aria-label="Appointment Time" required>
                               
                            </select>
                        </div>


                        <div class="bg-gray-100 text-gray-800 p-3 rounded-lg mb-4">
                            <h3 class="font-semibold mb-2 text-center">Clinic Hours</h3>
                            <ul class="space-y-1 text-center text-sm">
                                <li>Monday: 9 AM - 5 PM</li>
                                <li>Tuesday: 9 AM - 5 PM</li>
                                <li>Wednesday: 9 AM - 5 PM</li>
                                <li>Thursday: 9 AM - 5 PM</li>
                                <li>Friday: 9 AM - 5 PM</li>
                                <li>Saturday: Closed</li>
                                <li>Sunday: Closed</li>
                            </ul>
                        </div>

                     
                        <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-200 w-full">Book Now</button>
                    </form>
                </section>
            </div> -->
            <div class="formcontainer mx-auto p-6">
                <section id="book-appointment" class="bg-green-custom text-white p-6 rounded-lg mt-6 shadow-md">
                    <h2 class="text-2xl font-bold mb-4 text-center">Book an Appointment</h2>
                    <p class="text-2xl font-bold mb-4 text-center">Please book an appointment 3 - 4 business days</p>

                    <div class="text-center mb-4 space-x-4">
                        <button id="newMemberButton"
                            class="bg-white text-green-600 font-semibold py-3 px-6 rounded-full shadow-md border border-green-600 
               hover:bg-gray-200 hover:text-green-800 transition-transform transform hover:scale-105">
                            🆕 New Member
                        </button>

                        <button id="existingMemberButton"
                            class="bg-white text-green-600 font-semibold py-3 px-6 rounded-full shadow-md border border-green-600 
               hover:bg-gray-200 hover:text-green-800 transition-transform transform hover:scale-105">
                            🏥 Existing Member
                        </button>
                    </div>


                    <form id="newMemberForm" action="<?= base_url('onlineappointments/onlinestore'); ?>" method="post" class="space-y-4 hidden">
                        <input type="hidden" id="registration_id" name="registration_id" value="<?= isset($registration_id) ? $registration_id : ''; ?>">

                        <!-- Email -->
                        <div class="flex flex-col mb-3">
                            <label for="email" class="text-sm font-medium mb-1">Email:</label>
                            <input type="email" id="email" name="email" class="bg-gray-100 border rounded-lg p-2 text-black" placeholder="Enter email" required>
                        </div>

                        <!-- First Name -->
                        <div class="flex flex-col mb-3">
                            <label for="name" class="text-sm font-medium mb-1">First Name:</label>
                            <input type="text" id="name" name="name" class="bg-gray-100 border rounded-lg p-2 text-black" placeholder="Enter first name" required>
                        </div>
                        <div class="flex flex-col mb-3">
                            <label for="mname" class="text-sm font-medium mb-1">Middle Name:</label>
                            <input type="text" id="mname" name="mname" class="bg-gray-100 border rounded-lg p-2 text-black" placeholder="Enter middle name" required>
                        </div>
                        <!-- Last Name -->
                        <div class="flex flex-col mb-3">
                            <label for="lname" class="text-sm font-medium mb-1">Last Name:</label>
                            <input type="text" id="lname" name="lname" class="bg-gray-100 border rounded-lg p-2 text-black" placeholder="Enter last name" required>
                        </div>

                        <!-- Contact Number -->
                        <div class="flex flex-col mb-3">
                            <label for="patient_contact_no" class="text-sm font-medium mb-1">Contact Number:</label>
                            <input type="tel" id="patient_contact_no" name="patient_contact_no" class="bg-gray-100 border rounded-lg p-2 text-black" placeholder="Enter contact number" required>
                        </div>
                        <!-- PhilHealth ID -->
                        <div class="flex flex-col mb-3">
                            <label for="philhealth_id" class="text-sm font-medium mb-1">PhilHealth ID:</label>
                            <input type="text" id="philhealth_id" name="philhealth_id" class="bg-gray-100 border rounded-lg p-2 text-black" placeholder="Enter PhilHealth ID">
                        </div>

                        <!-- Birthday -->
                        <div class="flex flex-col mb-3">
                            <label for="birthday" class="text-sm font-medium mb-1">Birthday:</label>
                            <input type="date" id="birthday" name="birthday" class="bg-gray-100 border rounded-lg p-2 text-black" required onchange="calculateAge()">
                        </div>
                        <!-- Age -->
                        <div class="flex flex-col mb-3">
                            <label for="age" class="text-sm font-medium mb-1">Age:</label>
                            <input type="number" id="age" name="age" class="bg-gray-100 border rounded-lg p-2 text-black" placeholder="Enter your age" min="0" required readonly>
                        </div>

                        <script>
                            function calculateAge() {
                                const birthdayInput = document.getElementById('birthday');
                                const ageInput = document.getElementById('age');

                                if (birthdayInput.value) {
                                    const birthday = new Date(birthdayInput.value);
                                    const today = new Date();
                                    let age = today.getFullYear() - birthday.getFullYear();
                                    const monthDifference = today.getMonth() - birthday.getMonth();

                                    // Adjust age if the birthday hasn't occurred yet this year
                                    if (monthDifference < 0 || (monthDifference === 0 && today.getDate() < birthday.getDate())) {
                                        age--;
                                    }

                                    ageInput.value = age;
                                } else {
                                    ageInput.value = ''; // Clear the age field if no date is provided
                                }
                            }
                        </script>

                        <!-- Address -->
                        <div class="flex flex-col mb-3">
                            <label for="address" class="text-sm font-medium mb-1">Address:</label>
                            <input type="text" id="address" name="address" class="bg-gray-100 border rounded-lg p-2 text-black" placeholder="Enter address" required>
                        </div>
                        <!-- Marital Status -->
                        <div class="flex flex-col mb-3">
                            <label for="marital_status" class="text-sm font-medium mb-1">Marital Status:</label>
                            <select id="marital_status" name="marital_status" class="bg-gray-100 border rounded-lg p-2 text-black" required>
                                <option value="" disabled selected>Select marital status</option>
                                <option value="single">Single</option>
                                <option value="married">Married</option>
                                <option value="divorced">Divorced</option>
                                <option value="widowed">Widowed</option>
                            </select>
                        </div>

                        <!-- Husband's Name -->
                        <div class="flex flex-col mb-3">
                            <label for="husband" class="text-sm font-medium mb-1">Name of Guardian (if applicable):</label>
                            <input type="text" id="husband" name="husband" class="bg-gray-100 border rounded-lg p-2 text-black" placeholder="Enter Guardian's name">
                        </div>

                        <!-- Husband's Contact Number -->
                        <div class="flex flex-col mb-3">
                            <label for="husband_phone" class="text-sm font-medium mb-1">Contact Number:</label>
                            <input type="tel" id="husband_phone" name="husband_phone" class="bg-gray-100 border rounded-lg p-2 text-black" placeholder="Enter Guardian's contact number">
                        </div>

                        <!-- Occupation -->
                        <div class="flex flex-col mb-3">
                            <label for="occupation" class="text-sm font-medium mb-1">Relation to the Patient:</label>
                            <input type="text" id="occupation" name="occupation" class="bg-gray-100 border rounded-lg p-2 text-black" placeholder="Enter Relation">
                        </div>

                        <div class="flex flex-col mb-3">
                            <label for="appointment_date" class="text-sm font-medium mb-1">Appointment Date:</label>
                            <input
                                type="date"
                                class="bg-gray-100 border border-gray-300 rounded-lg p-2 text-black focus:outline-none focus:ring-2 focus:ring-green-500"
                                id="appointment_date"
                                name="appointment_date"
                                aria-label="Appointment Date"
                                required>
                        </div>

                        <script>
                            const appointmentDateInput = document.getElementById('appointment_date');

                            appointmentDateInput.addEventListener('input', () => {
                                const selectedDate = new Date(appointmentDateInput.value);
                                const day = selectedDate.getDay(); // 0 = Sunday

                                if (day === 0) {
                                    // If Sunday, clear the input and alert the user
                                    appointmentDateInput.value = '';
                                    alert('Appointments cannot be scheduled on Sundays. Please select another day.');
                                }
                            });
                        </script>

                        <div class="flex flex-col mb-3">
                            <label for="appointment_time" class="text-sm font-medium mb-1">Appointment Time:</label>
                            <select class="bg-gray-100 border border-gray-300 rounded-lg p-2 text-black focus:outline-none focus:ring-2 focus:ring-green-500" id="appointment_time" name="appointment_time" aria-label="Appointment Time" required>
                            </select>
                        </div>

                        <div class="bg-gray-100 text-gray-800 p-3 rounded-lg mb-4">
                            <h3 class="font-semibold mb-2 text-center">Clinic Hours</h3>
                            <ul class="space-y-1 text-center text-sm">
                                <li>Monday: 9 AM - 5 PM</li>
                                <li>Tuesday: 9 AM - 5 PM</li>
                                <li>Wednesday: 9 AM - 5 PM</li>
                                <li>Thursday: 9 AM - 5 PM</li>
                                <li>Friday: 9 AM - 5 PM</li>
                                <li>Saturday: 9 AM - 5 PM</li>
                                <li>Sunday: Closed</li>
                            </ul>
                        </div>

                        <button type="submit" class="bg-green-600 text-white py-2 px-4 rounded-lg hover:bg-green-700 transition duration-200 w-full">Next</button>
                    </form>

                    <div id="existingMemberForm" class="hidden text-center mt-6">
                        <a href="<?= base_url('appointments/search_form') ?>"
                            class="bg-white text-green-600 font-semibold py-3 px-6 rounded-full shadow-md hover:bg-gray-200 hover:text-green-800 transition-transform transform hover:scale-105 inline-block border border-green-600">
                            ✅ Already a member? Book an Appointment!
                        </a>
                    </div>


                </section>
            </div>

            <script>
                document.getElementById('newMemberButton').addEventListener('click', function() {
                    document.getElementById('newMemberForm').classList.remove('hidden');
                    document.getElementById('existingMemberForm').classList.add('hidden');
                });

                document.getElementById('existingMemberButton').addEventListener('click', function() {
                    document.getElementById('newMemberForm').classList.add('hidden');
                    document.getElementById('existingMemberForm').classList.remove('hidden');
                });
            </script>
            </section>
    </div>
    <script>
        document.querySelectorAll("input[type='text'], input[type='tel'], input[type='date']").forEach(function(input) {
            input.addEventListener("input", function() {
                this.value = this.value
                    .toLowerCase() // Ensure lowercase for non-initial letters
                    .replace(/\b\w/g, function(char) {
                        return char.toUpperCase(); // Capitalize each word
                    });
            });
        });
    </script>

    <!-- <script>
    document.getElementById('nextButton').addEventListener('click', function() {
        const registrationId = document.getElementById('registration_id').value;
        if (registrationId) {
            window.location.href = "<?= base_url('medication/online_medication/'); ?>" + registrationId;
        } else {
            alert("Please book an appointment first.");
        }
    });
</script> -->
    <!-- Add some CSS for the container (if needed) -->
    <style>
        .formcontainer {
            max-width: 800px;
            /* Adjust this value to change the maximum width */
            margin: 0 auto;
            /* Center the container */
        }

        .bg-green-custom {
            background-color: #6bbf77;

        }
    </style>



    <!-- Appointment Calendar -->
    <section id="book-appointment" class="max-w-4xl mx-auto mt-8">
        <h2 class="text-3xl font-bold mb-4 text-center text-green-custom">Book Your Appointment</h2>
        <div id="calendar" class="mt-4"></div>
    </section>
    </main>

    <!-- <footer class="text-center py-4">
    <p class="text-gray-600">© 2024 OBGYN Clinic. All Rights Reserved.</p>
</footer> -->
    </div>

    <script>
        $(document).ready(function() {
            $('#calendar').fullCalendar({
                header: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'month,agendaWeek,agendaDay'
                },
                editable: true,
                events: {
                    url: '<?= site_url('calendar/get_events'); ?>', // Call the new method
                    method: 'GET',
                    failure: function() {
                        alert('There was an error while fetching events!');
                    }
                },
                eventRender: function(event, element) {
                    // Remove the clickability
                    element.removeAttr('href'); // Remove href if it exists
                    element.css('cursor', 'default'); // Change cursor to default
                }
            });
        });
    </script>




    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    </main>

    <!-- <footer class="bg-green-500 text-green-100 py-4 px-6 text-center">
            <p>&copy; <?= date('Y'); ?> OBGYN Clinic. All rights reserved.</p>
        </footer> -->
    <!-- dont delete the script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.11.6/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const appointmentDateInput = document.getElementById("appointment_date");
            const appointmentTimeSelect = document.getElementById("appointment_time");

            // Set min date to today and set today's date as default
            const today = new Date();
            const philippinesTimeOffset = 8 * 60; // Offset in minutes for UTC+8
            today.setMinutes(today.getMinutes() + today.getTimezoneOffset() + philippinesTimeOffset);

            const todayString = today.toISOString().split("T")[0];
            appointmentDateInput.setAttribute("min", todayString);
            appointmentDateInput.value = todayString; // Set current date as default

            // Define lunch break slots for exclusion
            const lunchBreakSlots = [{
                    hour: 11,
                    minute: 30
                },
                {
                    hour: 12,
                    minute: 0
                },
                {
                    hour: 17,
                    minute: 0
                },
                {
                    hour: 17,
                    minute: 30
                }

            ];

            // Helper function to check if the time is within lunch break
            function isLunchBreak(hour, minute) {
                return lunchBreakSlots.some(slot => slot.hour === hour && slot.minute === minute);
            }

            // Function to populate time slots based on selected date and current time
            function populateTimeSlots() {
                const selectedDate = new Date(appointmentDateInput.value);
                const now = new Date();
                now.setMinutes(now.getMinutes() + now.getTimezoneOffset() + philippinesTimeOffset); // Convert current time to Philippine time

                // Clear previous options
                appointmentTimeSelect.innerHTML = "";

                // Generate time options in 30-minute intervals from the current time to 5:00 PM Philippine time
                if (selectedDate.toDateString() === now.toDateString()) {
                    let startHour = now.getHours();
                    let startMinute = now.getMinutes() >= 30 ? 30 : 0;
                    const endHour = 17;

                    for (let hour = startHour; hour <= endHour; hour++) {
                        for (let minute = startMinute; minute < 60; minute += 30) {
                            if (isLunchBreak(hour, minute)) continue;

                            const option = document.createElement("option");
                            const formattedHour = hour > 12 ? hour - 12 : hour; // Convert to 12-hour format
                            const formattedMinute = minute === 0 ? '00' : minute;
                            const amPm = hour >= 12 ? 'PM' : 'AM';

                            option.value = `${hour.toString().padStart(2, '0')}:${formattedMinute}`; // Store as 'HH:MM'
                            option.textContent = `${formattedHour}:${formattedMinute} ${amPm}`; // Display in 12-hour format
                            appointmentTimeSelect.appendChild(option);
                        }
                        startMinute = 0; // Reset startMinute after the first hour
                    }
                } else {
                    // For other future dates, show all slots from 9:00 AM to 5:00 PM
                    for (let hour = 9; hour <= 17; hour++) {
                        for (let minute = 0; minute < 60; minute += 30) {
                            if (isLunchBreak(hour, minute)) continue;

                            const option = document.createElement("option");
                            const formattedHour = hour > 12 ? hour - 12 : hour;
                            const formattedMinute = minute === 0 ? '00' : minute;
                            const amPm = hour >= 12 ? 'PM' : 'AM';

                            option.value = `${hour.toString().padStart(2, '0')}:${formattedMinute}`;
                            option.textContent = `${formattedHour}:${formattedMinute} ${amPm}`;
                            appointmentTimeSelect.appendChild(option);
                        }
                    }
                }
            }

            // Update time slots every minute to ensure past times are removed
            setInterval(() => {
                if (appointmentDateInput.value === todayString) {
                    populateTimeSlots();
                }
            }, 60000); // Refresh every 60 seconds

            // Initial population of time slots
            appointmentDateInput.addEventListener("change", populateTimeSlots);
            populateTimeSlots();
        });
    </script>

    </div>
</body>

</html>