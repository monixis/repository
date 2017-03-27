<link rel="stylesheet" type="text/css" href="./styles/main.css" />
<meta charset="utf-8">

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>

	<h2>Results:</h2><p style="font-size: 10pt;">Currently papers under 'Limit to Marist Users' license are not available for searching.</p>
		<ol id="list">
			<?php
				foreach ($result as $row) {
					$id = $row -> id;
					$title = $row -> title;
					$name = $row -> name;
					$date = $row -> date;
					$abstract = $row ->abstract;
					$license = $row -> license;
					$display = $row -> display;
			?>
				<li class="results">
					<a href="<?php echo base_url("?c=repository&m=fileInfo&id=".$id)?>" target="_blank"><?php echo $title; ?></a></br>
					<a href="<?php echo base_url("?c=repository&m=searchResultsByKeyWord&q=".$name)?>" style="font-size: 12pt;"><span style="color: #b31b1b;font-weight:bold; ">Author: </span><?php echo $name; ?></a></br>
					<p style="font-size: 12pt; margin-top: -10px;"> <span style="color: #b31b1b;font-weight:bold; ">Submitted On: </span><?php echo $date?></p>
			     	<p class='abstract' style="font-size: 12pt;"> <span style="color: #b31b1b;font-weight:bold; ">Abstract: </span><?php echo $abstract?></p><a data-toggle="modal" href="#<?php echo $id?>">more</a></div></br>
					<p style="font-size: 8pt; margin-top: -10px;"><?php echo $display?> </p>
				</li>

					<div class="modal fade" id="<?php echo $id?>" role="dialog">
						<div class="modal-dialog modal-lg">

							<!-- Modal content-->
							<div class="modal-content">
								<div class="modal-header">
									<button type="button" class="close" data-dismiss="modal">&times;</button>
									<h4 align="center" class="modal-title"><?php echo $title; ?></h4>
								</div>
								<div class="modal-body">
									<h4><span style="color: #b31b1b;font-weight:bold; ">Abstract:</span><?php echo $abstract; ?></h4>
								</div>

							</div>

						</div>
					</div>


					<!--<div id="<!--?php /*echo $id; */?>" class="modalDialog">
						<div>
							<a href="#close" title="Close" class="close">X</a>
							<h4 align="center"><!--?php /*echo $title; */?></h4>
							<p><span style="color: #b31b1b;font-weight:bold; ">Abstract:</span> </p></br>
							<p><!--?php /*echo $abstract*/?></p>
						</div>
					</div>-->

			<?php
				}
			?>	
		</ol></br>



<style>
	.abstract{
		width:100%;
		height: 48px;
		line-height: 1em;
		margin-bottom: 50px;
		overflow:hidden;
	}

</style>
			  