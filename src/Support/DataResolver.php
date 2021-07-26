<?php

namespace Spatie\LaravelData\Support;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Spatie\LaravelData\Actions\ResolveValidationRulesForDataAction;
use Spatie\LaravelData\Data;

class DataResolver
{
    public function __construct(
        protected Request $request,
        protected ResolveValidationRulesForDataAction $resolveValidationRulesForDataAction
    ) {
    }

    public function get(string $class): Data
    {
        /** @var \Spatie\LaravelData\RequestData|string $class */
        $rules = $this->resolveValidationRulesForDataAction
            ->execute($class)
            ->merge($class::rules())
            ->toArray();

        $validator = Validator::make($this->request->all(), $rules);

        $validator->validate();

        return $class::createFromRequest($this->request);
    }
}