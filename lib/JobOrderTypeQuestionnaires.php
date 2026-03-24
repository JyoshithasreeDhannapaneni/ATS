<?php

/**
 * Job Order Type to Questionnaire Mapping
 * Maps different job order types to their corresponding questionnaires
 *
 * @package OpenCATS
 * @subpackage Library
 */

class JobOrderTypeQuestionnaires
{
    /**
     * Configuration mapping job order types to questionnaire IDs
     *
     * Example structure:
     * 'C' => array(
     *     'title' => 'Contract Position Questions',
     *     'questionnaireID' => 1  // ID from database, or null for manual selection
     * )
     */
    private $_typeQuestionnaireMapping;

    public function __construct()
    {
        // Define default mapping - these questionnaire IDs are mapped to database questionnaires
        $this->_typeQuestionnaireMapping = array(
            'C' => array(
                'title' => 'Contract Position Questions',
                'questionnaireID' => 2  // Contract positions
            ),
            'C2H' => array(
                'title' => 'Contract to Hire Questions',
                'questionnaireID' => 3  // Contract to Hire positions
            ),
            'FL' => array(
                'title' => 'Freelance Position Questions',
                'questionnaireID' => 4  // Freelance positions
            ),
            'FT' => array(
                'title' => 'Full Time Position Questions',
                'questionnaireID' => 5  // Full Time positions
            ),
            'H' => array(
                'title' => 'Direct Hire Questions',
                'questionnaireID' => 6  // Direct Hire positions
            )
        );
    }

    /**
     * Get questionnaire mapping for a specific job order type
     *
     * @param string $jobOrderType Job order type code (C, C2H, FL, FT, H)
     * @return array Mapping with title and questionnaireID, or null if not configured
     */
    public function getMappingForType($jobOrderType)
    {
        return isset($this->_typeQuestionnaireMapping[$jobOrderType])
            ? $this->_typeQuestionnaireMapping[$jobOrderType]
            : null;
    }

    /**
     * Get all type-questionnaire mappings
     *
     * @return array Complete mapping array
     */
    public function getAllMappings()
    {
        return $this->_typeQuestionnaireMapping;
    }

    /**
     * Get questionnaire ID for a specific job order type
     *
     * @param string $jobOrderType Job order type code
     * @return int|null Questionnaire ID or null if not configured
     */
    public function getQuestionnaireIDForType($jobOrderType)
    {
        $mapping = $this->getMappingForType($jobOrderType);
        return $mapping ? $mapping['questionnaireID'] : null;
    }

    /**
     * Set questionnaire mapping for a type
     * (Can be used to update mappings programmatically)
     *
     * @param string $jobOrderType Job order type code
     * @param int $questionnaireID Database questionnaire ID
     * @param string $title Description title
     */
    public function setMappingForType($jobOrderType, $questionnaireID, $title = '')
    {
        if (isset($this->_typeQuestionnaireMapping[$jobOrderType])) {
            $this->_typeQuestionnaireMapping[$jobOrderType]['questionnaireID'] = $questionnaireID;
            if (!empty($title)) {
                $this->_typeQuestionnaireMapping[$jobOrderType]['title'] = $title;
            }
        }
    }
}
?>
