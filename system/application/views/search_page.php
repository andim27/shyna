<?php include('head.php'); ?>
<body>

<div class="container">
<?php include('header.php'); ?>

  <div class="<?=(($page=="search"?'sidebar1S':'sidebar1'));?>">

<?php include('search_params.php'); ?>
   
<?php include('search_more.php'); ?>
  <!-- end .sidebar1 --></div>

  <div class="contentS">
  
<?php //include('about.php'); ?>
<?php include('search_result.php'); ?>
<div id="vendor_info"></div>
<?php //include('vendor_address.php'); ?>
<?php //include('registration.php'); ?>
  <!-- end .content --></div>

  <div class="<?=(($page=="search"?'sidebar1Sright':'sidebar1'));?>">

<?php include('proposal_info.php'); ?>
<?php include('currency_control.php'); ?>
  


  <!-- end .sidebarS --></div>
  
  <div class="clear"></div>
<?php include('footer.php'); ?>
