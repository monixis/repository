<?php


?>
<link rel="stylesheet" type="text/css" href="http://library.marist.edu/crrs/styles/main.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script type="text/javascript" src="http://library.marist.edu/crrs/js/jquery-ui.js"></script>
<script>
    $(document).ready(function(){
    	var availableTags = [];
		<?php 
		foreach($tags as $row){
		?>
		availableTags.push('<?php echo $row -> tag;?>');
		<?php }	?>
    	$( "#tags" ).autocomplete({
    	source: availableTags
	    });
   	})
</script>

<input type="text" id="tags" class="form-control input-md"  name="q" />

