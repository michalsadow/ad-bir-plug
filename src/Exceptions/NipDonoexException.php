<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug\Exceptions;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Given NIP is not present in BIR database.
 */
class NipDonoexException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Given NIP is not present in BIR database.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'nip',
    ];
}
