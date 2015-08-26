# Socialize

This plug-in for the flat file CMS [Pico](http://picocms.org/) adds the must-have meta tags for Facebook and Twitter.

## Configuration

Add the following code to your ```config.php``` and add the appropriate values.

```php
$config['socialize'] = array(
    // Your Twitter handle with or without the @-sign
    'twitter' => 'twitter_id'
);
```

## Meta Data

You can also add the following optional infos to your files meta information.

```php
/*
Twitter_Author: twitter_id
*/
```

```Twitter_Author``` overrides the default Twitter ID in the ```twitter:creator``` meta node. 
