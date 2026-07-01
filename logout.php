<?php 

require 'config/function.php';

if(isset($_SESSION['loggedIn']) || isset($_SESSION['pendingStaffLogin'])){
  logoutSession();
  unset($_SESSION['pendingStaffLogin']);
  unset($_SESSION['attendance_marked']);
  redirect('login.php', 'Logged Out Successfully.');
}

?>