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
    $day = isset($_POST['day']) ? $_POST['day'] : '';
    $time = isset($_POST['time']) ? $_POST['time'] : '';

    // Build the SQL query based on the selected criteria
    $sql = "SELECT *
            FROM tbl_schedule
            WHERE 1";

    if (!empty($day)) {
        $sql .= " AND FIND_IN_SET('$day', available_days) > 0";
    }

    // if (!empty($time)) {
    //     $sql .= " AND start_time <= '$time' AND end_time >= '$time'";
    // }

    // Execute the query
    $result = mysqli_query($connection, $sql);
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }
} else {
    // If the form is not submitted, fetch all doctors
    $result = mysqli_query($connection, "SELECT * FROM tbl_schedule");
    if (!$result) {
        die("Query failed: " . mysqli_error($connection));
    }
}
?>

<div class="page-wrapper">
    <div class="content">
        <div class="row">
            <div class="col-sm-12">
                <h4 class="page-title">Filter Doctors</h4>
            </div>
        </div>
        <form method="post" action="">
            <div class="row">
                <div class="col-sm-4">
                    <div class="form-group">
                        <label>Select Day</label>
                        <select class="form-control" name="day">
                            <option value="">Select Day</option>
                            <!-- Add options for days -->
                            <?php
                            $days = mysqli_query($connection, "SELECT * FROM tbl_week");
                            while ($dayRow = mysqli_fetch_array($days)) {
                                echo "<option value='" . $dayRow['name'] . "'>" . $dayRow['name'] . "</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <!-- <div class="col-sm-4">
                    <div class="form-group">
                        <label>Select Time</label>
                        <input type="time" class="form-control" name="time">
                    </div>
                </div> -->
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
                        // Display doctor information
                        // display information in the form of table rows
                        echo "<tr>";
                        echo "<td>" . $row['doctor_name'] . "</td>";
                        echo "<td>" . $row['available_days'] . "</td>";
                        echo "<td>" . $row['start_time'] . "</td>";
                        echo "<td>" . $row['end_time'] . "</td>";
                        // ... Display other columns
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
