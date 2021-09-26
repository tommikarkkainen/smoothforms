<?php

/*!
 * Provides strings in the requested language. Looks string up from .ini files
 * in the lang/ directory. If a translated string is not available, outputs the
 * string in the default language (lang/default.ini).
 */
class Translator
{
    private static $instance = null;
    private array $strings;

    /*!
     * This class doesn't really need a constructor, but one is defined as
     * private in order to make it a singleton
     */
    private function __construct() {}

    /*!
     * Returns the instance of this singleton class.
     */
    public static function getInstance()
    {
        if(self::$instance == null)
        {
            self::$instance = new Translator();
        }
        return self::$instance;
    }

    /*!
     * Reads translated strings from a translation file.
     * 
     * @param $lang the language code for the desired language. A file named
     * $lang.ini must be found in the lang directory of the app.
     */
    public function setLanguage(string $lang)
    {
        $default_strings = parse_ini_file("lang/default.ini", true);
        $strings = parse_ini_file("lang/".$lang.".ini", true);
        if($strings === false)
        {
            trigger_error("Language file for ". $lang ." was not found." .
                " Using default language.", E_USER_NOTICE);
            $strings = array();
        }

        $default_strings = $this->unrollIniSections($default_strings);
        $strings = $this->unrollIniSections($strings);

        /* The order of arguments for array_merge() is important in this case
        because we want values of default.ini to appear ONLY where $lang.ini
        does not set them */
        $this->strings = array_merge($default_strings, $strings);
    }

    /*!
     * Convert the multidimensional sectioned arrays returned by parse_ini_file
     * into single-dimensional arrays with keys of the form $section.$key
     */
    private function unrollIniSections($sections): array
    {
        $retArr = array();
        foreach($sections as $section => $keyvalues)
        {
            foreach ($keyvalues as $key => $value)
            {
                $newkey = $section.".".$key;
                $retArr += array($newkey => $value);
            }
        }
        return $retArr;
    }

    /*!
     * @returns the string associated with a specific @param $key located in a
     * specific @param $section.
     */
    public function text($key)
    {
        if(!isset($this->strings))
        {
            trigger_error("Translated string ".$section.".".$key.
                " was requested before a language was set. Using default ".
                "language", E_USER_NOTICE);
            $this->setLanguage("default");
        }

        return $this->strings[$key];
    }
}

?>