<?php

/*!
 * A class used to generate HTML tags
 */
class TagFactory {
    protected string $tag_name;
    protected bool $self_closing;
    protected array $attributes;
    protected array $children;

    /*!
     * Generate a TagFactory object using the constructor
     * @param $tag_name The name of the tag, e.g. for a form tag, use "form"
     * @param $attributes An associative array of HTML tag attributes
     *        e.g. for a form, use array("method" => "POST", "action" => "<url>")
     * @param $self_closing if true, an ending tag is NOT created for this tag.
     */
    public function __construct($tag_name, $attributes=array(),
        $self_closing=false)
    {
        $this->tag_name = $tag_name;
        $this->attributes = $attributes;
        $this->self_closing = $self_closing;
        $this->children = array();
    }

    /*!
     * Add a new attribute to the tag
     */
    public function addAttribute($key, $value)
    {
        $new_item = array($key => $value);
        $this->attributes = array_merge($this->attributes, $new_item);
    }

    /*!
     * Change the tag name
     * @param $new_name the new name of the tag
     */
    public function setTagName($new_name)
    {
        $this->tag_name = $new_name;
    }

    /*!
     * Find an element in the tag tree by its id attribute. First checks if the
     * current object is the desired element. If not, check each of the
     * children.
     * 
     * @param $id The form field id parameter being looked for
     */
    public function findByID(string $id): ?TagFactory
    {
        if(array_key_exists("id", $this->attributes))
        {
            if(strcmp($this->attributes["id"], $id) == 0)
            {
                return $this;
            }
        }

        foreach($this->children as $child)
        {
            if($child->findByID($id) != null)
            {
                return $child;
            }
        }

        return null;
    }

    /*!
     * Add a child element to this HTML tag. Not allowed for self-closing tags!
     * @param $child The HTML element to be added
     */
    public function addChild(TagFactory $child)
    {
        if($this->self_closing)
            throw new Exception("Cannot add child tag to a self-closing tag.");
        array_push($this->children, $child);
    }

    /*!
     * Generate the HTML code for this tag, and its children recursively
     * @returns The HTML code as a string
     */
    public function makeHTML(): string
    {
        $ret_str = "";
        $ret_str .= "<".$this->tag_name;
        foreach($this->attributes as $key => $value)
        {
            $ret_str .= " ".$key."=\"".$value."\"";
        }
        $ret_str .=">\n";

        foreach($this->children as $child)
        {
            $ret_str .= $child->makeHTML();
        }

        if(!$this->self_closing)
            $ret_str .= "</".$this->tag_name.">";
        $ret_str .= "\n";

        return $ret_str;
    }
}

?>