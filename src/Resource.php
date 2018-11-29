<?php

namespace Ratno\Resources;

abstract class Resource {

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
        return \App\Semar\Users::class;
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
            ResourceConstant::CREATED_AT => col()->timestamp(),
            ResourceConstant::UPDATED_AT => col()->timestamp(),
        ];
    }

    protected function getSoftdeleteFields() : array
    {
        return [
            ResourceConstant::DELETED_AT => col()->timestamp(),
        ];
    }

    public function getSaveActorFields() : array
    {
        $fields = [];

        if($this->timestamps()) {
            $fields[ResourceConstant::CREATED_BY_ID] = col()->int(11)->index()->foreignKey($this->actorclass())->title('Created By');
            $fields[ResourceConstant::UPDATED_BY_ID] = col()->int(11)->index()->foreignKey($this->actorclass())->title('Updated By');
        }

        if($this->softdelete()) {
            $fields[ResourceConstant::DELETED_BY_ID] = col()->int(11)->index()->foreignKey($this->actorclass())->title('Deleted By');
            $fields[ResourceConstant::RESTORED_AT] = col()->timestamp();
            $fields[ResourceConstant::RESTORED_BY_ID] = col()->int(11)->index()->foreignKey($this->actorclass())->title('Restored By');
        }

        return $fields;
    }

    public function getFieldsVar($varname,$value_return="key") : array
    {
        $return = [];
        $fields = $this->getAllFields();
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