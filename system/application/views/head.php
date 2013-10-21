<?php
 /*
 * Head
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>ShinOk</title>
<link href="<?= base_url(); ?>css/style.css" rel="stylesheet" type="text/css" />
<script src="<?= base_url(); ?>js/jquery-1.4.2.min.js" type="text/javascript" charset="utf-8"></script>
<script src="<?= base_url(); ?>js/jquery.cookie.js" type="text/javascript"></script>  
<?php if (!( empty($page) ) && ($page == "search")) : ?>
  <link href="<?= base_url(); ?>css/ui.jqgrid.css" rel="stylesheet" type="text/css" />
  <link href="<?= base_url(); ?>css/themes/redmond/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" charset="utf-8" />
  <script src="<?= base_url(); ?>js/i18n/grid.locale-ru.js" type="text/javascript" charset="utf-8"></script>
  <script src="<?= base_url(); ?>js/jquery.jqGrid.js" type="text/javascript"></script>

<?php endif; ?>
<?php if (!( empty($page) ) && ($page == "profile")) : ?>
  <link href="<?= base_url(); ?>css/themes/redmond/jquery-ui-1.8.4.custom.css" rel="stylesheet" type="text/css" charset="utf-8" />
  <script src="<?php echo base_url(); ?>js/jquery.form.js" type="text/javascript" ></script>
<?php endif; ?>
</head>