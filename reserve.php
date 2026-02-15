<?php
    session_start();
    include "connect.php";
    include "Includes/templates/header.php";
    include "Includes/templates/navbar.php";
    include "Includes/functions/functions.php";

    if (isset($_POST['reserve_car']) && $_SERVER['REQUEST_METHOD'] === 'POST')
    {
        $_SESSION['pickup_location'] = test_input($_POST['pickup_location']);
        $_SESSION['return_location'] = test_input($_POST['return_location']);
        $_SESSION['pickup_date'] = test_input($_POST['pickup_date']);
        $_SESSION['return_date'] = test_input($_POST['return_date']);
    }
?>

<!-- BANNER SECTION -->
<div class="reserve-banner-section">
    <div class="banner-overlay"></div>
    <div class="banner-content">
        <h1>ជ្រើសរើសរថយន្តដែលស័ក្តិសមបំផុតសម្រាប់អ្នក</h1>
        <p>កក់ឡានស្អាតៗ សម្រាប់ដំណើរកម្សាន្តរបស់អ្នក</p>
    </div>
</div>

<!-- CAR RESERVATION SECTION -->
<section class="car_reservation_section">
    <div class="container">
        <?php
            if(isset($_POST['submit_reservation']) && $_SERVER['REQUEST_METHOD'] === 'POST')
            {
                $selected_car = $_POST['selected_car'];
                $full_name = test_input($_POST['full_name']);
                $client_email = test_input($_POST['client_email']);
                $client_phonenumber = test_input($_POST['client_phonenumber']);
                $pickup_location = $_SESSION['pickup_location'];
                $return_location = $_SESSION['return_location'];
                $pickup_date = $_SESSION['pickup_date'];
                $return_date = $_SESSION['return_date'];
                
                $con->beginTransaction();

                try
                {
                    // Check if client already exists
                    $stmtCheckClient = $con->prepare("SELECT client_id FROM clients WHERE client_email = ?");
                    $stmtCheckClient->execute(array($client_email));
                    $existingClient = $stmtCheckClient->fetch();
                    
                    if($existingClient) {
                        // Use existing client ID
                        $client_id = $existingClient['client_id'];
                    } else {
                        // Insert new client
                        $stmtClient = $con->prepare("insert into clients(full_name,client_email,client_phone) 
                                        values(?,?,?)");
                        $stmtClient->execute(array($full_name,$client_email,$client_phonenumber));
                        $client_id = $con->lastInsertId();
                    }

                    //Inserting Reservation Details
                    $stmt_appointment = $con->prepare("insert into reservations(client_id, car_id, pickup_date, return_date, pickup_location, return_location ) values(?, ?, ?, ?, ?, ?)");
                    $stmt_appointment->execute(array($client_id,$selected_car,$pickup_date,$return_date,$pickup_location,$return_location));
                    
                    echo "<div class='success-notification'>";
                        echo "<div class='success-icon'><i class='fas fa-check-circle'></i></div>";
                        echo "<div class='success-message'>";
                            echo "<h3>Reservation Confirmed!</h3>";
                            echo "<p>Great! Your reservation has been created successfully.</p>";
                        echo "</div>";
                    echo "</div>";

                    $con->commit();
                }
                catch(Exception $e)
                {
                    $con->rollBack();
                    echo "<div class='error-notification'>";
                        echo "<div class='error-icon'><i class='fas fa-exclamation-circle'></i></div>";
                        echo "<div class='error-message'>";
                            echo "<h3>Reservation Failed</h3>";
                            echo "<p>".$e->getMessage()."</p>";
                        echo "</div>";
                    echo "</div>";
                }


            }
            elseif (isset($_SESSION['pickup_date']) && isset($_SESSION['return_date']))
            {
                $pickup_location = $_SESSION['pickup_location'];
                $return_location = $_SESSION['return_location'];
                $pickup_date = $_SESSION['pickup_date'];
                $return_date = $_SESSION['return_date'];

                // Calculate days between pickup and return
                $datetime1 = new DateTime($pickup_date);
                $datetime2 = new DateTime($return_date);
                $interval = $datetime1->diff($datetime2);
                $days = $interval->days + 1; // Add 1 to include both pickup and return days

                // MODIFIED: Explicitly select columns to avoid conflicts and ensure image is retrieved
                $stmt = $con->prepare("SELECT cars.id, cars.car_name, cars.brand_id, cars.type_id, cars.color, cars.model, cars.description, cars.price, cars.image,
                car_brands.brand_name, car_brands.brand_image, car_types.type_label
                from cars, car_brands, car_types
                where cars.brand_id = car_brands.brand_id and cars.type_id = car_types.type_id and 
                        cars.id not in (select car_id 
                                     from reservations
                                     where (? between pickup_date and return_date 
                                         or ? BETWEEN pickup_date and return_date )
                                         and canceled = 0
                                     )");
                $stmt->execute(array($pickup_date, $return_date));
                $available_cars = $stmt->fetchAll();
                ?>
                    <form action="reserve.php" method="POST" id="reservation_second_form" v-on:submit="checkForm">
                        <div class="reservation-details">
                            <div class="detail-card">
                                <div class="detail-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="detail-info">
                                    <span class="detail-label">Pickup Date</span>
                                    <span class="detail-value"><?php echo $_SESSION['pickup_date']; ?></span>
                                </div>
                            </div>
                            <div class="detail-card">
                                <div class="detail-icon">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                                <div class="detail-info">
                                    <span class="detail-label">Return Date</span>
                                    <span class="detail-value"><?php echo $_SESSION['return_date']; ?></span>
                                </div>
                            </div>
                            <div class="detail-card">
                                <div class="detail-icon">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <div class="detail-info">
                                    <span class="detail-label">Pickup Location</span>
                                    <span class="detail-value"><?php echo $_SESSION['pickup_location']; ?></span>
                                </div>
                            </div>
                            <div class="detail-card">
                                <div class="detail-icon">
                                    <i class="fas fa-map-marked-alt"></i>
                                </div>
                                <div class="detail-info">
                                    <span class="detail-label">Return Location</span>
                                    <span class="detail-value"><?php echo $_SESSION['return_location']; ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="reservation-content">
                            <div class="cars-selection">
                                <h2 class="section-title">Available Cars</h2>
                                <div class="selection-error" v-if="selected_car === null">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <span>Please select a car to continue</span>
                                </div>
                                <div class="cars-grid">
                                    <?php
                                        foreach($available_cars as $car)
                                        {
                                            echo "<div class='car-card'>";
                                                echo "<div class='car-image-container'>";
                                                    // Display the actual car image uploaded through dashboard
                                                    if(!empty($car['image'])) {
                                                        // FIXED: Use the correct path to the uploaded images - add admin/ prefix
                                                        echo "<img src='admin/uploads/cars/".$car['image']."' alt='".$car['car_name']."' class='car-image'>";
                                                    } else {
                                                        // Fallback to brand image if car image is not available
                                                        echo "<img src='Includes/images/".$car['brand_image']."' alt='".$car['brand_name']."' class='car-image'>";
                                                    }
                                                    echo "<div class='car-badge'>".$car['type_label']."</div>";
                                                echo "</div>";
                                                echo "<div class='car-info'>";
                                                    echo "<h3 class='car-name'>".$car['car_name']."</h3>";
                                                    echo "<div class='car-brand'>".$car['brand_name']."</div>";
                                                    echo "<div class='car-specs'>";
                                                        echo "<div class='spec-item'>";
                                                            echo "<i class='fas fa-palette'></i>";
                                                            echo "<span>".$car['color']."</span>";
                                                        echo "</div>";
                                                        echo "<div class='spec-item'>";
                                                            echo "<i class='fas fa-car'></i>";
                                                            echo "<span>".$car['model']."</span>";
                                                        echo "</div>";
                                                    echo "</div>";
                                                    echo "<p class='car-description'>".$car['description']."</p>";
                                                    echo "<div class='car-footer'>";
                                                        echo "<div class='car-price'>";
                                                            echo "<span class='price-label'>$".number_format($car['price'], 2)."</span>";
                                                            echo "<span class='price-unit'>/day</span>";
                                                        echo "</div>";
                                                        echo "<div class='car-selection'>";
                                                    ?>
                                                            <label class="car-select-btn">
                                                                <input type="radio" class="radio_car_select" name="selected_car" v-model='selected_car' value="<?php echo $car['id'] ?>" v-on:change="calculateTotalPrice(<?php echo $car['price']; ?>)">
                                                                <span class="select-text">Select</span>
                                                            </label>
                                                    <?php
                                                        echo "</div>";
                                                    echo "</div>";
                                                echo "</div>";
                                            echo "</div>";
                                        }
                                    ?>
                                </div>
                            </div>
                            
                            <div class="customer-details">
                                <h2 class="section-title">Your Information</h2>
                                <div class="price-summary" v-if="total_price > 0">
                                    <div class="price-card">
                                        <div class="price-title">Total Price</div>
                                        <div class="price-value">${{ total_price.toFixed(2) }}</div>
                                        <div class="price-details">
                                            <div class="price-detail">
                                                <span>Daily Rate:</span>
                                                <span>${{ daily_rate.toFixed(2) }}</span>
                                            </div>
                                            <div class="price-detail">
                                                <span>Days:</span>
                                                <span>{{ days }}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-container">
                                    <div class="form-group">
                                        <label for="full_name" class="form-label">Full Name</label>
                                        <div class="input-container">
                                            <i class="fas fa-user input-icon"></i>
                                            <input type="text" class="form-control" placeholder="John Doe" name="full_name" v-model='full_name'>
                                        </div>
                                        <div class="error-message" v-if="full_name === null">
                                            <i class="fas fa-exclamation-circle"></i>
                                            <span>Full name is required</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="client_email" class="form-label">Email Address</label>
                                        <div class="input-container">
                                            <i class="fas fa-envelope input-icon"></i>
                                            <input type="email" class="form-control" name="client_email" placeholder="abc@mail.xyz" v-model='client_email'>
                                        </div>
                                        <div class="error-message" v-if="client_email === null">
                                            <i class="fas fa-exclamation-circle"></i>
                                            <span>Email is required</span>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="client_phonenumber" class="form-label">Phone Number</label>
                                        <div class="input-container">
                                            <i class="fas fa-phone input-icon"></i>
                                            <input type="text" name="client_phonenumber" placeholder="0123456789" class="form-control" v-model='client_phonenumber'>
                                        </div>
                                        <div class="error-message" v-if="client_phonenumber === null">
                                            <i class="fas fa-exclamation-circle"></i>
                                            <span>Phone number is required</span>
                                        </div>
                                    </div>
                                    <button type="submit" class="submit-btn" name="submit_reservation">
                                        <span>Complete Reservation</span>
                                        <i class="fas fa-arrow-right"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                <?php
            }
            else
            {
                ?>
                    <div class="no-selection-container">
                        <div class="no-selection-content">
                            <div class="no-selection-icon">
                                <i class="fas fa-calendar-times"></i>
                            </div>
                            <h3>No Reservation Details Found</h3>
                            <p>Please select pickup and return dates first to view available cars.</p>
                            <a href="./#reserve" class="home-btn">
                                <i class="fas fa-home"></i>
                                <span>Go to Homepage</span>
                            </a>
                        </div>
                    </div>
                <?php
            }
        ?>
    </div>
</section>

<!-- FOOTER BOTTOM -->
<?php include "Includes/templates/footer.php"; ?>

<style>
/* General Styles */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    color: #ffffff;
    line-height: 1.6;
    background-color: #f8f9fa;
}

/* NAVBAR STYLING - WHITE BACKGROUND */
.navbar {
    background-color: #ffffff !important;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.navbar .navbar-brand {
    color: #333333 !important;
}

.navbar .navbar-nav .nav-link {
    color: #333333 !important;
}

.navbar .navbar-nav .nav-link:hover {
    color: #0033a0 !important;
}

.navbar .navbar-nav .nav-item.active .nav-link {
    color: #0033a0 !important;
}

.navbar .dropdown-menu {
    background-color: #ffffff;
    border: 1px solid #e0e0e0;
}

.navbar .dropdown-item {
    color: #333333;
}

.navbar .dropdown-item:hover {
    background-color: #f5f5f5;
    color: #0033a0;
}

/* Banner Section */
.reserve-banner-section {
    background: 
                url('https://phnompenh.com/wp-content/uploads/2020/01/phnom-penh-unsplash-800x500.jpg');
    background-size: cover;
    background-position: center;
    color: white;
    padding: 80px 0;
    text-align: center;
    position: relative;
    overflow: hidden;
    min-height: 400px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.reserve-banner-section::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="none"/><path d="M0,0 L100,100 M100,0 L0,100" stroke="rgba(255,255,255,0.1)" stroke-width="0.5"/></svg>');
    opacity: 0.3;
    z-index: 1;
}

.banner-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, );
    z-index: 2;
}

