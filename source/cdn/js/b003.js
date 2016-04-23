	console.log('This is a browser feature intended for developers.\n\nThe url will expire after 1000 clicks.\nBecause this is a beta software, the urls might expire earlier.\n\nHere are some commands that you can use:\nnegotiate() : Start the creation of a link.\nreset() : Reset the browser view\nencrypt(code) : Returns the encrypted url');
	var aespre = "";
	var aespost = "";
	function encrypt(code) {
		return sjcl.encrypt(aespre + code + aespost, $("#userlink").val());
	}
	function negotiate() {
		$("#th").html("Reset");
		$(".part").hide();
		$(".loader").show();
		$.post( "worker.php", { a: "b3p1", cr: $("#g-recaptcha-response").val() })
		.done(function( data ) {
			$(".l1").toggleClass("prg");
			if ( data != "" ) {
				$.post( "worker.php", { a: "b3p2", link: encrypt(data) })
				.done(function( ndata ) {
					$(".l2").toggleClass("prg");
					$(".loader").css({"font-size": "120%", "line-height": "25px", "padding-top": "100px", "height": "200px"});
					$(".loader div").html('<a href="https://yoursite/🚀/#' + data + '" target="_black">https://yoursite/🚀/#' + data);
				});
			} else $(".loader div").html('Error');
		});
	}
	function reset() {
		$("#th").html('🔗 Shortener <span style="font-size: 50%;">[beta 2]</span>');
		$(".loader").hide();
		$(".part").show();
		$("#userlink").val("Link");
		$(".loader div").html('🚀<span class="prg l1">🚀</span><span class="prg l2">🚀</span>');
		$(".loader").css({"font-size": "300%", "line-height": "300px", "padding-top": "0px", "height": "300px"});
		grecaptcha.reset();
	}