<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug\Api;

use Przeslijmi\AgileDataBirPlug\Api\Robot;
use Przeslijmi\AgileDataBirPlug\Robot as RealRobot;
use Przeslijmi\AgileDataBirPlug\NipHandler;

/**
 * Resource for API call for list of silos.
 */
class RobotReadApi extends Robot
{

    /**
     * Answers for GET list of siloses on Api.
     *
     * @return void
     */
    public function get(): void
    {

        // Lvd.
        $robot = new RealRobot();
        $state = $robot->check();
        $loc   = $_ENV['LOCALE'];

        // Prepare response.
        $this->response['data']['alerts']  = [];
        $this->response['data']['inQueue'] = ( $state->inQueue ?? null );
        $this->response['data']['missing'] = count($robot->getQueue());

        // Create arams for alerts (number of missing NIPs).
        $localeParams = [
            $this->response['data']['missing'],
        ];

        // Say is it active or not.
        if ($state === null) {
            $this->response['data']['active']   = false;
            $this->response['data']['alerts'][] = [
                'text' => $loc->get('Przeslijmi.AgileDataBirPlug.robot.notActive', $localeParams),
                'type' => 'success',
            ];
        } else {
            $this->response['data']['active']   = true;
            $this->response['data']['alerts'][] = [
                'text' => $loc->get('Przeslijmi.AgileDataBirPlug.robot.active', $localeParams),
                'type' => 'success',
            ];
        }

        $this->sendJson($this->response);
    }
}
