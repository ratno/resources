<?php

namespace Ratno\Resources;

class Column
{
    public $real_column;
    public $type;
    public $size;
    public $scale;
    /*
     * https://laravel.com/docs/5.7/eloquent-mutators
     */
    public $cast;

    public $column_primary_key = false;
    public $auto_increment = false;
    public $column_foreign_key;
    public $column_foreign_key_cascade;
    public $column_foreign_key_setnull;
    public $column_index;
    public $column_unique;

    public $column_uuid = false;
    public $column_id = false;

    public $column_title;
    public $column_default;
    public $column_required = false;
    public $column_unsigned;
    public $column_comment;

    public $column_file;
    public $column_file_type;
    public $column_avatar;

    public $column_chain_to = [];
    public $column_chain_from = [];
    public $column_chain_to_where = [];
    public $column_chain_from_where = [];

    public $column_filter_visible = true;
    public $column_grid_visible = true;
    public $column_grid_width;
    public $column_grid_fixed = false;
    public $column_form_visible = true;
    public $column_detail_visible = true;
    public $column_model_visible = true;
    public $column_without_model_via = false;

    public $column_max_length;
    public $column_min_length;
    public $column_max_value;
    public $column_min_value;
    public $column_alpha = false;
    public $column_alpha_num = false;
    public $column_num = false;
    public $column_email = false;
    public $column_autocomplete = false;
    public $column_radio = false;
    public $column_password = false;
    public $column_json = false;

    const TYPE_CHAR = "char";
    const TYPE_VARCHAR = "varchar";
    const TYPE_TEXT = "text";
    const TYPE_TINYINT = "tinyint";
    const TYPE_INT = "integer";
    const TYPE_BIGINT = "biginteger";
    const TYPE_DATE = "date";
    const TYPE_DATETIME = "datetime";
    const TYPE_TIME = "time";
    const TYPE_TIMESTAMP = "timestamp";
    const TYPE_DECIMAL = "decimal";

    const VAR_STRING = "string";
    const VAR_ARRAY = "array";
    const VAR_DOUBLE = "double";
    const VAR_INTEGER = "integer";
    const VAR_BOOLEAN = "boolean";
    const VAR_DATETIME = "\Carbon\Carbon";

    /**
     * Column constructor.
     * @param $real_column
     */
    public function __construct($real_column = true)
    {
        $this->real_column = $real_column;
    }

    public function dataType($name, $size = null, $scale = null) : Column
    {
        $this->type = $name;
        $this->size = $size;
        $this->scale = $scale;
        return $this;
    }

    public function char($size = 191) : Column
    {
        return $this->dataType(Column::TYPE_CHAR, $size);
    }

    public function text() : Column
    {
        return $this->dataType(Column::TYPE_TEXT);
    }

    public function string($size = 191) : Column
    {
        return $this->varchar($size);
    }

    public function varchar($size = 191) : Column
    {
        return $this->dataType(Column::TYPE_VARCHAR, $size);
    }

    public function boolean() : Column
    {
        $this->cast = "boolean";
        return $this->dataType(Column::TYPE_TINYINT, 1);
    }

    public function date() : Column
    {
        $this->cast = "date";
        return $this->dataType(Column::TYPE_DATE);
    }

    public function dateTime() : Column
    {
        $this->cast = "datetime";
        return $this->dataType(Column::TYPE_DATETIME);
    }

    public function time() : Column
    {
        $this->cast = "time";
        return $this->dataType(Column::TYPE_TIME);
    }

    public function timestamp() : Column
    {
        $this->cast = "timestamp";
        return $this->dataType(Column::TYPE_TIMESTAMP);
    }

    public function decimal($size, $scale = 0) : Column
    {
        $this->cast = ($scale) ? "decimal:$scale" : "decimal";
        return $this->dataType("decimal", $size, $scale);
    }

    public function jsonText() : Column
    {
        $this->cast = "array";
        $this->column_json = true;
        return $this->text();
    }

