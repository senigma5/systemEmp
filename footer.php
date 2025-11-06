<footer style="background-color:#001F54; color:white; padding:40px 0; font-family:Arial, sans-serif;">
  <div style="max-width:1100px; margin:auto; display:flex; justify-content:space-between; flex-wrap:wrap;">
    
    <!-- Logo & Company Info -->
    <div style="flex:1; min-width:280px;">
      <img src="images/logo.png" alt="CCS Logo" style="width:200px;">
      <p style="margin-top:15px; font-size:15px; line-height:1.6;">
        We provide a unique combination of on-demand, time-sensitive, door-to-door delivery
        services within Nairobi and its environs and across the entire country.
        We’re your Lastmile partner.
      </p>
    </div>

    <!-- Links -->
    <div style="flex:1; min-width:200px;">
      <h3 style="font-weight:bold;">Pages</h3>
      <ul style="list-style:none; padding:0; margin-top:10px;">
        <li><a href="index.php" style="color:white; text-decoration:none;">› Home</a></li>
        <li><a href="services.php" style="color:white; text-decoration:none;">› Services</a></li>
        <li><a href="contact.php" style="color:white; text-decoration:none;">› Contact</a></li>
        <li><a href="track_order.php" style="color:white; text-decoration:none;">› Track Your Order</a></li>
        <?php if(isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
          <li><a href="admin_dashboard.php" style="color:white; text-decoration:none;">› Admin Dashboard</a></li>
        <?php elseif(isset($_SESSION['role']) && $_SESSION['role'] === 'employee'): ?>
          <li><a href="employee_dashboard.php" style="color:white; text-decoration:none;">› Employee Dashboard</a></li>
        <?php endif; ?>
        <li><a href="logout.php" style="color:white; text-decoration:none;">› Logout</a></li>
      </ul>
    </div>

    <!-- Working Hours -->
    <div style="flex:1; min-width:200px;">
      <h3 style="font-weight:bold;">Working Hours</h3>
      <p style="margin-top:10px;">Monday – Sunday: 8:00 – 7:00</p>
    </div>

  </div>
  <p style="text-align:center; margin-top:30px; font-size:14px; color:#ccc;">
    © <?php echo date("Y"); ?> Convenient Courier Services Limited. All Rights Reserved.
  </p>
</footer>
