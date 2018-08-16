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

$room=new room();

$guest=new guest();


$WINDOW_NAME="About";

?>

<html>
<head>
<?php include('includes/meta-main.php'); ?>	
	<title><?php echo($WINDOW_NAME); ?></title>
</head>
<body>
<?php include('includes/sidebar.php'); //sidebar ?>
<?php include('includes/settings-topbar.php'); //topbar?>

<center>
	<div class="about-container" align="left">
		<h1 align="left">About Tesseract </h1>
		<h2 align="left">Hotel Management System</h2>				
		<p>
			<strong>Program Version:</strong> <span>1.0.0.1</span>
		</p>

		<p>
			Copyright &#169; 2016-2018 Clinton Nzedimma, Novacom Webs Nigeria. All rights reserved
			<br>
			Tesseract Hotel Managment System is registered under MIT license
		</p>

		<p>
			Credits:
			<ul style="font-size: 17px;">
				<li>Clinton Nzedimma - <small>Lead Developer </small></li>
				<li>Paul Princewill -  <small>Associate Lead Developer </small></li>
				<li>Bobby Nzedimma - <small>Systems Designer </small></li>
				<li>Nana Perfect - <small>Project Manager </small></li>
				<li>Oboko Emma -  <small>Associate Project Manager  </small></li>
				<li>Doye Solomon - <small> Associate Consultant </small></li>
				<li>Prince Ekemini Darlington - <small> Systems Consultant </small> </li>
			</ul>

			In Gratitude to:
			<ul style="font-size: 16px;">
				<li>Ejaita Okpako, <small>PhD</small></li>
				<li>Rita Ako, <small>PhD</small></li>
				<li>Joseph Jakpa <small>Croft Ecosystems</small></li>
				<li>Kelly Idehen <small>Linkorion Technology</small></li>
				<li>Fejiro Otokutu</li>
				<li>Apeh Stephen</li>
			</ul>

			
		</p>

		<p>
			This product uses the following components
			<br><br>
			<ul style="text-decoration: underline; font-size: 16px;">
				<li>PHPDesktop &#169; Czarek Tomczak</li>
				<li>SQLite, in public domain</li>
				<li>Chromium Embedded Framework licensed under the BSD 3-clause license.</li>
				<li>ChartJs Of MIT licence</li>
				<li>PHP Software licensed under the PHP License 3.0.1.</li>
				<li>JSON parser licensed under the BSD 2-clause license.</li>
				<li> Mongoose webserver revision 04fc209 licensed under the MIT license.</li>
			</ul>
		</p>

		<p>
THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND
CONTRIBUTORS "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES,
INCLUDING, BUT NOT LIMITED TO, THE IMPLIED WARRANTIES OF
MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR
CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT
NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES;
LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION)
HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN
CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE
OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS
SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
						
		</p>
	</div>
</center>

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
