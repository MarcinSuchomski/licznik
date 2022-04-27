<?php

namespace App\Services;

interface ServiceInterface
{
    public function getCollection($options);

    public function getSingle($id);

    public function createSingle(array $data);

    public function updateSingle($id, array $data);

    public function deleteSingle($id);
}
