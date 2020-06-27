<?php
//defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Pernil 181 md</title>

	<?php if (strpos(host(),'localhost:8888')===0) { ?>
       <link href="<?php echo base_url('images/favicon.jpg') ?>" rel="shortcut icon" type="image/x-icon" /> 
    <?php  }else{ ?> 
        <link href="<?php echo base_url('images/favicon_nuevo.jpg') ?>" rel="shortcut icon" type="image/x-icon" /> 
     <?php } ?> 

    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.11.2/css/all.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700&display=swap">

<link rel="stylesheet" href="<?php echo base_url() ?>/assets/mdb-pro/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>/assets/mdb-pro/css/mdb.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>/assets/mdb-pro/css/addons/datatables.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>/assets/mdb-pro/css/addons-pro/cards-extended.min.css">

<!-- <link rel="stylesheet" href="<?php echo base_url() ?>/css/bootstrap.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>/css/mdb.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>/css/addons/datatables.min.css">
<link rel="stylesheet" href="<?php echo base_url() ?>/css/addons-pro/cards-extended.min.css"> -->

<link rel="stylesheet" href="<?php echo base_url() ?>/css/<?php echo $style ?>">

</head>
<body>

<script type="text/javascript" src="<?php echo base_url() ?>assets/mdb-pro/js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/mdb-pro/js/popper.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/mdb-pro/js/bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>assets/mdb-pro/js/mdb.min.js"></script>

<!-- <script type="text/javascript" src="<?php echo base_url() ?>js/jquery.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/popper.min.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/bootstrap.js"></script>
<script type="text/javascript" src="<?php echo base_url() ?>js/mdb.min.js"></script> -->

<script src="<?php echo base_url() ?>assets/mdb-pro/js/addons/datatables.min.js"></script>



<!-- <script src="<?php echo base_url() ?>js/addons/datatables.min.js"></script> -->
<script src="<?php echo base_url() ?>/js/<?php echo $js ?>"></script>