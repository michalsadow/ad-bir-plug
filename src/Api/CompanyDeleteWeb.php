<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug\Api;

use Przeslijmi\AgileDataBirPlug\Api\Company;

/**
 * Resource showing page with list of fieldses.
 */
class CompanyDeleteWeb extends Company
{

    /**
     * Answers for GET list of fieldses on Api.
     *
     * @return void
     */
    public function get(): void
    {

        // Preparations.
        $this->prepareSwts('companyDelete', ( dirname(dirname(dirname(__FILE__))) . '/tpl/' ));

        // Show errors if there are new.
        if ($this->countErrors() > 0) {
            $this->showErrors('web');
            return;
        }

        // Assign.
        $this->swts->assign('nip', $this->route->getParam('nip'));

        // Print parsed contents.
        echo $this->swts->parse();
    }
}
