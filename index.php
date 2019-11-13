<?php
require_once('includes/init.php');
$judul_page = 'SPK Zakat Fitrah';
require_once('template-parts/header.php');
?>

<div class="main-content-row">
	<div class="container clearfix">
		<div class="the-content">
	<br><br><br><br><br><br>
	<script language="JavaScript" type="text/javascript">

 var message = "Selamat Datang di Sistem Pendukung Keputusan Penentuan Penerima Zakat Fitrah di Mesjid Nur Hasanah :)";
 var count = 0;
 var i = 0;
 var gap = message.length;

 while (i < gap) { i++; message = " " + message; }

function statScroll() {
 document.scroll.msg.value = message.substring(count, message.length);
 count++;
 if (message.length == count) { count = 0; }
 window.setTimeout("statScroll()", 100);
 }
window.onload = statScroll;

//-->
</script>

<center>

<form name="scroll">
  <input type="text" name="msg" value=" " size="20" style=color:red;font-family:Arial;font-weight:bold;font-size:100px;>
</form>

</center>
	</div><!-- .container -->
	</div><!-- .main-content-row -->
</div>
<?php

require_once('template-parts/footer.php');?>