.banner-content {
    position: relative;
    z-index: 3;
    max-width: 800px;
    margin: 0 auto;
    padding: 0 20px;
}

.banner-content h1 {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 15px;
    letter-spacing: -0.5px;
   color: white;
}

.banner-content p {
    font-size: 1.2rem;
    opacity: 0.9;
    max-width: 600px;
    margin: 0 auto;
  
}

/* Reservation Section */
.car_reservation_section {
    padding: 60px 0;
}

.container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 15px;
}

/* Reservation Details Cards */
.reservation-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 40px;
}

.detail-card {
    background: white;
    border-radius: 12px;
    padding: 20px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    display: flex;
    align-items: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.detail-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 24px rgba(0,0,0,0.1);
}

.detail-icon {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    background-color: #e8f0fe;
    display: flex;
    align-items: center;
    justify-content: center;
    margin-right: 15px;
    color: #1a73e8;
    font-size: 1.2rem;
}

.detail-info {
    display: flex;
    flex-direction: column;
}

.detail-label {
    font-size: 0.85rem;
    color: #5f6368;
    margin-bottom: 4px;
}

.detail-value {
    font-size: 1rem;
    font-weight: 600;
    color: #202124;
}

/* Reservation Content */
.reservation-content {
    display: grid;
    grid-template-columns: 1fr 380px;
    gap: 30px;
}

