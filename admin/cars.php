<?php
session_start();

//Page Title
 $pageTitle = 'Cars';

//Includes
include 'connect.php';
include 'Includes/functions/functions.php';
include 'Includes/templates/header.php';

//Check If user is already logged in
if (isset($_SESSION['username_yahya_car_rental']) && isset($_SESSION['password_yahya_car_rental'])) {
?>
    <!-- Begin Page Content -->
    <div class="container-fluid">


        <!-- ADD NEW CAR SUBMITTED -->
        <?php
        if (isset($_POST['add_car_sbmt']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $car_name = test_input($_POST['car_name']);
            $car_brand = test_input($_POST['car_brand']);
            $car_type = test_input($_POST['car_type']);
            $car_color = test_input($_POST['car_color']);
            $car_model = test_input($_POST['car_model']);
            $car_description = test_input($_POST['car_description']);
            $car_price = test_input($_POST['car_price']);

            // Handle image upload
            $car_image = '';
            // Check if an image file was uploaded and there are no errors
            if (isset($_FILES['car_image']) && $_FILES['car_image']['error'] == 0) {
                // Define allowed file types
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['car_image']['name'];
                $filetype = pathinfo($filename, PATHINFO_EXTENSION);

                // Check if the file type is allowed
                if (in_array(strtolower($filetype), $allowed)) {
                    // Create a unique filename to avoid conflicts
                    $newname = 'car_' . time() . '.' . $filetype;
                    $upload_path = 'uploads/cars/' . $newname;

                    // Create the upload directory if it doesn't exist
                    if (!is_dir('uploads/cars')) {
                        mkdir('uploads/cars', 0777, true);
                    }

                    // Move the uploaded file from temporary location to final destination
                    if (move_uploaded_file($_FILES['car_image']['tmp_name'], $upload_path)) {
                        // Store the filename in the variable to be saved to database
                        $car_image = $newname;
                    }
                }
            }

            // Save car data including image filename to database
            try {
                $stmt = $con->prepare("insert into cars(car_name, brand_id, type_id, color, model, description, price, image) values(?,?,?,?,?,?,?,?) ");
                $stmt->execute(array($car_name, $car_brand, $car_type, $car_color, $car_model, $car_description, $car_price, $car_image));
                echo "<div class = 'alert alert-success'>";
                echo 'New Car has been inserted successfully';
                echo "</div>";
            } catch (Exception $e) {
                echo "<div class = 'alert alert-danger'>";
                echo 'Error occurred: ' . $e->getMessage();
                echo "</div>";
            }
        }

        // Handle car editing
        if (isset($_POST['edit_car_sbmt']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $car_id = test_input($_POST['car_id']);
            $car_name = test_input($_POST['car_name']);
            $car_brand = test_input($_POST['car_brand']);
            $car_type = test_input($_POST['car_type']);
            $car_color = test_input($_POST['car_color']);
            $car_model = test_input($_POST['car_model']);
            $car_description = test_input($_POST['car_description']);
            $car_price = test_input($_POST['car_price']);

            // Get current image
            $stmt = $con->prepare("SELECT image FROM cars WHERE id = ?");
            $stmt->execute(array($car_id));
            $current_car = $stmt->fetch();
            $car_image = $current_car['image'];

            // Handle image upload if new image is provided
            if (isset($_FILES['car_image']) && $_FILES['car_image']['error'] == 0) {
                $allowed = ['jpg', 'jpeg', 'png', 'gif'];
                $filename = $_FILES['car_image']['name'];
                $filetype = pathinfo($filename, PATHINFO_EXTENSION);

                if (in_array(strtolower($filetype), $allowed)) {
                    // Create unique filename
                    $newname = 'car_' . time() . '.' . $filetype;
                    $upload_path = 'uploads/cars/' . $newname;

                    // Create directory if it doesn't exist
                    if (!is_dir('uploads/cars')) {
                        mkdir('uploads/cars', 0777, true);
                    }

                    // Move the uploaded file
                    if (move_uploaded_file($_FILES['car_image']['tmp_name'], $upload_path)) {
                        // Delete old image if exists
                        if (!empty($car_image) && file_exists('uploads/cars/' . $car_image)) {
                            unlink('uploads/cars/' . $car_image);
                        }
                        $car_image = $newname;
                    }
                }
            }

            try {
                $stmt = $con->prepare("UPDATE cars SET car_name=?, brand_id=?, type_id=?, color=?, model=?, description=?, price=?, image=? WHERE id=?");
                $stmt->execute(array($car_name, $car_brand, $car_type, $car_color, $car_model, $car_description, $car_price, $car_image, $car_id));
                echo "<div class = 'alert alert-success'>";
                echo 'Car has been updated successfully';
                echo "</div>";
            } catch (Exception $e) {
                echo "<div class = 'alert alert-danger'>";
                echo 'Error occurred: ' . $e->getMessage();
                echo "</div>";
            }
        }

        // Handle car deletion
        if (isset($_POST['delete_car_sbmt']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $car_id = $_POST['car_id'];

            // Get image to delete
            $stmt = $con->prepare("SELECT image FROM cars WHERE id = ?");
            $stmt->execute(array($car_id));
            $car = $stmt->fetch();

            try {
                $stmt = $con->prepare("DELETE FROM cars WHERE id = ?");
                $stmt->execute(array($car_id));

                // Delete image file if exists
                if (!empty($car['image']) && file_exists('uploads/cars/' . $car['image'])) {
                    unlink('uploads/cars/' . $car['image']);
                }

                echo "<div class = 'alert alert-success'>";
                echo 'Car has been deleted successfully';
                echo "</div>";
            } catch (Exception $e) {
                echo "<div class = 'alert alert-danger'>";
                echo 'Error occurred: ' . $e->getMessage();
                echo "</div>";
            }
        }

        if (isset($_POST['delete_type_sbmt']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $type_id = $_POST['type_id'];
            try {
                $stmt = $con->prepare("DELETE FROM car_types where type_id = ?");
                $stmt->execute(array($type_id));
                echo "<div class = 'alert alert-success'>";
                echo 'Car Type has been deleted successfully';
                echo "</div>";
            } catch (Exception $e) {
                echo "<div class = 'alert alert-danger'>";
                echo 'Error occurred: ' . $e->getMessage();
                echo "</div>";
            }
        }
        ?>

        <!-- Cars Table -->
        <?php
        $stmt = $con->prepare("SELECT * FROM cars");
        $stmt->execute();
        $rows_cars = $stmt->fetchAll();

        $stmt = $con->prepare("SELECT * FROM car_brands");
        $stmt->execute();
        $rows_brands = $stmt->fetchAll();

        // Define the car types we want to ensure exist
        $desired_types = [
            'Sedan',
            'SUV',
            'Hatchback',
            'Coupe',
            'Convertible',
            'Pickup',
            'Van / Minivan',
            'Wagon',
            'Sports Car',
            'Crossover',
            'Luxury',
            'Electric'
        ];

        // Check if car_types table exists, create if not
        $stmt = $con->prepare("SHOW TABLES LIKE 'car_types'");
        $stmt->execute();
        if ($stmt->rowCount() == 0) {
            // Create the table
            $con->exec("CREATE TABLE car_types (
                type_id INT AUTO_INCREMENT PRIMARY KEY,
                type_label VARCHAR(50) NOT NULL
            )");
        }

        // Get existing types from the database
        $stmt = $con->prepare("SELECT * FROM car_types");
        $stmt->execute();
        $rows_types = $stmt->fetchAll();

        // Check which types are missing
        $existing_type_labels = array_column($rows_types, 'type_label');
        $missing_types = array_diff($desired_types, $existing_type_labels);

        // Insert missing types into the database
        if (!empty($missing_types)) {
            foreach ($missing_types as $type_label) {
                $stmt = $con->prepare("INSERT INTO car_types (type_label) VALUES (?)");
                $stmt->execute([$type_label]);
            }
            
            // Re-fetch the types to get the updated list
            $stmt = $con->prepare("SELECT * FROM car_types");
            $stmt->execute();
            $rows_types = $stmt->fetchAll();
        }
        ?>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Cars</h6>
            </div>
            <div class="card-body">

                <!-- ADD NEW CAR BUTTON -->
                <button class="btn btn-success btn-sm" style="margin-bottom: 10px;" type="button" data-toggle="modal" data-target="#add_new_car" data-placement="top">
                    <i class="fa fa-plus"></i> Add New Car
                </button>
                <!-- Add New Car Modal -->
                <div class="modal fade" id="add_new_car" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add New Car</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <form action="cars.php" method="POST" v-on:submit="checkForm" enctype="multipart/form-data">
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="car_name">Car Name</label>
                                        <input type="text" class="form-control" placeholder="Car Name" name="car_name" v-model="car_name">
                                        <div class="invalid-feedback" style="display:block" v-if="car_name === null">Car name is required</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="car_brand">Car Brand</label>
                                        <select name="car_brand" class="custom-select">
                                            <?php foreach ($rows_brands as $brand) {
                                                echo "<option value = '" . $brand['brand_id'] . "'>" . $brand['brand_name'] . "</option>";
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="car_type">Car Type</label>
                                        <select name="car_type" class="custom-select">
                                            <?php foreach ($rows_types as $type) {
                                                echo "<option value = '" . $type['type_id'] . "'>" . $type['type_label'] . "</option>";
                                            } ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="car_color">Car Color</label>
                                        <input type="text" class="form-control" placeholder="Car Color" name="car_color" v-model="car_color">
                                        <div class="invalid-feedback" style="display:block" v-if="car_color === null">Car color is required</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="car_model">Car Model</label>
                                        <input type="text" class="form-control" placeholder="Car Model" name="car_model" v-model="car_model">
                                        <div class="invalid-feedback" style="display:block" v-if="car_model === null">Car model is required</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="car_price">Price per Day</label>
                                        <input type="number" class="form-control" placeholder="Price per Day" name="car_price" v-model="car_price" step="0.01" min="0">
                                        <div class="invalid-feedback" style="display:block" v-if="car_price === null">Car price is required</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="car_image">Car Image</label>
                                        <input type="file" class="form-control" name="car_image" accept="image/*">
                                        <small class="form-text text-muted">Allowed formats: JPG, JPEG, PNG, GIF</small>
                                    </div>
                                    <div class="form-group">
                                        <label for="car_description">Car Description</label>
                                        <textarea class="form-control" name="car_description" v-model="car_description"></textarea>
                                        <div class="invalid-feedback" style="display:block" v-if="car_description === null">Car description is required</div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    <button type="submit" class="btn btn-info" name="add_car_sbmt">Add Car</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Cars Table -->
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>Image</th>
                                <th>Car ID</th>
                                <th>Car Name</th>
                                <th>Brand</th>
                                <th>Car Type</th>
                                <th>Color</th>
                                <th>Model</th>
                                <th>Price/Day</th>
                                <th style="width:30%">Description</th>
                                <th>Manage</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($rows_cars as $car) {
                                echo "<tr>";
                                echo "<td>";
                                if (!empty($car['image'])) {
                                    echo "<img src='uploads/cars/" . $car['image'] . "' alt='" . $car['car_name'] . "' style='width: 80px; height: 60px; object-fit: cover;'>";
                                } else {
                                    echo "<img src='https://via.placeholder.com/80x60?text=No+Image' alt='No Image' style='width: 80px; height: 60px; object-fit: cover;'>";
                                }
                                echo "</td>";
                                echo "<td>" . $car['id'] . "</td>";
                                echo "<td>" . $car['car_name'] . "</td>";
                                echo "<td>";
                                foreach ($rows_brands as $brand) {
                                    if ($brand['brand_id'] == $car['brand_id']) {
                                        echo $brand['brand_name'];
                                        break;
                                    }
                                }
                                echo "</td>";
                                echo "<td>";
                                foreach ($rows_types as $type) {
                                    if ($type['type_id'] == $car['type_id']) {
                                        echo $type['type_label'];
                                        break;
                                    }
                                }
                                echo "</td>";
                                echo "<td>" . $car['color'] . "</td>";
                                echo "<td>" . $car['model'] . "</td>";
                                echo "<td>$" . number_format($car['price'], 2) . "</td>";
                                echo "<td>" . $car['description'] . "</td>";
                                echo "<td>";
                                $delete_data = "delete_" . $car["id"];
                                $edit_data = "edit_" . $car["id"];
                            ?>
                                <ul>
                                    <li class="list-inline-item" data-toggle="tooltip" title="Edit">
                                        <button class="btn btn-success btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $edit_data; ?>"><i class="fa fa-edit"></i></button>

                                        <!-- Edit Modal -->
                                        <div class="modal fade" id="<?php echo $edit_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $edit_data; ?>" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Edit Car</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                            <span aria-hidden="true">&times;</span>
                                                        </button>
                                                    </div>
                                                    <form action="cars.php" method="POST" enctype="multipart/form-data">
                                                        <div class="modal-body">
                                                            <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">

                                                            <div class="form-group">
                                                                <label for="car_name">Car Name</label>
                                                                <input type="text" class="form-control" name="car_name" value="<?php echo $car['car_name']; ?>" required>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="car_brand">Car Brand</label>
                                                                <select name="car_brand" class="custom-select">
                                                                    <?php
                                                                    foreach ($rows_brands as $brand) {
                                                                        $selected = ($brand['brand_id'] == $car['brand_id']) ? 'selected' : '';
                                                                        echo "<option value='" . $brand['brand_id'] . "' $selected>" . $brand['brand_name'] . "</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="car_type">Car Type</label>
                                                                <select name="car_type" class="custom-select">
                                                                    <?php
                                                                    foreach ($rows_types as $type) {
                                                                        $selected = ($type['type_id'] == $car['type_id']) ? 'selected' : '';
                                                                        echo "<option value='" . $type['type_id'] . "' $selected>" . $type['type_label'] . "</option>";
                                                                    }
                                                                    ?>
                                                                </select>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="car_color">Car Color</label>
                                                                <input type="text" class="form-control" name="car_color" value="<?php echo $car['color']; ?>" required>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="car_model">Car Model</label>
                                                                <input type="text" class="form-control" name="car_model" value="<?php echo $car['model']; ?>" required>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="car_price">Price per Day</label>
                                                                <input type="number" class="form-control" name="car_price" value="<?php echo $car['price']; ?>" step="0.01" min="0" required>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="car_image">Car Image</label>
                                                                <input type="file" class="form-control" name="car_image" accept="image/*">
                                                                <small class="form-text text-muted">Leave empty to keep current image. Allowed formats: JPG, JPEG, PNG, GIF</small>
                                                                <?php if (!empty($car['image'])): ?>
                                                                    <div class="mt-2">
                                                                        <img src="uploads/cars/<?php echo $car['image']; ?>" alt="Current Image" style="max-width: 200px; max-height: 150px;">
                                                                    </div>
                                                                <?php endif; ?>
                                                            </div>

                                                            <div class="form-group">
                                                                <label for="car_description">Car Description</label>
                                                                <textarea class="form-control" name="car_description"><?php echo $car['description']; ?></textarea>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                            <button type="submit" name="edit_car_sbmt" class="btn btn-primary">Save Changes</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="list-inline-item" data-toggle="tooltip" title="Delete">
                                        <button class="btn btn-danger btn-sm rounded-0" type="button" data-toggle="modal" data-target="#<?php echo $delete_data; ?>" data-placement="top"><i class="fa fa-trash"></i></button>
                                        <div class="modal fade" id="<?php echo $delete_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $delete_data; ?>" aria-hidden="true">
                                            <div class="modal-dialog" role="document">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title">Delete Car</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                    </div>
                                                    <div class="modal-body">Are you sure you want to delete this Car?</div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                        <form action="cars.php" method="POST" style="display: inline;">
                                                            <input type="hidden" name="car_id" value="<?php echo $car['id']; ?>">
                                                            <button type="submit" name="delete_car_sbmt" class="btn btn-danger">Delete</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            <?php
                                echo "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php
    include 'Includes/templates/footer.php';
} else {
    header('Location: index.php');
    exit();
}
?>

<script>
    new Vue({
        el: "#add_new_car",
        data: {
            car_name: '',
            car_color: '',
            car_model: '',
            car_description: '',
            car_price: ''
        },
        methods: {
            checkForm: function(event) {
                if (this.car_name && this.car_color && this.car_model && this.car_description && this.car_price) {
                    return true;
                }
                if (!this.car_name) {
                    this.car_name = null;
                }
                if (!this.car_color) {
                    this.car_color = null;
                }
                if (!this.car_model) {
                    this.car_model = null;
                }
                if (!this.car_description) {
                    this.car_description = null;
                }
                if (!this.car_price) {
                    this.car_price = null;
                }
                event.preventDefault();
            },
        }
    })
</script>