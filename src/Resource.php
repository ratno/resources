<?php

namespace Ratno\Resources;

abstract class Resource {
    const _TIMESTAMPS = 'timestamps';
    const _TIMESTAMPS_ACTOR = 'timestamps_actor';
    const _REMEMBER_TOKEN = 'remember_token';
    const _SOFT_DELETE = 'soft_delete';
    const _SOFT_DELETE_ACTOR = 'soft_delete_actor';
    const TABLENAME = "";
    const TABLETITLE = "";

    protected $allFields;
    protected $timestamps = false;
    protected $rememberToken = false;
    protected $softdelete = false;
    protected $timestampActor = false;
    protected $softDeleteActor = false;

    abstract public function tablename() : string;
    public function tabletitle() : string
    {
        if(static::TABLETITLE == "") {
            return ucwords(str_replace("_"," ",static::TABLENAME));
        } else {
            return static::TABLETITLE;
        }
    }

    public function __construct()
    {
        $this->populateFields();
    }

    public function resetAllProperties()
    {
        $this->allFields = [];

        $this->rememberToken = false;

        $this->timestamps = false;
        $this->timestampActor = false;

        $this->softdelete = false;
        $this->softDeleteActor = false;
    }

    public function populateFields()
    {
        $this->resetAllProperties();

        $allFields = [];
        $fields = $this->fields();
        foreach($this->changelog() as $create_ts => $item_field_array) {
            foreach($item_field_array as $item_field_name) {
                if(array_key_exists($item_field_name,$fields)) {
                    $allFields[$item_field_name] = $fields[$item_field_name];
                } else {
                    // set properties
                    $properties = camel_case($item_field_name);
                    $this->{$properties} = true;
                    // ambil method get{$item_field_name}Fields()
                    $method = "get".studly_case($item_field_name)."Fields";
                    foreach($this->$method() as $item_field_predefined_column => $item_field_predefined_definition) {
                        $allFields[$item_field_predefined_column] = $item_field_predefined_definition;
                    }
                }
            }
        }

        $this->allFields = $allFields;
    }


    public function getMigrationInstance() {
        $return = [];
        $fields = $this->fields();
        foreach($this->changelog() as $create_ts => $item_field_array) {
            $clone = clone $this;
            $clone->resetAllProperties();
            $allFields = [];
            foreach($item_field_array as $item_field_name) {
                if(array_key_exists($item_field_name,$fields)) {
                    $allFields[$item_field_name] = $fields[$item_field_name];
                } else {
                    // set properties
                    $properties = camel_case($item_field_name);
                    $clone->{$properties} = true;
                    // ga perlu ambil method get{$item_field_name}Fields()
                    // karena yg akan di generate hanya yg didefinisikan di field saja
                }
            }
            $clone->allFields = $allFields;

            $return[$create_ts] = $clone;
        }

        return $return;
    }

    /**
     * @return \Ratno\Resources\Column[]
     */
    abstract public function fields() : array;
    abstract public function changelog() : array;

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

    /**
     * @return \Ratno\Resources\Column[]
     */
    public function getAllFields() : array
    {
        return $this->allFields;
    }

    public function getRememberTokenFields() : array
    {
        return [
            ResourceConstant::REMEMBER_TOKEN => col()->string(100)->title('Remember Token')->model_hide()->form_hide()->grid_hide()->filter_hide()->detail_hide()
        ];
    }

    public function getTimestampsFields() : array
    {
        return [
            ResourceConstant::CREATED_AT => col()->timestamp(),
            ResourceConstant::UPDATED_AT => col()->timestamp(),
        ];
    }

    public function getSoftdeleteFields() : array
    {
        return [
            ResourceConstant::DELETED_AT => col()->timestamp(),
        ];
    }

    public function getTimestampsActorFields() : array
    {
        return [
            ResourceConstant::CREATED_BY_ID => col()->int(11)->index()->foreignKey($this->actorclass())->title('Created By'),
            ResourceConstant::UPDATED_BY_ID => col()->int(11)->index()->foreignKey($this->actorclass())->title('Updated By')
        ];
    }
    public function getSoftdeleteActorFields() : array
    {
        return [
            ResourceConstant::DELETED_BY_ID => col()->int(11)->index()->foreignKey($this->actorclass())->title('Deleted By'),
            ResourceConstant::RESTORED_AT => col()->timestamp(),
            ResourceConstant::RESTORED_BY_ID => col()->int(11)->index()->foreignKey($this->actorclass())->title('Restored By'),
        ];
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

    public function primary() : array
    {
        return $this->getFieldsVar("column_primary_key");
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
        return $this->timestamps;
    }

    public function rememberToken() : bool
    {
        return $this->rememberToken;
    }

    public function softdelete() : bool
    {
        return $this->softdelete;
    }

    public function timestampActor() : bool
    {
        return $this->timestampActor;
    }

    public function softDeleteActor() : bool
    {
        return $this->softDeleteActor;
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

    public function initial_seeder() : array
    {
        return [];
    }

    public function getCustomPolicy() : array
    {
        /* Contoh:
         * return [
         *     "policyName" => [
         *         "title" => "policy title",
         *         "params_type" => 1/2
         *     ]
         * ];
         */

        return [];
    }

    public function getActionList() : array
    {
        /*
         * return [
         *     [
         *         "type" => 1/2, => 1 untuk independent, 2 untuk bulk
         *         "policy" => "policyName",
         *         "action"=>"action_method",
         *         "title"=>"action title",
         *     ],
         *     [
         *         "type" => 1/2, => 1 untuk independent, 2 untuk bulk
         *         "policy" => "policyName",
         *         "action" => "openDialogModal",
         *         "title" => "Set Parent Menu",
         *         "class" => \App\Http\Livewire\Actions\AppMenu\SetParentMenu::class
         *     ]
         * ];
         */

        return [];
    }

    public function isEnumeration() : bool
    {
        return false;
    }
}