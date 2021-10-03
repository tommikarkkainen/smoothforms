<?php

trait HoneypotCSS
{
	/*!
	 * Generate a CSS class name to be used for hiding honeypot fields from
	 * regular users
	 */
	public function generateHoneypotClassName(): string
	{
		$honeypot_class = base64_encode("honeypot-".$_SERVER['PHP_SELF']);
        $honeypot_class = preg_replace("/[^a-zA-Z]/", "", $honeypot_class);
        $honeypot_class = strtolower($honeypot_class);
        $honeypot_class = substr($honeypot_class, 0, 6);

        return $honeypot_class;
	}

	public function generateHoneypotCSS(): string
	{
		$css_lines = array(
			"opacity: 0;",
        	"position: absolute;",
        	"top: 0;",
        	"left: 0;",
        	"height: 0;",
        	"width: 0;",
        	"z-index: -1;"
		);
		shuffle($css_lines);
		$css  = ".".$this::generateHoneypotClassName()."\n";
		$css .= "{\n";
		foreach($css_lines as $line)
		{
			$css .= "  ".$line."\n";
		}
		$css .= "}\n";

		return $css;
	}
}
?>
