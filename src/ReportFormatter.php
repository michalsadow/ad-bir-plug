<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug;

use stdClass;

/**
 * Formats index and whole historical state to send to API.
 */
class ReportFormatter
{

    /**
     * Which reports (krs, ceidg, rural, other) has to be ignored in final return.
     *
     * @var array
     */
    private $reportsToIgnore = [];

    /**
     * Formats index data.
     *
     * @param stdClass $entry Index entry.
     *
     * @return array
     */
    public function formatIndex(stdClass $entry): array
    {

        // Lvd.
        $company = [];
        $loc     = $_ENV['LOCALE'];

        // Prepare regsLoc.
        $regsLoc = [];
        foreach ($entry->regs as $reg) {
            $regsLoc[] = $loc->get('Przeslijmi.AgileDataBirPlug.regs.' . $reg);
        }

        // Prepare main part.
        $company['regon']            = $entry->regon;
        $company['name']             = $entry->name;
        $company['regsCount']        = count($regsLoc);
        $company['regsLoc']          = implode(', ', $regsLoc);
        $company['noOfChanges']      = $entry->noOfChanges;
        $company['activityEnd']      = $entry->activityEnd;
        $company['wasEverRefreshed'] = $entry->wasEverRefreshed;
        $company['lastRefreshDate']  = $entry->lastRefreshDate;
        $company['refreshOn']        = $entry->refreshOn;
        $company['refreshOnLoc']     = $loc->get('Przeslijmi.AgileDataBirPlug.refresh.' . $entry->refreshOn);
        $company['refreshDeadline']  = $entry->refreshDeadline;

        // Add more details.
        if ($entry->wasEverRefreshed === false) {
            $company['done']    = 0;
            $company['doneLoc'] = 'nie';
        } else {
            $company['done']    = 1;
            $company['doneLoc'] = 'tak';
        }
        if (
            ( $entry->wasntStarted === false && empty($entry->activityEnd) === true )
            || $entry->activityEnd > date('Y-m-d')
        ) {
            $company['isActive']    = 1;
            $company['isActiveLoc'] = 'tak';
        } else {
            $company['isActive']    = 0;
            $company['isActiveLoc'] = 'nie';
        }

        return $company;
    }

    /**
     * Define which reports (krs, ceidg, rural or other) are to be ignored from reading.
     *
     * @param array $reportsToIgnore Arrey of reports. Send empty array to ignore none.
     *
     * @return self
     */
    public function ignoreReports(array $reportsToIgnore): self
    {

        // Define.
        $this->reportsToIgnore = $reportsToIgnore;

        return $this;
    }

    /**
     * Formats whole big state (complete information) of NIP.
     *
     * @param stdClass $state Whoe historical state of NIP.
     *
     * @return array
     */
    public function formatState(stdClass $state): array
    {

        // Lvd.
        $result = [];

        // Call underlying formatters.
        if (in_array('ceidg', $this->reportsToIgnore) === false) {
            if (isset($state->{'BIR11OsFizycznaDzialalnoscCeidg'}->{'0'}) === true) {
                $result['ceidg'] = $this->formatPersonDetails(
                    'ceidg',
                    $state->{'BIR11OsFizycznaDzialalnoscCeidg'}->{'0'},
                    $state->{'BIR11OsFizycznaDaneOgolne'}->{'0'}
                );
            }
        }

        if (in_array('rural', $this->reportsToIgnore) === false) {
            if (isset($state->{'BIR11OsFizycznaDzialalnoscRolnicza'}->{'0'}) === true) {
                $result['rural'] = $this->formatPersonDetails(
                    'rural',
                    $state->{'BIR11OsFizycznaDzialalnoscRolnicza'}->{'0'},
                    $state->{'BIR11OsFizycznaDaneOgolne'}->{'0'}
                );
            }
        }

        if (in_array('other', $this->reportsToIgnore) === false) {
            if (isset($state->{'BIR11OsFizycznaDzialalnoscPozostala'}->{'0'}) === true) {
                $result['other'] = $this->formatPersonDetails(
                    'other',
                    $state->{'BIR11OsFizycznaDzialalnoscPozostala'}->{'0'},
                    $state->{'BIR11OsFizycznaDaneOgolne'}->{'0'}
                );
            }
        }

        if (in_array('krs', $this->reportsToIgnore) === false) {
            if (isset($state->{'BIR11OsPrawna'}->{'0'}) === true) {
                $result['krs'] = $this->formatCompanyDetails($state->{'BIR11OsPrawna'}->{'0'});
            }
        }

        if (isset($state->{'BIR11OsPrawnaPkd'}) === true) {
            $result['pkd'] = $this->formatPkd($state->{'BIR11OsPrawnaPkd'});
        }

        return $result;
    }

