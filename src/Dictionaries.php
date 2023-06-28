<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug;

use Exception;

/**
 * Operation that reads data from JSON and merges it with date in current possesion.
 */
class Dictionaries
{

    /**
     * Delivers array of columns (details) defining every scope of TERYT (eg. v - voivodeships).
     *
     * @return array
     */
    public static function getDetails(): array
    {

        // Lvd.
        $loc    = $_ENV['LOCALE'];
        $locDet = 'Przeslijmi.AgileDataBirPlug.details.';

        return [
            'date' => [
                'text' => $loc->get($locDet . 'date.name'),
                'dataType' => 'dateYmd',
            ],
            'reg' => [
                'text' => $loc->get($locDet . 'reg.name'),
                'dataType' => 'txt',
            ],
            'nip' => [
                'text' => $loc->get($locDet . 'nip.name'),
                'dataType' => 'txt',
            ],
            'r.regon' => [
                'text' => $loc->get($locDet . 'r.regon.name'),
                'dataType' => 'txt',
            ],
            'r.name.long' => [
                'text' => $loc->get($locDet . 'r.name.long.name'),
                'dataType' => 'txt',
            ],
            'r.name.short' => [
                'text' => $loc->get($locDet . 'r.name.short.name'),
                'dataType' => 'txt',
            ],
            'r.dates.creation' => [
                'text' => $loc->get($locDet . 'r.dates.creation.name'),
                'dataType' => 'dateYmd',
            ],
            'r.dates.operationStarted' => [
                'text' => $loc->get($locDet . 'r.dates.operationStarted.name'),
                'dataType' => 'dateYmd',
            ],
            'r.dates.regonReg' => [
                'text' => $loc->get($locDet . 'r.dates.regonReg.name'),
                'dataType' => 'dateYmd',
            ],
            'r.dates.suspended' => [
                'text' => $loc->get($locDet . 'r.dates.suspended.name'),
                'dataType' => 'dateYmd',
            ],
            'r.dates.renewed' => [
                'text' => $loc->get($locDet . 'r.dates.renewed.name'),
                'dataType' => 'dateYmd',
            ],
            'r.dates.lastChange' => [
                'text' => $loc->get($locDet . 'r.dates.lastChange.name'),
                'dataType' => 'dateYmd',
            ],
            'r.dates.ended' => [
                'text' => $loc->get($locDet . 'r.dates.ended.name'),
                'dataType' => 'dateYmd',
            ],
            'r.dates.regonUnreg' => [
                'text' => $loc->get($locDet . 'r.dates.regonUnreg.name'),
                'dataType' => 'dateYmd',
            ],
            'r.dates.bankruptcyDeclared' => [
                'text' => $loc->get($locDet . 'r.dates.bankruptcyDeclared.name'),
                'dataType' => 'dateYmd',
            ],
            'r.dates.bankruptcyProceeded' => [
                'text' => $loc->get($locDet . 'r.dates.bankruptcyProceeded.name'),
                'dataType' => 'dateYmd',
            ],
            'r.regAddress.voivodeship' => [
                'text' => $loc->get($locDet . 'r.regAddress.voivodeship.name'),
                'dataType' => 'txt',
            ],
            'r.regAddress.county' => [
                'text' => $loc->get($locDet . 'r.regAddress.county.name'),
                'dataType' => 'txt',
            ],
            'r.regAddress.municipality' => [
                'text' => $loc->get($locDet . 'r.regAddress.municipality.name'),
                'dataType' => 'txt',
            ],
            'r.regAddress.city' => [
                'text' => $loc->get($locDet . 'r.regAddress.city.name'),
                'dataType' => 'txt',
            ],
            'r.regAddress.street' => [
                'text' => $loc->get($locDet . 'r.regAddress.street.name'),
                'dataType' => 'txt',
            ],
            'r.regAddress.propNumber' => [
                'text' => $loc->get($locDet . 'r.regAddress.propNumber.name'),
                'dataType' => 'txt',
            ],
            'r.regAddress.flatNumber' => [
                'text' => $loc->get($locDet . 'r.regAddress.flatNumber.name'),
                'dataType' => 'txt',
            ],
            'r.regAddress.postCity' => [
                'text' => $loc->get($locDet . 'r.regAddress.postCity.name'),
                'dataType' => 'txt',
            ],
            'r.regAddress.zipCode' => [
                'text' => $loc->get($locDet . 'r.regAddress.zipCode.name'),
                'dataType' => 'txt',
            ],
            'r.regAddress.country' => [
                'text' => $loc->get($locDet . 'r.regAddress.country.name'),
                'dataType' => 'txt',
            ],
            'r.regAddress.teryt.terc' => [
                'text' => $loc->get($locDet . 'r.regAddress.teryt.terc.name'),
                'dataType' => 'txt',
            ],
            'r.regAddress.teryt.simcPost' => [
                'text' => $loc->get($locDet . 'r.regAddress.teryt.simcPost.name'),
                'dataType' => 'txt',
            ],
            'r.regAddress.teryt.simc' => [
                'text' => $loc->get($locDet . 'r.regAddress.teryt.simc.name'),
                'dataType' => 'txt',
            ],
            'r.regAddress.teryt.ulic' => [
                'text' => $loc->get($locDet . 'r.regAddress.teryt.ulic.name'),
                'dataType' => 'txt',
            ],
            'r.regAddress.untypical' => [
                'text' => $loc->get($locDet . 'r.regAddress.untypical.name'),
                'dataType' => 'txt',
            ],
            'r.form.baseCode' => [
                'text' => $loc->get($locDet . 'r.form.baseCode.name'),
                'dataType' => 'txt',
            ],
            'r.form.detailedCode' => [
                'text' => $loc->get($locDet . 'r.form.detailedCode.name'),
                'dataType' => 'txt',
            ],
            'r.form.financialCode' => [
                'text' => $loc->get($locDet . 'r.form.financialCode.name'),
                'dataType' => 'txt',
            ],
            'r.form.ownershipCode' => [
                'text' => $loc->get($locDet . 'r.form.ownershipCode.name'),
                'dataType' => 'txt',
            ],
            'r.form.baseName' => [
                'text' => $loc->get($locDet . 'r.form.baseName.name'),
                'dataType' => 'txt',
            ],
            'r.form.detailedName' => [
                'text' => $loc->get($locDet . 'r.form.detailedName.name'),
                'dataType' => 'txt',
            ],
            'r.form.financialName' => [
                'text' => $loc->get($locDet . 'r.form.financialName.name'),
                'dataType' => 'txt',
            ],
            'r.form.ownershipName' => [
                'text' => $loc->get($locDet . 'r.form.ownershipName.name'),
                'dataType' => 'txt',
            ],
            'r.mother.symbol' => [
                'text' => $loc->get($locDet . 'r.mother.symbol.name'),
                'dataType' => 'txt',
            ],
            'r.mother.name' => [
                'text' => $loc->get($locDet . 'r.mother.name.name'),
                'dataType' => 'txt',
            ],
            'r.contact.phone' => [
                'text' => $loc->get($locDet . 'r.contact.phone.name'),
                'dataType' => 'txt',
            ],
            'r.contact.internalPhone' => [
                'text' => $loc->get($locDet . 'r.contact.internalPhone.name'),
                'dataType' => 'txt',
            ],
            'r.contact.fax' => [
                'text' => $loc->get($locDet . 'r.contact.fax.name'),
                'dataType' => 'txt',
            ],
            'r.contact.email' => [
                'text' => $loc->get($locDet . 'r.contact.email.name'),
                'dataType' => 'txt',
            ],
            'r.contact.web' => [
                'text' => $loc->get($locDet . 'r.contact.web.name'),
                'dataType' => 'txt',
            ],
            'r.registry.type' => [
                'text' => $loc->get($locDet . 'r.registry.type.name'),
                'dataType' => 'txt',
            ],
            'r.registry.name.formal' => [
                'text' => $loc->get($locDet . 'r.registry.name.formal.name'),
                'dataType' => 'txt',
            ],
            'r.registry.number' => [
                'text' => $loc->get($locDet . 'r.registry.number.name'),
                'dataType' => 'txt',
            ],
            'r.registry.addedDate' => [
                'text' => $loc->get($locDet . 'r.registry.addedDate.name'),
                'dataType' => 'dateYmd',
            ],
            'r.registry.deletedDate' => [
                'text' => $loc->get($locDet . 'r.registry.deletedDate.name'),
                'dataType' => 'dateYmd',
            ],
            'r.registry.registree.symbol' => [
                'text' => $loc->get($locDet . 'r.registry.registree.symbol.name'),
                'dataType' => 'txt',
            ],
            'r.registry.registree.name' => [
                'text' => $loc->get($locDet . 'r.registry.registree.name.name'),
                'dataType' => 'txt',
            ],
            'activityEnd' => [
                'text' => $loc->get($locDet . 'activityEnd.name'),
                'dataType' => 'dateYmd',
            ],
            'noOfChanges' => [
                'text' => $loc->get($locDet . 'noOfChanges.name'),
                'dataType' => 'int',
            ],
            'lastRefreshDate' => [
                'text' => $loc->get($locDet . 'lastRefreshDate.name'),
                'dataType' => 'dateYmd',
            ],
            'refreshDeadline' => [
                'text' => $loc->get($locDet . 'refreshDeadline.name'),
                'dataType' => 'dateYmd',
            ],
        ];
    }

    /**
     * Return columns.
     *
     * @return array
     */
    public static function getColumnOptions(): array
    {

        // Lvd.
        $result = [];

        foreach (self::getDetails() as $columnId => $columnDef) {
            $result[$columnId] = $columnDef;
        }

        return $result;
    }

    /**
     * Return simple array of data types for given fields.
     *
     * @param boolean $useLocale  Optional, true. Set to false to return un-localed prop names.
     * @param array   $onlyFields Optional, empty. For which fields data type has to be returned.
     *
     * @return array
     */
    public static function getDataTypes(bool $useLocale = true, array $onlyFields = []): array
    {

        // Lvd.
        $fields = self::getDetails();
        $result = [];
        $loc    = $_ENV['LOCALE'];

        // Prepare result.
        foreach ($fields as $fieldId => $fieldDef) {
            if (empty($onlyFields) === true || in_array($fieldId, $onlyFields) === true) {

                // Get proper prop name.
                if ($useLocale === true) {
                    $propName = $loc->get('Przeslijmi.AgileDataBirPlug.details.' . $fieldId . '.name');
                } else {
                    $propName = $fieldId;
                }

                // Add result.
                $result[$propName] = $fieldDef['dataType'];
            }
        }

        return $result;
    }
}