    public function bigIncrement() : Column
    {
        $this->cast = "integer";
        return $this->increment(true);
    }

    public function increment($big = false) : Column
    {
        if ($big) {
            $this->bigint();
        } else {
            $this->int();
        }
        $this->auto_increment = true;
        $this->primaryKey();
        $this->unsigned();
        return $this;
    }

    public function bigint($size = 20) : Column
    {
        $this->cast = "integer";
        return $this->dataType(Column::TYPE_BIGINT, $size);
    }

    public function int($size = 11) : Column
    {
        $this->cast = "integer";
        return $this->dataType(Column::TYPE_INT, $size);
    }

    public function primaryKey() : Column
    {
        $this->column_primary_key = true;
        $this->column_required = true;
        return $this;
    }

    public function id() : Column
    {
        $this->column_id = true;
        // sementara ini blom support untuk fully pakai uuid, seperti pada dropdown dll, maka model_hide() belom bisa diimplementasikan
        return $this->grid_hide()->form_hide()->detail_hide();
    }

    public function uuid() : Column
    {
        $this->column_uuid = true;
        return $this->char(36)->title('UUID')->required()->unique()->grid_hide()->form_hide();
    }

    public function unsigned() : Column
    {
        $this->column_unsigned = true;
        return $this;
    }

    public function foreignKey($classname) : Column
    {
        $this->column_foreign_key = $classname;
        $this->index();
        return $this;
    }

    public function index() : Column
    {
        $this->column_index = true;
        return $this;
    }

    public function fkCascade() : Column
    {
        $this->column_foreign_key_cascade = true;
        return $this;
    }

    public function fkSetNull() : Column
    {
        $this->column_foreign_key_setnull = true;
        return $this;
    }

    public function unique() : Column
    {
        $this->column_unique = true;
        return $this;
    }

    public function title($title) : Column
    {
        $this->column_title = $title;
        return $this;
    }

    public function required() : Column
    {
        $this->column_required = true;
        return $this;
    }

    public function defaultvalue($value) : Column
    {
        $this->column_default = $value;
        return $this;
    }

    public function comment($comment) : Column
    {
        $this->column_comment = $comment;
        return $this;
    }

    function avatar() : Column
    {
        $this->image();
        $this->column_avatar = true;
        return $this;
    }

    function image() : Column
    {
        return $this->file("image");
    }

    public function file($type = "all") : Column
    {
        $this->column_file = true;
        $this->column_file_type = $type;
        return $this;
    }

    public function chain_to(array $chain_to) : Column
    {
        $this->column_chain_to = $chain_to;
        return $this;
    }

    public function chain_from(array $chain_from) : Column
    {
        $this->column_chain_from = $chain_from;
        return $this;
    }

    public function chain_to_where(array $chain_to_where) : Column
    {
        $this->column_chain_to_where = $chain_to_where;
        $this->column_chain_to = array_keys($chain_to_where);
        return $this;
    }

    public function chain_from_where(array $chain_from_where) : Column
    {
        $this->column_chain_from_where = $chain_from_where;
        $this->column_chain_from = array_keys($chain_from_where);
        return $this;
    }

    public function filter_hide() : Column
    {
        $this->column_filter_visible = false;
        return $this;
    }

    public function grid_hide() : Column
    {
        $this->column_grid_visible = false;
        return $this;
    }

    public function form_hide() : Column
    {
        $this->column_form_visible = false;
        return $this;
    }

    public function detail_hide() : Column
    {
        $this->column_detail_visible = false;
        return $this;
    }

    public function model_hide() : Column
    {
        $this->column_model_visible = false;
        return $this;
    }

    public function grid_width($width) : Column
    {
        $this->column_grid_width = $width;
        return $this;
    }

    public function grid_fixed() : Column
    {
        $this->column_grid_fixed = true;
        return $this;
    }

    public function max_length($value) : Column
    {
        $this->column_max_length = $value;
        return $this;
    }

    public function min_length($value) : Column
    {
        $this->column_min_length = $value;
        return $this;
    }

