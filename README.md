# Disable WP Rubbish

This repo is a PHP file to disable/remove certain things in WordPress that is generally rubbish, not needed, and generally just bloatware both on backend and frontend.

eg. The Welcome panel on the Admin Dashboard; Emojis, etc.

Adding the file can be done by following the below instructions, you can then add to it disable or remove functions to reenable the functionality.

#### Auto-download

If you often use the file, and find yourself redownloading it every project or copy/pasting from project to project, you can automatically download the file by adding a script into composer.json file or some other automation.

```json
{
	// composer.json

	"scripts": {
		"download":[
			"(cd 'path/to/directory' && curl -OL https://raw.githubusercontent.com/yCodeTech/disable-wp-rubbish/refs/heads/master/disable_wp_rubbish.php)"
		]
	}
}
```

Note:

The `download` key is the script name, and can be anything you want. The value is an array to allow for multiple extra scripts to run after each other if needed.

To run script, just do a `composer download` which is an alias for `composer run-script download`.

What the script does is using a subshell (denoted by the brackets `( )`) it changes the directory to the path where you wish to download it to, and downloads the raw php file from this repo using `cURL`. The subshell is important to prevent the current working directory from actually changing once it's completed.

#### Add to most themes

To use this in your WP site, just drop the file into a suitable place in your theme (I typically use an `includes` directory), and `include` it in your `functions.php` file like so:

```php
include get_stylesheet_directory() . "/includes/disable_wp_rubbish.php";
```

#### Add to Roots Sage themes

For those using Roots Sage or my [sage10-laravelmix](https://github.com/yCodeTech/sage10-laravelmix) starter theme, you can drop the file into the `app` directory of the Sage theme, and add the filename to the `collect` array of the "Register Sage Theme Files" section in the functions.php file:


```php
/*
|--------------------------------------------------------------------------
| Register Sage Theme Files
|--------------------------------------------------------------------------
|
| Out of the box, Sage ships with categorically named theme files
| containing common functionality and setup to be bootstrapped with your
| theme. Simply add (or remove) files from the array below to change what
| is registered alongside Sage.
|
*/

// In my sage10-laravelmix theme...

$required_files = ['setup', 'filters', 'disable_wp_rubbish']; // <--- here
collect($required_files)
	->each(function ($file) {
		...
	});

// OR 

// In the original Sage theme...

collect(['setup', 'filters', 'disable_wp_rubbish']) // <--- here
	->each(function ($file) {
		...
	});
```
