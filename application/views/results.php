<link rel="stylesheet" type="text/css" href="./styles/main.css" />
	<h2>Results:</h2>
		<ol id="list">
			<?php
				foreach ($result as $row) {
					$id = $row -> id;
					$title = $row -> title;
					$name = $row -> name;
					$date = $row -> date;
			?>
				<li class="results">
					<a href="<?php echo base_url("?c=repository&m=fileInfo&id=".$id)?>" target="_blank"><?php echo $title; ?></a></br>
					<a href="<?php echo base_url("?c=repository&m=searchResultsByKeyWord&q=".$name)?>" style="font-size: 12pt;"><?php echo $name; ?></a></br>
					<p style="font-size: 12pt; margin-top: -10px;">Updated On:"<?php echo $date?>"</p>
				</li>
			
			<?php
				}
			?>	
		</ol></br>
			  