<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug\Exceptions;

use Przeslijmi\Sexceptions\Sexception;

/**
 * Given key to GusApi is inproper - correct it or contact GUS.
 */
class GusApiLoginFopException extends Sexception
{

    /**
     * Hint.
     *
     * @var string
     */
    protected $hint = 'Given key to GusApi is inproper - correct it or contact GUS.';

    /**
     * Keys for extra data array.
     *
     * @var array
     */
    protected $keys = [
        'usedBirGusApiKey',
    ];
}
