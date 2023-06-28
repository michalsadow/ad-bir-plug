<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug\EnvChecks;

use Przeslijmi\AgileData\Exceptions\CronFrequencyWrosynException;
use Przeslijmi\AgileData\Configure\EnvChecks\EnvChecksParent;
use Przeslijmi\AgileData\Tools\CronFrequencyParser;

/**
 * Checks if given ENV value is proper.
 */
class CronFrequency extends EnvChecksParent
{

    /**
     * Standard rules to be checked.
     *
     * @var array
     */
    protected static $rules = [
        'dataType' => 'string',
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

        // Only when value has changed.
        if (empty($value) === false && $_ENV['PRZESLIJMI_ADBIRPLUG_CRON_FREQUENCY'] !== $value) {

            // Start client.
            $parser = new CronFrequencyParser($value);

            // Try to parse.
            try {
                $parser->parse();
            } catch (CronFrequencyWrosynException $exc) {
                self::throw('Wrosyn', $value);
            }
        }
    }
}
