<?php

namespace App\DataParsers;

class AutocompleteParser implements GridParserInterface
{

    public function parseInputToRepositoryFormat(array $input)
    {
        $parsedData = [];

        $filters = [];
        $filters[] = [
            'field' => $input['field'],
            'value' => $input['search'],
        ];
        $parsedData['filters'] = !empty($input['filters']) ? array_merge($input['filters'], $filters) : $filters;

        $parsedData['page'] = [
            'num'  => 15, //limit results to first 15
            'page' => 0,
        ];

        // TODO - implement pagination

        if (!empty($input['order'])) {
            $parsedData['orders'] = $input['order'];
        }

        if (!empty($input['where'])) {
            $parsedData['where'] = $input['where'];
        }

        return $parsedData;
    }

    public function parseOutputFromRepositoryFormat(array $output, array $input, $count = 0, $total = 0)
    {
        return $output;
    }
}
