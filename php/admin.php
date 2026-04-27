<?php
// ====================================
// TorqueTrail - admin.php
// View all bookings from bookings.txt
// ====================================

$bookingsFile = __DIR__ . '/../data/bookings.txt';
$bookings = [];

if (file_exists($bookingsFile)) {
    $lines = file($bookingsFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        // Parse each field from the pipe-separated line
        $record = [];
        $parts = explode(' | ', $line);
        foreach ($parts as $part) {
            if (strpos($part, ': ') !== false) {
                list($key, $val) = explode(': ', $part, 2);
                $record[trim($key)] = trim($val);
            } else {
                // First element is the timestamp without key
                $record['Timestamp'] = trim($part);
            }
        }
        $bookings[] = $record;
    }
    // Latest bookings first
    $bookings = array_reverse($bookings);
}

$totalBookings = count($bookings);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TorqueTrail - Admin Panel</title>
  <link rel="stylesheet" href="../css/style.css" />
  <style>
    .stats-row { display:flex; gap:20px; flex-wrap:wrap; margin-bottom:30px; }
    .stat-box  { background:#1a1a2e; color:white; border-radius:10px; padding:20px 30px; min-width:160px; text-align:center; }
    .stat-box .num { font-size:2.5rem; color:#f5a623; font-weight:700; }
    .stat-box .lbl { font-size:0.9rem; color:#aaa; margin-top:4px; }
    .admin-table { width:100%; border-collapse:collapse; background:white; border-radius:12px; overflow:hidden; box-shadow:0 4px 15px rgba(0,0,0,0.08); font-size:0.88rem; }
    .admin-table thead { background:#1a1a2e; color:#f5a623; }
    .admin-table th, .admin-table td { padding:12px 14px; text-align:center; border-bottom:1px solid #eee; }
    .admin-table tbody tr:hover { background:#fdf8ef; }
    .no-data { text-align:center; color:#888; padding:50px; font-size:1.1rem; }
    .search-bar { margin-bottom:20px; }
    .search-bar input { padding:10px 16px; width:100%; max-width:400px; border:2px solid #ddd; border-radius:8px; font-size:1rem; }
    .search-bar input:focus { outline:none; border-color:#f5a623; }
  </style>
</head>
<body>

  <!-- Navigation -->
  <nav class="navbar">
    <div class="logo"><span class="logo-mark">TT</span> TorqueTrail</div>
    <ul class="nav-links">
      <li><a href="../index.html">Home</a></li>
      <li><a href="../cars.html">Cars</a></li>
      <li><a href="../booking.html">Book Now</a></li>
      <li><a href="../contact.html">Contact</a></li>
      <li><a href="admin.php" class="active">Admin</a></li>
    </ul>
  </nav>

  <!-- Page Header -->
  <div class="page-header">
    <h1>Admin Panel</h1>
    <p>Manage and view all car bookings</p>
  </div>

  <div class="admin-section">

    <!-- Stats -->
    <div class="stats-row">
      <div class="stat-box">
        <div class="num"><?php echo $totalBookings; ?></div>
        <div class="lbl">Total Bookings</div>
      </div>
      <div class="stat-box">
        <div class="num"><?php echo $totalBookings > 0 ? $totalBookings : 0; ?></div>
        <div class="lbl">Active Bookings</div>
      </div>
      <div class="stat-box">
        <div class="num">8</div>
        <div class="lbl">Cars Available</div>
      </div>
    </div>

    <!-- Bookings Section -->
    <h2>All Bookings</h2>

    <?php if ($totalBookings > 0): ?>

      <!-- Search -->
      <div class="search-bar">
        <input type="text" id="searchInput" onkeyup="searchTable()" placeholder="Search by name, car, booking ID..." />
      </div>

      <table class="admin-table" id="bookingTable">
        <thead>
          <tr>
            <th>#</th>
            <th>Booking ID</th>
            <th>Date &amp; Time</th>
            <th>Name</th>
            <th>Email</th>
            <th>Phone</th>
            <th>Car</th>
            <th>Pickup</th>
            <th>Return</th>
            <th>Days</th>
            <th>Total Cost</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($bookings as $index => $b): ?>
          <tr>
            <td><?php echo $index + 1; ?></td>
            <td><strong><?php echo htmlspecialchars($b['BookingID'] ?? 'N/A'); ?></strong></td>
            <td><?php echo htmlspecialchars($b['Timestamp'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($b['Name'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($b['Email'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($b['Phone'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($b['Car'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($b['Pickup'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($b['Return'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($b['Days'] ?? 'N/A'); ?></td>
            <td><?php echo htmlspecialchars($b['Total'] ?? 'N/A'); ?></td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>

    <?php else: ?>
      <div class="no-data">
        <p>📋 No bookings found yet.</p>
        <p style="margin-top:10px;font-size:0.9rem;">Bookings will appear here after customers submit the <a href="../booking.html" style="color:#f5a623;">booking form</a>.</p>
      </div>
    <?php endif; ?>

    <div style="margin-top:30px;">
      <a href="../booking.html" class="btn">+ New Booking</a>
    </div>

  </div>

  <!-- Footer -->
  <footer class="footer">
    <p>&copy; 2026 TorqueTrail. All rights reserved.</p>
  </footer>

  <script>
    // Simple table search
    function searchTable() {
      var input = document.getElementById("searchInput").value.toLowerCase();
      var rows  = document.querySelectorAll("#bookingTable tbody tr");
      rows.forEach(function(row) {
        var text = row.textContent.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
      });
    }
  </script>

</body>
</html>
