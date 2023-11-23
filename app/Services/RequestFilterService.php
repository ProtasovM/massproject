<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class RequestFilterService
{
    public const VALIDATION_RULES = [
        'filter' => 'array:sort,date,field',

        'filter.sort' => 'array',
        'filter.sort.column' => [
            'string',
            'in:id,created_at,status,answered_at'
        ],
        'filter.sort.direction' => 'required_with:filter.sort|in:acs,desc',

        'filter.date' => 'array:created_at,answered_at',
        'filter.date.*.from' => 'integer|before_or_equal:now',
        'filter.date.*.to' => 'integer|before_or_equal:now',

        'filter.field' => 'array:id,status,created_at,answered_at',
        'filter.field.*.value' => 'required',
        'filter.field.*.operator' => 'in:=,>=,<=,<,>,!=',
    ];

    public function resolveFilterFromRequest(
        Request $request,
        Builder $builder
    ): void {
        if (isset($request->filter['sort'])) {
            $this->resolveSort(
                $request,
                $builder
            );
        }

        $this->resolveDates(
            $request,
            $builder
        );

        $this->resolveFields(
            $request,
            $builder
        );
    }

    protected function resolveFields(
        Request $request,
        Builder $builder
    ): void {
        $fields = [
            'id',
            'status',
            'created_at',
            'answered_at',
        ];
        foreach ($fields as $field) {
            if (isset($request->filter['field'][$field])) {
                $this->resolveField(
                    $field,
                    $request,
                    $builder
                );
            }
        }
    }

    protected function resolveSort(
        Request $request,
        Builder $builder
    ): void {
        $builder->orderBy(
            $request->filter['sort']['column'] ?? 'id',
            $request->filter['sort']['direction']
        );
    }

    protected function resolveDates(
        Request $request,
        Builder $builder
    ): void {
        foreach (['created_at', 'answered_at'] as $field) {
            if (isset($request->filter['date'][$field])) {
                $this->resolveDate(
                    $field,
                    $request,
                    $builder
                );
            }
        }
    }

    protected function resolveField(
        string $field,
        Request $request,
        Builder $builder
    ): void {
        $values = explode(
            ',',
            $request->filter['field'][$field]['value']
        );
        if (count($values) === 1) {
            $builder->where(
                $field,
                $request->filter['field'][$field]['operator'] ?? '=',
                head($values),
            );
        } else {
            if (
                !isset($request->filter['field'][$field]['operator'])
                || $request->filter['field'][$field]['operator'] === '='
            ) {
                $builder->whereIn(
                    $field,
                    $values
                );
            } else if ($request->filter['field'][$field]['operator'] === '!=') {
                $builder->whereNotIn(
                    $field,
                    $values
                );
            } else {
                throw new \Exception();//todo
            }
        }
    }

    protected function resolveDate(
        string $field,
        Request $request,
        Builder $builder
    ): void {
        if (isset($request->filter['date'][$field]['from'])) {
            $builder->where(
                $field,
                '>=',
                $request->filter['date'][$field]['from']
            );
        }
        if (isset($request->filter['date'][$field]['to'])) {
            $builder->where(
                $field,
                '<=',
                $request->filter['date'][$field]['to']
            );
        }
    }

    public function getValidationRules(): array
    {
        return static::VALIDATION_RULES;
    }
}
