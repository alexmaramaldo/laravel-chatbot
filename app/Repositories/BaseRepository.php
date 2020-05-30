<?php

namespace App\Repositories;

class BaseRepository
{
    protected $obj;

    protected function __construct(object $obj)
    {
        $this->obj = $obj;
    }

    /**
     * Get all ocurrencies from the table
     *
     * @return object
     */
    public function all(): object
    {
        return $this->obj->all();
    }

    /**
     * Find a ocurrency on the table by id
     *
     * @param int $id Searched id
     *
     * @return object
     */
    public function find(int $id): object
    {
        return $this->obj->find($id);
    }

    /**
     * Find a ocurrency on the table by the column informed
     *
     * @param string $column Column to be searched
     * @param mixed $value Searched value
     *
     * @return object
     */
    public function findByColumn(string $column, $value): object
    {
        return $this->obj->where($column, $value)->get();
    }

    /**
     * Insert a new register on the table
     *
     * @param array $attributes Values to be save
     *
     * @return bool
     */
    public function insert(array $attributes): bool
    {
        return $this->obj->insert($attributes);
    }

    /**
     * Create a new register on the table
     *
     * @param array $attributes Values to be save
     *
     * @return object
     */
    public function create(array $attributes): object
    {
        return $this->obj::create($attributes);
    }

    /**
     * Update a register on the table
     *
     * @param int $id Id from the register to be save
     * @param array $attributes Values to be save
     *
     * @return bool
     */
    public function update(int $id, array $attributes): bool
    {
        return $this->obj->find($id)->update($attributes);
    }
}
