<?php

declare(strict_types=1);

use Przeslijmi\AgileDataBirPlug\Api\CompaniesReadWeb as CompsR;
use Przeslijmi\AgileDataBirPlug\Api\CompanyCreateWeb as CompC;
use Przeslijmi\AgileDataBirPlug\Api\CompanyDeleteWeb as CompD;
use Przeslijmi\AgileDataBirPlug\Api\CompanyReadWeb as CompR;
use Przeslijmi\AgileDataBirPlug\Api\CompanyRobotWeb as CompRob;
use Przeslijmi\AgileDataBirPlug\Api\HistoryStateReadWeb as HistR;
use Przeslijmi\Sirouter\Sirouter as R;

// Lvd.
$c  = '/dane/bir';
$cs = '/dane/bir/([0-9]{10})';
$hs = '/dane/bir/([0-9]{10})/states/([0-9]{10})';

// All pages.
R::register($c, 'GET')->setCall(CompsR::class, 'get');
R::register($c . '/dodawanie', 'GET')->setCall(CompC::class, 'get');
R::register($cs, 'GET')->setCall(CompR::class, 'get')->setParam(0, 'nip');
R::register($cs . '/kasuj', 'GET')->setCall(CompD::class, 'get')->setParam(0, 'nip');
R::register($hs, 'GET')->setCall(HistR::class, 'get')->setParam(0, 'nip')->setParam(1, 'stateId');

// Robot.
R::register($c . '/robot', 'GET')->setCall(CompRob::class, 'get');
