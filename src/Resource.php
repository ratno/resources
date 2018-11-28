<?php

namespace Ratno\Resources;

abstract class Resource {

    const MORPH_MANY = "morphMany";
    const MORPH_TO_MANY = "morphToMany";

    abstract public function tablename() : string;
    abstract public function fields() : array;

    public function title() : string
    {
        return "id";
    }

    public function tostring() : array
    {
        return ["%s","id"];
    }

    public function getFieldsVar($varname,$value_return="key") : array
    {
        $return = [];
        $fields = $this->fields();
        if(is_array($fields) && count($fields)) {
            foreach($fields as $key => $field_data) {
                if($field_data->$varname) {
                    if($value_return == "key") {
                        $return[$key] = $key;
                    } else {
                        $return[$key] = $field_data->$value_return;
                    }
                }
            }
        }
        return $return;
    }

    public function index() : array
    {
        return $this->getFieldsVar("column_index");
    }

    public function unique() : array
    {
        return $this->getFieldsVar("column_unique");
    }

    public function getDependency()
    {
        return $this->getFieldsVar("column_foreign_key","column_foreign_key");
    }

    public function subtitle() : string
    {
        return "";
    }

    public function avatar() : string {
        return "";
    }

    public function timestamps() : bool
    {
        return true;
    }

    public function softdelete() : bool
    {
        return false;
    }

    public function saveactor() : bool
    {
        return false;
    }

    public function insertdata() : array
    {
        return [];
    }

    public function pivotTable() : bool
    {
        return false;
    }

    public function morphTable() : bool
    {
        return false;
    }

    public function morphReverseReference() : array
    {
        /* contoh:
         *
         * return [
         *      [Resource::MORPH_MANY, MORPH_COLUMN_TYPE => CLASSNAME_ARRAY],
         *      [Resource::MORPH_TO_MANY, MORPH_COLUMN_TYPE => CLASSNAME_ARRAY]
         * ];
         *
         */
        return [];
    }
}