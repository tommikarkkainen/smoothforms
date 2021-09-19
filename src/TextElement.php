<?php

/*!
 * A class for including text (wihtout tags) in HTML output
 */
class TextElement extends TagFactory
{
    /*!
     * Generate a TextElement object using the constructor
     * @param $tag_name The text to be included in the HTML.
     */
    public function __construct($text)
    {
        $this->tag_name = $text;
        $this->attributes = array();
        $this->self_closing = true;
        $this->children = array();
    }

    public function makeHTML(): string {
        return $this->tag_name . "\n";
    }

    public function addChild(TagFactory $child) {
        throw new Exception("Cannot add child to a text element.");
    }


}
?>