/* Section Title */
.section-title {
    font-size: 1.5rem;
    color: #202124;
    margin-bottom: 20px;
    padding-bottom: 10px;
    border-bottom: 2px solid #e8eaed;
}

/* Selection Error */
.selection-error {
    background-color: #fef7e0;
    border-left: 4px solid #fbbc04;
    padding: 12px 16px;
    border-radius: 4px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    color: #b06000;
}

.selection-error i {
    margin-right: 10px;
}

/* Cars Grid */
.cars-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
    gap: 25px;
}

/* Car Card */
.car-card {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    position: relative;
}

.car-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 12px 24px rgba(0,0,0,0.1);
}

.car-image-container {
    position: relative;
    height: 180px;
    overflow: hidden;
}

.car-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform 0.5s ease;
}

.car-card:hover .car-image {
    transform: scale(1.05);
}

.car-badge {
    position: absolute;
    top: 15px;
    right: 15px;
    background-color: rgba(26, 115, 232, 0.9);
    color: white;
    padding: 5px 10px;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
}

.car-info {
    padding: 20px;
}

.car-name {
    font-size: 1.2rem;
    font-weight: 600;
    color: #202124;
    margin-bottom: 5px;
}

.car-brand {
    color: #5f6368;
    font-size: 0.9rem;
    margin-bottom: 15px;
}

