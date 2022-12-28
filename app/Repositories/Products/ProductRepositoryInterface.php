<?php

namespace App\Repositories\Products;

interface ProductRepositoryInterface
{
    public function all($params = []);

    public function save(): array;

    public function update($id): array;

    public function findById($id);

    public function delete($id);
}
