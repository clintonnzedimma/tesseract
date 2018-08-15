<?php
ob_start();
session_start();
 ?>
<!DOCTYPE html>
<?php 
include $_SERVER['DOCUMENT_ROOT']."/engine/functions/database/class.sqlite_DB.php";
include "engine/functions/database/class.mainDB.php";
include "engine/functions/core/class.stats.php";
include "engine/functions/core/init.php";
include "engine/functions/core/errors.php";
include "engine/functions/core/class.time_object.php";
include "engine/functions/core/class.admin.php";
include "engine/functions/core/class.config.php";
include "engine/functions/core/class.room.php";
include "engine/functions/core/class.guest.php";
include "engine/functions/core/class.guest_singleton.php";

$admin= new admin();
$admin->protect_page();

$room=new room();

$guest=new guest();


$WINDOW_NAME="Settings";

?>

<html>
<head>
<?php include('includes/meta-main.php'); ?>	
	<title><?php echo($WINDOW_NAME); ?></title>
</head>
<body>
<?php include('includes/sidebar.php'); //sidebar ?>
<?php include('includes/settings-topbar.php'); //topbar?>

<?php 
if (isset($_GET['security'])) {
	include("includes/specifics/settings_security.php");
}

if (isset($_GET['globals'])) {
	include("includes/specifics/settings_globals.php");
}



?>

<script src="/js/custom/main-lib.1.0.0.js"></script>
<!--Central Javascript Library -->
<script type="text/javascript">

</script>


<script type="text/javascript">
    	// This javascript section activates the AOS library (aos.js) 
      AOS.init({
        easing: 'ease-in-out-sine'
      });
</script>
</body>
</html>