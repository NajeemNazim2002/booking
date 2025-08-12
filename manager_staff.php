<?php
session_start();
include 'db_con/db_connect.php';

// Check if user is logged in and is a manager
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

// Get manager's centre
$manager_id = $_SESSION['user_id'];
$centre_query = "SELECT c.* FROM centres c 
                 WHERE c.manager_id = ?";
$stmt = $conn->prepare($centre_query);
$stmt->bind_param("i", $manager_id);
$stmt->execute();
$centre = $stmt->get_result()->fetch_assoc();

// Fetch staff members
$staff_query = "SELECT u.*, r.role_name 
                FROM users u 
                INNER JOIN user_roles ur ON u.user_id = ur.user_id
                INNER JOIN roles r ON ur.role_id = r.role_id
                INNER JOIN centre_staff cs ON u.user_id = cs.user_id
                WHERE cs.centre_id = ? AND r.role_name IN ('trainer', 'coach', 'referee')
                ORDER BY r.role_name, u.name";
$stmt = $conn->prepare($staff_query);
$stmt->bind_param("i", $centre['centre_id']);
$stmt->execute();
$staff_result = $stmt->get_result();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Staff - Indoor Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="manager_dashboard.php">
                <img src="images/logo.jpg" alt="Indoor Hub Logo" width="40" height="40" class="me-2">
                Centre Manager Dashboard
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" href="manager_dashboard.php">Dashboard</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manager_bookings.php">Bookings</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="manager_staff.php">Staff</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="manager_reports.php">Reports</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-md-8">
                <h2>Manage Staff - <?= htmlspecialchars($centre['name']) ?></h2>
                <p class="text-muted">Manage trainers, coaches, and referees for your centre</p>
            </div>
            <div class="col-md-4 text-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                    <i class="bi bi-person-plus"></i> Add New Staff
                </button>
            </div>
        </div>

        <div class="card shadow-sm">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table" id="staffTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($staff = $staff_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($staff['name']) ?></td>
                                    <td>
                                        <span class="badge bg-info">
                                            <?= ucfirst(htmlspecialchars($staff['role_name'])) ?>
                                        </span>
                                    </td>
                                    <td><?= htmlspecialchars($staff['email']) ?></td>
                                    <td><?= htmlspecialchars($staff['phone'] ?? 'N/A') ?></td>
                                    <td>
                                        <span class="badge bg-<?= $staff['status'] === 'active' ? 'success' : 'danger' ?>">
                                            <?= ucfirst(htmlspecialchars($staff['status'])) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary edit-staff" 
                                                data-staff-id="<?= $staff['user_id'] ?>">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <button class="btn btn-sm btn-danger delete-staff"
                                                data-staff-id="<?= $staff['user_id'] ?>">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Staff Modal -->
    <div class="modal fade" id="addStaffModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Add New Staff Member</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="addStaffForm">
                        <div class="mb-3">
                            <label class="form-label">Name</label>
                            <input type="text" class="form-control" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Phone</label>
                            <input type="tel" class="form-control" name="phone">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Role</label>
                            <select class="form-select" name="role" required>
                                <option value="trainer">Trainer</option>
                                <option value="coach">Coach</option>
                                <option value="referee">Referee</option>
                            </select>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" id="saveStaff">Add Staff</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#staffTable').DataTable();

            // Handle staff addition
            $('#saveStaff').click(function() {
                // Add AJAX call to save new staff member
                $('#addStaffModal').modal('hide');
                location.reload();
            });

            // Handle staff deletion
            $('.delete-staff').click(function() {
                const staffId = $(this).data('staff-id');
                if (confirm('Are you sure you want to remove this staff member?')) {
                    // Add AJAX call to delete staff member
                    location.reload();
                }
            });
        });
    </script>
</body>
</html>
