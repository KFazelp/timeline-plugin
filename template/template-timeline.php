<meta name="viewport" content="width=device-width,initial-scale=1.0">
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?php echo plugin_dir_url( __FILE__ ); ?>assets/style/style.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="<?php echo plugin_dir_url( __FILE__ );?>assets/js/filter.js"></script>

<div class="timeline-body-bg"></div>
<?php 
    $background = get_field('background'); 
    if (empty($background))
        $background = plugin_dir_url( __FILE__ ) . 'assets/images/frame-beach.jpg';
?>
<style>
    .timeline-body-bg {
        background: 
            linear-gradient(rgba(50, 50, 50, 0.8), rgba(50, 50, 50, 0.8)),
            url('<?php echo $background; ?>') center no-repeat;
        background-size: cover;
    }
</style>


<div class="main-timeline">

    <form style="display: none" action="<?php echo site_url() ?>/wp-admin/admin-ajax.php" method="POST" id="filterForm">
        <input type="hidden" name="filters" value="" id="filtersInput" />
        <input type="hidden" name="action" value="timelinefilter">
    </form>

    <?php $category_args = array(
        'taxonomy' => 'timelinecategories',
        'hide_empty' => false,
    );
    $categories = get_terms($category_args);?>

    <div class="filter-box" id="filterBox">
        <div class="filter-box-title">Categories</div>
        <div class="filter-btns-container">
            <div class="categories-container">
                <?php foreach($categories as $category) {?>
                    <div class="timeline-filter" id="filter-chip-<?php echo $category->term_id ?>" onclick="selectFilter(<?php echo $category->term_id ?>)">
                        <?php echo $category->name ?>
                    </div>
                <?php } ?>
            </div>
            <div class="clear-filter-btn" onclick="removeFilters()">remove filters</div>
        </div>
    </div>
    <div class="filter-box-button" id="filterBoxBtn" onclick="openFilterBox()">Filter Timelines</div>

    <div class="loader" id="spinner"></div> 

    <div id="timeline-results">
        <?php $timelines_args = array(
            'post_type' => 'timelines',
            'posts_per_page' => 100,
            'meta_key' => 'date',
            'orderby' => 'meta_value',
            'order' => 'ASC',
        );
        $timelines = new WP_Query($timelines_args);
        if ($timelines -> have_posts()):
            $i = 1;
            echo '<script>let previous, self, difference, distance;</script>';
            while ($timelines -> have_posts()) :
                $timelines -> the_post();
                $image = get_field('image');
                $date = get_field('date');
                $description = get_field('description');
                $moment = get_field('moment');
                $cats = get_the_terms($timelines->post->post_id, 'timelinecategories');?>

                <div class="timeline-container" id="timeline-<?php echo $i; ?>">
                    <div class="timeline-year"><?php echo substr($date, strpos($date, ',') + 1); ?></div>
                    <div class="timeline-bullet"></div>
                    <div class="timeline-guide" style="width: <?php echo $i % 2 != 0 ? '5%' : '52%' ?>;"></div>
                    <div class="timeline-card" id="timeline-card-<?php echo $i; ?>">
                        <div class="timeline-head">
                            <img class="timeline-image" src="<?php echo $image; ?>" alt="image">
                            <div class="timeline-info-sec">
                                <div class="timeline-title"><?php the_title() ?></div>
                                <div class="timeline-date"><?php echo $date; ?></div>
                                <?php if ($cats) : ?>
                                    <div class="timeline-tags-box">
                                    <?php foreach ($cats as $cat) { ?>
                                        <?php 
                                            $color = get_field('color', 'category_'.$cat->term_id);
                                        ?>
                                        <div class="timeline-tag" style="background: <?php echo $color ?>"><?php echo $cat->name; ?></div>
                                    <?php } ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="timeline-body">
                            <div class="timeline-description"><?php echo $description ?></div>
                            <?php if(!empty($moment)) : ?>
                                <div class="moment-link" onclick="window.open('<?php echo $moment ?>', '_blank')">
                                    <div>Read more</div>
                                    <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 0 24 24" width="24px" fill="#ffffff">
                                        <path d="M0 0h24v24H0V0z" fill="none"/><path d="M9.31 6.71c-.39.39-.39 1.02 0 1.41L13.19 12l-3.88 3.88c-.39.39-.39 1.02 0 1.41.39.39 1.02.39 1.41 0l4.59-4.59c.39-.39.39-1.02 0-1.41L10.72 6.7c-.38-.38-1.02-.38-1.41.01z"/>
                                    </svg>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <style>
                        #timeline-card-<?php echo $i ?> {
                            background: 
                                linear-gradient(rgba(20, 20, 20, 0.95), rgba(17, 17, 17, 0.85)),
                                url('<?php echo $image; ?>') center no-repeat;
                            background-size: cover;
                        }
                    </style>

                    <?php if ($i != 1) : ?>
                    <script>
                        previous = document.getElementById("timeline-<?php echo $i - 1; ?>");
                        self = document.getElementById("timeline-<?php echo $i; ?>");
                        difference = previous.offsetHeight - self.offsetHeight;
                        if (difference > 30)
                            distance = self.offsetHeight / 2 - 10;
                        else if (difference > -30)
                            distance = self.offsetHeight / 2 - (Math.abs(difference) + 10);
                        else
                            distance = previous.offsetHeight / 2 - 10;

                        self.style.marginTop = `-${distance}px`;
                    </script>
                    <?php endif; ?>
                </div>

                <?php
                $i++;
            endwhile;
        endif;
        wp_reset_postdata();
        ?>
    </div>

</div>