<?php

namespace App\DataParsers;

class DataTablesParser implements GridParserInterface
{

    public function parseInputToRepositoryFormat(array $input)
    {
        $parsedData = [];
        $filters = [];
        $parsedData['filters'] = [];

        /**
         * Generic search for all columns
         */
        if (isset($input['search']['value']) && $input['search']['value']) {
            foreach ($input['columns'] as $k => $column) {
                if ($column['searchable'] == 'true' && $column['data']) {
                    $filters[$k] = [
                        'field' => $column['data'],
                        'value' => $input['search']['value'],
                    ];
                    if (isset($column['grouping']) and $column['grouping']) {
                        $filters[$k]['grouping'] = $column['grouping'];
                    }
                    if (isset($column['operation']) and $column['operation']) {
                        $filters[$k]['operation'] = $column['operation'];
                    }
                }
            }
            $parsedData['filters'] = $filters;
        }

        /**
         * Overwrite with individual settings, if any
         */
        if (!empty($input['columns'])) {
            foreach ($input['columns'] as $k => $column) {
                if ($column['searchable'] == 'true' && $column['data'] && preg_match('/\S/', $column['search']['value'])) {
                    $filters[$k] = [
                        'field' => $column['data'],
                        'value' => $column['search']['value'],
                    ];
                    if (isset($column['grouping']) and $column['grouping']) {
                        $filters[$k]['grouping'] = $column['grouping'];
                    }
                    if (isset($column['operation']) and $column['operation']) {
                        $filters[$k]['operation'] = $column['operation'];
                    }
                }
            }
        }

        if (count($filters)) {
            $parsedData['filters'] = array_values($filters);
        }

        // allow passing filters from input
        $parsedData['filters'] = !empty($input['filters']) ? array_merge($input['filters'], $parsedData['filters']) : $parsedData['filters'];

        // Default pagination params
        $parsedData['page'] = [
            'num'  => 10,
            'page' => 0,
        ];

        if (isset($input['start'])) {

            // Limit max number of items to 100 only
            if(isset($input['length']) && $input['length'] > 100){
                $input['length'] = 100;
            }

            $parsedData['page'] = [
                'num'  => $input['length'] ,
                'page' => ($input['start'] / $input['length']),
            ];
        }

        if (!empty($input['order'])) {
            foreach ($input['order'] as $order) {
                $colIndex = $order['column'];
                $parsedData['orders'][] = [
                    'field'     => $input['columns'][$colIndex]['data'],
                    'direction' => $order['dir'],
                ];
            }
        }

        if (!empty($input['where'])) {
            $parsedData['where'] = $input['where'];
        }

        return $parsedData;
    }

    public function parseOutputFromRepositoryFormat(array $output, array $input, $count = 0, $total = 0)
    {
        return [
            'data'            => $output,
            'recordsFiltered' => $count,
            'recordsTotal'    => $total,
        ];
    }

}
