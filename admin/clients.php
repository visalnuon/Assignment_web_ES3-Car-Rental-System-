<?php
    session_start();

     //Page Title
    $pageTitle = 'Clients';

    //Includes
    include 'connect.php';
    include 'Includes/functions/functions.php'; 
    include 'Includes/templates/header.php';

    //Check If user is already logged in
    if(isset($_SESSION['username_yahya_car_rental']) && isset($_SESSION['password_yahya_car_rental']))
    {
        // Handle client deletion
        if (isset($_POST['delete_client_sbmt']) && $_SERVER['REQUEST_METHOD'] === 'POST') {
            $client_id = $_POST['client_id'];
            
            try {
                // Start transaction
                $con->beginTransaction();
                
                // First, check if client has reservations
                $stmt = $con->prepare("SELECT COUNT(*) as count FROM reservations WHERE client_id = ?");
                $stmt->execute(array($client_id));
                $result = $stmt->fetch();
                
                if ($result['count'] > 0) {
                    // Option 1: Delete all reservations associated with the client
                    $stmt = $con->prepare("DELETE FROM reservations WHERE client_id = ?");
                    $stmt->execute(array($client_id));
                    
                    // Option 2: If you prefer to keep reservations but just remove the client reference,
                    // you could use: UPDATE reservations SET client_id = NULL WHERE client_id = ?
                }
                
                // Now delete the client
                $stmt = $con->prepare("DELETE FROM clients WHERE client_id = ?");
                $stmt->execute(array($client_id));
                
                // Commit transaction
                $con->commit();
                
                echo "<div class='alert alert-success'>";
                echo 'Client has been deleted successfully';
                echo "</div>";
            } catch (Exception $e) {
                // Roll back transaction if something went wrong
                $con->rollBack();
                echo "<div class='alert alert-danger'>";
                echo 'Error occurred: ' . $e->getMessage();
                echo "</div>";
            }
        }
?>
        <!-- Begin Page Content -->
        <div class="container-fluid">
    
            <!-- Page Heading -->

            <!-- Clients Table -->
            <?php
                // *** FIX APPLIED HERE ***
                // Modified query to correctly calculate total price and include clients with no reservations
                $stmt = $con->prepare("
                    SELECT 
                        c.client_id, 
                        c.full_name, 
                        c.client_phone, 
                        c.client_email, 
                        COUNT(r.reservation_id) as total_reservations,
                        COALESCE(SUM(car.price * DATEDIFF(r.return_date, r.pickup_date)), 0) as total_price
                    FROM clients c
                    LEFT JOIN reservations r ON c.client_id = r.client_id AND r.canceled = 0
                    LEFT JOIN cars car ON r.car_id = car.id
                    GROUP BY c.client_id
                    ORDER BY c.client_id DESC
                ");
                $stmt->execute();
                $rows_clients = $stmt->fetchAll(); 
            ?>
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Clients</h6>
                </div>
                <div class="card-body">
                    
                    <!-- Clients Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th scope="col">ID#</th>
                                    <th scope="col">Full Name</th>
                                    <th scope="col">Phone Number</th>
                                    <th scope="col">E-mail</th>
                                    <th scope="col">Total Reservations</th>
                                    <th scope="col">Total Price</th>
                                    <th scope="col">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                    foreach($rows_clients as $client)
                                    {
                                        $delete_modal_id = "delete_" . $client['client_id'];
                                        echo "<tr>";
                                            echo "<td>" . $client['client_id'] . "</td>";
                                            echo "<td>" . $client['full_name'] . "</td>";
                                            echo "<td>" . $client['client_phone'] . "</td>";
                                            echo "<td>" . $client['client_email'] . "</td>";
                                            echo "<td>" . $client['total_reservations'] . "</td>";
                                            echo "<td>";
                                                // *** FIX APPLIED HERE ***
                                                // The total_price will now be 0 instead of NULL, so this check works correctly.
                                                if($client['total_price'] > 0) {
                                                    echo '$' . number_format($client['total_price'], 2);
                                                } else {
                                                    echo '$0.00'; // Changed from 'N/A' for consistency
                                                }
                                            echo "</td>";
                                            echo "<td>";
                                                echo "<button class='btn btn-danger btn-sm' data-toggle='modal' data-target='#" . $delete_modal_id . "' title='Delete Client'>";
                                                    echo "<i class='fas fa-trash'></i>";
                                                echo "</button>";
                                                
                                                // Delete Confirmation Modal
                                                echo "<div class='modal fade' id='" . $delete_modal_id . "' tabindex='-1' role='dialog' aria-hidden='true'>";
                                                    echo "<div class='modal-dialog' role='document'>";
                                                        echo "<div class='modal-content'>";
                                                            echo "<div class='modal-header'>";
                                                                echo "<h5 class='modal-title'>Delete Client</h5>";
                                                                echo "<button type='button' class='close' data-dismiss='modal' aria-label='Close'>";
                                                                    echo "<span aria-hidden='true'>&times;</span>";
                                                                echo "</button>";
                                                            echo "</div>";
                                                            echo "<div class='modal-body'>";
                                                                echo "Are you sure you want to delete this client?";
                                                                if ($client['total_reservations'] > 0) {
                                                                    echo "<br><br><strong>Warning:</strong> This client has " . $client['total_reservations'] . " reservation(s) that will also be deleted.";
                                                                }
                                                            echo "</div>";
                                                            echo "<div class='modal-footer'>";
                                                                echo "<button type='button' class='btn btn-secondary' data-dismiss='modal'>Cancel</button>";
                                                                echo "<form action='clients.php' method='POST' style='display: inline;'>";
                                                                    echo "<input type='hidden' name='client_id' value='" . $client['client_id'] . "'>";
                                                                    echo "<button type='submit' name='delete_client_sbmt' class='btn btn-danger'>Delete</button>";
                                                                echo "</form>";
                                                            echo "</div>";
                                                        echo "</div>";
                                                    echo "</div>";
                                                echo "</div>";
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
        
        //Include Footer
        include 'Includes/templates/footer.php';
    }
    else
    {
        header('Location: index.php');
        exit();
    }

?>