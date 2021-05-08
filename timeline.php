<?php
/**
 * Plugin Name: Timeline Plugin
 * Plugin URI: https://kachooee.com
 * Description: Personal timeline plugin designed for Mr Kachooee
 * Version: 1.0.2
 * Author: Fazel Kazempour
 * Author URI: https://kfazel.com
 */

require 'includes/custom-postype.php';
require 'includes/acf-fields.php';
include 'timeline-page-template.php';


// filter timelines function
add_action('wp_ajax_timelinefilter', 'timeline_filter'); // wp_ajax
add_action('wp_ajax_nopriv_timelinefilter', 'timeline_filter');
 
function timeline_filter(){
	if ($_POST['filters'] && !empty($_POST['filters'])) {
		$filterList = explode(",", $_POST['filters']);
		$args = array(
			'post_type' => 'timelines',
			'posts_per_page' => 100,
			'tax_query' => array(array(
				'taxonomy' => 'timelinecategories',
				'field'    => 'term_id',
				'terms'    => $filterList,
			),),
			'meta_key' => 'date',
			'orderby' => 'meta_value',
			'order' => 'ASC',
		);
	} else {
		$args = array(
			'post_type' => 'timelines',
			'posts_per_page' => 100,
			'meta_key' => 'date',
			'orderby' => 'meta_value',
			'order' => 'ASC',
		);
	}

	$timelines = new WP_Query($args);
	echo $_POST['cat'];
	if ($timelines -> have_posts()):
		$i = 1;
		while ($timelines -> have_posts()) :
			$timelines -> the_post();
			$image = get_field('image');
			$date = get_field('date');
			$description = get_field('description');
			$moment = get_field('moment');
			$cats = get_the_terms($timelines->post->post_id, 'timelinecategories');
			
			echo '
			<div class="timeline-container" id="timeline-'.$i.'">
				<div class="timeline-year">'.substr($date, strpos($date, ",") + 1).'</div>
				<div class="timeline-bullet"></div>
				<div class="timeline-guide" style="width:';
				if (fmod($i, 2) == 0) {
					echo '52%';
				} else {
					echo '5%';
				}
				echo '"></div>
				<div class="timeline-card" id="timeline-card-'.$i.'">
					<div class="timeline-head">
						<img class="timeline-image" src="'.$image.'" alt="image">
						<div class="timeline-info-sec">
							<div class="timeline-title">'.$timelines->post->post_title.'</div>
							<div class="timeline-date">'.$date.'</div>';
							if ($cats) :
								echo '<div class="timeline-tags-box">';
								foreach ($cats as $cat) {
									$color = get_field('color', 'category_'.$cat->term_id);
									echo '<div class="timeline-tag" style="background: '.$color.'">'.$cat->name.'</div>';
								}
								echo '</div>';
							endif;
						echo '</div>
					</div>
					<div class="timeline-body">
						<div class="timeline-description">'.$description.'</div>';
						if (!empty($moment)) {
							echo '
							<div class="moment-link" onclick="window.open(\''.$moment.'\', \'_blank\')">
								<div>Read more</div>
								<svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#ffffff">
									<path d="M0 0h24v24H0V0z" fill="none"/><path d="M9.31 6.71c-.39.39-.39 1.02 0 1.41L13.19 12l-3.88 3.88c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0l4.59-4.59c.39-.39.39-1.02 0-1.41L10.72 6.7c-.38-.38-1.02-.38-1.41.01z"/>
								</svg>
							</div>
							';
						}
					echo '</div>
				</div>

				<style>
					#timeline-card-'.$i.' {
						background: 
							linear-gradient(rgba(20, 20, 20, 0.95), rgba(17, 17, 17, 0.85)),
							url('.$image.') center no-repeat;
						background-size: cover;
					}
				</style>';
				
				if ($i != 1) {
					echo '
					<script>
						previous = document.getElementById("timeline-'.($i-1).'");
						self = document.getElementById("timeline-'.$i.'");
						difference = previous.offsetHeight - self.offsetHeight;
						if (difference > 30)
							distance = self.offsetHeight / 2 - 10;
						else if (difference > -30)
							distance = self.offsetHeight / 2 - (Math.abs(difference) + 10);
						else
							distance = previous.offsetHeight / 2 - 10;

						self.style.marginTop = `-${distance}px`;
					</script>
					';
				}
			echo '</div>
			';

			$i++;
		endwhile;
		wp_reset_postdata();
	else :
		echo '<div class="no-timeline-message">No Timeline Found</div>';
	endif;
 
	die();
}