    /**
     * Formats `BIR11OsFizycznaDzialalnoscCeidg` report.
     *
     * @param string   $reportType Common name, possibilities: ceidg, rural, other.
     * @param stdClass $activity   Actual report contents.
     * @param stdClass $general    General report contents (`BIR11OsFizycznaDaneOgolne`).
     *
     * @return array
     */
    private function formatPersonDetails(string $reportType, stdClass $activity, stdClass $general): array
    {

        // Precorrection of error in rural registry (correct letter misteake).
        if (isset($activity->{'fiz_dataSkresleniaDzialalanosciZRegon'}) === true) {
            $activity->{'fiz_dataSkresleniaDzialalnosciZRegon'} = $activity->{'fiz_dataSkresleniaDzialalanosciZRegon'};
        }

        // Lvd.
        $terc  = $activity->{'fiz_adSiedzWojewodztwo_Symbol'} . $activity->{'fiz_adSiedzPowiat_Symbol'};
        $terc .= $activity->{'fiz_adSiedzGmina_Symbol'};

        // Lvd.
        $result = [
            'regon' => $this->nullify($activity, 'fiz_regon9'),
            'name' => [
                'long' => $this->nullify($activity, 'fiz_nazwa'),
                'short' => $this->nullify($activity, 'fiz_nazwaSkrocona'),
            ],
            'wasntStarted' => $this->nullify($activity, 'fizC_NiePodjetoDzialalnosci'),
            'dates' => [
                'creation' => $this->nullify($activity, 'fiz_dataPowstania'),
                'operationStarted' => $this->nullify($activity, 'fiz_dataRozpoczeciaDzialalnosci'),
                'regonReg' => $this->nullify($activity, 'fiz_dataWpisuDzialalnosciDoRegon'),
                'suspended' => $this->nullify($activity, 'fiz_dataZawieszeniaDzialalnosci'),
                'renewed' => $this->nullify($activity, 'fiz_dataWznowieniaDzialalnosci'),
                'lastChange' => $this->nullify($activity, 'fiz_dataZaistnieniaZmianyDzialalnosci'),
                'ended' => $this->nullify($activity, 'fiz_dataZakonczeniaDzialalnosci'),
                'regonUnreg' => $this->nullify($activity, 'fiz_dataSkresleniaDzialalnosciZRegon'),
                'bankruptcyDeclared' => $this->nullify($activity, 'fiz_dataOrzeczeniaOUpadlosci'),
                'bankruptcyProceeded' => $this->nullify($activity, 'fiz_dataZakonczeniaPostepowaniaUpadlosciowego'),
            ],
            'regAddress' => [
                'voivodeship' => $this->nullify($activity, 'fiz_adSiedzWojewodztwo_Nazwa'),
                'county' => $this->nullify($activity, 'fiz_adSiedzPowiat_Nazwa'),
                'municipality' => $this->nullify($activity, 'fiz_adSiedzGmina_Nazwa'),
                'city' => $this->nullify($activity, 'fiz_adSiedzMiejscowosc_Nazwa'),
                'street' => $this->nullify($activity, 'fiz_adSiedzUlica_Nazwa'),
                'propNumber' => $this->nullify($activity, 'fiz_adSiedzNumerNieruchomosci'),
                'flatNumber' => $this->nullify($activity, 'fiz_adSiedzNumerLokalu'),
                'postCity' => $this->nullify($activity, 'fiz_adSiedzMiejscowoscPoczty_Nazwa'),
                'zipCode' => $this->nullify($activity, 'fiz_adSiedzKodPocztowy'),
                'country' => $this->nullify($activity, 'fiz_adSiedzKraj_Symbol'),
                'teryt' => [
                    'terc' => $terc,
                    'simcPost' => $this->nullify($activity, 'fiz_adSiedzMiejscowoscPoczty_Symbol'),
                    'simc' => $this->nullify($activity, 'fiz_adSiedzMiejscowosc_Symbol'),
                    'ulic' => $this->nullify($activity, 'fiz_adSiedzUlica_Symbol'),
                ],
                'untypical' => $this->nullify($activity, 'fiz_adSiedzNietypoweMiejsceLokalizacji'),
            ],
            'form' => [
                'baseCode' => $this->nullify($general, 'fiz_podstawowaFormaPrawna_Symbol'),
                'detailedCode' => $this->nullify($general, 'fiz_szczegolnaFormaPrawna_Symbol'),
                'financialCode' => $this->nullify($general, 'fiz_formaFinansowania_Symbol'),
                'ownershipCode' => $this->nullify($general, 'fiz_formaWlasnosci_Symbol'),
                'baseName' => $this->nullify($general, 'fiz_podstawowaFormaPrawna_Nazwa'),
                'detailedName' => $this->nullify($general, 'fiz_szczegolnaFormaPrawna_Nazwa'),
                'financialName' => $this->nullify($general, 'fiz_formaFinansowania_Nazwa'),
                'ownershipName' => $this->nullify($general, 'fiz_formaWlasnosci_Nazwa'),
            ],
            'mother' => [
                'symbol' => null,
                'name' => null,
            ],
            'contact' => [
                'phone' => $this->nullify($activity, 'fiz_numerTelefonu'),
                'internalPhone' => $this->nullify($activity, 'fiz_numerWewnetrznyTelefonu'),
                'fax' => $this->nullify($activity, 'fiz_numerFaksu'),
                'email' => $this->nullify($activity, 'fiz_adresEmail'),
                'web' => $this->nullify($activity, 'fiz_adresStronyinternetowej'),
            ],
            'registry' => [
                'type' => $this->nullify($activity, 'fizC_RodzajRejestru_Symbol'),
                'name' => [
                    'std' => $reportType,
                    'formal' => $this->nullify($activity, 'fizC_RodzajRejestru_Nazwa'),
                ],
                'number' => $this->nullify($activity, 'fizC_numerWRejestrzeEwidencji'),
                'addedDate' => $this->nullify($activity, 'fizC_dataWpisuDoRejestruEwidencji'),
                'deletedDate' => $this->nullify($activity, 'fizC_dataSkresleniaZRejestruEwidencji'),
                'registree' => [
                    'symbol' => $this->nullify($activity, 'fizC_OrganRejestrowy_Symbol'),
                    'name' => $this->nullify($activity, 'fizC_OrganRejestrowy_Nazwa'),
                ],
            ],
            'localCount' => (int) $this->nullify($activity, 'fiz_liczbaJednLokalnych'),
        ];

        return $this->detailedReportCorrection($result);
    }

