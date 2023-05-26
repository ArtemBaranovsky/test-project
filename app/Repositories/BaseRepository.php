<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository
{
    protected Model $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function find($id){
        return $this->model::find($id);
    }

    public function getAll(): Collection
    {
        return $this->model::all();
    }

    public function create($data){
        return $this->model::create($data);
    }

    public function update($id, $data){
        $model = $this->model::findOrFail($id);
        $model->fill($data);
        $model->save();

        return $model;
    }

    public function delete($id){
        $model = $this->model::findOrFail($id);
        $model->delete();
    }
}