.car-specs {
    display: flex;
    gap: 15px;
    margin-bottom: 15px;
}

.spec-item {
    display: flex;
    align-items: center;
    color: #5f6368;
    font-size: 0.85rem;
}

.spec-item i {
    margin-right: 5px;
    color: #1a73e8;
}

.car-description {
    color: #5f6368;
    font-size: 0.9rem;
    margin-bottom: 15px;
    display: -webkit-box;
    -webkit-line-clamp: 2;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.car-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.car-price {
    display: flex;
    align-items: baseline;
}

.price-label {
    font-size: 1.2rem;
    font-weight: 700;
    color: #1a73e8;
}

.price-unit {
    font-size: 0.8rem;
    color: #5f6368;
    margin-left: 4px;
}

/* Car Selection Button */
.car-select-btn {
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 8px 16px;
    background-color: #e8f0fe;
    color: #1a73e8;
    border-radius: 20px;
    cursor: pointer;
    transition: all 0.2s ease;
    font-weight: 500;
    font-size: 0.9rem;
}

.car-select-btn:hover {
    background-color: #d2e3fc;
}

.radio_car_select {
    display: none;
}

.radio_car_select:checked + .car-select-btn {
    background-color: #1a73e8;
    color: white;
}

/* Customer Details */
.customer-details {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    height: fit-content;
    position: sticky;
    top: 20px;
}

.form-container {
    margin-top: 20px;
}

.form-group {
    margin-bottom: 20px;
}

.form-label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
    color: #202124;
}

.input-container {
    position: relative;
}

.input-icon {
    position: absolute;
    left: 15px;
    top: 50%;
    transform: translateY(-50%);
    color: #5f6368;
}

.form-control {
    width: 100%;
    padding: 12px 15px 12px 45px;
    border: 1px solid #dadce0;
    border-radius: 8px;
    font-size: 1rem;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}

.form-control:focus {
    outline: none;
    border-color: #1a73e8;
    box-shadow: 0 0 0 3px rgba(26, 115, 232, 0.1);
}

.error-message {
    color: #d93025;
    font-size: 0.85rem;
    margin-top: 5px;
    display: flex;
    align-items: center;
}

.error-message i {
    margin-right: 5px;
}

