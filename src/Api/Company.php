<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug\Api;

use Przeslijmi\AgileDataClient\StandardResource;

/**
 * Company definitions to use in multiple api calls.
 */
class Company extends StandardResource
{

    /**
     * List of all possible fields.
     *
     * @var array
     */
    protected $fields = [
        'id' => [
            'sortable' => true,
        ],
        'nip' => [
            'sortable' => true,
            'defaultSort' => true,
        ],
        'regon' => [
            'sortable' => true,
        ],
        'regs' => [
            'sortable' => true,
        ],
        'name' => [
            'sortable' => true,
        ],
        'refreshOn' => [
            'sortable' => true,
        ],
        'refreshOnLoc' => [
            'sortable' => true,
        ],
    ];

    /**
     * Delivers fields to prepare form of creating/editing silo.
     *
     * @return void
     */
    public function getCreateUpdateFields(): void
    {

        // Lvd.
        $loc       = $_ENV['LOCALE'];
        $locSta    = 'Przeslijmi.AgileDataBirPlug.massAdd.fields.';
        $fields    = [];
        $databases = [];

        // Create fields.
        $fields[] = [
            'type' => 'textarea',
            'id' => 'nips',
            'rows' => 5,
            'value' => null,
            'name' => $loc->get($locSta . 'nips.name'),
            'desc' => $loc->get($locSta . 'nips.desc'),
            'maxlength' => 255,
            'group' => $loc->get('Przeslijmi.AgileData.tabs.general'),
        ];
        $fields[] = [
            'type' => 'select',
            'id' => 'refresh',
            'value' => 180,
            'name' => $loc->get($locSta . 'refresh.name'),
            'desc' => $loc->get($locSta . 'refresh.desc'),
            'options' => [
                7 => $loc->get('Przeslijmi.AgileDataBirPlug.refresh.7'),
                14 => $loc->get('Przeslijmi.AgileDataBirPlug.refresh.14'),
                30 => $loc->get('Przeslijmi.AgileDataBirPlug.refresh.30'),
                60 => $loc->get('Przeslijmi.AgileDataBirPlug.refresh.60'),
                90 => $loc->get('Przeslijmi.AgileDataBirPlug.refresh.90'),
                180 => $loc->get('Przeslijmi.AgileDataBirPlug.refresh.180'),
                -1 => $loc->get('Przeslijmi.AgileDataBirPlug.refresh.-1'),
            ],
            'group' => $loc->get('Przeslijmi.AgileData.tabs.general'),
        ];

        // Create response.
        $this->sendJson([
            'status' => 'success',
            'data' => [
                'fields' => $fields,
            ],
        ]);
    }

    /**
     * Converts any text sent by the user into proper NIPS - searching for NIPs.
     *
     * @param string $nips Text that will be scanned.
     *
     * @return array
     */
    protected function readAndValidateNips(string $nips): array
    {

        // Lvd.
        $nips = preg_replace('/([^0-9-])/', ',', $nips);
        $nips = explode(',', str_replace('-', '', $nips));

        // Validate.
        foreach ($nips as $nipId => $nip) {

            // Ignore empty.
            if (empty($nip) === true) {
                unset($nips[$nipId]);
                continue;
            }

            // Check.
            if (strlen($nip) !== 10) {
                unset($nips[$nipId]);
                continue;
            }
        }

        return array_values($nips);
    }
}
