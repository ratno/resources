<?php

namespace Ratno\Resources;

abstract class Resource {

    const MORPH_MANY = "morphMany";
    const MORPH_TO_MANY = "morphToMany";
    const MORPHED_BY_MANY = "morphedByMany";

    const CREATED_AT = "created_at";
    const UPDATED_AT = "updated_at";
    const DELETED_AT = "deleted_at";
    const RESTORED_AT = "restored_at";

    const CREATED_BY_ID = "created_by_id";
    const UPDATED_BY_ID = "updated_by_id";
    const DELETED_BY_ID = "deleted_by_id";
    const RESTORED_BY_ID = "restored_by_id";

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

    public function actorclass() : string
    {
        return Users::class;
    }

    public function getAllFields() : array
    {
        $fields = $this->fields();

        if($this->timestamps()) {
            $fields = array_merge($fields, $this->getTimestampsFields());
        }

        if($this->softdelete()) {
            $fields = array_merge($fields, $this->getSoftdeleteFields());
        }

        if($this->saveactor()) {
            $fields = array_merge($fields, $this->getSaveActorFields());
        }

        return $fields;
    }

    protected function getTimestampsFields() : array
    {
        return [
            self::CREATED_AT => col()->timestamp(),
            self::UPDATED_AT => col()->timestamp(),
        ];
    }

    protected function getSoftdeleteFields() : array
    {
        return [
            self::DELETED_AT => col()->timestamp(),
            self::RESTORED_AT => col()->timestamp(),
        ];
    }

    protected function getSaveActorFields() : array
    {
        $fields = [];

        if($this->timestamps()) {
            $fields[] = [
                self::CREATED_BY_ID => col()->int(11)->index()->foreignKey($this->actorclass())->title('Created By'),
                self::UPDATED_BY_ID => col()->int(11)->index()->foreignKey($this->actorclass())->title('Updated By'),
            ];
        }

        if($this->softdelete()) {
            $fields[] = [
                self::DELETED_BY_ID => col()->int(11)->index()->foreignKey($this->actorclass())->title('Deleted By'),
                self::RESTORED_BY_ID => col()->int(11)->index()->foreignKey($this->actorclass())->title('Restored By'),
            ];
        }

        return $fields;
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

    public function morphReverseReferenceCreate($type, $column_type_name, $reference, $mtm_table = null,
                                                $mtm_target_class = null, $mtm_target_id_column = null) : array
    {
        if($mtm_target_class) {
            return compact("type","column_type_name", "reference", "mtm_table", "mtm_target_class","mtm_target_id_column");
        } else {
            return compact("type","column_type_name", "reference");
        }
    }
}