.submit-btn {
    width: 100%;
    padding: 14px 20px;
    background: linear-gradient(135deg, #1a73e8, #0d47a1);
    color: white;
    border: none;
    border-radius: 8px;
    font-size: 1rem;
    font-weight: 600;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    margin-top: 10px;
}

.submit-btn:hover {
    background: linear-gradient(135deg, #0d47a1, #1a73e8);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(26, 115, 232, 0.3);
}

.submit-btn i {
    margin-left: 8px;
}

/* No Selection Container */
.no-selection-container {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 400px;
}

.no-selection-content {
    text-align: center;
    max-width: 500px;
    padding: 40px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.no-selection-icon {
    font-size: 3rem;
    color: #fbbc04;
    margin-bottom: 20px;
}

.no-selection-content h3 {
    font-size: 1.5rem;
    margin-bottom: 15px;
    color: #202124;
}

.no-selection-content p {
    color: #5f6368;
    margin-bottom: 25px;
}

.home-btn {
    display: inline-flex;
    align-items: center;
    padding: 12px 20px;
    background-color: #1a73e8;
    color: white;
    border-radius: 8px;
    text-decoration: none;
    font-weight: 500;
    transition: all 0.3s ease;
}

.home-btn:hover {
    background-color: #0d47a1;
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(26, 115, 232, 0.3);
}

.home-btn i {
    margin-right: 8px;
}

/* Success/Error Notifications */
.success-notification, .error-notification {
    display: flex;
    align-items: center;
    padding: 20px;
    border-radius: 12px;
    margin-bottom: 30px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
}

.success-notification {
    background-color: #e6f4ea;
    border-left: 4px solid #34a853;
}

.error-notification {
    background-color: #fce8e6;
    border-left: 4px solid #ea4335;
}

.success-icon, .error-icon {
    font-size: 1.5rem;
    margin-right: 15px;
}

.success-icon {
    color: #34a853;
}

.error-icon {
    color: #ea4335;
}

.success-message h3, .error-message h3 {
    margin-bottom: 5px;
    color: #202124;
}

.success-message p, .error-message p {
    color: #5f6368;
    margin: 0;
}

/* Price Summary */
.price-summary {
    margin-bottom: 25px;
}

.price-card {
    background: linear-gradient(135deg, #1a73e8, #0d47a1);
    border-radius: 12px;
    padding: 20px;
    color: white;
    box-shadow: 0 4px 12px rgba(26, 115, 232, 0.3);
    animation: fadeIn 0.5s ease-in-out;
}

@keyframes fadeIn {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.price-title {
    font-size: 1rem;
    opacity: 0.9;
    margin-bottom: 5px;
}

.price-value {
    font-size: 2rem;
    font-weight: 700;
    margin-bottom: 15px;
}

.price-details {
    border-top: 1px solid rgba(255, 255, 255, 0.2);
    padding-top: 15px;
}

.price-detail {
    display: flex;
    justify-content: space-between;
    margin-bottom: 8px;
    font-size: 0.9rem;
}

/* Responsive Design */
@media (max-width: 992px) {
    .reservation-content {
        grid-template-columns: 1fr;
    }
    
    .customer-details {
        position: static;
    }
}

@media (max-width: 768px) {
    .banner-content h1 {
        font-size: 2rem;
    }
    
    .banner-content p {
        font-size: 1rem;
    }
    
    .cars-grid {
        grid-template-columns: repeat(auto-fill, minmax(240px, 1fr));
    }
    
    .reservation-details {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 480px) {
    .cars-grid {
        grid-template-columns: 1fr;
    }
    
    .car-card {
        max-width: 100%;
    }
}
</style>

<script src="https://cdn.jsdelivr.net/npm/vue@2.6.14/dist/vue.js"></script>
<script>
new Vue({
    el: "#reservation_second_form",
    data: {
        selected_car : '',
        full_name: '',
        client_email: '',
        client_phonenumber: '',
        total_price: 0,
        daily_rate: 0,
        days: <?php echo $days; ?>
    },
    methods:{
        checkForm: function(event){
            if( this.full_name && this.client_email && this.client_phonenumber && this.selected_car)
            {
                return true;
            }
            
            if (!this.full_name)
            {
                this.full_name = null;
            }

            if (!this.client_email)
            {
                this.client_email = null;
            }

            if (!this.client_phonenumber)
            {
                this.client_phonenumber = null;
            }

            if (!this.selected_car)
            {
                this.selected_car = null;
            }
            
            event.preventDefault();
        },
        calculateTotalPrice: function(price) {
            this.daily_rate = price;
            this.total_price = price * this.days;
            console.log('Total price calculated: $' + this.total_price.toFixed(2));
        }
    },
    mounted: function() {
        // Make sure Vue is properly initialized
        console.log('Vue app mounted. Days: ' + this.days);
    }
})
</script>