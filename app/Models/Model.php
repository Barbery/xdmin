<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;
use Illuminate\Support\Str;

class Model extends BaseModel
{
    public static $ignoreFields = [];
    public $timestamps          = false;
    const NO                    = 0;
    const YES                   = 1;
    const ENABLE                = 1;
    const DISABLE               = 2;
    const UNKNOW                = 0;

    protected $hidden = ['deleted_at', 'pivot', 'password', 'secret'];
    private $modelMap = [
    ];
    public static $boolMap = [
        self::NO  => '否',
        self::YES => '是',
    ];

    public static $turnMaps = [
        self::ENABLE  => '启用',
        self::DISABLE => '禁用',
    ];

    public function scopeWithSelect($Query, $table, $fields, $level = 1)
    {
        $modelName = ucfirst(Str::singular($table));
        $class     = 'App\\Models\\' . ($this->modelMap[$modelName] ?? $modelName);
        $fields    = array_diff($fields, $class::$ignoreFields);

        if ($level > 2) {
            return;
        }

        return $Query->with([$table => function ($Query) use ($fields, $level) {
            $column         = [];
            $subTableFileds = [];
            foreach ($fields as $fieldArr) {
                if (count($fieldArr) === 1) {
                    $column[] = $fieldArr[0];
                } elseif (count($fieldArr) === 2) {
                    $table                    = array_shift($fieldArr);
                    $tableName                = Str::plural($table);
                    $subTableFileds[$table][] = ["{$tableName}.id"];
                    $subTableFileds[$table][] = $fieldArr;
                }
            }

            $Query->select($column);
            foreach ($subTableFileds as $table => $tableFields) {
                $Query->withSelect(Str::studly($table), $tableFields, $level + 1);
            }
        }]);
    }

    public static function getSelection()
    {
        return static::pluck('name', 'id')->toArray();
    }

}
