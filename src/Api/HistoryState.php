<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug\Api;

use Przeslijmi\AgileDataClient\StandardResource;

/**
 * History state definitions to use in multiple api calls.
 */
class HistoryState extends StandardResource
{

    /**
     * List of all possible fields.
     *
     * @var array
     */
    protected $fields = [
        'id' => [
            'sortable' => true,
        ],
        'name' => [
            'sortable' => true,
        ],
        'date' => [
            'sortable' => true,
        ],
        'age' => [
            'sortable' => true,
            'defaultSort' => true,
        ],
    ];
}