    public function max_value($value) : Column
    {
        $this->column_max_value = $value;
        return $this;
    }

    public function min_value($value) : Column
    {
        $this->column_min_value = $value;
        return $this;
    }

    public function alpha() : Column
    {
        $this->column_alpha = true;
        return $this;
    }

    public function alpha_num() : Column
    {
        $this->column_alpha_num = true;
        return $this;
    }

    public function num() : Column
    {
        $this->column_num = true;
        return $this;
    }

    public function email() : Column
    {
        $this->column_email = true;
        return $this;
    }

    public function autocomplete() : Column
    {
        $this->column_autocomplete = true;
        return $this;
    }

    public function radio() : Column
    {
        $this->column_radio = true;
        return $this;
    }

    public function password() : Column
    {
        $this->column_password = true;
        return $this->max_length(30)->model_hide()->detail_hide()->grid_hide();
    }

    public function without_model_via(array $reference_column) : Column
    {
        $this->column_without_model_via = $reference_column;
        return $this;
    }

    public function toArray()
    {
        $clone = (array)$this;
        $rtn = [];

        foreach ($clone as $key => $value) {
            $aux = explode("\0", $key);
            $newkey = $aux[count($aux) - 1];
            $rtn[$newkey] = $clone[$key];
        }

        return $rtn;
    }

