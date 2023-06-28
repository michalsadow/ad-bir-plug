<?php

declare(strict_types=1);

use Przeslijmi\AgileDataBirPlug\Api\CompaniesReadApi as CompsR;
use Przeslijmi\AgileDataBirPlug\Api\Company;
use Przeslijmi\AgileDataBirPlug\Api\CompanyCreateApi as CompC;
use Przeslijmi\AgileDataBirPlug\Api\CompanyDeleteApi as CompD;
use Przeslijmi\AgileDataBirPlug\Api\CompanyRefreshApi as CompRef;
use Przeslijmi\AgileDataBirPlug\Api\HistoryStatesReadApi as StatesR;
use Przeslijmi\AgileDataBirPlug\Api\RobotCreateApi as RobotC;
use Przeslijmi\AgileDataBirPlug\Api\RobotDeleteApi as RobotD;
use Przeslijmi\AgileDataBirPlug\Api\RobotReadApi as RobotR;
use Przeslijmi\Sirouter\Sirouter as R;

// Lvd.
$c   = '/api/v1/bir/companies';
$cs  = '/api/v1/bir/companies/([0-9]{10})';
$h   = '/api/v1/bir/companies/([0-9]{10})/states';
$hs  = '/api/v1/bir/companies/([0-9]{10})/states/([0-9]{10})';
$rob = '/api/v1/bir/robot';
$cf  = '/api/v1/bir/form-fields/mass-add';

// Mage companies.
R::register($c, 'GET')->setCall(CompsR::class, 'get');
R::register($c, 'POST')->setCall(CompC::class, 'post');
R::register($cs, 'DELETE')->setCall(CompD::class, 'delete')->setParam(0, 'nip');
R::register($cs . '/refresh', 'GET')->setCall(CompRef::class, 'get')->setParam(0, 'nip');

// Manage history states.
R::register($h, 'GET')->setCall(StatesR::class, 'get')->setParam(0, 'nip');

// Manage robot.
R::register($rob, 'GET')->setCall(RobotR::class, 'get');
R::register($rob, 'POST')->setCall(RobotC::class, 'post');
R::register($rob, 'DELETE')->setCall(RobotD::class, 'delete');

// Form fields.
R::register($cf, 'GET')->setCall(Company::class, 'getCreateUpdateFields');