    /**
     * Formats `BIR11OsPrawna` report.
     *
     * @param stdClass $activity Actual report contents.
     *
     * @return array
     */
    private function formatCompanyDetails(stdClass $activity): array
    {

        // Lvd.
        $terc  = $activity->{'praw_adSiedzWojewodztwo_Symbol'} . $activity->{'praw_adSiedzPowiat_Symbol'};
        $terc .= $activity->{'praw_adSiedzGmina_Symbol'};

        // Lvd.
        $result = [
            'regon' => $this->nullify($activity, 'praw_regon9'),
            'name' => [
                'long' => $this->nullify($activity, 'praw_nazwa'),
                'short' => $this->nullify($activity, 'praw_nazwaSkrocona'),
            ],
            'wasntStarted' => false,
            'dates' => [
                'creation' => $this->nullify($activity, 'praw_dataPowstania'),
                'operationStarted' => $this->nullify($activity, 'praw_dataRozpoczeciaDzialalnosci'),
                'regonReg' => $this->nullify($activity, 'praw_dataWpisuDoRegon'),
                'suspended' => $this->nullify($activity, 'praw_dataZawieszeniaDzialalnosci'),
                'renewed' => $this->nullify($activity, 'praw_dataWznowieniaDzialalnosci'),
                'lastChange' => $this->nullify($activity, 'praw_dataZaistnieniaZmiany'),
                'ended' => $this->nullify($activity, 'praw_dataZakonczeniaDzialalnosci'),
                'regonUnreg' => $this->nullify($activity, 'praw_dataSkresleniaZRegon'),
                'bankruptcyDeclared' => $this->nullify($activity, 'praw_dataOrzeczeniaOUpadlosci'),
                'bankruptcyProceeded' => $this->nullify($activity, 'praw_dataZakonczeniaPostepowaniaUpadlosciowego'),
            ],
            'regAddress' => [
                'voivodeship' => $this->nullify($activity, 'praw_adSiedzWojewodztwo_Nazwa'),
                'county' => $this->nullify($activity, 'praw_adSiedzPowiat_Nazwa'),
                'municipality' => $this->nullify($activity, 'praw_adSiedzGmina_Nazwa'),
                'city' => $this->nullify($activity, 'praw_adSiedzMiejscowosc_Nazwa'),
                'street' => $this->nullify($activity, 'praw_adSiedzUlica_Nazwa'),
                'propNumber' => $this->nullify($activity, 'praw_adSiedzNumerNieruchomosci'),
                'flatNumber' => $this->nullify($activity, 'praw_adSiedzNumerLokalu'),
                'postCity' => $this->nullify($activity, 'praw_adSiedzMiejscowoscPoczty_Nazwa'),
                'zipCode' => $this->nullify($activity, 'praw_adSiedzKodPocztowy'),
                'country' => $this->nullify($activity, 'praw_adSiedzKraj_Symbol'),
                'teryt' => [
                    'terc' => $terc,
                    'simcPost' => $this->nullify($activity, 'praw_adSiedzMiejscowoscPoczty_Symbol'),
                    'simc' => $this->nullify($activity, 'praw_adSiedzMiejscowosc_Symbol'),
                    'ulic' => $this->nullify($activity, 'praw_adSiedzUlica_Symbol'),
                ],
                'untypical' => $this->nullify($activity, 'praw_adSiedzNietypoweMiejsceLokalizacji'),
            ],
            'form' => [
                'baseCode' => $this->nullify($activity, 'praw_podstawowaFormaPrawna_Symbol'),
                'detailedCode' => $this->nullify($activity, 'praw_szczegolnaFormaPrawna_Symbol'),
                'financialCode' => $this->nullify($activity, 'praw_formaFinansowania_Symbol'),
                'ownershipCode' => $this->nullify($activity, 'praw_formaWlasnosci_Symbol'),
                'baseName' => $this->nullify($activity, 'praw_podstawowaFormaPrawna_Nazwa'),
                'detailedName' => $this->nullify($activity, 'praw_szczegolnaFormaPrawna_Nazwa'),
                'financialName' => $this->nullify($activity, 'praw_formaFinansowania_Nazwa'),
                'ownershipName' => $this->nullify($activity, 'praw_formaWlasnosci_Nazwa'),
            ],
            'mother' => [
                'symbol' => $this->nullify($activity, 'praw_organZalozycielski_Symbol'),
                'name' => $this->nullify($activity, 'praw_organZalozycielski_Nazwa'),
            ],
            'contact' => [
                'phone' => $this->nullify($activity, 'praw_numerTelefonu'),
                'internalPhone' => $this->nullify($activity, 'praw_numerWewnetrznyTelefonu'),
                'fax' => $this->nullify($activity, 'praw_numerFaksu'),
                'email' => $this->nullify($activity, 'praw_adresEmail'),
                'web' => $this->nullify($activity, 'praw_adresStronyinternetowej'),
            ],
            'registry' => [
                'type' => $this->nullify($activity, 'praw_rodzajRejestruEwidencji_Symbol'),
                'name' => [
                    'std' => 'krs',
                    'formal' => $this->nullify($activity, 'praw_rodzajRejestruEwidencji_Nazwa'),
                ],
                'number' => $this->nullify($activity, 'praw_numerWRejestrzeEwidencji'),
                'addedDate' => $this->nullify($activity, 'praw_dataWpisuDoRejestruEwidencji'),
                'deletedDate' => null,
                'registree' => [
                    'symbol' => $this->nullify($activity, 'praw_organRejestrowy_Symbol'),
                    'name' => $this->nullify($activity, 'praw_organRejestrowy_Nazwa'),
                ],
            ],
            'localCount' => (int) $this->nullify($activity, 'praw_liczbaJednLokalnych'),
        ];

        return $this->detailedReportCorrection($result);
    }

