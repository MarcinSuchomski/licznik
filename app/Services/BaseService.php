<?php

namespace App\Services;

use App\Repositories\RepositoryInterface;
use App\Validators\ValidatorInterface;

class BaseService
{
    /**
     * @var RepositoryInterface
     */
    protected $repo;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @var GridParserInterface
     */
    protected $grid_parser = null;

    protected $logger;

    public function prepareGridParser($options)
    {
        $parser = isset($options['parser']) ? $options['parser'] : 'datatables';
        switch ($parser) {
            case 'infinitescroll':
                return  $this->grid_parser = new InfiniteScrollParser();
            case 'angucomplete': //keep it for legacy uses
            case 'autocomplete':
                return $this->grid_parser = new AutocompleteParser();
            case 'datatables':
            default:
                return $this->grid_parser = new DataTablesParser();
        }
    }

    public function parseCollectionOptions(array $data)
    {
        $indexes =["filters", "page", "orders", "where"];
        return array_filter(
            $data,
            function ($key) use ($indexes) {
                return in_array($key, $indexes);
            },
            ARRAY_FILTER_USE_KEY
        );

    }

    /**
     * Used to get the count of items in a collection - used in some menu rules to decide if should be presented or not
     *
     * @param $options
     * @return mixed
     */
    public function getCountCollection($options)
    {
        $this->prepareGridParser($options);
        $parsed_options = $this->grid_parser ? $this->grid_parser->parseInputToRepositoryFormat($options) : $options;
        $parsed_options = $this->parseCollectionOptions($parsed_options);
        $count = $this->repo->getCountCollection($parsed_options);
        return $count;
    }

    public function getCollection($options)
    {
        $this->prepareGridParser($options);
        $parsed_options = $this->grid_parser ? $this->grid_parser->parseInputToRepositoryFormat($options) : $options;
        $parsed_options = $this->parseCollectionOptions($parsed_options);


        $total = $this->repo->getTotal($parsed_options);
        $collection = $this->repo->getCollection($parsed_options);

        if ($collection instanceof \Illuminate\Pagination\LengthAwarePaginator) {
            $data = $collection->items();
            $count = $collection->total();
        } else {
            $data = $collection;
            $count = $collection->count();
        }

        if ($this->grid_parser) {
            $data = is_array($data) ? $data : $data->toArray();
            return $this->grid_parser->parseOutputFromRepositoryFormat($data, $options, $count, $total);
        }


        return $data;
    }

    public function getSingle($uuid_or_id, $is_id = false)
    {
        try {
            $single = $this->repo->getSingle($uuid_or_id, $is_id);
        } catch (ModelNotFoundException $e) {
            if (!config("app.debug")) {
                throw new \Exception(get_class($this->repo->getModel()) . " not found with id: $uuid_or_id");
            }
            throw new AuthorizationException;
        } catch (\Exception $e) {
            throw $e;
        }

        return $single;
    }

    public function createSingle(array $data)
    {
        $validateObject = $this->validator->validate($data);
        if (!$validateObject->valid) {
            throw new \Exception($validateObject->errors);
        }
        return $this->repo->create($data);
    }

    public function updateSingle($id, array $data)
    {
        $validateObject = $this->validator->validate($data, $id);
        if (!$validateObject->valid) {
            throw new \Exception($validateObject->errors);
        }
        return $this->repo->update($id, $data);
    }

    public function deleteSingle($id)
    {
        throw new \Exception("Deleting is disabled by administrator!");
        //return $this->repo->delete($id);
    }

    protected function getLogger($path)
    {
        if (!$this->logger) {
            $this->logger = with(new \Monolog\Logger('api-consumer'))->pushHandler(
                new \Monolog\Handler\RotatingFileHandler(storage_path($path))
            );
        }

        return $this->logger;
    }

    protected function createLoggingHandlerStack($path = 'logs/guzzle.log')
    {
        $stack = \GuzzleHttp\HandlerStack::create();
        $stack->push(
            \GuzzleHttp\Middleware::log(
                $this->getLogger($path),
                new \GuzzleHttp\MessageFormatter('{method} {uri} HTTP/{version} {req_body} ==> RESPONSE: {code} - {res_body}')
            )
        );

        return $stack;
    }
}
