<?php

use App\Models\User;
use App\Notifications\SendUserNotification;
use Illuminate\Database\Eloquent\Builder;
use EloquentFilter\Filterable;
use Illuminate\Support\Str;


function getRecords($model, $filters, $resourceClass, $with = [], $specificIds = [], $canSeeAll = false, $filterClass = null)
{
    $orderBy = $filters['orderBy'] ?? 'id';
    $orderDirection = $filters['orderDirection'] ?? 'desc';
    $query = $model->with($with)->orderBy($orderBy, $orderDirection);
    if ($specificIds || $canSeeAll) {
        $query->whereIn('id', $specificIds);
    }
    if ($filterClass && $filters) {
        foreach ($filters as $key => $value) {
            $methodName = snakeToCamel($key);
            if (method_exists($filterClass, $methodName)) {
                $query = (new $filterClass($query))->$methodName($value);
            }
        }
    }
    $isPaginated = isset($filters['paginated']) && $filters['paginated'];
    if ($isPaginated) {
        $pageSize = isset($filters['page_size']) ? $filters['page_size'] : 10;
        $results = $query->paginate($pageSize);
        $pagination = getPagination($results);
    } else {
        $results = $query->get();
        $pagination = [];
    }
    return [
        'data' => $resourceClass::collection($results),
        'paginator' => $pagination,
    ];
}

function snakeToCamel($value)
{
    return lcfirst(str_replace('_', '', ucwords($value, '_')));
}


function getAllUsersTrashed($model, $filters, $resourceClass, $with = [], $specificIds = [], $canSeeAll = false, $filterClass = null)
{
    $orderBy = $filters['orderBy'] ?? 'id';
    $orderDirection = $filters['orderDirection'] ?? 'desc';

    $query = $model::onlyTrashed()->whereNotNull('deleted_at')->orderBy($orderBy, $orderDirection);

    if ($specificIds || $canSeeAll) {
        $query->whereIn('id', $specificIds);
    }

    if ($filterClass && $filters) {
        $query = (new $filterClass($query))->filter($filters, $filterClass);
    }

    $isPaginated = isset($filters['paginated']) && $filters['paginated'];

    if ($isPaginated) {
        $pageSize = isset($filters['page_size']) ? $filters['page_size'] : 10;
        $results = $query->paginate($pageSize);
        $pagination = getPagination($results);
    } else {
        $results = $query->get();
        $pagination = [];
    }

    return  [
        'data' => $resourceClass::collection($results),
        'paginator' => $pagination,
    ];
}



function getPagination($obj)
{
    return [
        'count' => $obj->count(),
        'total' => $obj->total(),
        'per_page' => $obj->perPage(),
        'current_page' => $obj->currentPage(),
        'last_page' => $obj->lastPage(),
    ];
}

function castingAttributes($model, $casts)
{
    $digitsAfterDecimal = 'decimal:' . config('digits_after_decimal');

    foreach ($model->getAttributes() as $attribute => $value) {

        if (
            in_array($attribute, $model->getFillable(), true)
            &&
            is_numeric($value)
            &&
            strpos($value, '.')  !== false
        ) {
            $casts[$attribute] = $digitsAfterDecimal;
        }
    }

    return $casts;
}



function uploadFile($request, string $path, $attribute): ?string
{
    if ($request->hasFile($attribute)) {
        $file = $request->file($attribute);
        $fileName = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();

        // Move the uploaded file to the specified folder
        $file->move(public_path($path), $fileName);

        // Construct and return the full URL
        $fullUrl = env('APP_URL') . '/' . $path . '/' . $fileName;
        return $fullUrl;
    }

    return null;
}

function uploadFiles($request, string $path, $attribute): array
{
    $uploadedFiles = [];

    if ($request->hasFile($attribute)) {
        $files = $request->file($attribute);

        foreach ($files as $file) {
            $fileName = Str::uuid()->toString() . '.' . $file->getClientOriginalExtension();

            $file->move(public_path($path), $fileName);

            $fullUrl = env('APP_URL') . '/' . $path . '/' . $fileName;
            $uploadedFiles[] = $fullUrl;
        }
    }

    return $uploadedFiles;
}

function dateNow()
{
    return now()->format('d-m-Y H:i');
}

function getUserPermissionsByResourceIds($method, $resource, $user)
{
    $permissions = $user->getAllPermissions()->filter(function ($permission) use ($method, $resource) {
        return str_contains($permission->name, "{$method} {$resource}");
    })->map(function ($permission) use ($method, $resource) {

        $value = str_replace("{$method} {$resource}", '', $permission->name);
        $arrayValues = explode(';', $value);
        $value = $arrayValues[count($arrayValues) - 1];
        return trim($value) !== '' ? (int) trim($value) : null;
    });
    return $permissions->filter()->values()->toArray();
}
