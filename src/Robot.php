<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug;

use Przeslijmi\AgileData\Tools\JsonSettings as Json;
use Przeslijmi\AgileDataBirPlug\Exceptions\GusApiLoginFopException;
use Przeslijmi\AgileDataBirPlug\Exceptions\NipDonoexException;
use Przeslijmi\AgileDataBirPlug\IndexHandler;
use Przeslijmi\AgileDataBirPlug\NipHandler;
use stdClass;

/**
 * Updates nips in queue, until queue is empty.
 */
class Robot
{

    /**
     * Count tries for every NIP making possible to rull out NIP after three failed tries.
     *
     * @var array
     */
    private $tries = [];

    /**
     * Excludes NIP if api returned that thay do not exist.
     *
     * @var array
     */
    private $excluded = [];

    /**
     * Checks current status of robot. Returns null if robot is not working.
     *
     * @return null|stdClass
     */
    public function check(): ?stdClass
    {

        // Return null if robot is not working.
        if (file_exists($this->getUri()) === false) {
            return null;
        }

        // Parse and return stdClass or null if process file is not JSON properly syntaxed.
        return json_decode(file_get_contents($this->getUri()));
    }

    /**
     * Starts robot (and also called with every loop).
     *
     * @return void
     */
    public function start(): void
    {

        // Create new process information.
        $info          = new stdClass();
        $info->active  = true;
        $info->started = date('Y-m-d H:i:s');
        $info->inQueue = count($this->getQueue());

        // Encode to JSON string and save it.
        file_put_contents($this->getUri(), json_encode($info, Json::stdWrite()));

        // Serves one NIP and desieds what next - if returned is:
        // - true - then there is something more to be done,
        // - false - job has been finished - nothing in the queue - you can shut.
        $whatsNext = $this->serveOne();

        // Depending on above.
        if ($whatsNext === true) {

            // Make next call to API.
            $this->continueWork();
        } else {

            // Call to shut the process up.
            $this->stop();
        }
    }

    /**
     * Chcecks if process file exists - if it does - continue job - otherwise shut.
     *
     * @return void
     */
    private function continueWork(): void
    {

        // If process file has been deleted - it means that the process has to stop.
        // Otherwise wait for next API call and proceed.
        if (file_exists($this->getUri()) === true) {

            // Wait one second for next call to API.
            sleep(1);
            $this->start();
        } else {

            // Shut the process for sure.
            $this->stop();
        }
    }

    /**
     * Call to stop the process after serving current NIP in queue.
     *
     * @return void
     */
    public function stop(): void
    {

        if (file_exists($this->getUri()) === true) {
            unlink($this->getUri());
        }
    }

    /**
     * Serve one NIP from queue. Return true if there is anything more to be done, or false otherwise.
     *
     * @return boolean
     */
    private function serveOne(): bool
    {

        // Lvd.
        $nip = ( $this->getQueue()[0] ?? null );

        // If nothing found - return false - so it will stop the robot.
        if ($nip === null) {
            return false;
        }

        // Save this to tryes.
        if (isset($this->tries[$nip]) === false) {
            $this->tries[$nip] = 1;
        } else {
            ++$this->tries[$nip];
        }

        // Refresh NIP.
        try {
            $handler = new NipHandler($nip);
            $handler->refresh();
            $handler->save();
        } catch (GusApiLoginFopException $sexc) {
            $this->stop();
        } catch (NipDonoexException $sexc) {
            $this->excluded[$nip] = true;
        }

        return true;
    }

    /**
     * Delivers uri for robot process JSON file (see config `PRZESLIJMI_ADBIRPLUG_ROBOT_URI`).
     *
     * @return string
     */
    private function getUri(): string
    {

        return $_ENV['PRZESLIJMI_ADBIRPLUG_ROBOT_URI'];
    }

    /**
     * Checks index file and creates queue.
     *
     * @return array
     */
    public function getQueue(): array
    {

        // Lvd.
        $queue        = [];
        $nipsToIgnore = [];

        // Which to ignore after three tries.
        foreach ($this->tries as $nip => $count) {
            if ($count >= 3) {
                $nipsToIgnore[] = $nip;
            }
        }

        // Find first to be served in queue.
        $index = new IndexHandler();
        foreach ($index->getIndex() as $nip => $nipDef) {
            if (
                ( $nipDef->wasEverRefreshed === false
                    || $nipDef->refreshDeadline <= date('Y-m-d')
                    || $nipDef->refreshDeadline === null )
                && in_array($nip, $nipsToIgnore) === false
                && isset($this->excluded[$nip]) === false
            ) {
                $queue[] = $nip;
            }
        }

        return $queue;
    }
}
