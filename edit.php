<?php
include("./db/connection.php");

$id = $_GET["id"]; // e.g., update_student.php?id=1

// Fetch the record
$stmt = $conn->prepare("SELECT * FROM student WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Handle update
if (isset($_POST["update"])) {
    $_name = $_POST["name"];
    $_email = $_POST["email"];

    $updateStmt = $conn->prepare("UPDATE student SET Name = ?, email = ? WHERE id = ?");
    $updateStmt->bind_param("ssi", $_name, $_email, $id);

    if ($updateStmt->execute()) {
        header("Location: ./table.php");
        exit();
    } else {
        echo "Error updating record: " . $updateStmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Update Student</title>
    <style>
        /* Basic modal styling */
        #confirmationModal {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background: #fff;
            padding: 20px;
            width: 300px;
            margin: 100px auto;
            border-radius: 8px;
            text-align: center;
        }

        button {
            margin: 5px;
        }
    </style>
</head>
<body>
    <h2>Update Student Record</h2>

    <form id="updateForm" method="POST">
        <label>Name:</label>
        <input type="text" name="name" value="<?= htmlspecialchars($row['Name']) ?>" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>" required><br><br>

        <input type="button" value="Update" onclick="openModal()">
        <button type="button" onclick="window.location.href='./table.php';">View Table</button>

        <!-- Hidden submit button -->
        <input type="submit" name="update" id="hiddenSubmit" style="display:none;">
    </form>

    <!-- Modal HTML -->
    <div id="confirmationModal">
        <div class="modal-content">
            <p>Are you sure you want to update this record?</p>
            <button onclick="submitForm()">Yes, Update</button>
            <button onclick="closeModal()">Cancel</button>
        </div>
    </div>

    <script>
        function openModal() {
            document.getElementById("confirmationModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("confirmationModal").style.display = "none";
        }

        function submitForm() {
            document.getElementById("hiddenSubmit").click();
        }
    </script>
</body>
</html>