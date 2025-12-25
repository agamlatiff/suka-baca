<?php

namespace App\Repositories\Eloquent;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

abstract class BaseRepository
{
  protected Model $model;

  public function __construct(Model $model)
  {
    $this->model = $model;
  }

  public function all(): Collection
  {
    return $this->model->all();
  }

  public function find(int $id): ?object
  {
    return $this->model->find($id);
  }

  public function findOrFail(int $id): object
  {
    return $this->model->findOrFail($id);
  }

  public function create(array $data): object
  {
    return $this->model->create($data);
  }

  public function update(int $id, array $data): bool
  {
    $record = $this->findOrFail($id);
    return $record->update($data);
  }

  public function delete(int $id): bool
  {
    $record = $this->findOrFail($id);
    return $record->delete();
  }

  public function paginate(int $perPage = 15): LengthAwarePaginator
  {
    return $this->model->paginate($perPage);
  }
}
