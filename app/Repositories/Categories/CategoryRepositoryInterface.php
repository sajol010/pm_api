<?php

namespace App\Repositories\Categories;

interface CategoryRepositoryInterface
{
    public function all($params = []);

    public function save(): array;

    public function update($id): array;

    public function findById($id);

    public function delete($id);
}
