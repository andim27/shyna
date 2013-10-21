<?php include('head.php'); ?>
<body>

<div class="container">
<?php include('header.php'); ?>


  <div class="sidebar1">
  
  
<?php include('search_params.php'); ?>
   
<?php include('search_more.php'); ?>


  <!-- end .sidebar1 --></div>
  
  
  <div class="content">
  

<?php //include('search_result.php'); ?>

<?php include('contact_info.php'); ?>
<?php include('feedback.php'); ?>
<?php if (empty($user_id)): ?>
<?php include('registration.php'); ?>
<?php endif; ?>
 <?php //include('about.php'); ?>
  <!-- end .content --></div>

  <div class="sidebar2">
<?php //include('proposal_info.php'); ?>


<?php include('enter.php'); ?>

  <!-- end .sidebar2 --></div>

  <div class="clear"></div>
<?php include('footer.php'); ?>
