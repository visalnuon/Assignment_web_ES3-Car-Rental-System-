

```php
<?php
// =====================================================
// PHP LOGIC SECTION
// =====================================================

// Get current page for active menu highlighting
$current_page = basename($_SERVER['PHP_SELF']);

// Contact information
$contact_email = "TeamNU@gmail.com";
$contact_phone = "+855 12 345 678";

// Social media links
$social_links = [
    'facebook' => '#',
    'twitter' => '#',
    'instagram' => '#',
    'linkedin' => '#'
];

// Navigation menu items
$nav_items = [
    ['url' => 'index.php', 'label' => 'ទំព័រដើម', 'id' => 'home'],
    ['url' => 'index.php#services', 'label' => 'សេវាកម្ម', 'id' => 'services'],
    ['url' => 'index.php#brands', 'label' => 'ប្រភេទរថយន្ត', 'id' => 'brands'],
    ['url' => 'index.php#reserve', 'label' => 'ការកក់ទុក', 'id' => 'reserve'],
    ['url' => 'index.php#contact-us', 'label' => 'ទំនាក់ទំនង', 'id' => 'contact']
];

// Function to check if menu item is active
function isActive($url, $current_page) {
    // Remove anchor links to check the file name
    $page = explode('#', $url)[0];
    return ($page === $current_page) ? 'active' : '';
}

// Page Title (can be overridden by including scripts)
$page_title = isset($page_title) ? $page_title : 'Car Rental';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <!-- Font Awesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Custom CSS for Styling -->
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        /* --- Top Bar Styling --- */
        .top-bar {
            background-color: #0033a0;
            /* Deep blue from the image */
            color: white;
            padding: 8px 0;
            font-size: 14px;
        }

        .top-bar a {
            color: white;
            margin-left: 10px;
            transition: color 0.2s;
        }

        .top-bar a:hover {
            color: #adb5bd;
            /* Lighter color on hover */
        }

        .language-selector {
            background-color: transparent;
            border: 1px solid white;
            color: white;
            font-size: 14px;
            padding: 2px 10px;
            border-radius: 3px;
        }

        .language-selector:hover,
        .language-selector:focus {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
        }

        .dropdown-menu {
            border: none;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        /* --- Main Navbar Styling --- */
        .main-navbar {
            background-color: white;
            padding: 15px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
        }

        .main-navbar .navbar-brand {
            font-weight: bold;
            font-size: 24px;
            color: #333;
            display: flex;
            align-items: center;
        }

        .main-navbar .navbar-brand img {
            margin-right: 10px;
        }

        .main-navbar .navbar-nav .nav-link {
            color: #555;
            font-weight: 500;
            margin: 0 10px;
            text-transform: uppercase;
            font-size: 15px;
            transition: color 0.2s;
        }

        .main-navbar .navbar-nav .nav-link:hover,
        .main-navbar .navbar-nav .nav-item.active .nav-link {
            color: #0033a0;
            /* Blue color for active/hover state */
        }

        .btn-portal {
            background-color: #0033a0;
            border-color: #0033a0;
            border-radius: 4px;
            color: white;
            padding: 8px 20px; /* Slightly adjusted padding */
            font-weight: 500;
            transition: background-color 0.2s;
            text-transform: uppercase;
            font-size: 14px;
        }

        .btn-portal:hover {
            background-color: #002580;
            /* Darker blue on hover */
            border-color: #002580;
            color: white;
        }
    </style>
</head>

<body>

    <!-- START NAVBAR SECTION -->
    <header id="header" class="nav-header">
        <!-- TOP BAR -->
        <div class="top-bar">
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-md-6 d-none d-md-block">
                        <span>
                            <i class="fas fa-envelope"></i> <?php echo $contact_email; ?>
                        </span>
                    </div>
                    <div class="col-12 col-md-6 text-center text-md-right">
                        <span class="mr-2">Follow Us:</span>
                        <a href="<?php echo $social_links['facebook']; ?>" aria-label="Facebook">
                            <i class="fab fa-facebook-f"></i>
                        </a>
                        <a href="<?php echo $social_links['twitter']; ?>" aria-label="Twitter">
                            <i class="fab fa-twitter"></i>
                        </a>
                        <a href="<?php echo $social_links['instagram']; ?>" aria-label="Instagram">
                            <i class="fab fa-instagram"></i>
                        </a>
                        <a href="<?php echo $social_links['linkedin']; ?>" aria-label="LinkedIn">
                            <i class="fab fa-linkedin-in"></i>
                        </a>
                        <div class="dropdown d-inline-block ml-3">
                            <!-- Language selector can go here if needed -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- MAIN NAVBAR -->
        <nav class="navbar navbar-expand-lg navbar-light main-navbar">
            <div class="container">
                <!-- LOGO and BRAND NAME -->
                <a class="navbar-brand" href="index.php">
                    <img src="https://i.pinimg.com/736x/32/0a/f0/320af068b356b0521588f2b19159bbbf.jpg" 
                         alt="Car Rental Logo" height="40">
                    <span style="color:#333;">Car</span><span style="color:#0033a0;"> Rental</span>
                </a>

                <!-- Mobile Toggler Button -->
                <button class="navbar-toggler" type="button" data-toggle="collapse" 
                        data-target="#carRentalNavbar" aria-controls="carRentalNavbar" 
                        aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <!-- Navbar Links -->
                <div class="collapse navbar-collapse" id="carRentalNavbar">
                    
                    <ul class="navbar-nav mx-auto">
                        <?php foreach ($nav_items as $item): ?>
                            <li class="nav-item <?php echo isActive($item['url'], $current_page); ?>">
                                <a class="nav-link" href="<?php echo $item['url']; ?>">
                                    <?php echo $item['label']; ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>

                    <!-- Right Side Items (Login Button) -->
                    <div class="navbar-nav">
                        <a href="admin/dashboard.php" class="btn btn-portal my-2 my-sm-0 mr-2">
                            <i class="fas fa-tachometer-alt mr-1"></i> Dashboard
                        </a>      
                    </div>

                </div>
            </div>
        </nav>
    </header>

    <!-- =========================================== -->
    <!-- YOUR PAGE CONTENT GOES BELOW THIS LINE      -->
    <!-- =========================================== -->

    

    <!-- Bootstrap JS and dependencies (for dropdowns, toggler, etc.) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

</body>

</html>
```