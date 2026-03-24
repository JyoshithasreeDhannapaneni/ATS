# Job Order Type-Specific Questionnaires - Configuration Guide

## Overview

The system now supports **different questionnaires for different job order types**. When a recruiter selects a job order type (Contract, Full Time, Hire, etc.), the appropriate questionnaire is automatically selected for candidates applying to that position.

## How It Works

### Architecture

The system consists of three main components:

#### 1. **JobOrderTypeQuestionnaires.php** (Configuration)
- Located at: `/lib/JobOrderTypeQuestionnaires.php`
- Defines mappings between job order types and questionnaires
- Maps job types (C, C2H, FL, FT, H) to questionnaire IDs

#### 2. **getQuestionnaireByJobType.php** (AJAX Endpoint)
- Located at: `/ajax/getQuestionnaireByJobType.php`
- Receives job order type via POST request
- Returns the corresponding questionnaire ID
- Called automatically when job type is selected

#### 3. **Updated Templates** (User Interface)
- `/modules/joborders/Add.tpl` - Add job order form
- `/modules/joborders/Edit.tpl` - Edit job order form
- Both now include auto-selection logic

### Data Flow

```
User selects Job Type in dropdown
       ↓
selectQuestionnaireByType() JavaScript function is called
       ↓
AJAX request to getQuestionnaireByJobType.php with selected type
       ↓
JobOrderTypeQuestionnaires class returns mapping
       ↓
Questionnaire dropdown auto-updates with the appropriate questionnaire
       ↓
Candidates see questions specific to the job type
```

## Setup & Configuration

### Step 1: Create Questionnaires in the System

First, you need to create questionnaires for each job type:

1. Login to your ATS system with admin access
2. Navigate to **Settings → Career Portal Settings → Questionnaires**
3. Create separate questionnaires for each job type:
   - **Contract Positions** - Questions for contract roles
   - **Contract to Hire** - Questions for C2H positions
   - **Freelance** - Questions for freelance work
   - **Full Time** - Questions for permanent positions
   - **Direct Hire** - Questions for hire positions

Example questions you might include:

**For Contract Positions:**
- "How many years of experience do you have in this field?"
- "Are you available to start immediately?"
- "What is your expected hourly rate?"

**For Full Time Positions:**
- "Are you seeking a permanent position?"
- "Do you have benefits experience (insurance, 401k)?"
- "What is your salary expectation?"

**For Freelance:**
- "Do you have your own business license?"
- "Are you available for part-time work?"
- "What are your preferred project types?"

### Step 2: Note the Questionnaire IDs

After creating questionnaires, you need to find their database IDs:

1. Check the URL when viewing a questionnaire (should show ID in the URL)
2. Or check the database directly:
```sql
SELECT career_portal_questionnaire_id, title FROM career_portal_questionnaire;
```

Example output:
```
ID  Title
1   Contract Positions
2   Contract to Hire
3   Freelance
4   Full Time
5   Direct Hire
```

### Step 3: Configure the Mapping

Edit `/lib/JobOrderTypeQuestionnaires.php` and update the mapping:

```php
public function __construct() {
    $this->_typeQuestionnaireMapping = array(
        'C' => array(
            'title' => 'Contract Position Questions',
            'questionnaireID' => 1  // Change to your actual questionnaire ID
        ),
        'C2H' => array(
            'title' => 'Contract to Hire Questions',
            'questionnaireID' => 2  // Change to your actual questionnaire ID
        ),
        'FL' => array(
            'title' => 'Freelance Position Questions',
            'questionnaireID' => 3  // Change to your actual questionnaire ID
        ),
        'FT' => array(
            'title' => 'Full Time Position Questions',
            'questionnaireID' => 4  // Change to your actual questionnaire ID
        ),
        'H' => array(
            'title' => 'Direct Hire Questions',
            'questionnaireID' => 5  // Change to your actual questionnaire ID
        )
    );
}
```

### Step 4: Test the Configuration

1. Go to **Job Orders → Add Job Order** or **Edit Job Order**
2. Select a job type from the **Type** dropdown
3. Watch the **Questionnaire** field auto-update
4. The correct questionnaire should be automatically selected

## Example Scenario

### Scenario: Creating a Contract Position Job Order

1. **Recruiter opens Add Job Order form**
2. **Recruiter enters:**
   - Title: "PHP Developer - 6 months"
   - Company: "ABC Corp"
   - Type: **C (Contract)** ← Selects this
3. **System automatically:**
   - Calls AJAX endpoint
   - Finds questionnaire mapped to 'C' type
   - Sets questionnaire dropdown to "Contract Position Questions"
4. **When candidates apply:**
   - They see questions configured for contract roles
   - Example: "What is your expected hourly rate?"
   - They don't see full-time specific questions

### Another Scenario: Creating a Full Time Job Order

1. **Recruiter opens Add Job Order form**
2. **Recruiter enters:**
   - Title: "Senior Manager"
   - Company: "XYZ Inc"
   - Type: **FT (Full Time)** ← Selects this
