<?php
#PHP INCLUDES
include "connect.php";
include "Includes/templates/header.php";
include "Includes/templates/navbar.php";
?>

<style>
    @import url('https://fonts.googleapis.com/css2?family=Kantumruy:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700&display=swap');

    :root {
        --primary-color: #2563eb;
        --primary-dark: #1e40af;
        --primary-light: #3b82f6;
        --secondary-color: #0f172a;
        --accent-color: #f59e0b;
        --success-color: #10b981;
        --text-color: #1e293b;
        --text-light: #64748b;
        --text-muted: #94a3b8;
        --bg-color: #ffffff;
        --bg-secondary: #f8fafc;
        --bg-tertiary: #f1f5f9;
        --card-bg: #ffffff;
        --border-color: #e2e8f0;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
        --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
        --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1);
        --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        --navbar-bg: rgba(255, 255, 255, 0.95);
        --navbar-text: #1e293b;
        --gradient-primary: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        --gradient-accent: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    [data-theme="dark"] {
        --primary-color: #3b82f6;
        --primary-dark: #2563eb;
        --primary-light: #60a5fa;
        --secondary-color: #f8fafc;
        --accent-color: #fbbf24;
        --success-color: #34d399;
        --text-color: #f1f5f9;
        --text-light: #cbd5e1;
        --text-muted: #94a3b8;
        --bg-color: #0f172a;
        --bg-secondary: #1e293b;
        --bg-tertiary: #334155;
        --card-bg: #1e293b;
        --border-color: #334155;
        --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.3);
        --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.4);
        --shadow-md: 0 10px 15px -3px rgba(0, 0, 0, 0.4);
        --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.5);
        --navbar-bg: rgba(15, 23, 42, 0.95);
        --navbar-text: #f1f5f9;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    body {
        font-family: 'Inter', 'Kantumruy', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        background-color: var(--bg-color);
        color: var(--text-color);
        line-height: 1.7;
        overflow-x: hidden;
    }

    /* Smooth Scrolling */
    html {
        scroll-behavior: smooth;
    }

    /* Navbar Enhancement */
    .navbar {
        background-color: var(--navbar-bg) !important;
        backdrop-filter: blur(10px);
        box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1);
        padding: 1rem 0;
        transition: var(--transition);
    }

    .navbar.scrolled {
        padding: 0.5rem 0;
        box-shadow: var(--shadow);
    }

    .navbar .navbar-brand,
    .navbar .nav-link {
        color: var(--navbar-text) !important;
        font-weight: 500;
        transition: var(--transition);
    }

    .navbar .nav-link {
        position: relative;
        padding: 0.5rem 1rem !important;
        margin: 0 0.25rem;
    }

    .navbar .nav-link::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 0;
        height: 2px;
        background: var(--primary-color);
        transition: width 0.3s ease;
    }

    .navbar .nav-link:hover::after,
    .navbar .nav-link.active::after {
        width: 80%;
    }

    .navbar .nav-link:hover,
    .navbar .nav-link.active {
        color: var(--primary-color) !important;
    }

    .navbar-brand {
        font-size: 1.5rem;
        font-weight: 700;
        letter-spacing: -0.5px;
    }

    /* Theme Toggle Button */
    .theme-toggle {
        position: fixed;
        bottom: 30px;
        right: 30px;
        z-index: 1000;
        background: var(--primary-color);
        border: none;
        border-radius: 50%;
        width: 56px;
        height: 56px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        box-shadow: var(--shadow-lg);
        font-size: 20px;
        color: white;
        transition: var(--transition);
    }

    .theme-toggle:hover {
        transform: scale(1.1) rotate(15deg);
        background: var(--primary-dark);
    }

    /* Container */
    .container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
    }

    /* Section Header */
    .section-header {
        text-align: center;
        margin-bottom: 60px;
        animation: fadeInUp 0.6s ease-out;
    }

    .section-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        color: var(--text-color);
        letter-spacing: -0.5px;
    }

    .separator {
        width: 60px;
        height: 4px;
        background: var(--primary-color);
        margin: 0 auto 1.5rem;
        border: none;
        border-radius: 2px;
    }

    .section-tagline {
        font-size: 1.125rem;
        color: var(--text-light);
        max-width: 700px;
        margin: 0 auto;
        line-height: 1.8;
    }

    /* Home Section */
    .home_section {
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.95) 0%, rgba(79, 70, 229, 0.9) 100%), 
                    url('https://i.pinimg.com/1200x/af/f6/e9/aff6e9a8a6b21e15ddb3a6f8fcc884a4.jpg');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        color: white;
        position: relative;
        overflow: hidden;
    }

    .home_section::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: radial-gradient(circle at 30% 50%, rgba(255, 255, 255, 0.1) 0%, transparent 60%);
        pointer-events: none;
    }

    .home_section .section-header {
        position: relative;
        z-index: 2;
        animation: fadeInUp 0.8s ease-out;
    }

    .home_section .section-title {
        font-size: 3.5rem;
        color: white;
        margin-bottom: 1.5rem;
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        animation: fadeInUp 0.8s ease-out 0.2s both;
    }

    .home_section .separator {
        background: white;
        animation: fadeInUp 0.8s ease-out 0.4s both;
    }

    .home_section .section-tagline {
        color: rgba(255, 255, 255, 0.95);
        font-size: 1.25rem;
        animation: fadeInUp 0.8s ease-out 0.6s both;
    }

    /* Animations */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes float {
        0%, 100% {
            transform: translateY(0);
        }
        50% {
            transform: translateY(-20px);
        }
    }

    /* Our Services Section */
    .our-services {
        padding: 100px 0;
        background-color: var(--bg-secondary);
    }

    .single-feature {
        background: var(--card-bg);
        padding: 2.5rem;
        border-radius: 16px;
        margin-bottom: 30px;
        box-shadow: var(--shadow-sm);
        transition: var(--transition);
        height: 100%;
        text-align: center;
        border: 1px solid var(--border-color);
        position: relative;
        overflow: hidden;
    }

    .single-feature::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 4px;
        transition: var(--transition);
    }

    .online-learning::before {
        background: linear-gradient(90deg, #4285F4, #34A853);
    }

    .face-to-face::before {
        background: linear-gradient(90deg, #34A853, #FBBC04);
    }

    .flexible-learning::before {
        background: linear-gradient(90deg, #9C27B0, #E91E63);
    }

    .single-feature .icon-wrapper {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 80px;
        height: 80px;
        border-radius: 20px;
        margin: 0 auto 1.5rem;
        font-size: 28px;
        transition: var(--transition);
        position: relative;
    }

    .online-learning .icon-wrapper {
        background: linear-gradient(135deg, #4285F4, #34A853);
        color: white;
        box-shadow: 0 8px 16px rgba(66, 133, 244, 0.3);
    }

    .face-to-face .icon-wrapper {
        background: linear-gradient(135deg, #34A853, #FBBC04);
        color: white;
        box-shadow: 0 8px 16px rgba(52, 168, 83, 0.3);
    }

    .flexible-learning .icon-wrapper {
        background: linear-gradient(135deg, #9C27B0, #E91E63);
        color: white;
        box-shadow: 0 8px 16px rgba(156, 39, 176, 0.3);
    }

    .single-feature:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
        border-color: transparent;
    }

    .single-feature:hover .icon-wrapper {
        transform: scale(1.1) rotate(5deg);
    }

    .single-feature h4 {
        margin-bottom: 1rem;
        font-size: 1.375rem;
        color: var(--text-color);
        font-weight: 600;
    }

    .single-feature .percentage {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .single-feature p {
        color: var(--text-light);
        line-height: 1.7;
        margin-bottom: 0;
        font-size: 1rem;
    }

    /* About Area Section */
    .about-area {
        padding: 100px 0;
        background-color: var(--bg-color);
    }

    .about-area .left-area {
        padding: 0;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .about-area .left-area img {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.5s ease;
    }

    .about-area .left-area:hover img {
        transform: scale(1.05);
    }

    .about-area .right-area {
        padding: 50px;
        display: flex;
        flex-direction: column;
        justify-content: center;
    }

    .about-area h1 {
        font-size: 2.5rem;
        margin-bottom: 1.5rem;
        color: var(--text-color);
        font-weight: 700;
        line-height: 1.3;
    }

    .about-area p {
        margin-bottom: 1.25rem;
        color: var(--text-light);
        font-size: 1.0625rem;
        line-height: 1.8;
    }

    .about-area span {
        color: var(--primary-color);
        font-weight: 600;
    }

    .my-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 14px 32px;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        text-decoration: none;
        transition: var(--transition);
        align-self: flex-start;
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .my-btn:hover {
        background: var(--primary-dark);
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
    }

    /* Our Brands Section */
    .our-brands {
        padding: 100px 0;
        background-color: var(--bg-secondary);
    }

    .car-brand {
        height: 380px;
        background-size: cover;
        background-position: center;
        border-radius: 16px;
        margin-bottom: 30px;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow);
        transition: var(--transition);
    }

    .car-brand::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.7) 100%);
        transition: var(--transition);
    }

    .car-brand:hover {
        transform: translateY(-8px);
        box-shadow: var(--shadow-lg);
    }

    .car-brand:hover::before {
        background: linear-gradient(to bottom, transparent 0%, rgba(0, 0, 0, 0.85) 100%);
    }

    .brand_name {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 1.5rem;
        color: white;
        z-index: 2;
        transform: translateY(0);
        transition: var(--transition);
    }

    .brand_name h3 {
        margin: 0;
        font-size: 1.5rem;
        font-weight: 600;
    }

    /* Reservation Section */
    .reservation_section {
        padding: 100px 0;
        background: linear-gradient(135deg, rgba(37, 99, 235, 0.95) 0%, rgba(79, 70, 229, 0.9) 100%),
                    url('https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?w=1920');
        background-size: cover;
        background-position: center;
        background-attachment: fixed;
        color: white;
    }

    .car-reservation-form {
        background: var(--card-bg);
        padding: 2.5rem;
        border-radius: 16px;
        box-shadow: var(--shadow-lg);
        border: 1px solid var(--border-color);
    }

    .car-reservation-form .text_header {
        font-size: 1.75rem;
        font-weight: 700;
        margin-bottom: 2rem;
        text-align: center;
        color: var(--text-color);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
        color: var(--text-color);
        font-size: 0.9375rem;
    }

    .form-control {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        background: var(--bg-color);
        color: var(--text-color);
        font-size: 1rem;
        transition: var(--transition);
    }

    .form-control:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    .invalid-feedback {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.5rem;
    }

    .sbmt-bttn {
        width: 100%;
        padding: 16px;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .sbmt-bttn:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
    }

    /* Contact Section */
    .contact-section {
        padding: 100px 0;
        background-color: var(--bg-color);
    }

    .contact-info h2 {
        font-size: 2.25rem;
        margin-bottom: 1.5rem;
        color: var(--text-color);
        font-weight: 700;
    }

    .contact-info p {
        margin-bottom: 2rem;
        color: var(--text-light);
        font-size: 1.0625rem;
        line-height: 1.8;
    }

    .contact-info h3 {
        font-size: 1.375rem;
        margin-bottom: 1.5rem;
        color: var(--text-color);
        font-weight: 600;
    }

    .contact-info ul {
        list-style: none;
        padding-left: 0;
    }

    .contact-info ul li {
        margin-bottom: 0.75rem;
        color: var(--text-light);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .contact-info ul li span {
        color: var(--text-color);
        font-weight: 600;
    }

    .contact-form {
        background: var(--card-bg);
        padding: 2.5rem;
        border-radius: 16px;
        box-shadow: var(--shadow);
        border: 1px solid var(--border-color);
    }

    .contact_send_btn {
        width: 100%;
        padding: 16px;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-size: 1rem;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
        box-shadow: 0 4px 12px rgba(37, 99, 235, 0.3);
    }

    .contact_send_btn:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
        box-shadow: 0 6px 16px rgba(37, 99, 235, 0.4);
    }

    /* Footer Section */
    .widget_section {
        padding: 80px 0 30px;
        background: var(--bg-secondary);
        border-top: 1px solid var(--border-color);
    }

    .footer_widget {
        margin-bottom: 30px;
    }

    .footer_widget .navbar-brand {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--text-color);
        text-decoration: none;
        display: inline-block;
        margin-bottom: 1rem;
    }

    .footer_widget .navbar-brand span {
        color: var(--primary-color);
    }

    .footer_widget p {
        margin-bottom: 1.5rem;
        color: var(--text-light);
        line-height: 1.7;
    }

    .widget_social {
        list-style: none;
        padding-left: 0;
        display: flex;
        gap: 0.75rem;
    }

    .widget_social li a {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 44px;
        height: 44px;
        background: var(--primary-color);
        color: white;
        border-radius: 50%;
        transition: var(--transition);
    }

    .widget_social li a:hover {
        background: var(--primary-dark);
        transform: translateY(-4px);
    }

    .footer_widget h3 {
        font-size: 1.25rem;
        margin-bottom: 1.5rem;
        color: var(--text-color);
        font-weight: 600;
    }

    .contact_info {
        list-style: none;
        padding-left: 0;
    }

    .contact_info li {
        margin-bottom: 1rem;
        color: var(--text-light);
        display: flex;
        align-items: flex-start;
        gap: 0.75rem;
    }

    .contact_info li i {
        color: var(--primary-color);
        margin-top: 0.25rem;
    }

    .subscribe_form {
        position: relative;
    }

    .subscribe_form .form_input {
        width: 100%;
        padding: 14px 16px;
        border: 2px solid var(--border-color);
        border-radius: 8px;
        background: var(--bg-color);
        color: var(--text-color);
        margin-bottom: 1rem;
        transition: var(--transition);
    }

    .subscribe_form .form_input:focus {
        outline: none;
        border-color: var(--primary-color);
        box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
    }

    .subscribe_form .submit {
        width: 100%;
        padding: 14px;
        background: var(--primary-color);
        color: white;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        cursor: pointer;
        transition: var(--transition);
    }

    .subscribe_form .submit:hover {
        background: var(--primary-dark);
        transform: translateY(-2px);
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .section-title {
            font-size: 2rem;
        }

        .home_section .section-title {
            font-size: 2.5rem;
        }

        .about-area .right-area {
            padding: 30px 20px;
        }

        .car-reservation-form {
            padding: 2rem 1.5rem;
        }

        .contact-form {
            padding: 2rem 1.5rem;
        }

        .theme-toggle {
            width: 48px;
            height: 48px;
            bottom: 20px;
            right: 20px;
        }
    }

    /* Utility Classes */
    .text-gradient {
        background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .badge {
        display: inline-block;
        padding: 0.5rem 1rem;
        background: var(--primary-color);
        color: white;
        border-radius: 20px;
        font-size: 0.875rem;
        font-weight: 600;
        margin-bottom: 1rem;
    }
</style>

<!-- Theme Toggle Button -->
<button class="theme-toggle" id="theme-toggle" aria-label="Toggle theme">
    <i class="fas fa-moon" id="theme-icon"></i>
</button>

<!-- Home Section -->
<section class="home_section">
    <div class="section-header">
        <div class="section-title">
            សូមស្វាគម៍មកកាន់ គេហទំព័រយើងខ្ញុំ
        </div>
        <hr class="separator">
        <div class="section-tagline">
            ចាប់ពី $10 ក្នុងមួយថ្ងៃ ជាមួយនឹងការបញ្ចុះតម្លៃពិសេសក្នុងពេលកំណត់
        </div>
    </div>
</section>

<!-- Our Services Section -->
<section class="our-services" id="services">
    <div class="container">
        <div class="section-header">
            <h2 class="section-title">ការជួលរថយន្តតាមអនឡាញប៉ុណ្ណោះ</h2>
            <hr class="separator">
            <p class="section-tagline">
                សេវាកម្មជួលរថយន្តរបស់យើងអនុញ្ញាតឱ្យអ្នកកក់បានយ៉ាងងាយស្រួលតាមអនឡាញ ជាមួយប្រព័ន្ធសុវត្ថិភាព និងងាយស្រួលប្រើ។
            </p>
        </div>

        <div class="row g-4 justify-content-center">
            <!-- Feature 1 -->
            <div class="col-lg-4 col-md-6">
                <div class="single-feature online-learning">
                    <div class="icon-wrapper">
                        <i class="fas fa-laptop"></i>
                    </div>
                    <h4>កក់តាមអនឡាញ</h4>
                    <div class="percentage">100%</div>
                    <p>
                        អ្នកអាចជ្រើសរើស និងកក់រថយន្តបានគ្រប់ពេលវេលា តាមរយៈវេបសាយរបស់យើង ដោយមានប្រព័ន្ធបង់ប្រាក់សុវត្ថិភាព។
                    </p>
                </div>
            </div>

            <!-- Feature 2 -->
            <div class="col-lg-4 col-md-6">
                <div class="single-feature face-to-face">
                    <div class="icon-wrapper">
                        <i class="fas fa-mobile-alt"></i>
                    </div>
                    <h4>ងាយស្រួលប្រើ</h4>
                    <div class="percentage">100%</div>
                    <p>
                        រចនាឡើងឱ្យងាយស្រួលសម្រាប់អ្នកប្រើ អាចកក់បានត្រឹមតែប៉ុន្មានជំហាន មិនស្មុគស្មាញ។
                    </p>
                </div>
            </div>

            <!-- Feature 3 -->
            <div class="col-lg-4 col-md-6">
                <div class="single-feature flexible-learning">
                    <div class="icon-wrapper">
                        <i class="fas fa-shield-alt"></i>
                    </div>
                    <h4>សុវត្ថិភាពខ្ពស់</h4>
                    <div class="percentage">100%</div>
                    <p>
                        ទិន្នន័យ និងការទូទាត់របស់អ្នកត្រូវបានការពារ ដើម្បីផ្តល់ភាពទុកចិត្ត និងសុវត្ថិភាពក្នុងការកក់។
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- About Area Section -->
<section class="about-area">
    <div class="container-fluid">
        <div class="row align-items-center">
            <div class="col-md-6 left-area">
                <img src="https://i.pinimg.com/736x/06/02/7a/06027a4e2860a40809e60ae84bc0049f.jpg" alt="Car Rental Image">
            </div>
            <div class="col-md-6 right-area">
                <span class="badge">អំពីយើង</span>
                <h1>
                   ផ្តល់ជូននូវគុណភាពអស្ចារ្យ<br>
                   <span class="text-gradient">ជាមួយសេវាកម្មល្អបំផុត</span>
                </h1>
                <p>
                    យើងត្រៀមខ្លួនផ្តល់ជូនសេវាកម្មដ៏ល្អឥតខ្ចោះសម្រាប់អ្នក ជាមួយនឹងរថយន្តគុណភាពខ្ពស់ និងតម្លៃសមរម្យ។
                </p>
                <p>
                    ជ្រើសរើសរថយន្តដែលអ្នកស្រឡាញ់ កក់ទុកបានភ្លាមៗ ជាមួយបទពិសោធន៍ធ្វើដំណើរប្រកបដោយផាសុកភាព។ យើងនៅក្បែរអ្នក ២៤ ម៉ោង!
                </p>
                <a class="my-btn bttn" href="#reserve">
                    កក់ឥឡូវនេះ
                    <i class="fas fa-arrow-right"></i>
                </a>
            </div>
        </div>
    </div>
</section>

<!-- Our Brands Section -->
<section class="our-brands" id="brands">
    <div class="container">
        <div class="section-header">
            <div class="section-title">សេវាកម្មជួលរថយន្ត</div>
            <hr class="separator">
            <div class="section-tagline">
                ផ្តល់ជូននូវរថយន្តប្រណីតៗលំដាប់ថ្នាក់ខ្ពស់ ជាមួយសេវាកម្មល្អបំផុតដែលអ្នកអាចទុកចិត្តបាន សម្រាប់ការធ្វើដំណើរប្រកបដោយភាពថ្លៃថ្នូរ និងងាយស្រួលគ្រប់ពេលវេលា
            </div>
        </div>
        <div class="car-brands">
            <div class="row g-4">
                <?php
                $stmt = $con->prepare("Select * from car_brands");
                $stmt->execute();
                $car_brands = $stmt->fetchAll();

                foreach ($car_brands as $car_brand) {
                    $car_brand_img = "admin/Uploads/images/" . $car_brand['brand_image'];
                ?>
                    <div class="col-lg-4 col-md-6">
                        <div class="car-brand" style="background-image: url(<?php echo $car_brand_img ?>);">
                            <div class="brand_name">
                                <h3><?php echo $car_brand['brand_name']; ?></h3>
                            </div>
                        </div>
                    </div>
                <?php
                }
                ?>
            </div>
        </div>
    </div>
</section>

<!-- CAR RESERVATION SECTION -->
<section class="reservation_section" id="reserve">
    <div class="container">
        <div class="row">
            <div class="col-lg-5"></div>
            <div class="col-lg-7">
                <form method="POST" action="reserve.php" class="car-reservation-form" id="reservation_form" v-on:submit="checkForm">
                    <div class="text_header">
                        <span>កក់រថយន្តរបស់អ្នក</span>
                    </div>
                    <div>
                        <div class="form-group">
                            <label for="pickup_location">
                                <i class="fas fa-map-marker-alt"></i> ទីតាំងទទួល
                            </label>
                            <input type="text" class="form-control" name="pickup_location" placeholder="បញ្ចូលទីតាំងទទួល" v-model='pickup_location'>
                            <div class="invalid-feedback" style="display:block" v-if="pickup_location === null">
                                ទីតាំងទទួលត្រូវបានទាមទារ
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="return_location">
                                <i class="fas fa-map-marker-alt"></i> ទីតាំងត្រឡប់
                            </label>
                            <input type="text" class="form-control" name="return_location" placeholder="បញ្ចូលទីតាំងត្រឡប់" v-model='return_location'>
                            <div class="invalid-feedback" style="display:block" v-if="return_location === null">
                                ទីតាំងបញ្ចប់ត្រូវបានទាមទារ
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="pickup_date">
                                        <i class="far fa-calendar"></i> កាលបរិច្ឆេទទទួល
                                    </label>
                                    <input type="date" min="<?php echo date('Y-m-d', strtotime("+1 day")) ?>" name="pickup_date" class="form-control" v-model='pickup_date'>
                                    <div class="invalid-feedback" style="display:block" v-if="pickup_date === null">
                                        កាលបរិច្ឆេទទទួលត្រូវបានទាមទារ
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="return_date">
                                        <i class="far fa-calendar"></i> កាលបរិច្ឆេទបញ្ចប់
                                    </label>
                                    <input type="date" min="<?php echo date('Y-m-d', strtotime("+2 day")) ?>" name="return_date" class="form-control" v-model='return_date'>
                                    <div class="invalid-feedback" style="display:block" v-if="return_date === null">
                                        កាលបរិច្ឆេទបញ្ចប់ត្រូវបានទាមទារ
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Submit Button -->
                        <button type="submit" class="btn sbmt-bttn" name="reserve_car">
                            <i class="fas fa-check-circle"></i> កក់ភ្លាមៗ
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

<!-- CONTACT SECTION -->
<section class="contact-section" id="contact-us">
    <div class="container">
        <div class="row g-4">
            <div class="col-lg-6">
                <div class="contact-info">
                    <span class="badge">ទាក់ទងយើង</span>
                    <h2>
                        ទាក់ទងមកយើងហើយ
                        <br><span class="text-gradient">ផ្ញើសារមកយើងថ្ងៃនេះ!</span>
                    </h2>
                    <p>
                        ទាក់ទងមកយើងឥឡូវនេះ ដើម្បីកក់រថយន្តក្នុងក្តីស្រមៃរបស់អ្នក! រាល់ការធ្វើដំណើរ គឺជាបទពិសោធន៍ថ្មីដែលមិនអាចបំភ្លេចបាន។
                    </p>
                    <h3>
                        <i class="fas fa-map-marker-alt"></i>
                        Norton University
                    </h3>
                    <p style="margin-left: 1.75rem;">Keo Chenda St, Phnom Penh 12000</p>
                    <ul>
                        <li>
                            <i class="far fa-envelope"></i>
                            <span>អ៊ីមែល:</span> TeamNU@gmail.com
                        </li>
                        <li>
                            <i class="fas fa-phone"></i>
                            <span>ទូរស័ព្ទ:</span> +855 (0) 11 22 33 44
                        </li>
                        <li>
                            <i class="fab fa-telegram"></i>
                            <span>Telegram:</span> +855 (0) 96 898 189
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="contact-form">
                    <div id="contact_ajax_form" class="contactForm">
                        <div class="row g-3">
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>ឈ្មោះ</label>
                                    <input type="text" id="contact_name" name="name" class="form-control" placeholder="បញ្ចូលឈ្មោះ">
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group">
                                    <label>អ៊ីមែល</label>
                                    <input type="email" id="contact_email" name="email" class="form-control" placeholder="បញ្ចូលអ៊ីមែល">
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label>ប្រធានបទ</label>
                            <input type="text" id="contact_subject" name="subject" class="form-control" placeholder="បញ្ចូលប្រធានបទ">
                        </div>
                        <div class="form-group">
                            <label>សារ</label>
                            <textarea id="contact_message" name="message" cols="30" rows="5" class="form-control message" placeholder="បញ្ចូលសាររបស់អ្នក"></textarea>
                        </div>
                        <div class="form-group">
                            <button id="contact_send" class="contact_send_btn">
                                <i class="far fa-paper-plane"></i> ផ្ញើសារ
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Footer Section -->
<section class="widget_section">
    <div class="container">
        <div class="row">
            <div class="col-lg-4 col-md-6">
                <div class="footer_widget">
                    <a class="navbar-brand" href="">
                        Car<span>Rental</span>
                    </a>
                    <p>
                        ធ្វើឱ្យរាល់ការធ្វើដំណើររបស់អ្នក កាន់តែងាយស្រួល និងមានសុវត្ថិភាពជាមួយ Car Rental។
                    </p>
                    <ul class="widget_social">
                        <li><a href="#" data-toggle="tooltip" title="Facebook"><i class="fab fa-facebook-f"></i></a></li>
                        <li><a href="#" data-toggle="tooltip" title="Twitter"><i class="fab fa-twitter"></i></a></li>
                        <li><a href="#" data-toggle="tooltip" title="Instagram"><i class="fab fa-instagram"></i></a></li>
                        <li><a href="#" data-toggle="tooltip" title="LinkedIn"><i class="fab fa-linkedin"></i></a></li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="footer_widget">
                    <h3>ព័ត៌មានទំនាក់ទំនង</h3>
                    <ul class="contact_info">
                        <li>
                            <i class="fas fa-map-marker-alt"></i>
                            <span>Norton University, Keo Chenda St, Phnom Penh 12000</span>
                        </li>
                        <li>
                            <i class="far fa-envelope"></i>
                            <span>TeamNU@gmail.com</span>
                        </li>
                        <li>
                            <i class="fas fa-mobile-alt"></i>
                            <span>+855 11 22 33 44</span>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="footer_widget">
                    <h3>ព័ត៌មានដំណឹង</h3>
                    <p>កុំឱ្យខកខានអ្វីទាំងអស់! ចុះឈ្មោះដើម្បីទទួលបានការផ្តល់ជូនប្រចាំថ្ងៃ</p>
                    <div class="subscribe_form">
                        <form action="#" class="subscribe_form" novalidate="true">
                            <input type="email" name="EMAIL" id="subs-email" class="form_input" placeholder="អាសយដ្ឋានអ៊ីមែល...">
                            <button type="submit" class="submit">ចុះឈ្មោះ</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- BOTTOM FOOTER -->
<?php include "Includes/templates/footer.php"; ?>

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
<script>
    // Theme Toggle Functionality
    const themeToggle = document.getElementById('theme-toggle');
    const themeIcon = document.getElementById('theme-icon');
    const htmlElement = document.documentElement;

    // Check for saved theme preference or default to light
    const currentTheme = localStorage.getItem('theme') || 'light';
    htmlElement.setAttribute('data-theme', currentTheme);
    updateThemeIcon(currentTheme);

    themeToggle.addEventListener('click', function() {
        const theme = htmlElement.getAttribute('data-theme');
        const newTheme = theme === 'light' ? 'dark' : 'light';

        htmlElement.setAttribute('data-theme', newTheme);
        localStorage.setItem('theme', newTheme);
        updateThemeIcon(newTheme);
    });

    function updateThemeIcon(theme) {
        if (theme === 'dark') {
            themeIcon.classList.remove('fa-moon');
            themeIcon.classList.add('fa-sun');
        } else {
            themeIcon.classList.remove('fa-sun');
            themeIcon.classList.add('fa-moon');
        }
    }

    // Navbar scroll effect
    window.addEventListener('scroll', function() {
        const navbar = document.querySelector('.navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Vue.js for Form Validation
    new Vue({
        el: "#reservation_form",
        data: {
            pickup_location: '',
            return_location: '',
            pickup_date: '',
            return_date: ''
        },
        methods: {
            checkForm: function(event) {
                if (this.pickup_location && this.return_location && this.pickup_date && this.return_date) {
                    return true;
                }

                if (!this.pickup_location) {
                    this.pickup_location = null;
                }

                if (!this.return_location) {
                    this.return_location = null;
                }

                if (!this.pickup_date) {
                    this.pickup_date = null;
                }

                if (!this.return_date) {
                    this.return_date = null;
                }

                event.preventDefault();
            },
        }
    });
</script>