<?php 


add_shortcode("tppdb_edit_pageant", "tppdb_edit_pageant_func");

function tppdb_edit_pageant_func() {

	ob_start();

	if(isset($_GET['gform_post_id'])) {


		$pageant = $_GET['gform_post_id'];

		$link = get_permalink($pageant);
		echo "<hr />";
		echo '<a href="'.$link.'">Back to Pageant</a>';
		echo "<hr />";


		echo do_shortcode('[gravityform id="1" name="Pageant Edit Form" title="false" description="false" update='.$pageant.']');

	} else {

		echo "There was an error editing this item.";
	}

	$content = ob_get_contents();
    ob_end_clean();
    return $content;


}