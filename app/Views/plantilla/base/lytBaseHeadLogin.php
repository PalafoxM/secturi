<!DOCTYPE html>
<!-- saved from url=(0040)https://matthew.wagerfield.com/parallax/ -->
<html class="mouse"><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

	

	<title>SUSI</title>

	<!-- Behavioral Meta Data -->
	<meta name="apple-mobile-web-app-capable" content="yes">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

	<!-- Core Meta Data -->
	<meta name="author" content="Matthew Wagerfield">
	<meta name="description" content="Simple, lightweight Parallax Engine that reacts to the orientation of a smart device">
	<meta name="keywords" content="parallax,javascript,jquery,zepto,plugin">

	<!-- Open Graph Meta Data -->
	<meta property="og:description" content="Simple, lightweight Parallax Engine that reacts to the orientation of a smart device">
	<meta property="og:image" content="http://wagerfield.github.io/parallax/assets/images/thumbnail.png">
	<meta property="og:site_name" content="parallax.js">
	<meta property="og:title" content="parallax.js">
	<meta property="og:type" content="website">
	<meta property="og:url" content="http://wagerfield.github.io/parallax/index.html">

	<!-- Twitter Card Meta Data -->
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:site" content="http://wagerfield.github.io/parallax/index.html">
	<meta name="twitter:creator" content="@wagerfield">
	<meta name="twitter:title" content="parallax.js">
	<meta name="twitter:description" content="Simple, lightweight Parallax Engine that reacts to the orientation of a smart device">
	<meta name="twitter:image" content="http://wagerfield.github.io/parallax/assets/images/thumbnail.png">

	<!-- Humans -->
	<link rel="author" href="https://matthew.wagerfield.com/parallax/humans.txt">

	<!-- Styles -->
	<link rel="stylesheet" type="text/css" href="<?= base_url('parallax.js_files/') ?>styles.css">

	<!-- Favicon -->
	<link rel="shortcut icon" href="https://matthew.wagerfield.com/favicon.ico" type="image/x-icon">
	<link rel="shortcut icon" href="https://matthew.wagerfield.com/favicon.png" type="image/png">
	<link rel="shortcut icon" href="http://wagerfield.github.io/parallax/favicon.png" type="image/png">

	<!-- Apple Touch Icons -->
	<link rel="apple-touch-icon-precomposed" sizes="144x144" href="https://matthew.wagerfield.com/parallax/apple-touch-icon-144x144-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href="https://matthew.wagerfield.com/parallax/apple-touch-icon-114x114-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href="https://matthew.wagerfield.com/parallax/apple-touch-icon-72x72-precomposed.png">
	<link rel="apple-touch-icon-precomposed" sizes="57x57" href="https://matthew.wagerfield.com/parallax/apple-touch-icon-57x57-precomposed.png">

	<!-- Apple Startup Images -->
	<link rel="apple-touch-startup-image" href="https://matthew.wagerfield.com/parallax/apple-touch-startup-image-320x460.png" media="(device-width: 320px)">
	<link rel="apple-touch-startup-image" href="https://matthew.wagerfield.com/parallax/apple-touch-startup-image-640x920.png" media="(device-width: 320px) and (-webkit-device-pixel-ratio: 2)">
	<link rel="apple-touch-startup-image" href="https://matthew.wagerfield.com/parallax/apple-touch-startup-image-768x1004.png" media="(device-width: 768px) and (orientation: portrait)">
	<link rel="apple-touch-startup-image" href="https://matthew.wagerfield.com/parallax/apple-touch-startup-image-748x1024.png" media="(device-width: 768px) and (orientation: landscape)">
	<link rel="apple-touch-startup-image" href="https://matthew.wagerfield.com/parallax/apple-touch-startup-image-1536x2008.png" media="(device-width: 1536px) and (orientation: portrait) and (-webkit-device-pixel-ratio: 2)">
	<link rel="apple-touch-startup-image" href="https://matthew.wagerfield.com/parallax/apple-touch-startup-image-2048x1496.png" media="(device-width: 1536px) and (orientation: landscape) and (-webkit-device-pixel-ratio: 2)">

	<!-- Google Analytics -->
	<script src="<?= base_url('parallax.js_files/') ?>all.js.descarga" async="" crossorigin="anonymous"></script><script id="facebook-jssdk" src="<?= base_url('parallax.js_files/') ?>all(1).js.descarga"></script><script id="twitter-wjs" src="<?= base_url('parallax.js_files/') ?>widgets.js.descarga"></script><script async="" src="<?= base_url('parallax.js_files/') ?>analytics.js.descarga"></script><script>
	(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
	(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
	m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
	})(window,document,'script','//www.google-analytics.com/analytics.js','ga');
	ga('create', 'UA-39594063-4', 'wagerfield.github.io');
	ga('send', 'pageview');
	</script>

