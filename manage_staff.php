<?php
session_start();
include 'db_con/db_connect.php';

// Ensure user is a manager
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit();
}

// Get manager's centre
$user_id = $_SESSION['user_id'];
$centre_query = "SELECT c.* FROM centres c 
                 INNER JOIN centre_managers cm ON c.centre_id = cm.centre_id 
                 WHERE cm.user_id = ?";
$stmt = $conn->prepare($centre_query);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$centre = $stmt->get_result()->fetch_assoc();

// Get all staff for the centre
$staff_query = "SELECT u.*, r.role_name 
                FROM users u 
                INNER JOIN user_roles ur ON u.user_id = ur.user_id 
                INNER JOIN roles r ON ur.role_id = r.role_id
                INNER JOIN centre_staff cs ON u.user_id = cs.user_id
                WHERE cs.centre_id = ? AND r.role_name IN ('trainer', 'coach', 'referee')";
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
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container">
            <a class="navbar-brand" href="manager_dashboard.php">Manager Dashboard</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item"><a class="nav-link" href="manager_dashboard.php">Dashboard</a></li>
                    <li class="nav-item"><a class="nav-link" href="view_bookings.php">View Bookings</a></li>
                    <li class="nav-item"><a class="nav-link active" href="manage_staff.php">Manage Staff</a></li>
                    <li class="nav-item"><a class="nav-link" href="reports.php">Reports</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container py-4">
        <div class="row mb-4">
            <div class="col-md-6">
                <h2>Staff Management - <?= htmlspecialchars($centre['name']) ?></h2>
            </div>
            <div class="col-md-6 text-end">
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addStaffModal">
                    <i class="bi bi-person-plus"></i> Add New Staff
                </button>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped" id="staffTable">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Role</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($staff = $staff_result->fetch_assoc()): ?>
                                <tr>
                                    <td><?= htmlspecialchars($staff['name']) ?></td>
                                    <td><?= ucfirst(htmlspecialchars($staff['role_name'])) ?></td>
                                    <td><?= htmlspecialchars($staff['email']) ?></td>
                                    <td>
                                        <span class="badge bg-<?= $staff['status'] === 'active' ? 'success' : 'danger' ?>">
                                            <?= ucfirst($staff['status']) ?>
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
                    <h5 class="modal-title">Add New Staff</h5>
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
                    <button type="button" class="btn btn-primary" id="submitStaff">Add Staff</button>
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
        });
    </script>
</body>
</html>
