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
          {
            title: 'All Day Event',
            start: '2018-02-01'
          },
          {
            title: 'Long Event',
            start: '2018-02-07'
          },
          {
            id: 999,
            title: 'Repeating Event',
            start: '2018-02-09T16:00:00'
          },
          {
            id: 999,
            title: 'Repeating Event',
            start: '2018-02-16T16:00:00'
          },
          {
            title: 'Conference',
            start: '2015-02-11'
          },
          {
            title: 'Meeting',
            start: '2015-02-12T10:30:00'
          },
          {
            title: 'Lunch',
            start: '2015-02-12T12:00:00'
          },
          {
            title: 'Meeting',
            start: '2015-02-12T14:30:00'
          },
          {
            title: 'Happy Hour',
            start: '2015-02-12T17:30:00'
          },
          {
            title: 'Dinner',
            start: '2015-02-12T20:00:00'
          },
          {
            title: 'Birthday Party',
            start: '2015-02-13T07:00:00'
          },
          {
            title: 'Click for Google',
            url: 'http://google.com/',
            start: '2015-02-28'
          }
        ]
      })
  }());
</script>

<?php
}


add_shortcode('display_calendar', 'calendar');

add_action( 'wp_enqueue_scripts', 'my_scripts_for_events' );

function my_scripts_for_events(){
	$all_options = get_option('true_options');
	wp_enqueue_style( 'eventstyles', MAX_CALENDAR_URL.'assets/fullcalendar.css' );
	wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', 'https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js');
	wp_enqueue_script( 'jquery' );
	wp_enqueue_script( 'moment', MAX_CALENDAR_URL . 'assets/lib/moment.min.js');
	wp_enqueue_script( 'fullcalendar', MAX_CALENDAR_URL . 'assets/fullcalendar.js');
}