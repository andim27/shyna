<?php  if(!defined('BASEPATH')) exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Local Realm
|--------------------------------------------------------------------------
|
| Identification name for the web service
|
*/
$config['local_realm'] = 'ClickFuel Local WebService';

/*
|--------------------------------------------------------------------------
| REST Output format
|--------------------------------------------------------------------------
|
| What data type should be used for response:
|   - xml
|   - json
|   - php (parsable string representation of data to return, for debug purposes)
|
*/
$config['local_response_format'] = 'json';

/*
|--------------------------------------------------------------------------
| POVO Calling Url
|--------------------------------------------------------------------------
|
| What is the callling url for POVO:
|
|   Currently it is the computer on Hasty Granbery's desk. If the calls are not responding his computer may be down.
|
*/
$config['call_povo_server'] = 'http://66.92.65.4:4545';

/*
|--------------------------------------------------------------------------
| POVO Debug Tracing
|--------------------------------------------------------------------------
|
| Debug tracing?
|
|   If true perform tracing to the log in system/logs
|
*/
$config['povo_debug_tracing'] = True;

/* EOF */