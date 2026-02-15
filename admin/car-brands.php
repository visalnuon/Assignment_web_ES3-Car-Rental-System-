<?php
    session_start();

    //Page Title
    $pageTitle = 'Car Brands';

    //Includes
    include 'connect.php';
    include 'Includes/functions/functions.php'; 
    include 'Includes/templates/header.php';

    //Check If user is already logged in
    if(isset($_SESSION['username_yahya_car_rental']) && isset($_SESSION['password_yahya_car_rental']))
    {
        // Initialize variables
        $total_brands = 0;
        $top_brands = [];
        
        // Process form submissions first
        if (isset($_POST['add_brand_sbmt']) && $_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $brand_name = test_input($_POST['brand_name']);
            $brand_image = rand(0,100000).'_'.$_FILES['brand_image']['name'];
            move_uploaded_file($_FILES['brand_image']['tmp_name'],"Uploads/images//".$brand_image);
            try
            {
                $stmt = $con->prepare("insert into car_brands(brand_name,brand_image) values(?,?) ");
                $stmt->execute(array($brand_name,$brand_image));
                echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>";
                    echo '<i class="fas fa-check-circle mr-2"></i>New Car Brand has been inserted successfully!';
                    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>';
                echo "</div>";
            }
            catch(Exception $e)
            {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
                    echo '<i class="fas fa-exclamation-circle mr-2"></i>Error occurred: ' .$e->getMessage();
                    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>';
                echo "</div>";
            }
        }
        
        if (isset($_POST['edit_brand_sbmt']) && $_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $brand_id = $_POST['brand_id'];
            $brand_name = test_input($_POST['brand_name']);
            
            // Check if a new image was uploaded
            if (!empty($_FILES['brand_image']['name'])) {
                $brand_image = rand(0,100000).'_'.$_FILES['brand_image']['name'];
                move_uploaded_file($_FILES['brand_image']['tmp_name'],"Uploads/images//".$brand_image);
                
                // Update brand with new image
                try {
                    $stmt = $con->prepare("UPDATE car_brands SET brand_name = ?, brand_image = ? WHERE brand_id = ?");
                    $stmt->execute(array($brand_name, $brand_image, $brand_id));
                    
                    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>";
                        echo '<i class="fas fa-check-circle mr-2"></i>Car Brand has been updated successfully!';
                        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>';
                    echo "</div>";
                } catch(Exception $e) {
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
                        echo '<i class="fas fa-exclamation-circle mr-2"></i>Error occurred: ' .$e->getMessage();
                        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>';
                    echo "</div>";
                }
            } else {
                // Update brand without changing image
                try {
                    $stmt = $con->prepare("UPDATE car_brands SET brand_name = ? WHERE brand_id = ?");
                    $stmt->execute(array($brand_name, $brand_id));
                    
                    echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>";
                        echo '<i class="fas fa-check-circle mr-2"></i>Car Brand has been updated successfully!';
                        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>';
                    echo "</div>";
                } catch(Exception $e) {
                    echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
                        echo '<i class="fas fa-exclamation-circle mr-2"></i>Error occurred: ' .$e->getMessage();
                        echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>';
                    echo "</div>";
                }
            }
        }
        
        if (isset($_POST['delete_brand_sbmt']) && $_SERVER['REQUEST_METHOD'] === 'POST')
        {
            $brand_id = $_POST['brand_id'];
            try
            {
                $stmt = $con->prepare("DELETE FROM car_brands where brand_id = ?");
                $stmt->execute(array($brand_id));
                echo "<div class='alert alert-success alert-dismissible fade show' role='alert'>";
                    echo '<i class="fas fa-check-circle mr-2"></i>Car Brand has been deleted successfully!';
                    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>';
                echo "</div>";
            }
            catch(Exception $e)
            {
                echo "<div class='alert alert-danger alert-dismissible fade show' role='alert'>";
                    echo '<i class="fas fa-exclamation-circle mr-2"></i>Error occurred: ' .$e->getMessage();
                    echo '<button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                          </button>';
                echo "</div>";
            }
        }
        
        // Now get the updated data after any form processing
        // Get brand statistics
        $stmt = $con->prepare("SELECT COUNT(*) as total_brands FROM car_brands");
        $stmt->execute();
        $total_brands = $stmt->fetch()['total_brands'];
        
        // Get cars per brand
        $stmt = $con->prepare("SELECT cb.brand_id, cb.brand_name, COUNT(c.id) as car_count 
                              FROM car_brands cb 
                              LEFT JOIN cars c ON cb.brand_id = c.brand_id 
                              GROUP BY cb.brand_id 
                              ORDER BY car_count DESC 
                              LIMIT 5");
        $stmt->execute();
        $top_brands = $stmt->fetchAll();
?>
        <!-- Begin Page Content -->
        <div class="container-fluid">
    
            <!-- Page Heading -->
            <div class="d-sm-flex align-items-center justify-content-between mb-4">
                <div>
                    <h1 class="h3 mb-0 text-gray-800">Car Brands Management</h1>
                    <p class="text-muted">Manage your car rental fleet brands</p>
                </div>
                <div>
                   
                   
                   
                   
                    <button class="btn btn-primary shadow-sm" data-toggle="modal" data-target="#add_new_brand">
                        <i class="fas fa-plus fa-sm text-white-50"></i>
                        Add New Brand
                    </button>
                </div>
            </div>

            <!-- Stats Cards -->
            <div class="row mb-4">
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card stats-card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        Total Brands
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"><?php echo $total_brands; ?></div>
                                    <div class="mt-2 mb-0 text-muted text-xs">
                                        <span class="text-success font-weight-bold">+2</span> new this month
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="icon-circle bg-primary">
                                        <i class="fas fa-tags text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-xl-9 col-md-6 mb-4">
                    <div class="card stats-card border-left-info shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        Top Brands by Fleet Size
                                    </div>
                                    <div class="row mt-3">
                                        <?php 
                                        if (!empty($top_brands)) {
                                            foreach($top_brands as $index => $brand): ?>
                                                <div class="col-6 mb-2">
                                                    <div class="d-flex align-items-center">
                                                        <div class="badge badge-<?php echo $index == 0 ? 'primary' : ($index == 1 ? 'success' : ($index == 2 ? 'info' : 'secondary')); ?> mr-2">
                                                            <?php echo $index + 1; ?>
                                                        </div>
                                                        <div class="flex-grow-1">
                                                            <div class="small text-gray-800"><?php echo $brand['brand_name']; ?></div>
                                                            <div class="progress progress-sm mt-1">
                                                                <div class="progress-bar bg-<?php echo $index == 0 ? 'primary' : ($index == 1 ? 'success' : ($index == 2 ? 'info' : 'secondary')); ?>" 
                                                                     role="progressbar" 
                                                                     style="width: <?php echo min(100, $brand['car_count'] * 10); ?>%"
                                                                     aria-valuenow="<?php echo $brand['car_count']; ?>" 
                                                                     aria-valuemin="0" 
                                                                     aria-valuemax="100">
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="ml-2">
                                                            <span class="text-muted"><?php echo $brand['car_count']; ?></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach;
                                        } else {
                                            echo "<div class='col-12 text-center text-muted py-3'>No brands found</div>";
                                        }
                                        ?>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <div class="icon-circle bg-info">
                                        <i class="fas fa-chart-bar text-white"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Car Brands Table -->
            <?php
                $stmt = $con->prepare("SELECT * FROM car_brands ORDER BY brand_id DESC");
                $stmt->execute();
                $rows_brands = $stmt->fetchAll();
            ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">Car Brands</h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right shadow animated--fade-in">
                            <a class="dropdown-item" href="#" id="viewAll">View All</a>
                            <a class="dropdown-item" href="#" id="exportTable">Export Table</a>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="#" data-toggle="modal" data-target="#add_new_brand">Add New Brand</a>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-3">
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_length">
                                <label>
                                    Show 
                                    <select id="brandTableLength" class="custom-select custom-select-sm form-control form-control-sm">
                                        <option value="10">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select> 
                                    entries
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-6">
                            <div class="dataTables_filter">
                                <label>
                                    Search:
                                    <input type="text" id="brandSearch" class="form-control form-control-sm" placeholder="">
                                </label>
                            </div>
                        </div>
                    </div>

                    <!-- Brands Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered" id="brandsTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Brand ID</th>
                                    <th>Brand Name</th>
                                    <th>Brand Image</th>
                                    <th>Fleet Size</th>
                                    <th>Actions</th>
                                </tr>
                            </thead> 
                            <tbody>
                                <?php
                                foreach($rows_brands as $brand)
                                {
                                    // Get car count for this brand
                                    $stmt = $con->prepare("SELECT COUNT(*) as car_count FROM cars WHERE brand_id = ?");
                                    $stmt->execute(array($brand['brand_id']));
                                    $car_count = $stmt->fetch()['car_count'];
                                    
                                    echo "<tr>";
                                        echo "<td>";
                                            echo $brand['brand_id'];
                                        echo "</td>";
                                        echo "<td>";
                                            echo "<div class='d-flex align-items-center'>";
                                                echo "<div class='brand-logo mr-3'>";
                                                    echo "<img src='Uploads/images/".$brand['brand_image']."' alt='".$brand['brand_name']."' class='img-thumbnail' style='width: 40px; height: 40px; object-fit: cover;'>";
                                                echo "</div>";
                                                echo "<div>";
                                                    echo "<div class='font-weight-bold'>".$brand['brand_name']."</div>";
                                                    echo "<div class='text-muted small'>ID: ".$brand['brand_id']."</div>";
                                                echo "</div>";
                                            echo "</div>";
                                        echo "</td>";
                                        echo "<td>";
                                            echo "<img src='Uploads/images/".$brand['brand_image']."' alt='".$brand['brand_name']."' class='img-fluid img-thumbnail' style='max-height: 60px;'>";
                                        echo "</td>";
                                        echo "<td>";
                                            echo "<span class='badge badge-".($car_count > 10 ? 'primary' : ($car_count > 5 ? 'info' : 'secondary'))."'>".$car_count." cars</span>";
                                        echo "</td>";
                                        echo "<td>";
                                            $delete_data = "delete_".$brand["brand_id"];
                                            $edit_data = "edit_".$brand["brand_id"];
                                            ?>
                                            <div class="btn-group">
                                                <button class="btn btn-sm btn-info" type="button" data-toggle="modal" data-target="#<?php echo $edit_data; ?>" title="Edit">
                                                    <i class="fas fa-edit"></i>
                                                </button>
                                                <button class="btn btn-sm btn-danger" type="button" data-toggle="modal" data-target="#<?php echo $delete_data; ?>" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                            
                                            <!-- Edit Modal -->
                                            <div class="modal fade" id="<?php echo $edit_data; ?>" tabindex="-1" role="dialog" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header bg-info text-white">
                                                            <h5 class="modal-title">Edit Brand</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <form action="car-brands.php" method="POST" enctype="multipart/form-data">
                                                            <div class="modal-body">
                                                                <div class="form-group">
                                                                    <label for="edit_brand_name">Brand name</label>
                                                                    <input type="text" class="form-control" id="edit_brand_name" name="brand_name" value="<?php echo $brand['brand_name']; ?>" required>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="edit_brand_image">Current image</label>
                                                                    <div class="mb-2">
                                                                        <img src="Uploads/images/<?php echo $brand['brand_image']; ?>" alt="<?php echo $brand['brand_name']; ?>" class="img-thumbnail" style="max-height: 150px;">
                                                                    </div>
                                                                    <label for="new_brand_image">Upload new image (optional)</label>
                                                                    <input type="file" class="form-control" id="new_brand_image" name="brand_image">
                                                                </div>
                                                                <input type="hidden" name="brand_id" value="<?php echo $brand['brand_id']; ?>">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" name="edit_brand_sbmt" class="btn btn-info">Save Changes</button>
                                                            </div>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                            
                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="<?php echo $delete_data; ?>" tabindex="-1" role="dialog" aria-labelledby="<?php echo $delete_data; ?>" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <form action="car-brands.php" method="POST">
                                                        <div class="modal-content">
                                                            <div class="modal-header bg-danger text-white">
                                                                <h5 class="modal-title">Delete Brand</h5>
                                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                    <span aria-hidden="true">&times;</span>
                                                                </button>
                                                            </div>
                                                            <div class="modal-body">
                                                                <div class="text-center mb-4">
                                                                    <i class="fas fa-exclamation-triangle fa-4x text-warning mb-3"></i>
                                                                    <h4>Are you sure?</h4>
                                                                    <p>You are about to delete the brand "<strong><?php echo $brand['brand_name']; ?></strong>"</p>
                                                                    <?php if($car_count > 0): ?>
                                                                        <div class="alert alert-warning">
                                                                            <i class="fas fa-info-circle mr-2"></i>
                                                                            This brand has <?php echo $car_count; ?> cars associated with it. Deleting this brand may affect those records.
                                                                        </div>
                                                                    <?php endif; ?>
                                                                </div>
                                                                <input type="hidden" value="<?php echo $brand['brand_id']; ?>" name="brand_id">
                                                            </div>
                                                            <div class="modal-footer">
                                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                                                <button type="submit" name="delete_brand_sbmt" class="btn btn-danger">Delete</button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                            <?php
                                        echo "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Table Pagination -->
                    <div class="row mt-3">
                        <div class="col-sm-12 col-md-5">
                            <div class="dataTables_info" id="brandsTable_info" role="status" aria-live="polite">
                                Showing 1 to <?php echo min(10, count($rows_brands)); ?> of <?php echo count($rows_brands); ?> entries
                            </div>
                        </div>
                        <div class="col-sm-12 col-md-7">
                            <div class="dataTables_paginate paging_simple_numbers" id="brandsTable_paginate">
                                <ul class="pagination">
                                    <li class="paginate_button page-item previous disabled" id="brandsTable_previous">
                                        <a href="#" aria-controls="brandsTable" data-dt-idx="0" tabindex="0" class="page-link">Previous</a>
                                    </li>
                                    <li class="paginate_button page-item active">
                                        <a href="#" aria-controls="brandsTable" data-dt-idx="1" tabindex="0" class="page-link">1</a>
                                    </li>
                                    <li class="paginate_button page-item next disabled" id="brandsTable_next">
                                        <a href="#" aria-controls="brandsTable" data-dt-idx="2" tabindex="0" class="page-link">Next</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Add New Brand Modal -->
        <div class="modal fade" id="add_new_brand" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-primary text-white">
                        <h5 class="modal-title">
                            <i class="fas fa-plus-circle mr-2"></i>Add New Brand
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <form action="car-brands.php" method="POST" id="addBrandForm" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="brand_name">Brand name <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                            </div>
                                            <input type="text" id="brand_name_input" class="form-control" placeholder="Enter brand name" name="brand_name" v-model="brand_name" required>
                                        </div>
                                        <div class="invalid-feedback" v-if="!brand_name">
                                            Brand name is required
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="brand_image">Brand image <span class="text-danger">*</span></label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-image"></i></span>
                                            </div>
                                            <div class="custom-file">
                                                <input type="file" id="brand_image_input" class="custom-file-input" name="brand_image" @change="onFileChange" required>
                                                <label class="custom-file-label" for="brand_image_input">Choose file</label>
                                            </div>
                                        </div>
                                        <div class="invalid-feedback" v-if="!brand_image">
                                            Brand image is required
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label>Image Preview</label>
                                        <div id="preview" class="text-center p-3 border rounded">
                                            <img v-if="brand_image" :src="brand_image" class="img-fluid" style="max-height: 200px;" />
                                            <div v-else class="text-muted py-5">
                                                <i class="fas fa-image fa-3x mb-3"></i>
                                                <p>Image preview will appear here</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                <i class="fas fa-times mr-2"></i>Cancel
                            </button>
                            <button type="submit" class="btn btn-primary" name="add_brand_sbmt">
                                <i class="fas fa-save mr-2"></i>Add Brand
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
  
<?php 
        
        //Include Footer
        include 'Includes/templates/footer.php';
    }
    else
    {
        header('Location: index.php');
        exit();
    }
?>

<!-- Custom Styles -->
<style>
    .stats-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .stats-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .icon-circle {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .brand-logo {
        width: 40px;
        height: 40px;
        overflow: hidden;
        border-radius: 50%;
    }
    
    .brand-logo img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .progress-sm {
        height: 6px;
    }
    
    .dataTables_filter label {
        display: flex;
        align-items: center;
    }
    
    .dataTables_filter input {
        margin-left: 0.5em;
    }
    
    .custom-file-label::after {
        content: "Browse";
    }
</style>

<!-- Custom JavaScript -->
<script>
new Vue({
    el: "#add_new_brand",
    data: {
        brand_name: '',
        brand_image: ''
    },
    methods:{
        onFileChange(e) {
            const file = e.target.files[0];
            if (file) {
                this.brand_image = URL.createObjectURL(file);
                // Update the file input label
                const fileName = file.name;
                const label = e.target.nextElementSibling;
                label.innerText = fileName;
            }
        }
    },
    mounted() {
        // Initialize custom file input
        document.querySelector('.custom-file-input').addEventListener('change', function(e) {
            const fileName = e.target.files[0].name;
            const nextSibling = e.target.nextElementSibling;
            nextSibling.innerText = fileName;
        });
    }
});

// Table search functionality
document.getElementById('brandSearch').addEventListener('keyup', function() {
    const value = this.value.toLowerCase();
    const rows = document.querySelectorAll('#brandsTable tbody tr');
    
    rows.forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(value) ? '' : 'none';
    });
});

// Table length functionality
document.getElementById('brandTableLength').addEventListener('change', function() {
    const value = parseInt(this.value);
    const rows = document.querySelectorAll('#brandsTable tbody tr');
    const info = document.getElementById('brandsTable_info');
    
    let count = 0;
    rows.forEach((row, index) => {
        if (index < value) {
            row.style.display = '';
            count++;
        } else {
            row.style.display = 'none';
        }
    });
    
    info.textContent = `Showing 1 to ${count} of ${rows.length} entries`;
});

// Export functionality
document.getElementById('exportBtn').addEventListener('click', function() {
    // In a real application, this would generate and download a CSV/Excel file
    alert('Export functionality would be implemented here');
});

document.getElementById('exportTable').addEventListener('click', function(e) {
    e.preventDefault();
    // In a real application, this would generate and download a CSV/Excel file
    alert('Export table functionality would be implemented here');
});

// View all functionality
document.getElementById('viewAll').addEventListener('click', function(e) {
    e.preventDefault();
    const rows = document.querySelectorAll('#brandsTable tbody tr');
    rows.forEach(row => {
        row.style.display = '';
    });
    
    const info = document.getElementById('brandsTable_info');
    info.textContent = `Showing 1 to ${rows.length} of ${rows.length} entries`;
});
</script>