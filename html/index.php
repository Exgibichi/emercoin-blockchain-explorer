<?php
session_start();
require_once __DIR__ . '/../tools/include.php';

if (!empty($_COOKIE["lang"])) {
	$lang=$_COOKIE["lang"];
	require("lang/".$lang.".php");
} else {
	$lang="en";
	setcookie("lang","en",time()+(3600*24*14), "/");
	require("lang/en.php");
}

date_default_timezone_set('UTC');
$include_file="home";
if (isset($_SERVER['REQUEST_URI'])) {
	$URI=explode('/',$_SERVER['REQUEST_URI']);
	if ($URI[1]=="api") {
		$include_file="api";
	}
	if ($URI[1]=="chain") {
		$include_file="chain";
	}
	if ($URI[1]=="stats") {
		$include_file="stats";
	}
	if ($URI[1]=="chart") {
		$include_file="chart";
	}
	if ($URI[1]=="block") {
		$include_file="block";
	}
	if ($URI[1]=="tx") {
		$include_file="tx";
	}
	if ($URI[1]=="address") {
		$include_file="address";
	}
	if ($URI[1]=="account") {
		$include_file="account";
	}
	if ($URI[1]=="top") {
		$include_file="top";
	}
	if ($URI[1]=="cointrace") {
		$include_file="cointrace";
	}
	if ($URI[1]=="tos") {
		$include_file="tos";
	}
	$searchinput="";
	if ($URI[1]=="search") {
		$include_file="search";
		if (isset($URI[2])) {
			$searchinput=$URI[2];
		}
	}
}

?>

<?php
if ($include_file=="api") {
	include ($include_file.".php");
	exit;
}
?>


<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
	<link rel="stylesheet" href="/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="/bootstrap/css/bootstrap-theme.min.css">
	<link rel="stylesheet" type="text/css" href="/css/jquery.dataTables.css">
	<link rel="stylesheet" type="text/css" href="/css/dataTables.colVis.css">
	<link rel="stylesheet" href="/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="/css/bootstrap-select.min.css">
	<link rel="stylesheet" href="/css/style.css">
	<script src="/js/jquery.min.js"></script>
	<script src="/js/jquery-ui.min.js"></script>
	<script src="/js/jquery.tablesorter.min.js"></script>
	<script src="/js/jquery.metadata.js"></script>
	<script src="/js/bootstrap-select.js"></script>
	<script src="/bootstrap/js/bootstrap.min.js"></script>
	<script src="/js/notify-custom.js" type="text/javascript"></script>
	<script src="/js/qrcode.min.js" type="text/javascript"></script>
	<script src="/js/highstock.js" type="text/javascript"></script>
	<script src="/js/highcharts.js" type="text/javascript"></script>
	<script src="/js/exporting.js" type="text/javascript"></script>
	<script type="text/javascript" language="javascript" src="/js/jquery.dataTables.min.js"></script>
	<script type="text/javascript" language="javascript" src="/js/dataTables.colVis.min.js"></script>
	<script type="text/javascript" language="javascript" src="/js/bootstrap.datatable.js"></script>
	<script type="text/javascript" language="javascript" src="/js/d3.js"></script>
	<script type="text/javascript" language="javascript" src="/js/d3.layout.js"></script>
	<style type="text/css">
		a:hover {
			cursor:pointer;
		}
	</style>
	<title>Neko Blockchain</title>
</head>

<body>
<!-- Fixed navbar -->
<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
		<a class="navbar-brand" style="padding:11px" href="/"><img src="/img/neko_header.png" height="28" ></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
				<li <?php if ($include_file=='chain') { echo 'class="active"'; } ?>><a href="/chain"><?php echo lang('CHAIN_CHAIN'); ?></a></li>
				<li><a href="/clients">Clients</a></li>
				<li <?php if ($include_file=='top') { echo 'class="active"'; } ?>><a href="/top"><?php echo lang('TOP_TOP'); ?></a></li>
			</ul>
		<form class="navbar-form navbar-left" role="search" action="javascript:search();">
			<div class="form-group ">
				<input type="text" id="search" class="form-control" placeholder="<?php echo lang('ADDRESS_TX'); ?>" value="<?php echo $searchinput; ?>">
			</div>
			<button type="submit" class="btn btn-default"><?php echo lang('SHOW_SHOW'); ?></button>
		</form>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>

	<script type="text/javascript">

	$("#logout").click(function() {
		var request = $.ajax({
			type: "GET",
			url: "/usrmgmt/logout.php"
		});
		request.done(function( response ) {
			window.location.href = '/wallet';
		});
	});

	function setLanguage(lang) {
		var request = $.ajax({
			type: "POST",
			url: "/lang/setlanguage.php",
			data: { lang: lang }
		});
		request.done(function( response ) {
			location.reload();
		});
	};

	function search() {
		window.location.href = '/search/'+$("#search").val();
	};
	</script>

	<!-- Beginn include -->
	<?php
		include ($include_file.".php");
	?>
</body>

</html>
