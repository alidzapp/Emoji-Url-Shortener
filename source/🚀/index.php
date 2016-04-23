<!DOCTYPE html>
<html>
<head>
<meta name=viewport content="width=device-width, initial-scale=1, user-scalable=no">
<title>Redirecting</title>
</head>
<body style="background-color: #000; color: #f00; text-align: center;">
<div style="padding: 5% 0;">
<div style="padding: 10% 0; font-size: 200%;">Redirecting...</div>
</div>
<script src=../cdn/js/jquery.min.js></script>
<script src=../cdn/js/sha512.min.js></script>
<script src=../cdn/js/sjcl.min.js></script>
<script>
	function fetch() {
		var aespre = "";
		var aespost = "";
		var shapre = "";
		var shapost = "";
		var shortlink = location.hash.substr(1);
		if (location.hash.substr(1).indexOf('%') != -1) shortlink = decodeURI(location.hash.substr(1));
		$.ajax({
			type: 'POST',
			url: "../beta/worker.php",
			data: {
				a: "b3f",
				shorturl: sha512(shapre + sha512(shortlink) + shapost)
			},
			success: function (data) {
				if (data == "exit" || data.length < 10) {
					alert("error :(");
				} else {
					var site = sjcl.decrypt(aespre + shortlink + aespost, data);
					if (site.indexOf('http://') === -1 && site.indexOf('https://') === -1) site = "http://" + site;
					window.location = site;
				}
			},
			error: function (XMLHttpRequest, textStatus, errorThrown) {
				alert("error :( - Check your connecion");
			}
		});
		return false;
	}
	fetch();
</script>
</body>
</html>