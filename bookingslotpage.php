<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'db_con/db_connect.php';
require_once 'Auth.php';

Auth::requireRole('user');

$logged_in_user_id = $_SESSION['user_id'];
$msg = '';

// Handle date navigation params for calendar display
if (isset($_GET['year']) && isset($_GET['month']) &&
    checkdate(intval($_GET['month']), 1, intval($_GET['year']))) {
    $calendar_year = intval($_GET['year']);
    $calendar_month = intval($_GET['month']);
} else {
    // Default to month of selected date or today
    if (isset($_GET['date'])) {
        $calendar_year = intval(date('Y', strtotime($_GET['date'])));
        $calendar_month = intval(date('m', strtotime($_GET['date'])));
    } elseif (isset($_POST['booking_date'])) {
        $calendar_year = intval(date('Y', strtotime($_POST['booking_date'])));
        $calendar_month = intval(date('m', strtotime($_POST['booking_date'])));
    } else {
        $calendar_year = intval(date('Y'));
        $calendar_month = intval(date('m'));
    }
}

// Get selected booking date (from POST or GET)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['booking_date'])) {
    $selected_date = $_POST['booking_date'];
} elseif (isset($_GET['date'])) {
    $selected_date = $_GET['date'];
} else {
    $selected_date = null;
}

// Redirect to month of selected date if different from calendar month
if ($selected_date !== null) {
    $selected_year = intval(date('Y', strtotime($selected_date)));
    $selected_month = intval(date('m', strtotime($selected_date)));
    if ($selected_year !== $calendar_year || $selected_month !== $calendar_month) {
        $url = $_SERVER['PHP_SELF'] . "?year=$selected_year&month=$selected_month&date=$selected_date";
        header("Location: $url");
        exit;
    }
}

// Handle form submission (new booking)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['centre_id'], $_POST['start_time'], $_POST['end_time'])) {
    $centre_id = intval($_POST['centre_id']);
    $booking_date = $_POST['booking_date']; // 'Y-m-d'
    $start_time = $_POST['start_time'];
    $end_time = $_POST['end_time'];

    if ($start_time >= $end_time) {
        $msg = "❌ End time must be after start time.";
    } else {
        // Check overlapping bookings for that centre and date
        $check_sql = "SELECT * FROM bookings WHERE centre_id = ? AND DATE(booking_date) = ? AND (start_time < ? AND end_time > ?)";
        $check_stmt = $conn->prepare($check_sql);
        $check_stmt->bind_param("isss", $centre_id, $booking_date, $end_time, $start_time);
        $check_stmt->execute();
        $check_result = $check_stmt->get_result();

        if ($check_result->num_rows > 0) {
            $msg = "❌ Slot already booked for this time range.";
        } else {
            // Insert booking (slot_id = NULL)
            $booking_datetime = date('Y-m-d H:i:s');
            $insert_sql = "INSERT INTO bookings (user_id, slot_id, booking_date, centre_id, start_time, end_time, status, payment_status, amount_paid)
                           VALUES (?, NULL, ?, ?, ?, ?, 'booked', 'unpaid', 0.00)";
            $insert_stmt = $conn->prepare($insert_sql);
            $insert_stmt->bind_param("issss", $logged_in_user_id, $booking_datetime, $centre_id, $start_time, $end_time);

            if ($insert_stmt->execute()) {
                $msg = "✅ Booking request submitted!";
                // After successful booking, reload page to selected date calendar
                header("Location: " . $_SERVER['PHP_SELF'] . "?year=$calendar_year&month=$calendar_month&date=$booking_date");
                exit;
            } else {
                $msg = "❌ Booking failed: " . $conn->error;
            }
        }
    }
}

// Fetch indoor centres
$centres = $conn->query("SELECT centre_id, name FROM centres ORDER BY name ASC");

// Calculate month start/end for calendar
$start_of_month = sprintf("%04d-%02d-01 00:00:00", $calendar_year, $calendar_month);
$end_of_month = date("Y-m-t", strtotime($start_of_month)) . " 23:59:59";

// Fetch all booked dates (any user)
$booked_dates_sql = "SELECT DISTINCT DATE(booking_date) as booking_date FROM bookings WHERE booking_date BETWEEN ? AND ?";
$booked_dates_stmt = $conn->prepare($booked_dates_sql);
$booked_dates_stmt->bind_param("ss", $start_of_month, $end_of_month);
$booked_dates_stmt->execute();
$booked_dates_result = $booked_dates_stmt->get_result();

