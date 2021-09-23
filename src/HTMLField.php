<?php

/*!
 * HTMLField is not a *real* form field, but allows adding arbitrary HTML
 * (e.g. for explanatory texts) into the form.
 */
class HTMLField extends FormField
{
    public function __construct(object $obj)
    {
        $this->name = $obj->name;
        $this->label = $obj->label;
        $this->type = $obj->type;
    }

    public function makeField(): TagFactory
    {
        return new TextElement($this->label);
    }

    /*!
     * The HTML field does not change value when the form is submitted, which
     * means that the form response should output the same HTMl as the form
     * itself.
     */
    public function makeFieldResponseHTML(): TagFactory
    {
        return $this->makeField();
    }

    /*!
     * Outputs the label without HTML tags. Converts certain tags to make the
     * plaintext output more user friendly:
     * - <br> -> newline
     * - <p>, <h1...h6> -> remove tags; replace closing tag with double newline
     * - all other tages are stripped
     */
    public function makeFieldResponsePlain(): string
    {
        $str = $this->label;

        $convert_tags = ["p", "h1", "h2", "h3", "h4", "h5", "h6"];
        foreach($convert_tags as $tag)
        {
            $pattern = "/<".$tag."[^>]*>([\s\S]+)<\/".$tag."[\s]*>/Ui";
            $str = preg_replace($pattern, "\${1}\r\n\r\n", $str);
        }

        $str = preg_replace("/<br[^>]*>/Ui", "\r\n", $str);

        // remove remaining tags
        $str = strip_tags($str);

        // make sure that the string ends with exactly two newlines
        $str = trim($str);
        $str .= "\r\n\r\n";
        
        return $str;
    }
}

?>