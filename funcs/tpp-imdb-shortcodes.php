<?php 

/// SYSTEMS

add_shortcode("tppdb_edit_pageant", "tppdb_edit_pageant_func");

function tppdb_edit_pageant_func() {

	ob_start();

	if(isset($_GET['gform_post_id']) && get_post_type($_GET['gform_post_id']) == "pageants"){


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

/// PROFILES


add_shortcode("tppdb_edit_profile", "tppdb_edit_profile_func");

function tppdb_edit_profile_func() {

	ob_start();

	if(isset($_GET['gform_post_id']) && get_post_type($_GET['gform_post_id']) == "tpp_profiles"){


		$profile = $_GET['gform_post_id'];

		$link = get_permalink($profile);
		echo "<hr />";
		echo '<a href="'.$link.'">Back to Profile</a>';
		echo "<hr />";


		echo do_shortcode('[gravityform id="3" name="Profile Edit Form" title="false" description="false" update='.$profile.']');

	} else {

		echo "There was an error editing this item.";
	}

	$content = ob_get_contents();
    ob_end_clean();
    return $content;


}


add_shortcode("tppdb_add_system", "tppdb_add_system_func");

function tppdb_add_system_func() {
	ob_start();

	if (is_user_logged_in()) {
		echo do_shortcode('[gravityform id="6"]');
	} else {
		echo '<p>You must sign in before submitting a pageant.</p>';
	}

	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}