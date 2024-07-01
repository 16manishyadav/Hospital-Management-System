<?php
// Start the session and check if the user is logged in
session_start();
if (empty($_SESSION['name'])) {
    header('location:index.php');
}

// Include necessary files and establish a database connection
include('header.php');
include('includes/connection.php');

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get filter criteria from the form
    $patientType = isset($_POST['patient_type']) ? $_POST['patient_type'] : '';
    $patientStatus = isset($_POST['patient_status']) ? $_POST['patient_status'] : '';

    // Build the SQL query based on the selected criteria
    $sql = "SELECT *
            FROM tbl_patient
            WHERE 1";

    if (!empty($patientStatus)) {
        $sql .= " AND status = " . ($patientStatus == 'Active' ? 1 : 0);
    }

    if (!empty($patientType)) {
        $sql .= " AND patient_type = '$patientType'";
    }

    // Execute the query
    $result = mysqli_query($connection, $sql);
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }
} else {
    // If the form is not submitted, fetch all patients
    $result = mysqli_query($connection, "SELECT * FROM tbl_patient");
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }
}
?>

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">Filter Patients</h4>
            </div>
        </div>
        <form method="post" action="">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Patient Type</label>
                        <select class="form-control" name="patient_type">
                            <option value="">Select Type</option>
                            <option value="InPatient">Inpatient</option>
                            <option value="OutPatient">Outpatient</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Patient Status</label>
                        <select class="form-control" name="patient_status">
                            <option value="">Select Status</option>
                            <option value="Active">Active</option>
                            <option value="Inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>&nbsp;</label>
                        <button type="submit" class="btn btn-success btn-block">Filter</button>
                    </div>
                </div>
            </div>
        </form>

        <div class="table-responsive">
            <table class="datatable table table-stripped">
                <thead>
                    <tr>
                        <!-- Your table headers -->
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Loop through the results and display them
                    while ($row = mysqli_fetch_array($result)) {
                        // Display patient information
                        // display information in the form of table rows
                        echo "<tr>";
                        echo "<td>" . $row['first_name'] . " " . $row['last_name'] . "</td>";
                        // echo "<td>" . $row['age'] . "</td>";
                        echo "<td>" . $row['address'] . "</td>";
                        echo "<td>" . $row['email'] . "</td>";
                        echo "<td>" . $row['phone'] . "</td>";
                        echo "<td>" . $row['patient_type'] . "</td>";
                        // ... Display other columns
                        echo "<td class='text-right'>
                            <div class='dropdown dropdown-action'>
                                <a href='#' class='action-icon dropdown-toggle' data-toggle='dropdown' aria-expanded='false'><i class='fa fa-ellipsis-v'></i></a>
                                <div class='dropdown-menu dropdown-menu-right'>
                                    <a class='dropdown-item' href='edit-patient.php?id=" . $row['id'] . "'><i class='fa fa-pencil m-r-5'></i> Edit</a>
                                    <a class='dropdown-item' href='patients.php?ids=" . $row['id'] . "' onclick='return confirmDelete()'><i class='fa fa-trash-o m-r-5'></i> Delete</a>
                                </div>
                            </div>
                        </td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
include('footer.php');
?>
<script language="JavaScript" type="text/javascript">
    function confirmDelete() {
        return confirm('Are you sure want to delete this Patient?');
    }
</script>