    /**
     * Formats `BIR11OsPrawnaPkd` report.
     *
     * @param stdClass $data Actual report contents.
     *
     * @return array
     */
    private function formatPkd(stdClass $data): array
    {

        // Define report.
        $report = [
            'main' => null,
            'otherAr' => [],
        ];

        // Add all pkds.
        foreach ((array) $data as $pkd) {
            if ($pkd->{'praw_pkdPrzewazajace'} === '1') {
                $report['main'] = $pkd->{'praw_pkdKod'};
            } else {
                $report['otherAr'][] = $pkd->{'praw_pkdKod'};
            }
        }

        // Flatten.
        $report['other'] = implode(', ', $report['otherAr']);

        return $report;
    }

    /**
     * Internal correction after `formatPersonDetails` or `formatCompanyDetails` method.
     *
     * @param array $report Formatted report from one of thow above methods.
     *
     * @return array
     */
    private function detailedReportCorrection(array $report): array
    {

        // Correct wasn't started.
        if (empty($report['wasntStarted']) === true || $report['wasntStarted'] === 'false') {
            $report['wasntStarted'] = false;
        } else {
            $report['wasntStarted'] = true;
        }

        // Correct zip address.
        if ($report['regAddress']['zipCode'] !== null && is_numeric($report['regAddress']['zipCode']) === true) {

            // Copy.
            $zip = $report['regAddress']['zipCode'];

            // Change.
            $report['regAddress']['zipCode'] = substr($zip, 0, 2) . '-' . substr($zip, 2, 3);
        }

        // Correct voivodeship.
        if ($report['regAddress']['voivodeship'] !== null) {
            $report['regAddress']['voivodeship'] = mb_strtolower($report['regAddress']['voivodeship']);
        }

        // Correct form detailed code.
        $report['form']['detailedCode'] = str_pad($report['form']['detailedCode'], 3, '0', STR_PAD_LEFT);

        return $report;
    }

    /**
     * Helper method to return node contents or null if contents is empty.
     *
     * @param stdClass $data Data source (any report from formatted).
     * @param string   $key  Key of node to nullify if empty.
     *
     * @return null|string
     */
    private function nullify(stdClass $data, string $key): ?string
    {

        // Get value.
        $value = ( $data->{$key} ?? null );

        // Return null if that value is empty.
        if (empty($value) === true) {
            return null;
        }

        // Return this value otherwise.
        return (string) $value;
    }
}
