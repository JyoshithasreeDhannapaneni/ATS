<?php
/**
 * Setup Script - Create Sample Questionnaires for Each Job Type
 * Run this once to create questionnaires and display their IDs
 */

include_once('../config.php');
include_once('../constants.php');
include_once('../lib/DatabaseConnection.php');

header('Content-Type: application/json');

try {
    $db = DatabaseConnection::getInstance();
    $siteID = 1;

    // Define questionnaires to create
    $questionnaires = array(
        array(
            'title' => 'Contract Position Questions',
            'description' => 'Questions specific to contract positions'
        ),
        array(
            'title' => 'Contract to Hire Questions',
            'description' => 'Questions specific to contract-to-hire positions'
        ),
        array(
            'title' => 'Freelance Position Questions',
            'description' => 'Questions specific to freelance positions'
        ),
        array(
            'title' => 'Full Time Position Questions',
            'description' => 'Questions specific to full-time permanent positions'
        ),
        array(
            'title' => 'Direct Hire Questions',
            'description' => 'Questions specific to direct hire positions'
        )
    );

    $createdQuestionnaires = array();

    // Check if questionnaires already exist
    foreach ($questionnaires as $q) {
        $checkSQL = sprintf(
            "SELECT career_portal_questionnaire_id FROM career_portal_questionnaire
             WHERE title = %s AND site_id = %d",
            $db->makeQueryString($q['title']),
            $siteID
        );

        $existing = $db->getAssoc($checkSQL);

        if ($existing) {
            $createdQuestionnaires[] = array(
                'title' => $q['title'],
                'id' => $existing['career_portal_questionnaire_id'],
                'status' => 'already_exists'
            );
        } else {
            // Create new questionnaire
            $insertSQL = sprintf(
                "INSERT INTO career_portal_questionnaire
                (title, description, is_active, site_id)
                VALUES (%s, %s, 1, %d)",
                $db->makeQueryString($q['title']),
                $db->makeQueryString($q['description']),
                $siteID
            );

            if ($db->query($insertSQL)) {
                $id = $db->getLastInsertID();
                $createdQuestionnaires[] = array(
                    'title' => $q['title'],
                    'id' => $id,
                    'status' => 'created'
                );
            } else {
                $createdQuestionnaires[] = array(
                    'title' => $q['title'],
                    'status' => 'error'
                );
            }
        }
    }

    echo json_encode(array(
        'success' => true,
        'message' => 'Questionnaires setup complete',
        'questionnaires' => $createdQuestionnaires,
        'instructions' => array(
            'Copy the IDs below',
            'Edit: /lib/JobOrderTypeQuestionnaires.php',
            'Update the questionnaireID values with these IDs',
            'Test by creating a job order'
        )
    ), JSON_PRETTY_PRINT);

} catch (Exception $e) {
    echo json_encode(array(
        'success' => false,
        'error' => $e->getMessage()
    ));
}
?>
