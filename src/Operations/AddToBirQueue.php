<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug\Operations;

use Przeslijmi\AgileData\Operations\OperationsInterface as MyInterface;
use Przeslijmi\AgileData\Operations\OperationsParent as MyParent;
use Przeslijmi\AgileDataBirPlug\NipHandler;
use stdClass;

/**
 * Operation that adds NIPs into BIR queue.
 */
class AddToBirQueue extends MyParent implements MyInterface
{

    /**
     * Operation key.
     *
     * @var string
     */
    protected static $opKey = 'mulEMI1R';

    /**
     * Only those fields are accepted for this operation.
     *
     * @var array
     */
    public static $operationFields = [
        'nipSource',
        'refresh',
        'forced',
    ];

    /**
     * Get info (mainly name and category of this operation).
     *
     * @return stdClass
     */
    public static function getInfo(): stdClass
    {

        // Lvd.
        $locSta = 'Przeslijmi.AgileDataBirPlug.Operations.AddToBirQueue.';

        // Lvd.
        $result           = new stdClass();
        $result->name     = $_ENV['LOCALE']->get($locSta . 'title');
        $result->vendor   = 'Przeslijmi\AgileDataBirPlug';
        $result->class    = self::class;
        $result->depr     = false;
        $result->category = 800;

        return $result;
    }

    /**
     * Deliver fields to edit settings of this operation.
     *
     * @param string        $taskId Id of task in which edited step is present.
     * @param stdClass|null $step   Opt. Only when editing step (when creating it is null).
     *
     * @return array
     *
     * @phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter
     */
    public static function getStepFormFields(string $taskId, ?stdClass $step = null): array
    {

        // Lvd.
        $fields = [];
        $loc    = $_ENV['LOCALE'];
        $locSta = 'Przeslijmi.AgileDataBirPlug.Operations.AddToBirQueue.fields.';
        $file   = self::defineFileChooserValue(( $step->fileUri ?? '' ));

        // Add fields.
        $fields[] = [
            'type' => 'select',
            'id' => 'nipSource',
            'value' => ( $step->nipSource ?? '' ),
            'name' => $loc->get($locSta . 'nipSource.name'),
            'desc' => $loc->get($locSta . 'nipSource.desc'),
            'options' => [],
            'isAvailablePropChooser' => true,
            'group' => $loc->get('Przeslijmi.AgileData.tabs.operation'),
        ];
        $fields[] = [
            'type' => 'select',
            'id' => 'refresh',
            'value' => ( $step->refresh ?? 180 ),
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
            'group' => $loc->get('Przeslijmi.AgileData.tabs.operation'),
        ];
        $fields[] = [
            'type' => 'select',
            'id' => 'forced',
            'value' => ( $step->forced ?? 'yes' ),
            'name' => $loc->get($locSta . 'forced.name'),
            'desc' => $loc->get($locSta . 'forced.desc'),
            'options' => [
                'no' => $loc->get($locSta . 'forced.options.no'),
                'yes' => $loc->get($locSta . 'forced.options.yes'),
                'onlyWhenMoreFrequent' => $loc->get($locSta . 'forced.options.onlyWhenMoreFrequent'),
                'onlyWhenLessFrequent' => $loc->get($locSta . 'forced.options.onlyWhenLessFrequent'),
            ],
            'group' => $loc->get('Przeslijmi.AgileData.tabs.operation'),
        ];

        return $fields;
    }

    /**
     * Validates plug definition.
     *
     * @return void
     */
    public function validate(): void
    {

        // Lvd.
        $existingProps = array_column($this->getPropsAvailableBefore($this->getStepId()), 'name');

        // Test nodes.
        $this->testNodes($this->getStepPathInPlug(), $this->getStep(), [
            'nipSource' => [ '!stringEnum', $existingProps ],
            'refresh' => [ '!stringEnum', [ '7', '14', '30', '60', '90', '180', '-1' ] ],
            'forced' => [ '!stringEnum', [ 'no', 'yes', 'onlyWhenMoreFrequent', 'onlyWhenLessFrequent' ] ],
        ]);
    }

    /**
     * Add NIPs to queue.
     *
     * @return void
     */
    public function perform(): void
    {

        // Lvd.
        $nipProp = $this->getStep()->nipSource;
        $forced  = $this->getStep()->forced;
        $refresh = (int) $this->getStep()->refresh;

        // Add every one.
        foreach ($this->getCallingTask()->getRecords() as $record) {

            // Lvd.
            $nip = ( $record->properties->{$nipProp} ?? '' );
            $nip = preg_replace('/([^0-9])/', '', $nip);

            // Add.
            if (strlen($nip) !== 10) {
                continue;
            }

            $handler = new NipHandler($nip);

            if ($forced === 'yes') {
                $handler->setRefreshOn($refresh);
            } elseif ($forced === 'no' && $handler->isExisting() === false) {
                $handler->setRefreshOn($refresh);
            } elseif ($forced === 'onlyWhenMoreFrequent' && $handler->getRefreshOn() > $refresh) {
                $handler->setRefreshOn($refresh);
            } elseif ($forced === 'onlyWhenLessFrequent' && $handler->getRefreshOn() < $refresh) {
                $handler->setRefreshOn($refresh);
            }

            $handler->save();
        }//end foreach
    }
}
