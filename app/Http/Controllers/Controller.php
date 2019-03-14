<?php

namespace App\Http\Controllers;

use App\Exceptions\Error;
use Box\Spout\Common\Type;
use Box\Spout\Writer\WriterFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    const DB_STR_SEPARATOR = ' | ';

    protected $perPageMax = 500;
    protected $perPage    = 30;
    protected $model;
    protected $request;
    protected $searchFields                = [];
    protected $filterFields                = [];
    protected $orderFields                 = [];
    protected $timestampFields             = ['created_at', 'updated_at'];
    protected $rules                       = [];
    protected $messages                    = [];
    protected $customAttributes            = [];
    protected $withTables                  = [];
    protected $exportNumber                = 10000;
    protected $exportColumns               = [];
    protected $exportMaps                  = [];
    protected $exportFormatToStringColumns = [];
    protected $filterValue                 = null;

    protected $operators = [
        '=', '<', '>', '<=', '>=', '<>', '!=',
        'like', 'in', 'between',
    ];

    protected $modelCache = [];

    const FILTER_FIELD    = 0;
    const FILTER_OPERATOR = 1;
    const FILTER_VALUE    = 2;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function sayHello()
    {
        return 'Hello world!';
    }

    /**
     * @param string $model
     * @param bool $forceCreate
     * @return Builder
     * @throws Error
     */
    protected function getModelQuery($model = '', $forceCreate = false)
    {
        if (empty($this->model) && empty($model)) {
            throw new Error(500, 1, 'model设置有误');
        }

        $model = empty($model) ? $this->model : $model;
        if ($forceCreate) {
            return app($this->model)->query();
        }

        if (isset($this->modelCache[$model])) {
            return $this->modelCache[$model];
        }

        $this->modelCache[$model] = app($this->model)->query();
        return $this->modelCache[$model];
    }

    protected function beforeIndex()
    {
    }

    public function index()
    {
        $this->beforeIndex();
        $Query = $this->getModelQuery();
        $this->prepareSelect($Query);
        $this->prepareFilter($Query);
        $this->prepareSort($Query);
        $Models = $Query->paginate($this->getPerPage());
        if (method_exists($this, 'afterIndex')) {
            $this->afterIndex($Models);
        }

        return $this->response($Models);
    }

    protected function beforeStore()
    {
    }

    public function store()
    {
        $this->beforeStore();

        $this->validate($this->request, $this->rules, $this->messages, $this->customAttributes);
        $Model = $this->getModelQuery()->getModel()->create($this->request->all());

        $this->afterStore($Model);
        return $this->response($Model);
    }

    protected function afterStore($Model)
    {
    }

    protected function beforeShow($id)
    {
    }

    public function show($id)
    {
        $this->beforeShow($id);

        $Query = $this->getModelQuery();
        $this->prepareSelect($Query);
        $this->prepareFilter($Query);
        $Model = $Query->findOrFail($id);

        $this->afterShow($Model);
        return $this->response($Model);
    }

    protected function afterShow($Model)
    {
    }

    protected function beforeUpdate($id)
    {
    }

    public function update($id)
    {
        $this->beforeUpdate($id);

        $this->validate($this->request, $this->rules, $this->messages, $this->customAttributes);
        $Model = $this->getModelQuery()->findOrFail($id);
        $Model->fill($this->request->all());
        $Model->save();

        $this->afterUpdate($Model);
        return $this->response($Model);
    }

    protected function afterUpdate($Model)
    {
    }

    protected function beforeDestroy($id)
    {
    }

    public function destroy($id)
    {
        $this->beforeDestroy($id);

        $Model     = $this->getModelQuery()->findOrFail($id);
        $isSuccess = $Model->delete();
        if (!$isSuccess) {
            throw new Error(500);
        }

        $this->afterDestroy($Model);
        return $this->response();
    }

    protected function afterDestroy($Model)
    {
    }

    protected function beforeStatistics()
    {

    }

    public function statistics()
    {
        $this->beforeStatistics();

        $Query = $this->getModelQuery();
        $this->prepareFilter($Query);
        $this->prepareSort($Query);

        return $this->exportHandler($Query);
    }

    protected function exportHandler($Query, $Models = null)
    {
        switch ($this->request->input('format')) {
            case 'excel':
                if ($Models === null) {
                    $Models = $Query->limit($this->getExportNumber())->get();
                }

                $handler = 'boxSpoutExportExcel';
                break;

            case 'csv':
                if ($Models === null) {
                    $Models = $Query->limit($this->getExportNumber())->get();
                }

                $handler = 'exportCsv';
                break;

            default:
                if ($Models === null) {
                    $Models = $Query->paginate($this->getPerPage());
                }

                $handler = 'response';
                break;
        }

        if (method_exists($this, 'afterStatistics')) {
            $this->afterStatistics($Models);
        }

        return $this->{$handler}($Models);

    }

    protected function exportCsv($Models)
    {
        if (empty($this->exportColumns)) {
            throw new Error(500, 'COLUMNS_IS_EMPTY');
        }

        $filename = Arr::pull($this->exportColumns, 'filename', '导出报表');
        $fp       = fopen('php://memory', 'w+');
        fputs($fp, "\xEF\xBB\xBF");
        fputcsv($fp, array_keys($this->exportColumns));
        if (!empty($Models)) {
            foreach ($Models as $Model) {
                $row = [];
                foreach ($this->exportColumns as $column) {
                    $row[] = $this->getValue($Model, $column);
                }
                fputcsv($fp, $row);
            }
        }

        $date = date('Y-m-d H:i:s');
        header("Content-Disposition: attachment; filename=\"{$filename}-{$date}.csv\"");
        header("Cache-control: private");
        header("Content-type: text/csv; charset=utf-8");
        rewind($fp);
        echo iconv("UTF-8", "UNICODE", stream_get_contents($fp));
        fclose($fp);
    }

    protected function boxSpoutExportExcel($Models)
    {
        if (empty($this->exportColumns)) {
            throw new Error(500, 'COLUMNS_IS_EMPTY');
        }

        $filename = Arr::pull($this->exportColumns, 'filename', '导出报表');
        $rows     = $this->exportFormat($Models);

        $this->setExportHeaders($filename);
        return response()->streamDownload(function () use ($filename, $rows) {
            $writer = WriterFactory::create(Type::XLSX);
            $writer->openToFile('php://output');
            $writer->addRows($rows);
            $writer->close();
        });
    }

    protected function exportFormat($Models)
    {
        $rows[0]       = array_keys($this->exportColumns);
        $formatColumns = [];
        if (!empty($Models)) {
            if (!empty($this->exportFormatToStringColumns)) {
                $exportColumns = array_flip(array_values($this->exportColumns));
                foreach ($this->exportFormatToStringColumns as $formatColumn) {
                    if (isset($exportColumns[$formatColumn])) {
                        $formatColumns[] = chr(ord('A') + $exportColumns[$formatColumn]);
                    }
                }
            }

            foreach ($Models as $Model) {
                $row = [];
                foreach ($this->exportColumns as $column) {
                    $row[] = (string) $this->getValue($Model, $column);
                }
                $rows[] = $row;
            }
        }

        return $rows;
    }

    protected function setExportHeaders($filename)
    {
        $date = date('Y-m-d H:i:s');
        header('Content-Type: application/vnd.ms-excel; charset=utf-8"');
        header("Content-Disposition: attachment; filename=\"{$filename}-{$date}.xlsx\"");
        header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
        header("Cache-control: private", false);
    }

    private function getValue($Model, $column)
    {
        $part  = [];
        $value = '';
        if (strpos($column, '.') !== false) {
            $part = explode('.', $column);
        }

        switch (count($part)) {
            case 0:
            case 1:
                $value = isset($Model->{$column}) ? $Model->{$column} : '';
                break;

            case 2:
                $value = isset($Model->{$part[0]}->{$part[1]}) ? $Model->{$part[0]}->{$part[1]} : '';
                break;

            case 3:
                $value = isset($Model->{$part[0]}->{$part[1]}->{$part[2]}) ? $Model->{$part[0]}->{$part[1]}->{$part[2]} : '';
                break;
        }

        if (isset($this->exportMaps[$column])) {
            if (is_callable($this->exportMaps[$column])) {
                $value = $this->exportMaps[$column]($Model, $column);
            } elseif (isset($this->exportMaps[$column][$value])) {
                $value = $this->exportMaps[$column][$value];
            }
        }

        return $value;
    }

    protected function prepareFilter(Builder $Query)
    {
        $filter = $this->getFilter();
        if (empty($filter)) {
            return;
        }

        $Query->where(function (Builder $query) use ($filter) {
            foreach ($filter as $condition) {
                if (!isset($condition[self::FILTER_OPERATOR])
                    || !isset($condition[self::FILTER_FIELD])
                    || !isset($condition[self::FILTER_VALUE])) {
                    continue;
                }

                $operator = $condition[self::FILTER_OPERATOR];
                // 检查字段,表达式,值是否可用
                if (!in_array($operator, $this->operators, true)
                    || !in_array($condition[self::FILTER_FIELD], $this->filterFields, true)
                    || $condition[self::FILTER_VALUE] === '' || $condition[self::FILTER_VALUE] === null) {
                    continue;
                }

                // 如果搜索由数据库维护的时间信息，则自动转成数据库TIMESTAMP查找格式
                if (in_array($condition[self::FILTER_FIELD], $this->timestampFields)) {
                    if (is_numeric($condition[self::FILTER_VALUE])) {
                        $condition[self::FILTER_VALUE] = date('Y-m-d H:i:s', $condition[self::FILTER_VALUE]);
                    }

                    if (is_array($condition[self::FILTER_VALUE])) {
                        $condition[self::FILTER_VALUE][0] = date('Y-m-d H:i:s', $condition[self::FILTER_VALUE][0]);
                        $condition[self::FILTER_VALUE][1] = date('Y-m-d H:i:s', $condition[self::FILTER_VALUE][1]);
                    }
                }

                if ($operator === 'like') {
                    if (!in_array($condition[self::FILTER_FIELD], $this->searchFields, true)) {
                        continue;
                    }
                    $condition[self::FILTER_VALUE] = "%{$condition[self::FILTER_VALUE]}%";
                }

                if ($operator === 'in' && is_array($condition[self::FILTER_VALUE])) {
                    $query->whereIn($condition[self::FILTER_FIELD], $condition[self::FILTER_VALUE]);
                    continue;
                }

                if ($operator === 'between') {
                    $query->whereBetween($condition[self::FILTER_FIELD], $condition[self::FILTER_VALUE]);
                    continue;
                }

                $query->where($condition[self::FILTER_FIELD], $condition[self::FILTER_OPERATOR], $condition[self::FILTER_VALUE]);
            }
        });
    }

    protected function prepareSort(Builder $Query)
    {
        $modelTable = $this->getModelQuery()->getModel()->getTable();
        $orderBy    = $this->request->input('order_by', $modelTable . '.id');
        if (in_array($orderBy, $this->orderFields)) {
            $Query->orderBy(
                $orderBy,
                $this->request->input('sort', 'desc')
            );
        } else {
            $Query->orderBy($modelTable . '.id', 'desc');
        }
    }

    protected function prepareSelect(Builder $Query)
    {
        $select = $this->request->input('select');
        if (empty($select)) {
            return;
        }

        $selectArr        = explode(',', $select);
        $otherTableFields = [];
        $fields           = [];
        foreach ($selectArr as $field) {
            if (empty($field)) {
                continue;
            }

            if (strpos($field, '.', 1) === false) {
                $fields[] = $field;
                continue;
            }

            $fields = explode('.', $field);
            $table  = array_shift($fields);
            if (!in_array($table, $this->withTables, true)) {
                continue;
            }

            $otherTableFields[$table][] = ['id'];
            $otherTableFields[$table][] = $fields;
            // $fields[]                   = "{$table}_id";
        }

        // $Model = $Query->getModel();
        // $ignoreFields = $Model::$ignoreFields;
        // 暂时去掉select,回去后前端配合做
        // $diffFields   = array_diff($fields, $ignoreFields) ?: [];
        // $Query->select($diffFields);
        foreach ($otherTableFields as $table => $tableFields) {
            $Query->withSelect(Str::studly($table), $tableFields);
        }
    }

    protected function getPerPage()
    {
        $perPage = $this->request->input('per_page', $this->perPage);
        return max(min($perPage, $this->perPageMax), 1);
    }

    protected function getExportNumber()
    {
        $exportNumber = $this->request->input('export_number', $this->exportNumber);
        return $exportNumber > $this->exportNumber ? $this->exportNumber : $exportNumber;
    }

    protected function getFilter()
    {
        if ($this->filterValue === null) {
            $filters = json_decode($this->request->input('filter', '[]'), true);

            if (empty($filters)) {
                return null;
            }

            foreach ($filters as $filter) {
                if (isset($this->filterValue[$filter[self::FILTER_FIELD]])) {
                    $valueA                                         = $this->filterValue[$filter[self::FILTER_FIELD]][self::FILTER_VALUE];
                    $valueB                                         = $filter[self::FILTER_VALUE];
                    $this->filterValue[$filter[self::FILTER_FIELD]] = [
                        $filter[self::FILTER_FIELD],
                        'between',
                        [min($valueA, $valueB), max($valueA, $valueB)],
                    ];
                } else {
                    $this->filterValue[$filter[self::FILTER_FIELD]] = $filter;
                }
            }
        }

        return $this->filterValue;
    }

    protected function response($data = [], $msg = 'success', $code = 0, $statusCode = 200)
    {
        return response(json_encode([
            'data'        => empty($data) ? [] : $data,
            'msg'         => $msg,
            'code'        => $code,
            'status_code' => $statusCode,
        ], JSON_UNESCAPED_UNICODE), $statusCode)->header('Content-Type', 'application/json');
    }
}
