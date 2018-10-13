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

    public $column_title;
    public $column_required = false;
    public $column_unsigned;
    public $column_comment;

    public $column_file;
    public $column_file_type;
    public $column_avatar;

    public $column_chain_to;
    public $column_chain_from;

    public $column_filter_visible = true;
    public $column_grid_visible = true;
    public $column_grid_width;
    public $column_grid_fixed = false;
    public $column_form_visible = true;
    public $column_detail_visible = true;
    public $column_model_visible = true;

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

    /**
     * Column constructor.
     * @param $real_column
     */
    public function __construct($real_column = true)
    {
        $this->real_column = $real_column;
    }

    public function boolean() : Column
    {
        $this->cast = "boolean";
        return $this->dataType("tinyint", 1);
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
        return $this->dataType("char", $size);
    }

    public function text() : Column
    {
        return $this->dataType("text");
    }

    public function string($size = 191) : Column
    {
        return $this->varchar($size);
    }

    public function varchar($size = 191) : Column
    {
        return $this->dataType("varchar", $size);
    }

    public function date() : Column
    {
        $this->cast = "date";
        return $this->dataType("date");
    }

    public function dateTime() : Column
    {
        $this->cast = "datetime";
        return $this->dataType("datetime");
    }

    public function time() : Column
    {
        $this->cast = "timestamp";
        return $this->dataType("time");
    }

    public function decimal($size, $scale = 0) : Column
    {
        return $this->dataType("decimal", $size, $scale);
    }

    public function jsonText() : Column
    {
        $this->cast = "array";
        $this->column_json = true;
        return $this->dataType("text");
    }

    public function bigIncrement() : Column
    {
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
        return $this->dataType("biginteger", $size);
    }

    public function int($size = 11) : Column
    {
        return $this->dataType("integer", $size);
    }

    public function primaryKey() : Column
    {
        $this->column_primary_key = true;
        return $this;
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

    public function form_radio() : Column
    {
        $this->column_radio = true;
        return $this;
    }

    public function password() : Column
    {
        $this->column_password = true;
        return $this;
    }


    function toArray()
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
}