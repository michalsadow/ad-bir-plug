<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug;

use Przeslijmi\AgileData\Steps\Helpers\CronJobsInterface;
use Przeslijmi\AgileData\Steps\Helpers\CronJobsParent;
use Przeslijmi\AgileDataBirPlug\Robot;

/**
 * Caller of cron JOB that cleans BIR queue.
 */
class CronJob extends CronJobsParent implements CronJobsInterface
{

    /**
     * Cron job key.
     *
     * @var string
     */
    protected static $cjKey = '6AbKLzWZ';

    /**
     * Deliver frequency for this cron job (using `* * * * *` format).
     *
     * @return string
     */
    public function getFrequency(): string
    {

        return $_ENV['PRZESLIJMI_ADBIRPLUG_CRON_FREQUENCY'];
    }

    /**
     * Actually performing the job.
     *
     * @return void
     */
    public function perform(): void
    {

        $robot = new Robot();
        $robot->start();
    }
}
