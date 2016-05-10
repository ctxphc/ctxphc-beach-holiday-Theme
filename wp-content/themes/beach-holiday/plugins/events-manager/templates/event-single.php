<?php
/* 
 * Remember that this file is only used if you have chosen to override event pages with formats in your event settings!
 * You can also override the single event page completely in any case (e.g. at a level where you can control sidebars etc.), as described here - http://codex.wordpress.org/Post_Types#Template_Files
 * Your file would be named single-event.php
 */
/*
 * This page displays a single event, called during the the_content filter if this is an event page.
 * You can override the default display settings pages by copying this file to yourthemefolder/plugins/events-manager/templates/ and modifying it however you need.
 * You can display events however you wish, there are a few variables made available to you:
 * 
 * $args - the args passed onto EM_Events::output() 
 */
global $EM_Event;
/* @var $EM_Event EM_Event */
echo $EM_Event->output_single();

/*
$em_event_id = $EM_Event->event_id();
$em_event_post_id = $EM_Event->em_event_post_id();
$em_event_slug = $EM_Event->event_slug();
$em_event_owner = $em_event->event_owner();
$em_event_name = $EM_Event->event_name();
$em_event_start_time = $EM_Event->event_start_time();
$em_event_end_time = $EM_Event->event_end_time();
$em_event_all_day = $EM_Event->event_all_day();
$em_event_start_date = $EM_Event->event_start_date();
$em_event_end_date = $EM_Event->event_end_date();
$em_event_post_content = $EM_Event->post_content();
*/