    public function toString()
    {
        $return = [];

        // real column or not
        if($this->real_column) {
            $return[] = "col()";
        } else {
            $return[] = "col(false)";
        }

        if($this->column_uuid) {
            $return[] = "uuid()";
        }

        if($this->column_id) {
            $return[] = "id()";
        }

        // data type
        switch ($this->type) {
            case "char":
                $return[] = "char(". ($this->size ?: 191).")";
                break;
            case "varchar":
                $return[] = "string(". ($this->size ?: 191).")";
                break;
            case "text":
                if($this->column_json && $this->cast == "array") {
                    $return[] = "jsonText()";
                } else {
                    $return[] = "text()";
                }
                break;
            case "decimal":
                $return[] = "decimal(". $this->size .",". $this->scale .")";
                break;
            case "biginteger":
                if($this->auto_increment && $this->column_primary_key && $this->column_unsigned) {
                    $return[] = "bigIncrement()";
                } else {
                    $return[] = "bigint(". $this->size .")";
                    if($this->column_primary_key) {
                        $return[] = "primaryKey()";
                    }
                    if($this->column_unsigned) {
                        $return[] = "unsigned()";
                    }
                }
                break;
            case "integer":
                if($this->auto_increment && $this->column_primary_key && $this->column_unsigned) {
                    $return[] = "increment()";
                } else {
                    $return[] = "int(". $this->size .")";
                    if($this->column_primary_key) {
                        $return[] = "primaryKey()";
                    }
                    if($this->column_unsigned) {
                        $return[] = "unsigned()";
                    }
                }
                break;
            case "tinyint":
                $return[] = "boolean()";
                break;
            case "date":
                $return[] = "date()";
                break;
            case "datetime":
                $return[] = "dateTime()";
                break;
            case "time":
                $return[] = "time()";
                break;
            case "timestamp":
                $return[] = "timestamp()";
                break;
        }

        // index
        if($this->column_index) {
            $return[] = "index()";
        }
        if($this->column_unique) {
            $return[] = "unique()";
        }

        // foreign key
        if($this->column_foreign_key) {
            $str_foreign_key = $this->column_foreign_key . "::class";
            $return[] = "foreignKey(". $str_foreign_key .")";
        }
        if($this->column_foreign_key_cascade){
            $return[] = "fkCascade()";
        }
        if($this->column_foreign_key_setnull){
            $return[] = "fkSetNull()";
        }

        if($this->column_title){
            $return[] = "title('". $this->column_title ."')";
        }
        if($this->column_required) {
            $return[] = "required()";
        }
        if($this->column_default || is_numeric($this->column_default)) {
            if(is_numeric($this->column_default)) {
                $return[] = "defaultvalue(". $this->column_default .")";
            } else {
                $return[] = "defaultvalue('". $this->column_default ."')";
            }
        }
        if($this->column_comment) {
            $return[] = "comment('". $this->column_comment ."')";
        }

        // file
        if($this->column_file) {
            switch($this->column_file_type) {
                case "image":
                    if($this->column_avatar) {
                        $return[] = "avatar()";
                    } else {
                        $return[] = "image()";
                    }
                    break;
                case "all":
                default:
                    $return[] = "file()";

            }
        }

        if($this->column_chain_to) {
            $str_chain_to = [];
            foreach($this->column_chain_to as $item_chain_to) {
                $str_chain_to[] = "self::" . strtoupper($item_chain_to);
            }
            $return[] = "chain_to([".implode(",",$str_chain_to)."])";
        }
        if($this->column_chain_from) {
            $str_chain_from = [];
            foreach($this->column_chain_from as $item_chain_from) {
                $str_chain_from[] = "self::" . strtoupper($item_chain_from);
            }
            $return[] = "chain_from([".implode(",",$str_chain_from)."])";
        }

        if(!$this->column_filter_visible) {
            $return[] = "filter_hide()";
        }
        if(!$this->column_grid_visible) {
            $return[] = "grid_hide()";
        }
        if(!$this->column_form_visible) {
            $return[] = "form_hide()";
        }
        if(!$this->column_detail_visible) {
            $return[] = "detail_hide()";
        }
        if(!$this->column_model_visible) {
            $return[] = "model_hide()";
        }

        if($this->column_grid_width) {
            $return[] = "grid_width(".$this->column_grid_width.")";
        }
        if($this->column_grid_fixed) {
            $return[] = "grid_fixed()";
        }

        if($this->column_max_length) {
            $return[] = "max_length(". $this->column_max_length .")";
        }
        if($this->column_min_length) {
            $return[] = "min_length(". $this->column_min_length .")";
        }
        if($this->column_max_value) {
            $return[] = "max_value(". $this->column_max_value .")";
        }
        if($this->column_min_value) {
            $return[] = "min_value(". $this->column_min_value .")";
        }
        if($this->column_alpha) {
            $return[] = "alpha()";
        }
        if($this->column_alpha_num) {
            $return[] = "alpha_num()";
        }
        if($this->column_num) {
            $return[] = "num()";
        }
        if($this->column_email) {
            $return[] = "email()";
        }
        if($this->column_autocomplete) {
            $return[] = "autocomplete()";
        }
        if($this->column_radio) {
            $return[] = "radio()";
        }
        if($this->column_password) {
            $return[] = "password()";
        }
        if($this->column_without_model_via) {
            $str_without_model_via = [];
            foreach($this->column_without_model_via as $item_without_model_via) {
                $str_without_model_via[] = "self::" . strtoupper($item_without_model_via);
            }
            $return[] = "without_model_via([".implode(",",$str_without_model_via)."])";
        }

        return implode("->",$return);
    }

    public function toLaravelMigration($column_name) : array
    {
        $return = [];

        $field_definition = $this->toLaravelFieldDefinition($column_name);
        if($field_definition) {
            $return[] = [
                "up" => $field_definition,
                "down" => '$table->'."dropColumn('$column_name')",
            ];
        }

        $foreignkey_definition = $this->toLaravelForeignKey($column_name);
        if($foreignkey_definition) {
            $return[] = [
                "up" => $foreignkey_definition,
                "down" => '$table->'."dropForeign(['$column_name'])",
            ];
        }

        return $return;
    }

