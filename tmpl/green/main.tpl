<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="content-type" content="text/html; charset=utf-8" />
<title><?=$title?></title>
<meta name="keywords" content="<?=$meta_keys?>" />
<meta name="description" content="<?=$meta_desc?>" />
<link href="%adress%/tmpl/css/style.css" rel="stylesheet" type="text/css" media="screen" />
</head>

<body>

<center><div id='adminBar'>[script_adminBar]</div></center>
<div id="header">
	<div id="logo">
		[blocks_logo]
	</div>
	<div id="menu">
	[menus_main]
	</div>
</div>
<!-- start page -->
<div id="page">
	<!-- start sidebar1 -->
	<div id="sidebar1" class="sidebar">
		%sidebar_l%
	</div>
	<!-- end sidebar1 -->
	<!-- start content -->
	<div id="content">
		<?=$content?>
	</div>
	<!-- end content -->
	<!-- start sidebar2 -->
	<div id="sidebar2" class="sidebar">
		%sidebar_r%
	</div>
	<!-- end sidebar2 -->
	<div style="clear: both;">