<style type="text/css" data-fbcssmodules="css:fb.css.base css:fb.css.dialog css:fb.css.iframewidget">.fb_hidden{position:absolute;top:-10000px;z-index:10001}.fb_reposition{overflow:hidden;position:relative}.fb_invisible{display:none}.fb_reset{background:none;border:0px;border-spacing:0;color:#000;cursor:auto;direction:ltr;font-family:lucida grande,tahoma,verdana,arial,sans-serif;font-size:11px;font-style:normal;font-variant:normal;font-weight:400;letter-spacing:normal;line-height:1;margin:0;overflow:visible;padding:0;text-align:left;text-decoration:none;text-indent:0;text-shadow:none;text-transform:none;visibility:visible;white-space:normal;word-spacing:normal}.fb_reset>div{overflow:hidden}@keyframes fb_transform{0%{opacity:0;transform:scale(.95)}to{opacity:1;transform:scale(1)}}.fb_animate{animation:fb_transform .3s forwards}

.fb_hidden{position:absolute;top:-10000px;z-index:10001}.fb_reposition{overflow:hidden;position:relative}.fb_invisible{display:none}.fb_reset{background:none;border:0px;border-spacing:0;color:#000;cursor:auto;direction:ltr;font-family:lucida grande,tahoma,verdana,arial,sans-serif;font-size:11px;font-style:normal;font-variant:normal;font-weight:400;letter-spacing:normal;line-height:1;margin:0;overflow:visible;padding:0;text-align:left;text-decoration:none;text-indent:0;text-shadow:none;text-transform:none;visibility:visible;white-space:normal;word-spacing:normal}.fb_reset>div{overflow:hidden}@keyframes fb_transform{0%{opacity:0;transform:scale(.95)}to{opacity:1;transform:scale(1)}}.fb_animate{animation:fb_transform .3s forwards}

.fb_dialog{background:#525252b3;position:absolute;top:-10000px;z-index:10001}.fb_dialog_advanced{border-radius:8px;padding:10px}.fb_dialog_content{background:#fff;color:#373737}.fb_dialog_close_icon{background:url(https://connect.facebook.net/rsrc.php/v4/yq/r/IE9JII6Z1Ys.png) no-repeat scroll 0 0 transparent;cursor:pointer;display:block;height:15px;position:absolute;right:18px;top:17px;width:15px}.fb_dialog_mobile .fb_dialog_close_icon{left:5px;right:auto;top:5px}.fb_dialog_padding{background-color:transparent;position:absolute;width:1px;z-index:-1}.fb_dialog_close_icon:hover{background:url(https://connect.facebook.net/rsrc.php/v4/yq/r/IE9JII6Z1Ys.png) no-repeat scroll 0 -15px transparent}.fb_dialog_close_icon:active{background:url(https://connect.facebook.net/rsrc.php/v4/yq/r/IE9JII6Z1Ys.png) no-repeat scroll 0 -30px transparent}.fb_dialog_iframe{line-height:0}.fb_dialog_content .dialog_title{background:#6d84b4;border:1px solid #365899;color:#fff;font-size:14px;font-weight:700;margin:0}.fb_dialog_content .dialog_title>span{background:url(https://connect.facebook.net/rsrc.php/v4/yd/r/Cou7n-nqK52.gif) no-repeat 5px 50%;float:left;padding:5px 0 7px 26px}body.fb_hidden{height:100%;left:0;margin:0;overflow:visible;position:absolute;top:-10000px;transform:none;width:100%}.fb_dialog.fb_dialog_mobile.loading{background:url(https://connect.facebook.net/rsrc.php/v4/ya/r/3rhSv5V8j3o.gif) #fff no-repeat 50% 50%;min-height:100%;min-width:100%;overflow:hidden;position:absolute;top:0;z-index:10001}.fb_dialog.fb_dialog_mobile.loading.centered{background:none;height:auto;min-height:initial;min-width:initial;width:auto}.fb_dialog.fb_dialog_mobile.loading.centered #fb_dialog_loader_spinner{width:100%}.fb_dialog.fb_dialog_mobile.loading.centered .fb_dialog_content{background:none}.loading.centered #fb_dialog_loader_close{clear:both;color:#fff;display:block;font-size:18px;padding-top:20px}#fb-root #fb_dialog_ipad_overlay{background:#0006;inset:0;min-height:100%;position:absolute;width:100%;z-index:10000}#fb-root #fb_dialog_ipad_overlay.hidden{display:none}.fb_dialog.fb_dialog_mobile.loading iframe{visibility:hidden}.fb_dialog_mobile .fb_dialog_iframe{position:sticky;top:0}.fb_dialog_content .dialog_header{background:linear-gradient(from(#738aba),to(#2c4987));border-bottom:1px solid;border-color:#043b87;box-shadow:#fff 0 1px 1px -1px inset;color:#fff;font:700 14px Helvetica,sans-serif;text-overflow:ellipsis;text-shadow:rgba(0,30,84,.296875) 0px -1px 0px;vertical-align:middle;white-space:nowrap}.fb_dialog_content .dialog_header table{height:43px;width:100%}.fb_dialog_content .dialog_header td.header_left{font-size:12px;padding-left:5px;vertical-align:middle;width:60px}.fb_dialog_content .dialog_header td.header_right{font-size:12px;padding-right:5px;vertical-align:middle;width:60px}.fb_dialog_content .touchable_button{background:linear-gradient(from(#4267B2),to(#2a4887));background-clip:padding-box;border:1px solid #29487d;border-radius:3px;display:inline-block;line-height:18px;margin-top:3px;max-width:85px;padding:4px 12px;position:relative}.fb_dialog_content .dialog_header .touchable_button input{background:none;border:none;color:#fff;font:700 12px Helvetica,sans-serif;margin:2px -12px;padding:2px 6px 3px;text-shadow:rgba(0,30,84,.296875) 0px -1px 0px}.fb_dialog_content .dialog_header .header_center{color:#fff;font-size:16px;font-weight:700;line-height:18px;text-align:center;vertical-align:middle}.fb_dialog_content .dialog_content{background:url(https://connect.facebook.net/rsrc.php/v4/y9/r/jKEcVPZFk-2.gif) no-repeat 50% 50%;border:1px solid #4A4A4A;border-bottom:0;border-top:0;height:150px}.fb_dialog_content .dialog_footer{background:#f5f6f7;border:1px solid #4A4A4A;border-top-color:#ccc;height:40px}#fb_dialog_loader_close{float:left}.fb_dialog.fb_dialog_mobile .fb_dialog_close_icon{visibility:hidden}#fb_dialog_loader_spinner{animation:rotateSpinner 1.2s linear infinite;background-color:transparent;background-image:url(https://connect.facebook.net/rsrc.php/v4/y2/r/onuUJj0tCqE.png);background-position:50% 50%;background-repeat:no-repeat;height:24px;width:24px}@keyframes rotateSpinner{0%{transform:rotate(0)}to{transform:rotate(360deg)}}

.fb_iframe_widget{display:inline-block;position:relative}.fb_iframe_widget span{display:inline-block;position:relative;text-align:justify}.fb_iframe_widget iframe{position:absolute}.fb_iframe_widget_fluid_desktop,.fb_iframe_widget_fluid_desktop span,.fb_iframe_widget_fluid_desktop iframe{max-width:100%}.fb_iframe_widget_fluid_desktop iframe{min-width:220px;position:relative}.fb_iframe_widget_lift{z-index:1}.fb_iframe_widget_fluid{display:inline}.fb_iframe_widget_fluid span{width:100%}

/* Custom cursor */
body, html {
    cursor: url('<?= base_url("assets/raton1_32.png") ?>') 0 0, auto !important;
}
/* Ensure buttons and links still show pointer but overriding might be needed if user wants raton everywhere */
a, button, .btn-google {
    cursor: url('<?= base_url("assets/raton1_32.png") ?>') 0 0, pointer !important;
}
</style><script charset="utf-8" src="<?= base_url('parallax.js_files/') ?>button.856debeac157d9669cf51e73a08fbc93.js.descarga"></script></head>
