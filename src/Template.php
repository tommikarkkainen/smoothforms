<?php

/*!
 * Handles a form template
 */
class Template
{
    private string $template;
    private $variables = array();

    /*!
     * Registers a template variable. Each template variable can be used in a
     * template as {{ $name }} and will be replaced by $value.
     */
    public function registerVariable(string $name, string $value)
    {
        $this->variables[$name] = $value;
    }

    /*!
     * Loads the contents of a template file into the template holder variable.
     */
    public function loadTemplate(string $filename)
    {
        if(!file_exists($filename))
        {
            throw new Exception("Template ". $filename. " not found.");
        }

        $this->template = file_get_contents($filename);
    }

    /*!
     * Replaces variables in the template with the value of each variable.
     * @returns the variable-filled template as string.
     */
    public function output(): string
    {
        $retStr = $this->template;
        foreach($this->variables as $key => $value)
        {
            $pattern = "/{{\s*".$key."\s*}}/g";
            $retStr = preg_replace($pattern, $value, $retStr);
        }

        return retStr;
    }
}
?>
