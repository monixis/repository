<link rel="stylesheet" type="text/css" href="./styles/main.css" />
	<h2>Results:</h2>
		<ol id="list">
			<?php
				foreach ($result as $row) {
					$title = $row -> title;
					$name = $row -> name;
					$url = $row -> url;
			?>
				<li class="results">
					<a href="<?php echo $url; ?>" target="_blank"><?php echo $title; ?></a>
					<p style="font-size: 12pt;"><?php echo $name; ?></p>
					<p style="font-size: 12pt; margin-top: -10px;">Associted tags: </p>
				</li>
			
			<?php
				}
			?>	
		</ol>
			  