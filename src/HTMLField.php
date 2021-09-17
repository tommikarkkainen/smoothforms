<?php

class HTMLField extends FormField
{
    public function __construct(object $obj)
    {
        $this->name = $obj->name;
        $this->label = $obj->label;
        $this->type = $obj->type;
    }

    protected function makeLabel(): string { return ""; }
    protected function makeTag(): string { return $this->label; }
}

?>