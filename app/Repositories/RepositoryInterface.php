<?php

namespace App\Repositories;

interface RepositoryInterface
{
    public function getCollection($options);

    public function getSingle($id);

    public function getBy($field, $value);

    public function create(array $data);

    public function update($id, array $data);

    public function delete($id);

    public function getModel();
}
