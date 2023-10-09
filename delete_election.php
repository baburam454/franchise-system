<?php
include 'db_con.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Delete corresponding candidate information from election_results table
    $deleteResultsSql = "DELETE FROM election_results WHERE election_id = $id";

    if(mysqli_query($conn, $deleteResultsSql)) {
        // Candidate information deleted successfully, now delete the election
        $deleteElectionSql = "DELETE FROM election WHERE id = $id";

        if(mysqli_query($conn, $deleteElectionSql)) {
            // Election and candidate information deleted successfully
            header("Location:admindashboard.php"); // Redirect back to the admin dashboard
            exit();
        } else {
            // Failed to delete election
            echo "Error deleting election: " . mysqli_error($conn);
        }
    } else {
        // Failed to delete candidate information
        echo "Error deleting candidate information: " . mysqli_error($conn);
    }
} else {
    // Invalid or missing election ID
    echo "Invalid election ID.";
}

?>