3. **System automatically:**
   - Calls AJAX endpoint
   - Finds questionnaire mapped to 'FT' type
   - Sets questionnaire dropdown to "Full Time Position Questions"
4. **When candidates apply:**
   - They see questions configured for full-time roles
   - Example: "What is your salary expectation?"
   - They don't see contract-specific questions

## Advanced Configuration

### Option A: Dynamic Database-Driven Configuration

For a fully dynamic system (recommended for large organizations), you could create a database table:

```sql
CREATE TABLE `job_order_type_questionnaire_mapping` (
  `mapping_id` int(11) NOT NULL AUTO_INCREMENT,
  `job_order_type` varchar(10) NOT NULL,
  `career_portal_questionnaire_id` int(11),
  `site_id` int(11) NOT NULL,
  `created_date` datetime,
  PRIMARY KEY (`mapping_id`),
  UNIQUE KEY `unique_type_site` (`job_order_type`, `site_id`)
);
```

Then modify `JobOrderTypeQuestionnaires.php` to load from the database instead of hard-coded values.

### Option B: No Questionnaire for Certain Types

To disable auto-selection for certain types, set `questionnaireID` to `null`:

```php
'FT' => array(
    'title' => 'Full Time Position Questions',
    'questionnaireID' => null  // No auto-selection, manual choice only
)
```

### Option C: Default Questionnaire for All Types

Create a single master questionnaire with generic questions, and map all types to it:

```php
public function __construct() {
    $genericQuestionnaire = 2;

    $this->_typeQuestionnaireMapping = array(
        'C' => array('title' => 'Contract', 'questionnaireID' => $genericQuestionnaire),
        'C2H' => array('title' => 'Contract to Hire', 'questionnaireID' => $genericQuestionnaire),
        'FL' => array('title' => 'Freelance', 'questionnaireID' => $genericQuestionnaire),
        'FT' => array('title' => 'Full Time', 'questionnaireID' => $genericQuestionnaire),
        'H' => array('title' => 'Hire', 'questionnaireID' => $genericQuestionnaire)
    );
}
```

## Technical Details

### Files Modified/Created

1. **Created:**
   - `/lib/JobOrderTypeQuestionnaires.php` - Configuration class
   - `/ajax/getQuestionnaireByJobType.php` - AJAX endpoint

2. **Modified:**
   - `/modules/joborders/Add.tpl` - Added auto-selection JavaScript
   - `/modules/joborders/Edit.tpl` - Added auto-selection JavaScript

### JavaScript Functions

#### selectQuestionnaireByType()
Automatically called when job type is changed:
- Gets selected job order type
- Makes AJAX request to get questionnaire ID
- Updates questionnaire dropdown with auto-selected value
- Shows loading feedback to user

### Fallback Behavior

If no questionnaire is configured for a type:
- The questionnaire field defaults to "None"
- A console message informs the user
- Recruiter can still manually select a questionnaire

## Troubleshooting

### Issue: Questionnaire not auto-selecting

**Solution:**
1. Check browser console for JavaScript errors (F12 → Console tab)
2. Verify questionnaire IDs in `JobOrderTypeQuestionnaires.php` match database
3. Test AJAX endpoint directly:
   ```
   POST to: index.php?f=getQuestionnaireByJobType
   Body: jobOrderType=FT&siteID=1
   ```

### Issue: Wrong questionnaire selected

**Solution:**
1. Check the mapping in `JobOrderTypeQuestionnaires.php`
2. Verify questionnaire IDs are correct:
   ```sql
   SELECT * FROM career_portal_questionnaire;
   ```

### Issue: Auto-selection works in Add but not Edit

**Solution:**
1. Verify Edit.tpl has the `selectQuestionnaireByType()` function added
2. Check that the Type dropdown has `onchange="selectQuestionnaireByType();"`
3. Clear browser cache and refresh

## Best Practices

1. **Clear, Descriptive Questionnaires:** Give each questionnaire a clear title indicating the job type
2. **Type-Specific Questions:** Make questions relevant to the job type
3. **Consistent Naming:** Use the same naming convention for questionnaires (e.g., "Contract - Questions", "Full Time - Questions")
4. **Regular Review:** Periodically review and update questionnaires based on feedback
5. **Testing:** Test each job type before going live

## Support & Maintenance

- **Config Location:** `/lib/JobOrderTypeQuestionnaires.php`
- **AJAX Endpoint:** `/ajax/getQuestionnaireByJobType.php`
- **Requires:** Session authentication (handled automatically)
- **Database Tables Used:**
  - `career_portal_questionnaire` - stores questionnaire data
  - `job_order` - stores job orders with questionnaire_id field

## Conclusion

This system provides:
- ✅ Automatic questionnaire selection based on job type
- ✅ Flexible configuration without code changes
- ✅ Fallback to manual selection if needed
- ✅ Scalable to many questionnaires and types
- ✅ User-friendly experience with visual feedback
