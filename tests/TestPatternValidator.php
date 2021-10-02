<?php

$pv_phrase = new PatternValidator("pattern:/smoothforms/i");
assert($pv_phrase->isValid("Smoothforms is great!"));
assert($pv_phrase->isValid("Smoothieforms is the wrong name") === false);

$pv_url = new PatternValidator(
	"pattern:/^http[s]?:\/\/url\.example\/[^\/^\s]+$/");
assert($pv_url->isValid("http://url.example/username"));
assert($pv_url->isValid("https://url.example/username"));
assert($pv_url->isValid("http://url.example/user_name"));
assert($pv_url->isValid("http://url.example/") === false);
assert($pv_url->isValid("http://urls.example/username") === false);

assert(strcmp($pv_url->getErrorString(), "Invalid value") == 0);

?>
