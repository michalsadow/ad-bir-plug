<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug\EnvChecks;

use GusApi\Exception\InvalidUserKeyException;
use GusApi\GusApi;
use Przeslijmi\AgileData\Configure\EnvChecks\EnvChecksParent;
use SoapClient;

/**
 * Checks if given ENV value is proper.
 */
class BirKey extends EnvChecksParent
{

    /**
     * Standard actions to be performed on ENV.
     *
     * @var array
     */
    protected static $actions = [
        [ 'trim', ' ' ],
    ];

    /**
     * Standard rules to be checked.
     *
     * @var array
     */
    protected static $rules = [
        'dataType' => 'string',
        'canBeEmpty' => false,
    ];

    /**
     * Perform checks.
     *
     * @param mixed $value Value to be checked.
     *
     * @return void
     */
    public static function check($value): void
    {

        // Check if SOAP extension is present.
        if (class_exists(SoapClient::class) === false) {
            self::throw('SoapExtDonoex', $value);
        }

        // Only when value has changed.
        if (
            empty($value) === false
            && isset($_ENV['PRZESLIJMI_ADBIRPLUG_BIR_KEY']) === true
            && empty($_ENV['PRZESLIJMI_ADBIRPLUG_BIR_KEY']) === false
            && $_ENV['PRZESLIJMI_ADBIRPLUG_BIR_KEY'] !== $value
        ) {

            // Start client.
            $gus = new GusApi($value);

            // Try to login.
            try {
                $gus->login();
            } catch (InvalidUserKeyException $exc) {
                self::throw('AuthFailed', $value);
            }
        }
    }
}
