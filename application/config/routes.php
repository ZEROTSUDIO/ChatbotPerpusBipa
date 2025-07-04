<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['api/stats/overview'] = 'api/stats_overview';
$route['api/stats/charts'] = 'api/stats_charts';
$route['dashboard/statistics'] = 'dashboard/statistics';
$route['api/stats/intent-performance'] = 'api/stats_intent_performance';
$route['api/stats/recent-activity'] = 'api/stats_recent_activity';
$route['api/stats/volume'] = 'api/stats_volume';

// application/config/routes.php

// Intent Analytics Routes
$route['intent-analytics'] = 'intent_analytics/index';
$route['intent-analytics/performance'] = 'intent_analytics/get_intent_performance';
$route['intent-analytics/chat-details'] = 'intent_analytics/get_chat_details';
$route['intent-analytics/probabilities/(:num)'] = 'intent_analytics/get_class_probabilities/$1';
$route['intent-analytics/prediction-analysis'] = 'intent_analytics/get_prediction_analysis';
$route['intent-analytics/confusion-matrix'] = 'intent_analytics/get_confusion_matrix';
$route['intent-analytics/accuracy-metrics'] = 'intent_analytics/get_accuracy_metrics';
$route['intent-analytics/export'] = 'intent_analytics/export_intent_performance';

// API Routes (optional, for external access)
$route['api/intent-analytics/performance'] = 'intent_analytics/get_intent_performance';
$route['api/intent-analytics/chat-details'] = 'intent_analytics/get_chat_details';
$route['api/intent-analytics/probabilities/(:num)'] = 'intent_analytics/get_class_probabilities/$1';
$route['api/intent-analytics/metrics'] = 'intent_analytics/get_accuracy_metrics';
$route['intent-analytics/get_class_probabilities/(:num)'] = 'intent_analytics/get_class_probabilities/$1';


