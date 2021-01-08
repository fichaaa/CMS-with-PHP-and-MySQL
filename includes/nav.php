<ul class="nav">
  <li class="nav-item">
    <a class="nav-link" href="/">Home</a>
  </li>
  <?php if(!Auth::isLoggedIn()): ?>
    <li class="nav-item">
      <a class="nav-link" href="/login.php">Login</a>
    </li>
  <?php else: ?>
    <li class="nav-item">
      <a class="nav-link" href="/admin/index.php">Admin</a>
    </li>
    <li class="nav-item">
      <a class="nav-link" href="/logout.php">Logout</a>
    </li>
  <?php endif; ?>
</ul>