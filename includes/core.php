<?php

function calendar( $atts ){
?>
<div id="calendar"></div>

<script>

	(function calendar_m (){
    var calendar_b = $('#calendar');
      if (calendar_b.length <= 0)
        return;
      calendar_b.fullCalendar({
        lang: 'ru',
        titleFormat: 'MMMM',
        header: {
          'left' : 'prev title next',
          'right': 'month agendaWeek agendaDay'
        },
        eventLimit: 2, // for all non-agenda views
        events: 
        [

        <?php

        $args = array( 'post_type' => 'calendar', 'posts_per_page' => -1 );
                $loop = new WP_Query( $args );
               	while ( $loop->have_posts() ) : $loop->the_post();
               	$post_id = get_the_ID();
               	$img = get_the_post_thumbnail_url();
               	$date = get_post_meta( $post_id, 'date', true );
               	$date =  date("Y-m-d", strtotime($date));
               	$description = preg_replace("/[\r\n]+/", "<br>", get_the_content());
                $description = str_replace("'", "`", $description);
                $link = get_post_meta( $post_id, 'link-event', true );
                $link = str_replace("'", "`", $link);
               	echo '{id: \''.$post_id.'\',title: \''.get_the_title().'\',start: \''.$date.'\', urlimg: \''.$img.'\', descr: \''.$description.'\', linkevent: \''.$link.'\'},';
               	endwhile;
        
        ?>
        ],
        eventClick: function(event) {
        if (event.id) {
            $('.title-event h1').html(event.title);
            $('.event-date h3').html(event.start._i);
            $('.image-event').attr('src', event.urlimg);
            $('.event-description p').html(event.descr);
            if(event.linkevent){
            	$('.event-link').attr('href', event.linkevent);
            }
        }
    }
      })
  }());
</script>

<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  	<div class="modal-dialog">
    	<div class="modal-content">
	      	<div class="modal-header">
		        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Закрити</span></button>
		        <div class="modal-title title-event"><h1></h1></div>
	      	</div>
	      	<div class="modal-body">
			    <div class="row">
			      	<div class="col-md-12 event-body">
			        	<div class="event-date"><h3></h3></div>
			        	<div class="event-description"><p></p></div>
			        	<a class="event-link">
			        		<img class="image-event" src="" alt="">
			        	</a>
		        	</div>
		        </div>
	      	</div>
    	</div>
  	</div>
</div>

<?php
}

add_shortcode('display_calendar', 'calendar');

add_action( 'wp_enqueue_scripts', 'my_scripts_for_events' );

function my_scripts_for_events(){
	$all_options = get_option('true_options');
	wp_enqueue_style( 'eventstyles', MAX_CALENDAR_URL.'assets/fullcalendar.css' );
	wp_enqueue_style( 'bootstrap', MAX_CALENDAR_URL.'assets/css/bootstrap.css' );
	wp_enqueue_style( 'style', MAX_CALENDAR_URL.'assets/css/style.css' );
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js');
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'bootstrap', MAX_CALENDAR_URL . 'assets/js/bootstrap.min.js');
	wp_enqueue_script( 'moment', MAX_CALENDAR_URL . 'assets/lib/moment.min.js');
	wp_enqueue_script( 'fullcalendar', MAX_CALENDAR_URL . 'assets/fullcalendar.js');
}