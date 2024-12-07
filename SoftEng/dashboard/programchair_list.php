<?php
session_start(); // Start the session at the very beginning

// Check if the user is logged in; redirect to login page if not
if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'Program Chair') {
    header("Location: ../login/login.php");
    exit;
}

// Connection file
$connectionFile = '../connection.php';
if (!file_exists($connectionFile)) {
    die("Connection file not found.");
}
include($connectionFile);

if (!$con) {
    die("Database connection failed: " . $con->errorInfo()[2]);
}

// Get the logged-in user's department
$userDepartment = $_SESSION['user_department'] ?? null;

// Get filters
$sectionFilter = isset($_GET['section']) ? $_GET['section'] : null;

// Prepare the main alumni query
$query = "SELECT 
    a.*, 
    e.Employment, 
    e.Employment_Status, 
    e.Present_Occupation, 
    e.Name_of_Employer, 
    e.Address_of_Employer, 
    e.Number_of_Years_in_Present_Employer, 
    e.Type_of_Employer, 
    e.Major_Line_of_Business,
    CONCAT('AL', LPAD(a.Alumni_ID_Number, 5, '0')) AS Alumni_ID_Number_Format
FROM `2024-2025` a
LEFT JOIN `2024-2025_ed` e 
    ON a.`Alumni_ID_Number` = e.`Alumni_ID_Number`
WHERE a.Department = :department
AND (:section IS NULL OR a.Section = :section)";

$statement = $con->prepare($query);
$statement->bindParam(':department', $userDepartment);
$statement->bindParam(':section', $sectionFilter);
$statement->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Program Chair's Alumni List</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.css">
    <link rel="stylesheet" href="../assets/css/style.css">
    <link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" crossorigin="anonymous" />
</head>
<body class="bg-content">
<div class="container-fluid px">
    <?php include "component/nav.php"; ?>
            <h6 class="fw-bold">Alumni List for <?= htmlspecialchars($userDepartment ?? 'N/A') ?></h6>

            <!-- Filtering Dropdowns -->
            <div class="filter-container">
                <select id="sectionFilter" class="form-select" onchange="applyFilters()">
                    <option value="">Select Section</option>
                </select>
            </div>

            <!-- Alumni Table -->
            <div class="table-responsive table-container">
                <table class="table alumni_list table-borderless">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Alumni ID Number</th>
                            <th>Student Number</th>
                            <th>Last Name</th>
                            <th>First Name</th>
                            <th>Middle Name</th>
                            <th>Department</th>
                            <th>Section</th>
                            <th>Year Graduated</th>
                            <th>Contact Number</th>
                            <th>Personal Email</th>
                            <th>Employment</th>
                            <th>Employment Status</th>
                            <th>Present Occupation</th>
                            <th>Name of Employer</th>
                            <th>Address of Employer</th>
                            <th>Number of Years in Present Employer</th>
                            <th>Type of Employer</th>
                            <th>Major Line of Business</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($statement->rowCount() > 0): ?>
                            <?php while ($row = $statement->fetch(PDO::FETCH_ASSOC)): ?>
                                <tr>
                                    <td><?= htmlspecialchars($row['ID'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['Alumni_ID_Number_Format'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['Student_Number'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['Last_Name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['First_Name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['Middle_Name'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['Department'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['Section'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['Year_Graduated'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['Contact_Number'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['Personal_Email'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['Employment'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['Employment_Status'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['Present_Occupation'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['Name_of_Employer'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['Address_of_Employer'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['Number_of_Years_in_Present_Employer'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['Type_of_Employer'] ?? 'N/A') ?></td>
                                    <td><?= htmlspecialchars($row['Major_Line_of_Business'] ?? 'N/A') ?></td>
                                    <td>
                                        <a href="alumni_edit.php?Alumni_ID_Number=<?= $row['Alumni_ID_Number'] ?>"><i class="far fa-pen"></i></a>
                                        <a href="alumni_process.php?action=delete&alumni_id=<?= $row['Alumni_ID_Number'] ?>"><i class="far fa-trash"></i></a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="20" class="text-center">No alumni records found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sectionSelect = document.getElementById('sectionFilter');
            updateSections("<?= htmlspecialchars($userDepartment) ?>");
        });

        function updateSections(department) {
            const sectionSelect = document.getElementById('sectionFilter');
            sectionSelect.innerHTML = '<option value="">Select Section</option>';

            if (department) {
                fetch(`get_sections.php?department=${encodeURIComponent(department)}`)
                    .then(response => response.json())
                    .then(data => {
                        data.forEach(section => {
                            const option = document.createElement('option');
                            option.value = section;
                            option.textContent = section;
                            sectionSelect.appendChild(option);
                        });
                    })
                    .catch(error => console.error('Error fetching sections:', error));
            }
        }

        function applyFilters() {
            const section = document.getElementById('sectionFilter').value;

            const params = new URLSearchParams();
            if (section) params.append('section', section);

            window.location.search = params.toString();
        }
    </script>
</body>
</html>
