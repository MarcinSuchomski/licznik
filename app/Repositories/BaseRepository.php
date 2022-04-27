<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class BaseRepository
{

    /**
     * @var $query
     * QueryBuilder instance
     */
    protected $query;

    protected $wheres_applied = false;
    protected $filters_applied = false;
    protected $order_by_applied = false;
    protected $group_by_applied = false;

    /**
     * @return Model
     */
    public abstract function getModel();

    public function resetQuery()
    {
        $this->wheres_applied = false;
        $this->filters_applied = false;
        $this->order_by_applied = false;
        $this->group_by_applied = false;

        return $this->query = $this->setQuery();
    }

    /**
     * setQuery method
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function setQuery()
    {
        return $this->getModel()->newQuery();
    }

    /**
     * getQuery method
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getQuery()
    {
        return $this->query ?: $this->setQuery();
    }

    /**
     * Set all query filters
     *
     * @param array $options
     * @return mixed
     */
    protected function setFilters(array $options)
    {
        return $this->query;
    }

    /**
     * Apply all filters to QueryBuilder
     *
     * @param array $options
     */
    protected function applyFilters(array $options)
    {
        if (!$this->filters_applied) {
            $this->setFilters($options);
            $this->filters_applied = true;
        }
    }

    /**
     * Apply all additional where clauses to QueryBuilder
     *
     * @param array $options
     *
     * @example
     * $options['where'] =
     *    [
     *      [
     *          'column' => 'id',
     *          'table'   => table name here; when null model table is used,
     *          'value'   => value,
     *          'condition' => '=', "<", etc, when null "=" is used
     *      ]
     *    ],
     *
     */
    protected function applyWheres(array $options)
    {
        if (!empty($options['where']) && !$this->wheres_applied && is_array($options['where'])) {
            $model = $this->getModel();
            $model_table = $model->getTable();
            $wheres = $options['where'];

            foreach ($wheres as $where) {
                $table = !empty($where['table']) ? $where['table'] : $model_table;
                $condition = !empty($where['condition']) ? $where['condition'] : '=';

                if ($where['column'] == 'uuid' && method_exists($model, 'uuidColumn')) {
                    $this->query->whereUuid($where['value']);
                } else {
                    $this->query->where(
                        $table . '.' . $where['column'], $condition, $where['value']
                    );
                }
            }

            $this->wheres_applied = true;
        }

    }

    /**
     * Set all query group by's
     *
     * @param array $options
     * @return mixed
     */
    protected function setGroupBy()
    {
        return $this->query;
    }

    /**
     * Apply all group by clauses to QueryBuilder
     *
     * @param array $options
     */
    protected function applyGroupBy()
    {
        if (!$this->group_by_applied) {
            $this->setGroupBy();
            $this->group_by_applied = true;
        }
    }

    /**
     * Apply all order by to QueryBuilder
     *
     * @param array $options
     */
    protected function applyOrderBy(array $options)
    {
        if (!empty($options['orders']) && is_array($options['orders'])) {
            foreach ($options['orders'] as $order) {
                $this->query->orderBy($order['field'], $order['direction']);
            }
        }

        $this->order_by_applied = true;

    }

    /**
     * Get collection method
     *
     * @param array $options
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator|\Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getCollection(array $options)
    {
        $this->query = $this->getQuery();

        $this->applyWheres($options);
        $this->applyFilters($options);
        $this->applyOrderBy($options);
        $this->applyGroupBy();

//        dd($this->query->toSql(), $this->query->getBindings());

        // return paginated results
        if (!empty($options['page'])) {
            return $this->query->paginate($options['page']['num'], ['*'], 'page', $options['page']['page'] + 1);
        }

        // return all collection
        return $this->query->get();
    }

    /**
     * Get Count of Collection
     *
     * @param $options
     * @return int
     */
    public function getCountCollection($options)
    {
        $query = $this->getQuery();
        $this->applyWheres($options);
        $this->applyFilters($options);

        return $query->count();
    }

    /**
     * Get count of total items
     *
     * @param $options
     * @return int
     */
    public function getTotal($options)
    {
        $query = $this->getQuery();
        $this->applyWheres($options);

        return $query->count();

    }

    /**
     * Get model item by key and value
     *
     * @param $field
     * @param $value
     * @return mixed
     * @throws ModelNotFoundException
     */
    public function firstOrFailBy($field, $value)
    {
        return $this->getModel()
            ->where($field, $value)
            ->firstOrFail();
    }

    /**
     * Get Collection by UUID
     *
     * @param array $uuids
     * @return mixed
     */
    public function getCollectionByUuid(array $uuids)
    {
        return $this->getModel()->whereUuidIn($uuids)->get();
    }

    /**
     * @param $uuid_or_id
     * @param bool $is_id
     * @return Model
     * @throws Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function getSingle($uuid_or_id, $is_id = false)
    {
        $model = $this->getModel();
        if (method_exists($model, 'uuidColumn') && !$is_id) {
            return $model->whereUuid($uuid_or_id)
                ->firstOrFail();
        }

        return $model->findOrFail($uuid_or_id);
    }

    /**
     * Get model item by key and value
     *
     * @param $field
     * @param $value
     * @return mixed
     */
    public function getBy($field, $value)
    {
        $obj = $this->getModel();
        return $obj->where($field, $value)->get();
    }

    /**
     * Create new instance of model
     *
     * @param array $data
     * @param bool $return_object
     * @return array|Model
     */
    public function create(array $data, bool $return_object = false)
    {
        $obj = $this->getModel()
            ->create($data);

        return $return_object ? $obj : ["id" => $obj->id];
    }


    /**
     * Update Model
     *
     * @param $id
     * @param array $data
     * @param bool $return_object
     * @return array|Model
     * @throws \Exception
     */
    public function update($id, array $data, bool $return_object = false)
    {
        $obj = $this->getSingle($id);
        if (empty($obj)) {
            throw new \Exception("Entity not found for update.");
        }
        $obj->fill($data);
        $obj->save();

        return $return_object ? $obj : ["id" => $obj->id];
    }

    /**
     * Delete Model
     *
     * @param $id
     * @return array
     * @throws \Exception
     */
    public function delete($id)
    {
        $obj = $this->getSingle($id);
        $obj->delete();
        return ["id" => $id];
    }

    /**
     * Enable transaction
     *
     * @param $closure
     * @return mixed
     * @throws \Throwable
     */
    public function transaction($closure)
    {
        return $this->getModel()->getConnection()->transaction($closure);
    }
}
