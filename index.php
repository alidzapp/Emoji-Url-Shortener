<?php
session_start();
$_SESSION["shortlink"] = "";
?>
<!DOCTYPE html>
<html>
<head>
<meta charset=utf-8>
<meta http-equiv=X-UA-Compatible content="IE=edge">
<meta name=viewport content="width=device-width, initial-scale=1, user-scalable=no">
<title>[🔬] 🔗 Shortener</title>
<meta name=theme-color content=#a10>
<meta name="keyword" content="Stefano, Vazzoler, url, shortener, short, link, rocket">
<link href=/cdn/css/u/b003.min.css rel=stylesheet>
</head>
<body>
<main>
<div id="form">
	<div class="spart shadowed" id="th" onclick="reset();" style="font-size: 160%;">🔗 Shortener <span style="font-size: 50%;">[beta 2]</span></div>
	<div class="loader">
		<div class="center" style="color: #0f0;">🚀<span class="prg l1">🚀</span><span class="prg l2">🚀</span></div>
	</div>
	<div class="part">
		<input id="userlink" value="Link" onfocus="if(this.value==this.defaultValue)this.value=''" onblur="if(this.value=='')this.value=this.defaultValue" maxlength="512" minlength="10" class="center form" type="text" autocomplete="off" required/>
	</div>
	<div class="part" style="padding-top: 8px; height: 92px;">
		<div style="padding-left: 8px;"><div class="g-recaptcha" data-theme="dark" data-sitekey="<!-- Insert your sitekey here! -->"></div></div>
	</div>
	<div class="part rocket shadowed" onclick="negotiate();">🚀</div>
	<div class="spart shadowed" onclick="window.open('/cdn/tos.txt', '', 'width=600,height=400'); return false;">
		<div class="center">
			TOS
		</div>
	</div>
</div>
</main>
<script src="https://www.google.com/recaptcha/api.js"></script>
<script src="/cdn/js/jquery.min.js"></script>
<script src=/cdn/js/sjcl.min.js></script>
<script src="/cdn/js/u/b003.min.js"></script>
</body>
</html>