$booked_dates = [];
while ($row = $booked_dates_result->fetch_assoc()) {
    $booked_dates[] = $row['booking_date'];
}

// Fetch logged-in user's booked dates (same month)
$user_booked_dates_sql = "SELECT DISTINCT DATE(booking_date) as booking_date FROM bookings WHERE user_id = ? AND booking_date BETWEEN ? AND ?";
$user_booked_dates_stmt = $conn->prepare($user_booked_dates_sql);
$user_booked_dates_stmt->bind_param("iss", $logged_in_user_id, $start_of_month, $end_of_month);
$user_booked_dates_stmt->execute();
$user_booked_dates_result = $user_booked_dates_stmt->get_result();

$user_booked_dates = [];
while ($row = $user_booked_dates_result->fetch_assoc()) {
    $user_booked_dates[] = $row['booking_date'];
}

// Fetch booked slots for selected date and optional centre (for the "Booked Time Slots" list)
$booked_slots = [];
$centre_filter = '';
$centre_filter_param = null;

if (isset($_POST['centre_id']) && is_numeric($_POST['centre_id'])) {
    $centre_filter = " AND centre_id = ?";
    $centre_filter_param = intval($_POST['centre_id']);
}

if ($selected_date !== null) {
    if ($centre_filter) {
        $slots_sql = "SELECT start_time, end_time FROM bookings WHERE DATE(booking_date) = ?" . $centre_filter . " ORDER BY start_time";
        $slots_stmt = $conn->prepare($slots_sql);
        $slots_stmt->bind_param("si", $selected_date, $centre_filter_param);
        $slots_stmt->execute();
        $slots_result = $slots_stmt->get_result();
    } else {
        $slots_sql = "SELECT start_time, end_time FROM bookings WHERE DATE(booking_date) = ? ORDER BY start_time";
        $slots_stmt = $conn->prepare($slots_sql);
        $slots_stmt->bind_param("s", $selected_date);
        $slots_stmt->execute();
        $slots_result = $slots_stmt->get_result();
    }

    while ($slot = $slots_result->fetch_assoc()) {
        $booked_slots[] = $slot;
    }
}

// --- Fetch booking records filtered by selected date (or all if no date selected) ---
if ($selected_date !== null) {
    $all_bookings_sql = "
        SELECT b.booking_id, b.user_id, u.name AS username, b.centre_id, c.name AS centre_name, b.booking_date, b.start_time, b.end_time, b.status, b.payment_status, b.amount_paid
        FROM bookings b
        LEFT JOIN users u ON b.user_id = u.user_id
        LEFT JOIN centres c ON b.centre_id = c.centre_id
        WHERE DATE(b.booking_date) = ?
        ORDER BY b.booking_date DESC, b.start_time ASC
    ";
    $all_bookings_stmt = $conn->prepare($all_bookings_sql);
    $all_bookings_stmt->bind_param("s", $selected_date);
    $all_bookings_stmt->execute();
    $all_bookings_result = $all_bookings_stmt->get_result();
} else {
    // No date filter: fetch all bookings
    $all_bookings_sql = "
        SELECT b.booking_id, b.user_id, u.name AS username, b.centre_id, c.name AS centre_name, b.booking_date, b.start_time, b.end_time, b.status, b.payment_status, b.amount_paid
        FROM bookings b
        LEFT JOIN users u ON b.user_id = u.user_id
        LEFT JOIN centres c ON b.centre_id = c.centre_id
        ORDER BY b.booking_date DESC, b.start_time ASC
    ";
    $all_bookings_result = $conn->query($all_bookings_sql);
}

