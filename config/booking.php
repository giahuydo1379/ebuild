<?php

return [
	'max_week' 		=> env('MAX_WEEK', 3),
	'min_duration' 	=> env('MIN_DURATION', 30),//minutes
	'start_time'	=> env('START_TIME', '08:00'),
	'end_time'		=> env('END_TIME', '19:00'),
    'status_order_collected' => 5
];