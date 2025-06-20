<?php

declare(strict_types=1);

namespace App\DataTables;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Validation\Rule;
use Yajra\DataTables\DataTablesEditor;

/**
 * @extends DataTablesEditor<User>
 */
class UsersDataTableEditor extends DataTablesEditor
{
    protected $model = User::class;

    /**
     * Get create action validation rules.
     */
    public function createRules(): array
    {
        return [
            'email' => 'required|email|max:255|unique:'.$this->resolveModel()->getTable(),
            'name' => 'required|max:255',
            'password' => 'required||max:255|confirmed',
        ];
    }

    /**
     * Get edit action validation rules.
     */
    public function editRules(Model $model): array
    {
        return [
            'email' => 'sometimes|required|max:255|email|'.Rule::unique($model->getTable())->ignore($model->getKey()),
            'name' => 'sometimes|required|max:255',
            'password' => 'sometimes|required|max:255',
        ];
    }

    /**
     * Get remove action validation rules.
     */
    public function removeRules(Model $model): array
    {
        return [];
    }
    /**
     * Event hook that is fired after `creating` and `updating` hooks, but before
     * the model is saved to the database.
     */
    public function saving(Model $model, array $data): array
    {
        // Before saving the model, hash the password.
        if (! empty(data_get($data, 'password'))) {
            data_set($data, 'password', bcrypt($data['password']));
        }

        return $data;
    }

    /**
     * Event hook that is fired after `created` and `updated` events.
     */
    public function saved(Model $model, array $data): Model
    {
        // do something after saving the model

        return $model;
    }
}