// Calendar helper function with clickable dates and coloring
function buildCalendar($year, $month, $booked_dates, $user_booked_dates, $selected_date) {
    $first_day_of_month = strtotime("$year-$month-01");
    $days_in_month = date('t', $first_day_of_month);
    $start_weekday = date('N', $first_day_of_month); // 1 (Mon) - 7 (Sun)

    $html = '<table class="table table-bordered text-center">';
    $html .= '<thead><tr>';
    $days = ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'];
    foreach ($days as $day) {
        $html .= "<th>$day</th>";
    }
    $html .= '</tr></thead><tbody><tr>';

    // Empty cells before first day
    for ($i = 1; $i < $start_weekday; $i++) {
        $html .= '<td></td>';
    }

    for ($day = 1; $day <= $days_in_month; $day++) {
        $date_str = sprintf("%04d-%02d-%02d", $year, $month, $day);
        $class = '';
        $title = '';

        // Today highlight
        if ($date_str == date('Y-m-d')) {
            $class = 'today-date';
            $title = 'Today';
        }

        // User booked date highlight
        if (in_array($date_str, $user_booked_dates)) {
            $class = 'user-booked-date';
            $title = 'You have a booking';
        }

        // Booked by others but not user
        if (in_array($date_str, $booked_dates) && !in_array($date_str, $user_booked_dates)) {
            $class = 'booked-date';
            $title = 'Booked by others';
        }

        // Highlight selected date with border or different background
        if ($selected_date === $date_str) {
            $class .= ' selected-date';
            $title .= ' (Selected)';
        }

        // Make cell clickable with link to reload page with ?year=YYYY&month=MM&date=YYYY-MM-DD
        $href = $_SERVER['PHP_SELF'] . "?year=$year&month=$month&date=$date_str";

        $html .= "<td class='$class'><a href='$href' title='$title' style='text-decoration:none; color:inherit;'>$day</a></td>";

        if (($start_weekday + $day - 1) % 7 == 0 && $day != $days_in_month) {
            $html .= '</tr><tr>';
        }
    }

    // Empty cells after last day
    $last_day_weekday = ($start_weekday + $days_in_month - 1) % 7;
    if ($last_day_weekday != 0) {
        for ($i = $last_day_weekday + 1; $i <= 7; $i++) {
            $html .= '<td></td>';
        }
    }

    $html .= '</tr></tbody></table>';

    return $html;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Book Facility Slot - Indoor Hub</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        /* Calendar date colors */
        .today-date {
            background-color: #ffc107; /* yellow */
            font-weight: bold;
        }
        .user-booked-date {
            background-color: #28a745; /* green */
            color: white;
            font-weight: bold;
        }
        .booked-date {
            background-color: #dc3545; /* red */
            color: white;
            font-weight: bold;
        }
        /* Highlight selected date */
        .selected-date {
            border: 2px solid #0d6efd; /* Bootstrap primary blue */
        }
        /* Make anchor fill the cell fully */
        td a {
            display: block;
            width: 100%;
            height: 100%;
            padding: 6px 0;
        }
    </style>
</head>
<body class="bg-light">

<div class="container py-5">
    <h2 class="mb-4 text-center">Book Facility Slot</h2>

    <?php if ($msg): ?>
        <div class="alert alert-info text-center"><?= htmlspecialchars($msg) ?></div>
    <?php endif; ?>

    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card p-4 shadow-sm">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label class="form-label">Select Indoor Centre</label>
                        <select name="centre_id" class="form-select" required>
                            <option value="">-- Choose Centre --</option>
                            <?php
                            $centres->data_seek(0);
                            while ($centre = $centres->fetch_assoc()): ?>
                                <option value="<?= $centre['centre_id'] ?>" <?= (isset($_POST['centre_id']) && $_POST['centre_id'] == $centre['centre_id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($centre['name']) ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Booking Date</label>
                        <input type="date" name="booking_date" class="form-control" required min="<?= date('Y-m-d') ?>" value="<?= htmlspecialchars($selected_date ?? date('Y-m-d')) ?>" />
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Start Time</label>
                            <input type="time" name="start_time" class="form-control" required />
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">End Time</label>
                            <input type="time" name="end_time" class="form-control" required />
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary w-100">Book Slot</button>
                </form>
            </div>
        </div>

        <div class="col-md-4">
            <h4>
                Booking Calendar (<?= date('F Y', strtotime("$calendar_year-$calendar_month-01")) ?>)
            </h4>

            <?php
            // Calculate prev and next month/year
            $prev_month = $calendar_month - 1;
            $prev_year = $calendar_year;
            if ($prev_month < 1) {
                $prev_month = 12;
                $prev_year--;
            }
            $next_month = $calendar_month + 1;
            $next_year = $calendar_year;
            if ($next_month > 12) {
                $next_month = 1;
                $next_year++;
            }
            // Build URLs preserving current selected date if inside those months
            $prev_url = $_SERVER['PHP_SELF'] . "?year=$prev_year&month=$prev_month";
            $next_url = $_SERVER['PHP_SELF'] . "?year=$next_year&month=$next_month";
            if ($selected_date !== null) {
                $sel_year = date('Y', strtotime($selected_date));
                $sel_month = date('m', strtotime($selected_date));
                if ($sel_year == $prev_year && $sel_month == $prev_month) {
                    $prev_url .= "&date=" . urlencode($selected_date);
                }
                if ($sel_year == $next_year && $sel_month == $next_month) {
                    $next_url .= "&date=" . urlencode($selected_date);
                }
            }
            ?>

            <div class="d-flex justify-content-between mb-2">
                <a href="<?= htmlspecialchars($prev_url) ?>" class="btn btn-outline-primary">&laquo; Prev</a>
                <a href="<?= htmlspecialchars($next_url) ?>" class="btn btn-outline-primary">Next &raquo;</a>
            </div>

            <?= buildCalendar($calendar_year, $calendar_month, $booked_dates, $user_booked_dates, $selected_date) ?>
        </div>
    </div>

<div class="container mt-5">
    <h3>Your Bookings</h3>

    <?php
    include 'db_con/db_connect.php';
    $user_id = $_SESSION['user_id'] ?? 0;

    // --- Cancel Booking ---
    if (isset($_POST['cancel_id'])) {
        $cancel_id = (int)$_POST['cancel_id'];
        $stmt = $conn->prepare("UPDATE bookings SET status = 'cancelled' WHERE booking_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $cancel_id, $user_id);
        $stmt->execute();
        echo "<script>alert('❌ Booking Cancelled');</script>";
    }

    // --- Delete Booking ---
    if (isset($_POST['delete_id'])) {
        $delete_id = (int)$_POST['delete_id'];
        $stmt = $conn->prepare("DELETE FROM bookings WHERE booking_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $delete_id, $user_id);
        $stmt->execute();
        echo "<script>alert('🗑️ Booking Deleted');</script>";
    }

    // --- Update Booking ---
    if (isset($_POST['update_id'])) {
        $update_id = (int)$_POST['update_id'];
        $new_date = $_POST['booking_date'];
        $new_start = $_POST['start_time'];
        $new_end = $_POST['end_time'];

        if ($new_start >= $new_end) {
            echo "<script>alert('❌ End time must be after start time');</script>";
        } else {
            // Check for time conflicts
            $conflict_sql = "SELECT * FROM bookings 
                             WHERE booking_id != ? AND centre_id = (SELECT centre_id FROM bookings WHERE booking_id = ?) 
                             AND booking_date = ? AND (start_time < ? AND end_time > ?)";
            $stmt = $conn->prepare($conflict_sql);
            $stmt->bind_param("iisss", $update_id, $update_id, $new_date, $new_end, $new_start);
            $stmt->execute();
            $result_conflict = $stmt->get_result();

            if ($result_conflict->num_rows > 0) {
                echo "<script>alert('❌ Time slot overlaps with another booking');</script>";
            } else {
                $stmt = $conn->prepare("UPDATE bookings SET booking_date = ?, start_time = ?, end_time = ?, status = 'rescheduled' WHERE booking_id = ? AND user_id = ?");
                $stmt->bind_param("sssii", $new_date, $new_start, $new_end, $update_id, $user_id);
                $stmt->execute();
                echo "<script>alert('✅ Booking Updated');</script>";
            }
        }
    }

    // --- Fetch User Bookings ---
    $stmt = $conn->prepare("SELECT b.*, c.name AS centre_name 
                            FROM bookings b 
                            JOIN centres c ON b.centre_id = c.centre_id 
                            WHERE b.user_id = ? ORDER BY b.booking_date DESC");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    ?>

    <form method="POST">
        <!-- Scrollable table container -->
        <div style="max-height: 400px; overflow-y: auto; border: 1px solid #ddd;">
            <table class="table table-bordered table-hover mb-0">
                <thead class="table-light" style="position: sticky; top: 0; background: white; z-index: 1;">
                    <tr>
                        <th>Centre</th>
                        <th>Date</th>
                        <th>Time</th>
                        <th>Status</th>
                        <th>Edit Fields</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($row['centre_name']) ?></td>
                        <td>
                            <input type="date" name="booking_date" class="form-control" value="<?= $row['booking_date'] ?>">
                        </td>
                        <td>
                            <div class="d-flex">
                                <input type="time" name="start_time" class="form-control me-1" value="<?= $row['start_time'] ?>">
                                <input type="time" name="end_time" class="form-control" value="<?= $row['end_time'] ?>">
                            </div>
                        </td>
                        <td><?= htmlspecialchars($row['status']) ?></td>
                        <td>
                            <input type="hidden" name="update_id" value="<?= $row['booking_id'] ?>">
                            <button type="submit" class="btn btn-sm btn-success">Update</button>
                        </td>
                        <td>
                            <button type="submit" name="cancel_id" value="<?= $row['booking_id'] ?>" class="btn btn-sm btn-warning me-1" onclick="return confirm('Cancel this booking?')">Cancel</button>
                            <button type="submit" name="delete_id" value="<?= $row['booking_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Permanently delete this booking?')">Delete</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </form>
</div>


    <!-- === Bookings Table Section === -->
    <?php if ($selected_date !== null): ?>
        <p>
            Showing bookings for <strong><?= htmlspecialchars($selected_date) ?></strong>.
            <a href="bookingslotpage.php">Show all bookings</a>
        </p>
    <?php endif; ?>
<?php
// --- Existing setup code above ---

// Collect advanced search filters from GET
$filter_date = $_GET['filter_date'] ?? '';
$filter_user = $_GET['filter_user'] ?? '';
$filter_centre = $_GET['filter_centre'] ?? '';
$filter_status = $_GET['filter_status'] ?? '';
$filter_payment_status = $_GET['filter_payment_status'] ?? '';
$filter_start_time = $_GET['filter_start_time'] ?? '';
$filter_end_time = $_GET['filter_end_time'] ?? '';

// Build WHERE clauses dynamically for bookings search
$booking_where = [];
$booking_params = [];
$booking_types = '';

if ($filter_date !== '') {
    $booking_where[] = "DATE(b.booking_date) = ?";
    $booking_params[] = $filter_date;
    $booking_types .= 's';
}
if ($filter_user !== '') {
    $booking_where[] = "u.name LIKE ?";
    $booking_params[] = "%$filter_user%";
    $booking_types .= 's';
}
if ($filter_centre !== '') {
    $booking_where[] = "c.name LIKE ?";
    $booking_params[] = "%$filter_centre%";
    $booking_types .= 's';
}
if ($filter_status !== '') {
    $booking_where[] = "b.status = ?";
    $booking_params[] = $filter_status;
    $booking_types .= 's';
}
if ($filter_payment_status !== '') {
    $booking_where[] = "b.payment_status = ?";
    $booking_params[] = $filter_payment_status;
    $booking_types .= 's';
}
if ($filter_start_time !== '') {
    $booking_where[] = "b.start_time >= ?";
    $booking_params[] = $filter_start_time;
    $booking_types .= 's';
}
if ($filter_end_time !== '') {
    $booking_where[] = "b.end_time <= ?";
    $booking_params[] = $filter_end_time;
    $booking_types .= 's';
}

// Base bookings query
$booking_sql = "
    SELECT b.booking_id AS id, b.user_id, u.name AS username, b.centre_id, c.name AS centre_name, 
           b.booking_date, b.start_time, b.end_time, b.status, b.payment_status, b.amount_paid
    FROM bookings b
    LEFT JOIN users u ON b.user_id = u.user_id
    LEFT JOIN centres c ON b.centre_id = c.centre_id
";


// Add WHERE clauses if any filters applied
if (count($booking_where) > 0) {
    $booking_sql .= " WHERE " . implode(" AND ", $booking_where);
}

$booking_sql .= " ORDER BY b.booking_date DESC, b.start_time ASC";

if (count($booking_params) > 0) {
    $booking_stmt = $conn->prepare($booking_sql);
    $booking_stmt->bind_param($booking_types, ...$booking_params);
    $booking_stmt->execute();
    $booking_result = $booking_stmt->get_result();
} else {
    $booking_result = $conn->query($booking_sql);
}
?>
    <h4 class="mt-4">Bookings <?= $selected_date !== null ? 'on ' . htmlspecialchars($selected_date) : '(All Dates)' ?></h4>
    
<!-- Advanced Search Form for Bookings -->
<form method="GET" class="mb-3">
    <div class="row g-2 align-items-end">
        <div class="col-md-2">
            <label for="filter_date" class="form-label">Booking Date</label>
            <input type="date" id="filter_date" name="filter_date" value="<?= htmlspecialchars($filter_date) ?>" class="form-control" />
        </div>
        <div class="col-md-2">
            <label for="filter_user" class="form-label">User Name</label>
            <input type="text" id="filter_user" name="filter_user" value="<?= htmlspecialchars($filter_user) ?>" class="form-control" placeholder="User name" />
        </div>
<div class="col-md-2">
    <label for="filter_centre" class="form-label">Centre</label>
    <select id="filter_centre" name="filter_centre" class="form-select">
        <option value="">All Centres</option>
        <?php
        // Rewind centres result pointer
        $centres->data_seek(0);
        while ($centre = $centres->fetch_assoc()):
            $selected = ($filter_centre == $centre['name']) ? 'selected' : '';
        ?>
            <option value="<?= htmlspecialchars($centre['name']) ?>" <?= $selected ?>>
                <?= htmlspecialchars($centre['name']) ?>
            </option>
        <?php endwhile; ?>
    </select>
</div>

        <div class="col-md-2">
            <label for="filter_status" class="form-label">Status</label>
            <select id="filter_status" name="filter_status" class="form-select">
                <option value="">All</option>
                <option value="booked" <?= $filter_status === 'booked' ? 'selected' : '' ?>>Booked</option>
                <option value="cancelled" <?= $filter_status === 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                <option value="rescheduled" <?= $filter_status === 'rescheduled' ? 'selected' : '' ?>>Rescheduled</option>
                <option value="completed" <?= $filter_status === 'completed' ? 'selected' : '' ?>>Completed</option>
            </select>
        </div>
        <div class="col-md-2">
            <label for="filter_payment_status" class="form-label">Payment Status</label>
            <select id="filter_payment_status" name="filter_payment_status" class="form-select">
                <option value="">All</option>
                <option value="paid" <?= $filter_payment_status === 'paid' ? 'selected' : '' ?>>Paid</option>
                <option value="unpaid" <?= $filter_payment_status === 'unpaid' ? 'selected' : '' ?>>Unpaid</option>
            </select>
        </div>
        <div class="col-md-1">
            <label for="filter_start_time" class="form-label">Start Time ≥</label>
            <input type="time" id="filter_start_time" name="filter_start_time" value="<?= htmlspecialchars($filter_start_time) ?>" class="form-control" />
        </div>
        <div class="col-md-1">
            <label for="filter_end_time" class="form-label">End Time ≤</label>
            <input type="time" id="filter_end_time" name="filter_end_time" value="<?= htmlspecialchars($filter_end_time) ?>" class="form-control" />
        </div>
        <div class="col-md-12 mt-2">
            <button type="submit" class="btn btn-primary">Filter Bookings</button>
            <a href="bookingslotpage.php" class="btn btn-secondary">Reset</a>
        </div>
    </div>
</form>

<!-- Then display $booking_result in your bookings table -->
<table class="table table-striped table-bordered">
    <thead>
        <tr>
            <th>Booking ID</th>
            <th>User</th>
            <th>Centre</th>
            <th>Booking Date</th>
            <th>Start Time</th>
            <th>End Time</th>
            <th>Status</th>
            <th>Payment Status</th>
            <th>Amount Paid</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($booking_result && $booking_result->num_rows > 0): ?>
            <?php while ($booking = $booking_result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($booking['id']) ?></td>
                    <td><?= htmlspecialchars($booking['username'] ?? 'Unknown') ?></td>
                    <td><?= htmlspecialchars($booking['centre_name'] ?? 'Unknown') ?></td>
                    <td><?= htmlspecialchars(date('Y-m-d', strtotime($booking['booking_date']))) ?></td>
                    <td><?= htmlspecialchars($booking['start_time']) ?></td>
                    <td><?= htmlspecialchars($booking['end_time']) ?></td>
                    <td><?= htmlspecialchars(ucfirst($booking['status'])) ?></td>
                    <td><?= htmlspecialchars(ucfirst($booking['payment_status'])) ?></td>
                    <td><?= htmlspecialchars(number_format($booking['amount_paid'], 2)) ?></td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="9" class="text-center">No bookings found.</td></tr>
        <?php endif; ?>
    </tbody>
</table>
</div>

</body>
</html>
