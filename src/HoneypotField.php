<?php

/*! Implements a honeypot field in the form. Spam bots are expected to fill in
 * every field they find on the form (especially if it has a luring name and)
 * value. A honeypot field is hidden (by CSS) from real users, so they do not
 * fill the field in. If anything is found in the value for this field, a spam
 * bot is deemed to have filled it.
 *  The honeypot field also disregards any validators it may have added to it.
 * All it cares about is whether or not it has anything in its value, and if
 * so, it blocks further processing of the form.
 */
class HoneypotField extends InputField
{
    use HoneypotCSS;
    
	public function __construct(object $obj)
	{
		$this->name = $obj->name;
        $this->label = $obj->label;
        $this->type = $obj->type;
	}

	/*!
	 * Make the field HTML just as if this was a regular form field. Sneak in
	 * a special CSS class to hide the form field.
	 */
	public function makeField(): TagFactory
    {
        $field_id = "field_".$this->name;
        $honeypot_class = HoneypotField::generateHoneypotClassName();
        
        $field = new TagFactory("label", array(
        	"for" => $field_id, "class" => $honeypot_class));
        $field->addChild(new TextElement($this->label . ": "));
        $field->addChild(new TagFactory(
            "input",
            array(
                "name" => $this->name,
                "id" => $field_id,
                "class" => $honeypot_class,
            ), true
        ));

        return $field;
    }

    /*! HoneypotField is only ever interested in whether or not a value has
     * been submitted or not. Therefore, instead of running any validators,
     * we merely check if the field has values or not.
     */
    public function validate(): bool
    {
    	if(isset($_POST[$this->name]) && !empty($_POST[$this->name]))
    		return false;

    	return true;
    }

    /*!
     * Honeypots shouldn't display anything as returned values. Return an empty
     * span element.
     */
    public function makeFieldResponseHTML(): TagFactory
    {
    	return new TagFactory("span");
    }

    /*!
     * Return an empty string, as honeypots have no user-submitted data to show
     */
    public function makeFieldResponsePlain(): string
    {
    	return "";
    }
}
?>
