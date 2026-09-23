<?php

namespace Helper;

use Illuminate\Support\Facades\Config;

class StrataMeetingDocumentHelper
{
    public static function documentLabel($slug)
    {
        $key = 'strata_meeting_documents.' . $slug;
        $label = trans($key);

        return ($label !== $key) ? $label : $slug;
    }

    /**
     * @param int $type 1=First, 2=Subsequent
     * @param int $agmType 1=AGM, 2=EGM
     * @param string|null $timing before|after|null for both
     * @return array
     */
    public static function getVisibleDocuments($type, $agmType, $timing = null)
    {
        $type = (int) $type;
        $agmType = (int) $agmType;
        $documents = Config::get('strata_meeting_documents.documents', array());
        $visible = array();

        foreach ($documents as $document) {
            foreach ($document['show_for'] as $rule) {
                if ((int) $rule['type'] !== $type || (int) $rule['agm_type'] !== $agmType) {
                    continue;
                }
                $ruleTiming = isset($rule['timing']) ? $rule['timing'] : 'before';
                if ($timing !== null && $ruleTiming !== $timing) {
                    continue;
                }
                $visible[] = array(
                    'id' => $document['id'],
                    'label' => self::documentLabel($document['id']),
                    'category' => $rule['category'],
                    'timing' => $ruleTiming,
                    'is_field' => 'is_' . $document['id'],
                    'url_field' => $document['id'] . '_url',
                );
                break;
            }
        }

        return $visible;
    }

    public static function groupedFormDocuments($type, $agmType)
    {
        $beforeMandatory = array();
        $beforeAdditional = array();
        $afterMandatory = array();
        $afterAdditional = array();

        foreach (self::getVisibleDocuments($type, $agmType) as $doc) {
            if ($doc['timing'] === 'after') {
                if ($doc['category'] === 'mandatory') {
                    $afterMandatory[] = $doc;
                } else {
                    $afterAdditional[] = $doc;
                }
            } else {
                if ($doc['category'] === 'mandatory') {
                    $beforeMandatory[] = $doc;
                } else {
                    $beforeAdditional[] = $doc;
                }
            }
        }

        return array(
            'before_mandatory' => $beforeMandatory,
            'before_additional' => $beforeAdditional,
            'after_mandatory' => $afterMandatory,
            'after_additional' => $afterAdditional,
        );
    }

    public static function agmTypeLabel($agmType)
    {
        return (int) $agmType === \StrataMeetingDocument::IS_EGM ? strtoupper(trans('egm')) : strtoupper(trans('agm'));
    }

    public static function timingTypeLabel($type)
    {
        return (int) $type === \StrataMeetingDocument::FIRST_AGM
            ? trans('app.forms.first_agm')
            : trans('app.forms.strata_meeting_subsequent_agm');
    }
}
