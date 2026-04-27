<?php
// ====================================
// TorqueTrail - process.php
// Handles both booking and contact forms
// ====================================

// Determine form type
$formType = isset($_POST['form_type']) ? $_POST['form_type'] : 'booking';

if ($formType === 'contact') {

    // ---- Contact Form Processing ----
    $cname   = htmlspecialchars(trim($_POST['cname']   ?? ''));
    $cemail  = htmlspecialchars(trim($_POST['cemail']  ?? ''));
    $subject = htmlspecialchars(trim($_POST['subject'] ?? 'No Subject'));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));

    // Basic validation
    if (empty($cname) || empty($cemail) || empty($message)) {
        die("<h2 style='color:red;text-align:center;margin-top:50px;'>All required fields are missing. <a href='../contact.html'>Go Back</a></h2>");
    }

    // Save to contact_messages.txt
    $timestamp = date("Y-m-d H:i:s");
    $entry = "$timestamp | Name: $cname | Email: $cemail | Subject: $subject | Message: $message\n";
    $dataFile = __DIR__ . '/../data/contact_messages.txt';
    file_put_contents($dataFile, $entry, FILE_APPEND | LOCK_EX);

    // Display confirmation
    $rootPath = '../';
    $pageTitle = "Message Sent!";
    $icon = "✉️";
    $heading = "Message Sent Successfully!";
    $headingColor = "#17a2b8";
    $details = [
        "Name"    => $cname,
        "Email"   => $cemail,
        "Subject" => $subject,
        "Message" => $message,
    ];

} else {

    // ---- Booking Form Processing ----
    $name    = htmlspecialchars(trim($_POST['name']    ?? ''));
    $email   = htmlspecialchars(trim($_POST['email']   ?? ''));
    $phone   = htmlspecialchars(trim($_POST['phone']   ?? ''));
    $car     = htmlspecialchars(trim($_POST['car']     ?? ''));
    $pickup  = htmlspecialchars(trim($_POST['pickup']  ?? ''));
    $returnD = htmlspecialchars(trim($_POST['return']  ?? ''));
    $address = htmlspecialchars(trim($_POST['address'] ?? 'Not provided'));

    // Basic validation
    if (empty($name) || empty($email) || empty($phone) || empty($car) || empty($pickup) || empty($returnD)) {
        die("<h2 style='color:red;text-align:center;margin-top:50px;'>All required fields are missing. <a href='../booking.html'>Go Back</a></h2>");
    }

    // Calculate rental days
    $start = new DateTime($pickup);
    $end   = new DateTime($returnD);
    $diff  = $start->diff($end);
    $days  = $diff->days;

    // Get price per day from car option string
    $priceTag = '';
    if (preg_match('/₹([\d,]+)\/day/', $car, $match)) {
        $pricePerDay = (int) str_replace(',', '', $match[1]);
        $totalPrice  = $pricePerDay * $days;
        $priceTag    = "₹" . number_format($pricePerDay) . "/day x $days days = ₹" . number_format($totalPrice);
    } else {
        $totalPrice = 0;
        $priceTag   = "See price list";
    }

    // Generate Booking ID
    $bookingID = "TT" . strtoupper(substr(md5(time() . $phone), 0, 6));

    // Save to bookings.txt
    $timestamp = date("Y-m-d H:i:s");
    $entry = "$timestamp | BookingID: $bookingID | Name: $name | Email: $email | Phone: $phone | Car: $car | Pickup: $pickup | Return: $returnD | Days: $days | Total: ₹$totalPrice | Address: $address\n";
    $dataFile = __DIR__ . '/../data/bookings.txt';
    file_put_contents($dataFile, $entry, FILE_APPEND | LOCK_EX);

    // Display confirmation
    $rootPath = '../';
    $pageTitle = "Booking Confirmed!";
    $icon = "✅";
    $heading = "Booking Confirmed!";
    $headingColor = "#28a745";
    $details = [
        "Booking ID"    => "<strong style='color:#f5a623'>$bookingID</strong>",
        "Name"          => $name,
        "Email"         => $email,
        "Phone"         => $phone,
        "Car Selected"  => $car,
        "Pickup Date"   => $pickup,
        "Return Date"   => $returnD,
        "Rental Days"   => "$days day(s)",
        "Total Cost"    => $priceTag,
        "Pickup Address"=> $address,
    ];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>TorqueTrail - <?php echo $pageTitle; ?></title>
  <link rel="stylesheet" href="<?php echo $rootPath; ?>css/style.css" />
</head>
<body>

  <!-- Navigation -->
  <nav class="navbar">
    <div class="logo"><span class="logo-mark">TT</span> TorqueTrail</div>
    <ul class="nav-links">
      <li><a href="<?php echo $rootPath; ?>index.html">Home</a></li>
      <li><a href="<?php echo $rootPath; ?>cars.html">Cars</a></li>
      <li><a href="<?php echo $rootPath; ?>booking.html">Book Now</a></li>
      <li><a href="<?php echo $rootPath; ?>contact.html">Contact</a></li>
      <li><a href="admin.php">Admin</a></li>
    </ul>
  </nav>

  <!-- Confirmation Box -->
  <div class="confirm-box">
    <div style="font-size:3rem; margin-bottom:10px;"><?php echo $icon; ?></div>
    <h2 style="color:<?php echo $headingColor; ?>;"><?php echo $heading; ?></h2>
    <p style="color:#666; margin-bottom:20px;">Here are your submission details:</p>

    <table>
      <?php foreach ($details as $label => $value): ?>
      <tr>
        <td><?php echo htmlspecialchars($label); ?></td>
        <td><?php echo $value; ?></td>
      </tr>
      <?php endforeach; ?>
    </table>

    <div style="margin-top:30px; display:flex; gap:15px; justify-content:center; flex-wrap:wrap;">
      <a href="<?php echo $rootPath; ?>index.html" class="btn">Back to Home</a>
      <a href="<?php echo $rootPath; ?>booking.html" class="btn" style="background:#1a1a2e;color:#f5a623;">New Booking</a>
    </div>
  </div>

  <!-- Footer -->
  <footer class="footer">
    <p>&copy; 2026 TorqueTrail. All rights reserved.</p>
  </footer>

</body>
</html>
