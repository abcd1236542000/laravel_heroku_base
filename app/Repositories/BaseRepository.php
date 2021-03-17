<?php

namespace App\Repositories;

abstract class BaseRepository
{
    public function firstOrCreate($where_data, $where_in_data = [])
    {
        return $this->query()->firstOrCreate($where_data, $where_in_data);
    }

    public function create($data)
    {
        return $this->query()->create($data);
    }

    public function find($id)
    {
        return $this->query()->findOrFail($id);
    }

    public function getOne($where_data, $where_in_data = [], $used_lock = false)
    {
        return $this->query()->where(function ($query) use ($where_data, $where_in_data, $used_lock) {
            foreach ($where_data as $key => $value) {
                $query->where($key, $value);
            }
            foreach ($where_in_data as $key => $value) {
                $query->whereIn($key, $value);
            }
            if ($used_lock) {
                $query->lockForUpdate();
            }
        })->first();
    }

    public function getAll($where_data, $where_in_data = [], $used_lock = false)
    {
        return $this->query()->where(function ($query) use ($where_data, $where_in_data, $used_lock) {
            foreach ($where_data as $key => $value) {
                $query->where($key, $value);
            }
            foreach ($where_in_data as $key => $value) {
                $query->whereIn($key, $value);
            }
            if ($used_lock) {
                $query->lockForUpdate();
            }
        })->get();
    }

    public function getCount($where_data, $where_in_data = [])
    {
        return $this->query()->where(function ($query) use ($where_data, $where_in_data) {
            foreach ($where_data as $key => $value) {
                $query->where($key, $value);
            }
            foreach ($where_in_data as $key => $value) {
                $query->whereIn($key, $value);
            }
        })->count();
    }

    public function getAllByPage($where_data = [], $where_in_data = [], $per_page = 10)
    {
        return $this->query()->where(function ($query) use ($where_data, $where_in_data) {
            foreach ($where_data as $key => $value) {
                $query->where($key, $value);
            }
            foreach ($where_in_data as $key => $value) {
                $query->whereIn($key, $value);
            }
        })->paginate($per_page);
    }

    public function update($where_data, $update_data)
    {
        return $this->query()->where(function ($query) use ($where_data) {
            foreach ($where_data as $key => $value) {
                $query->where($key, $value);
            }
        })->update($update_data);
    }

    public function updateOrCreate($v_data, $update_data)
    {
        return $this->query()->updateOrCreate($v_data, $update_data);
    }

    public function increment($where_data, $increment_column, $increment_data = 1)
    {
        return $this->query()->where(function ($query) use ($where_data) {
            foreach ($where_data as $key => $value) {
                $query->where($key, $value);
            }
        })->increment($increment_column, $increment_data);
    }

    public function decrement($where_data, $decrement_column, $decrement_data = 1)
    {
        return $this->query()->where(function ($query) use ($where_data) {
            foreach ($where_data as $key => $value) {
                $query->where($key, $value);
            }
        })->decrement($decrement_column, $decrement_data);
    }

    public function delete($where_data, $where_in_data = [])
    {
        return $this->query()->where(function ($query) use ($where_data, $where_in_data) {
            foreach ($where_data as $key => $value) {
                $query->where($key, $value);
            }
            foreach ($where_in_data as $key => $value) {
                $query->whereIn($key, $value);
            }
        })->delete();
    }

    public function query()
    {
        return call_user_func(static::MODEL . '::query');
    }
}
