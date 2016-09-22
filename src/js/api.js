/**
 * api.js
 * (c) Andreas Mueller <webmaster@am-wd.de>
 */

if (typeof jQuery == 'undefined')
	throw new Error('PHP-API requires jQuery');

/**
 * api
 * 
 * Sends a POST request to a defined PHP file used as backend
 * and receives the result as JSON object.
 * 
 * @param string    func      Function to call in backend.
 * @param object    obj       Object with data to send to the backend.
 * @param function  callback  Function to call if response is received.
 * @param boolean   async     Perform request asynchonous or not. Default: true.
 * @param boolean   global    Trigger the global ajax request event. Default: false.
 *
 * @return object  Response object from jQuerys Ajax request.
 */
function api(func, obj, callback, async, global) {
	if (async === undefined) async = true;
	if (global === undefined) global = false;
	
	var req = {};
	req.func = func;
	req.data = obj;

	return $.ajax( {
		url: 'php/ajax/api.php',
		type: 'post',
		data: JSON.stringify(req),
		async: async,
		global: global,
		contentType: 'application/json',
		success: function(response) {
			if (typeof callback === 'function') {
				callback(response);
			} else {
				console.log('PHP-API | callback function not set');
			}
		},
		error: function(response) {
			console.log('PHP-API | execution failed');
		}
	});
}