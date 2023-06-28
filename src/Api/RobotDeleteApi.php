<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug\Api;

use Przeslijmi\AgileDataBirPlug\Api\Robot;
use Przeslijmi\AgileDataBirPlug\Robot as RealRobot;
use Przeslijmi\AgileDataBirPlug\NipHandler;

/**
 * Resource for API call for list of silos.
 */
class RobotDeleteApi extends Robot
{

    /**
     * Answers for GET list of siloses on Api.
     *
     * @return void
     */
    public function delete(): void
    {

        // Lvd.
        $loc   = $_ENV['LOCALE'];
        $robot = new RealRobot();
        $state = $robot->stop();

        // Prepare answer.
        $this->response['data']['alerts']   = [];
        $this->response['data']['alerts'][] = [
            'text' => $loc->get('Przeslijmi.AgileDataBirPlug.robot.stopped'),
            'type' => 'success',
        ];

        $this->sendJson($this->response);
    }
}