    protected function toLaravelFieldDefinition($column_name)
    {
        $return = [];

        // real column or not
        if(!$this->real_column) {
            return "";
        } else {
            $return[] = '$table';
        }

        $pk_definition_flag = false;

        // data type
        switch ($this->type) {
            case "char":
                $return[] = "char('$column_name',". ($this->size ?: 191).")";
                break;
            case "varchar":
                $return[] = "string('$column_name',". ($this->size ?: 191).")";
                break;
            case "text":
                if($this->column_json && $this->cast == "array") {
                    $return[] = "json('$column_name')";
                } else {
                    $return[] = "text('$column_name')";
                }
                break;
            case "decimal":
                $return[] = "decimal('$column_name',". $this->size .",". $this->scale .")";
                break;
            case "biginteger":
                if($this->auto_increment && $this->column_primary_key && $this->column_unsigned) {
                    $this->column_required = true;
                    $return[] = "bigIncrements('$column_name')";
                    $pk_definition_flag = true;
                } else {
                    $return[] = "bigInteger('$column_name')";
                    if($this->column_unsigned || $this->column_foreign_key) {
                        $return[] = "unsigned()";
                    }
                }
                break;
            case "integer":
                if($this->auto_increment && $this->column_primary_key && $this->column_unsigned) {
                    $this->column_required = true;
                    $return[] = "increments('$column_name')";
                    $pk_definition_flag = true;
                } else {
                    $return[] = "integer('$column_name')";
                    if($this->column_unsigned || $this->column_foreign_key) {
                        $return[] = "unsigned()";
                    }
                }
                break;
            case "tinyint":
                $return[] = "boolean('$column_name')";
                break;
            case "date":
                $return[] = "date('$column_name')";
                break;
            case "datetime":
                $return[] = "dateTime('$column_name')";
                break;
            case "time":
                $return[] = "time('$column_name')";
                break;
            case "timestamp":
                $return[] = "timestamp('$column_name')";
                break;
        }

        if(!$this->column_required) {
            $return[] = "nullable()";
        }

        if($this->column_default || is_numeric($this->column_default)) {
            if(is_numeric($this->column_default)) {
                $return[] = "default(". $this->column_default .")";
            } else {
                $return[] = "default('". $this->column_default ."')";
            }
        }

        if($this->column_comment) {
            $return[] = "comment('". $this->column_comment ."')";
        }

        if($this->column_primary_key && !$pk_definition_flag) {
            $return[] = "primary()";
        }

        return implode("->",$return);
    }

    protected function toLaravelForeignKey($column_name)
    {
        // foreign key
        if($this->column_foreign_key) {
            $str_foreign_key = ($this->column_foreign_key)::TABLENAME;
            $return = [];
            $return[] = '$table';
            $return[] = "foreign('". $column_name ."')";
            $return[] = "references('id')";
            $return[] = "on('$str_foreign_key')";
            if($this->column_foreign_key_cascade) {
                $return[] = "onUpdate('cascade')";
                $return[] = "onDelete('cascade')";
            }
            if($this->column_foreign_key_setnull) {
                $return[] = "onUpdate('set null')";
                $return[] = "onDelete('set null')";
            }
            return implode("->",$return);
        } else {
            return null;
        }
    }

    public function getVariabelType() : string
    {
        $variableType = "";
        switch ($this->type) {
            case Column::TYPE_CHAR:
            case Column::TYPE_VARCHAR:
            case Column::TYPE_TEXT:
                if($this->column_json && $this->cast == "array") {
                    $variableType = Column::VAR_ARRAY;
                } else {
                    $variableType = Column::VAR_STRING;
                }
                break;
            case Column::TYPE_DECIMAL:
                $variableType = Column::VAR_DOUBLE;
                break;
            case Column::TYPE_BIGINT:
            case Column::TYPE_INT:
                $variableType = Column::VAR_INTEGER;
                break;
            case Column::TYPE_TINYINT:
                $variableType = Column::VAR_BOOLEAN;
                break;
            case Column::TYPE_DATE:
            case Column::TYPE_DATETIME:
            case Column::TYPE_TIME:
            case Column::TYPE_TIMESTAMP:
                $variableType = Column::VAR_DATETIME;
                break;
        }

        return $variableType;
    }

    public function __toString()
    {
        return $this->toString();
    }
}