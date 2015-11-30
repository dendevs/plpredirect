# Plpwpredirect

[![Build Status](https://travis-ci.org/dendevs/plpwpredirect.svg)](https://travis-ci.org/dendevs/plpwpredirect)
[![Coverage Status](https://coveralls.io/repos/dendevs/plpredirect/badge.svg?branch=master&service=github)](https://coveralls.io/github/dendevs/plpredirect?branch=master)

*Simple redirect in wp*

## Install

```bash
composer require dendev/plpwpredirect
#   "minimum-stability": "dev",
```

```php
<?php
require 'vendor/autoload.php';
use DenDev\Plpwpredirect\Redirect;

$root_path = plugin_dir_path( __FILE__ );
$root_url = plugins_url() . '/package/';
$redirect = Redirect::get_instance( array(
	'root_path' => $root_path,
	'root_url' => $root_url,
	'set_update_manager' => true ) 
);
```

## Refs:

[wp create table](https://codex.wordpress.org/Creating_Tables_with_Plugins)

[wp redirect](https://codex.wordpress.org/Function_Reference/wp_redirect)

[hook update permalien](https://codex.wordpress.org/Plugin_API/Filter_Reference/wp_insert_post_data)

[filtre](http://wordpress.stackexchange.com/questions/128825/hook-for-post-permalink-update )
