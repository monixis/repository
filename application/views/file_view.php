<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="apple-touch-icon" href="http://library.marist.edu/images/jac-m.png"/>
    <link rel="shortcut icon" href="http://library.marist.edu/images/jac.png" />
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Honor's Thesis Repository</title>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
    <!-- Bootstrap core CSS -->
    <link href="http://library.marist.edu/css/bootstrap.css" rel="stylesheet">
    <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
    <link href="http://library.marist.edu/css/ie10-viewport-bug-workaround.css" rel="stylesheet">
    <link href="http://library.marist.edu/css/library.css" rel="stylesheet">
    <link href="http://library.marist.edu/css/menuStyle.css" rel="stylesheet">
    <link href="styles/main.css" rel="stylesheet">
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    <script type="text/javascript" src="http://library.marist.edu/js/libraryMenu.js"></script>
    <script type="text/javascript" src="http://library.marist.edu/crrs/js/jquery-ui.js"></script>
    <link rel="stylesheet" href="http://library.marist.edu/font-awesome/css/font-awesome.min.css">
    <link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
    
    <style>
		table {.table-file-information > tbody > tr {
			border-top: 1px solid rgb(221, 221, 221);
		}

		.table-file-information > tbody > tr:first-child {
			border-top: 0;
		}

		.table-file-information > tbody > tr > td {
			border-top: 0;
		}

		}

    </style>
    <?php
	foreach ($paperinfo as $paper)
		$title = $paper['title'];
		$date = $paper['updatedate'];
		$name = $paper['name'];
		$url = $paper['url'];
    ?>
</head>
<body>
<div id="headerContainer">
			<a href="http://library.marist.edu/" target="_self"> <div id="header"></div> </a>
		</div>
		<a class="menu-link" href="#menu"><img src="http://library.marist.edu/images/r-menu.png" style="width: 20px; margin-top: 4px;" /></a>
		<div id="menu">
			<div id="menuItems"></div>
		</div>
		<div id="miniMenu" style="width: 100%;border: 1px solid black; border-bottom: none;">

		</div>

		<!-- Main jumbotron for a primary marketing message or call to action -->
		<div id="main-container" class="container">
			<div class="jumbotron">
				<div class="container" style="margin-top: -36px;">
					<!-- Example row of columns -->
					<div class="row">
						<div class="col-md-12">
							<h2 style="text-align: center; margin: 30px; font-size: 40px;">Honor's Thesis Repository</h2>
								<div>
									 <table class="table table-file-information">
            							<thead>
            								<tr><h4>Details:</h4></tr>
            							</thead>
            							<tbody>
            								<tr>
                								<td class ="col-md-2">Title:</td><td> <?php echo $title ?></td>
            								</tr>
            								<tr>
                								<td class ="col-md-2" >Submited By:</td> <td><a href="<?php echo base_url("?c=repository&m=searchResultsByKeyWord&q=".$name);?>"><?php echo $name ?></a></td>
      										</tr>
            								<tr>
                								<td class ="col-md-2" >Submitted On:</td> <td><?php echo $date ?></td>
            								</tr>
            								<tr>
                								<td class ="col-md-2"> Associated Tags:</td>
                								<td>
                    								<?php
                    									foreach ($associatedTags as $associatedTag){?><a href="<?php echo base_url("?c=repository&m=searchResultsByTag&q=" . $associatedTag['tag']); ?>"> <?php echo $associatedTag['tag'] . " , "; ?> </a><?php  } ?>
                    							</td>
                    						</tr>
            							</tbody>
            						</table>
									<iframe src="<?php echo $url ?>" style="width:100%; height:700px;"></iframe>
								</div>
						</div>
					</div><!-- row -->
				</div><!-- container -->
			</div>
			<!-- jumbotron -->

		
		</div></br>
		<!-- main-container -->
		<div class="container">
			<p  class = "foot">
				James A. Cannavino Library, 3399 North Road, Poughkeepsie, NY 12601; 845.575.3106
				<br />
				&#169; Copyright 2007-2016 Marist College. All Rights Reserved.

				<a href="http://www.marist.edu/disclaimers.html" target="_blank" >Disclaimers</a> | <a href="http://www.marist.edu/privacy.html" target="_blank" >Privacy Policy</a> | <a href="http://library.marist.edu/ack.html?iframe=true&width=50%&height=62%" rel="prettyphoto[iframes]">Acknowledgements</a>
			</p>
		</div>
</body>
</html>

