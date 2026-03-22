<?php
/**
 * Neutara ATS
 * Local Resume Parsing Engine
 *
 * Extracts structured data from resume text using pattern matching.
 * Replaces the external SOAP-based ParseUtility when the service is unavailable.
 *
 * Extracts: name, email, phone, address, city, state, zip, skills, education, experience.
 */

class LocalParseUtility
{
    private $_text;
    private $_lines;

    /* Common skill keywords for matching */
    private static $_skillPatterns = array(
        // Programming Languages
        'PHP', 'Java', 'Python', 'JavaScript', 'TypeScript', 'C\+\+', 'C#', 'Ruby', 'Go',
        'Swift', 'Kotlin', 'Rust', 'Scala', 'Perl', 'R', 'MATLAB', 'Objective-C',
        'Visual Basic', 'VB\.NET', 'Dart', 'Lua', 'Haskell', 'Elixir', 'Clojure',
        // Web
        'HTML', 'CSS', 'SASS', 'LESS', 'React', 'Angular', 'Vue\.?js', 'Node\.?js',
        'Express\.?js', 'Next\.?js', 'Nuxt\.?js', 'jQuery', 'Bootstrap', 'Tailwind',
        'WordPress', 'Django', 'Flask', 'Laravel', 'Spring Boot', 'ASP\.NET',
        'Ruby on Rails', 'Symfony', 'CodeIgniter',
        // Databases
        'MySQL', 'PostgreSQL', 'MongoDB', 'Redis', 'Oracle', 'SQL Server', 'SQLite',
        'Cassandra', 'DynamoDB', 'Elasticsearch', 'MariaDB', 'Firebase',
        // Cloud & DevOps
        'AWS', 'Azure', 'GCP', 'Google Cloud', 'Docker', 'Kubernetes', 'Jenkins',
        'CI/CD', 'Terraform', 'Ansible', 'Chef', 'Puppet', 'Nginx', 'Apache',
        'Linux', 'Unix', 'Git', 'GitHub', 'GitLab', 'Bitbucket',
        // Data & AI
        'Machine Learning', 'Deep Learning', 'TensorFlow', 'PyTorch', 'Keras',
        'Pandas', 'NumPy', 'Scikit-learn', 'NLP', 'Computer Vision',
        'Data Science', 'Data Analysis', 'Data Engineering', 'Big Data',
        'Hadoop', 'Spark', 'Kafka', 'Tableau', 'Power BI',
        // Mobile
        'Android', 'iOS', 'React Native', 'Flutter', 'Xamarin', 'Ionic',
        // Tools & Methodologies
        'Agile', 'Scrum', 'Kanban', 'JIRA', 'Confluence', 'REST API', 'GraphQL',
        'Microservices', 'SOA', 'Design Patterns', 'TDD', 'BDD',
        'Unit Testing', 'Integration Testing', 'Selenium', 'Cypress',
        // Business & Other
        'Project Management', 'Product Management', 'Business Analysis',
        'SAP', 'Salesforce', 'CRM', 'ERP', 'Excel', 'PowerPoint',
        'Communication', 'Leadership', 'Problem Solving', 'Team Management',
        'Six Sigma', 'Lean', 'PMP', 'ITIL', 'Certified', 'Accounting',
        'Marketing', 'SEO', 'SEM', 'Google Analytics', 'Figma', 'Adobe',
        'Photoshop', 'Illustrator', 'AutoCAD', 'SolidWorks'
    );

    /* US state abbreviations and names */
    private static $_usStates = array(
        'AL' => 'Alabama', 'AK' => 'Alaska', 'AZ' => 'Arizona', 'AR' => 'Arkansas',
        'CA' => 'California', 'CO' => 'Colorado', 'CT' => 'Connecticut', 'DE' => 'Delaware',
        'FL' => 'Florida', 'GA' => 'Georgia', 'HI' => 'Hawaii', 'ID' => 'Idaho',
        'IL' => 'Illinois', 'IN' => 'Indiana', 'IA' => 'Iowa', 'KS' => 'Kansas',
        'KY' => 'Kentucky', 'LA' => 'Louisiana', 'ME' => 'Maine', 'MD' => 'Maryland',
        'MA' => 'Massachusetts', 'MI' => 'Michigan', 'MN' => 'Minnesota', 'MS' => 'Mississippi',
        'MO' => 'Missouri', 'MT' => 'Montana', 'NE' => 'Nebraska', 'NV' => 'Nevada',
        'NH' => 'New Hampshire', 'NJ' => 'New Jersey', 'NM' => 'New Mexico', 'NY' => 'New York',
        'NC' => 'North Carolina', 'ND' => 'North Dakota', 'OH' => 'Ohio', 'OK' => 'Oklahoma',
        'OR' => 'Oregon', 'PA' => 'Pennsylvania', 'RI' => 'Rhode Island', 'SC' => 'South Carolina',
        'SD' => 'South Dakota', 'TN' => 'Tennessee', 'TX' => 'Texas', 'UT' => 'Utah',
        'VT' => 'Vermont', 'VA' => 'Virginia', 'WA' => 'Washington', 'WV' => 'West Virginia',
        'WI' => 'Wisconsin', 'WY' => 'Wyoming', 'DC' => 'District of Columbia'
    );

    /* Indian states */
    private static $_indianStates = array(
        'Andhra Pradesh', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh',
        'Goa', 'Gujarat', 'Haryana', 'Himachal Pradesh', 'Jharkhand', 'Karnataka',
        'Kerala', 'Madhya Pradesh', 'Maharashtra', 'Manipur', 'Meghalaya', 'Mizoram',
        'Nagaland', 'Odisha', 'Punjab', 'Rajasthan', 'Sikkim', 'Tamil Nadu',
        'Telangana', 'Tripura', 'Uttar Pradesh', 'Uttarakhand', 'West Bengal',
        'Delhi', 'New Delhi', 'Hyderabad', 'Bangalore', 'Bengaluru', 'Mumbai',
        'Chennai', 'Kolkata', 'Pune', 'Ahmedabad', 'Jaipur', 'Lucknow'
    );

    /* Education degree patterns */
    private static $_degreePatterns = array(
        'Ph\.?D\.?', 'Doctorate', 'Doctor of',
        'M\.?B\.?A\.?', 'Master(?:\'?s)?(?:\s+of\s+\w+)?', 'M\.?S\.?', 'M\.?A\.?',
        'M\.?Tech\.?', 'M\.?E\.?', 'M\.?C\.?A\.?', 'M\.?Sc\.?', 'M\.?Com\.?',
        'B\.?Tech\.?', 'B\.?E\.?', 'B\.?S\.?', 'B\.?A\.?', 'B\.?C\.?A\.?',
        'B\.?Sc\.?', 'B\.?Com\.?', 'B\.?B\.?A\.?',
        'Bachelor(?:\'?s)?(?:\s+of\s+\w+)?',
        'Associate(?:\'?s)?(?:\s+of\s+\w+)?',
        'Diploma', 'Certificate', 'Certification',
        'High School', 'GED', 'HSC', 'SSC', 'SSLC',
        'Computer Science', 'Information Technology', 'Engineering',
        'Business Administration', 'Management'
    );

    /* Section header patterns */
    private static $_sectionHeaders = array(
        'education'  => '/^(?:EDUCATION|ACADEMIC|QUALIFICATION|DEGREE|SCHOLASTIC)/i',
        'experience' => '/^(?:EXPERIENCE|EMPLOYMENT|WORK\s*HISTORY|PROFESSIONAL\s*EXPERIENCE|CAREER|JOB\s*HISTORY)/i',
        'skills'     => '/^(?:SKILLS|TECHNICAL\s*SKILLS|KEY\s*SKILLS|CORE\s*COMPETENC|PROFICIENC|EXPERTISE|TECHNOLOGIES)/i',
        'summary'    => '/^(?:SUMMARY|OBJECTIVE|PROFILE|ABOUT\s*ME|PROFESSIONAL\s*SUMMARY|CAREER\s*OBJECTIVE)/i',
        'projects'   => '/^(?:PROJECTS|KEY\s*PROJECTS|NOTABLE\s*PROJECTS)/i',
        'certifications' => '/^(?:CERTIFICATIONS?|LICENSES?|CREDENTIALS)/i',
        'contact'    => '/^(?:CONTACT|PERSONAL\s*(?:INFO|DETAILS)|ADDRESS)/i'
    );


    public function __construct()
    {
    }

    /**
     * Parse resume text and extract structured data.
     *
     * @param string $text Raw resume text (extracted from document)
     * @return array Parsed fields matching ParseUtility output format
     */
    public function parse($text)
    {
        $this->_text = $text;
        $this->_lines = preg_split('/\r?\n/', $text);

        $result = array(
            'first_name'    => '',
            'last_name'     => '',
            'us_address'    => '',
            'city'          => '',
            'state'         => '',
            'zip_code'      => '',
            'email_address' => '',
            'phone_number'  => '',
            'skills'        => '',
            'education'     => '',
            'experience'    => ''
        );

        // Extract in order of reliability
        $result['email_address'] = $this->_extractEmail();
        $result['phone_number']  = $this->_extractPhone();

        $nameResult = $this->_extractName();
        $result['first_name'] = $nameResult['first'];
        $result['last_name']  = $nameResult['last'];

        $locationResult = $this->_extractLocation();
        $result['us_address'] = $locationResult['address'];
        $result['city']       = $locationResult['city'];
        $result['state']      = $locationResult['state'];
        $result['zip_code']   = $locationResult['zip'];

        // Extract sections
        $sections = $this->_extractSections();

        $result['skills']     = $this->_extractSkills($sections);
        $result['education']  = $this->_extractEducation($sections);
        $result['experience'] = $this->_extractExperience($sections);

        return $result;
    }

    /**
     * Extract email address from resume text.
     */
    private function _extractEmail()
    {
        if (preg_match('/[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}/', $this->_text, $matches))
        {
            return strtolower(trim($matches[0]));
        }
        return '';
    }

    /**
     * Extract phone number from resume text.
     */
    private function _extractPhone()
    {
        $patterns = array(
            // +1 (xxx) xxx-xxxx or +91 xxxxx xxxxx
            '/(?:\+\d{1,3}[\s\-]?)?\(?\d{3}\)?[\s.\-]?\d{3}[\s.\-]?\d{4}/',
            // Indian: +91-XXXXX-XXXXX
            '/\+91[\s\-]?\d{5}[\s\-]?\d{5}/',
            // 10-digit number
            '/\b\d{10}\b/',
            // xxx-xxx-xxxx
            '/\b\d{3}[\-\.]\d{3}[\-\.]\d{4}\b/'
        );

        foreach ($patterns as $pattern)
        {
            if (preg_match($pattern, $this->_text, $matches))
            {
                return trim($matches[0]);
            }
        }
        return '';
    }

    /**
     * Extract candidate name.
     * Strategy: The name is usually the first non-empty, non-email, non-phone line.
     */
    private function _extractName()
    {
        $result = array('first' => '', 'last' => '');

        foreach ($this->_lines as $line)
        {
            $line = trim($line);

            // Skip empty lines
            if (empty($line)) continue;

            // Skip lines that look like contact info
            if (preg_match('/@|http|www\.|phone|tel:|fax:|mobile:/i', $line)) continue;

            // Skip lines that look like section headers
            $isHeader = false;
            foreach (self::$_sectionHeaders as $pattern)
            {
                if (preg_match($pattern, $line))
                {
                    $isHeader = true;
                    break;
                }
            }
            if ($isHeader) continue;

            // Skip lines with only numbers (phone, zip, etc)
            if (preg_match('/^\d+$/', $line)) continue;

            // Skip very long lines (likely a paragraph, not a name)
            if (strlen($line) > 60) continue;

            // A name line typically has 2-4 words, all alphabetical
            $words = preg_split('/\s+/', $line);
            if (count($words) >= 2 && count($words) <= 4)
            {
                $allAlpha = true;
                foreach ($words as $word)
                {
                    // Allow periods (for initials like J.) and hyphens
                    if (!preg_match('/^[A-Za-z.\-\']+$/', $word))
                    {
                        $allAlpha = false;
                        break;
                    }
                }

                if ($allAlpha)
                {
                    $result['first'] = ucfirst(strtolower($words[0]));
                    $result['last'] = ucfirst(strtolower($words[count($words) - 1]));
                    break;
                }
            }

            // Single word name at top — might be just a first name
            if (count($words) == 1 && preg_match('/^[A-Za-z\-\']{2,}$/', $words[0]))
            {
                $result['first'] = ucfirst(strtolower($words[0]));
                break;
            }
        }

        return $result;
    }

    /**
     * Extract location (address, city, state, zip).
     */
    private function _extractLocation()
    {
        $result = array('address' => '', 'city' => '', 'state' => '', 'zip' => '');

        // Look for US-style: City, ST ZIP
        if (preg_match('/([A-Za-z\s]+),\s*([A-Z]{2})\s+(\d{5}(?:-\d{4})?)/', $this->_text, $matches))
        {
            $result['city']  = trim($matches[1]);
            $result['state'] = $matches[2];
            $result['zip']   = $matches[3];
        }
        // Look for City, State (full name)
        else
        {
            $statePattern = implode('|', array_merge(
                array_keys(self::$_usStates),
                array_values(self::$_usStates),
                self::$_indianStates
            ));

            if (preg_match('/([A-Za-z\s]+),\s*(' . $statePattern . ')\b/i', $this->_text, $matches))
            {
                $result['city']  = trim($matches[1]);
                $result['state'] = trim($matches[2]);
            }
        }

        // Look for street address (number + street name)
        if (preg_match('/\d+\s+[A-Za-z]+\s+(?:Street|St|Avenue|Ave|Boulevard|Blvd|Drive|Dr|Road|Rd|Lane|Ln|Way|Court|Ct|Circle|Place|Pl|Terrace)\b\.?/i', $this->_text, $matches))
        {
            $result['address'] = trim($matches[0]);
        }

        // Look for ZIP code if not found yet
        if (empty($result['zip']) && preg_match('/\b(\d{5}(?:-\d{4})?)\b/', $this->_text, $matches))
        {
            // Verify it's not a phone number fragment
            $pos = strpos($this->_text, $matches[0]);
            $context = substr($this->_text, max(0, $pos - 20), 40);
            if (!preg_match('/phone|tel|fax|mobile|cell/i', $context))
            {
                $result['zip'] = $matches[1];
            }
        }

        return $result;
    }

    /**
     * Identify and extract resume sections by their headers.
     *
     * @return array Section name => text content
     */
    private function _extractSections()
    {
        $sections = array();
        $currentSection = 'header';
        $sectionContent = array();

        foreach ($this->_lines as $line)
        {
            $trimmedLine = trim($line);
            if (empty($trimmedLine)) continue;

            // Check if this line is a section header
            $foundSection = null;
            foreach (self::$_sectionHeaders as $sectionName => $pattern)
            {
                // Section headers are often: all caps, or followed by colon, or standalone short line
                $testLine = preg_replace('/[\-_=:]+$/', '', $trimmedLine); // Remove trailing separators
                $testLine = trim($testLine);

                if (preg_match($pattern, $testLine) && strlen($testLine) < 50)
                {
                    $foundSection = $sectionName;
                    break;
                }
            }

            if ($foundSection !== null)
            {
                // Save previous section
                if (!empty($sectionContent))
                {
                    $sections[$currentSection] = implode("\n", $sectionContent);
                }
                $currentSection = $foundSection;
                $sectionContent = array();
            }
            else
            {
                $sectionContent[] = $trimmedLine;
            }
        }

        // Save last section
        if (!empty($sectionContent))
        {
            $sections[$currentSection] = implode("\n", $sectionContent);
        }

        return $sections;
    }

    /**
     * Extract skills from resume text.
     * Uses both section detection and keyword matching.
     */
    private function _extractSkills($sections)
    {
        $foundSkills = array();

        // First: check skills section if it exists
        if (isset($sections['skills']))
        {
            // Skills section often has comma/pipe separated lists
            $skillText = $sections['skills'];
            // Split by common delimiters
            $candidates = preg_split('/[,|;•·\n]+/', $skillText);
            foreach ($candidates as $candidate)
            {
                $candidate = trim($candidate);
                if (!empty($candidate) && strlen($candidate) < 50 && strlen($candidate) > 1)
                {
                    // Clean up bullet points, numbers, etc.
                    $candidate = preg_replace('/^[\d\.\)\-\*\#]+\s*/', '', $candidate);
                    $candidate = trim($candidate);
                    if (!empty($candidate))
                    {
                        $foundSkills[] = $candidate;
                    }
                }
            }
        }

        // Second: scan entire text for known skill keywords
        foreach (self::$_skillPatterns as $skill)
        {
            // Use (?<![a-zA-Z]) and (?![a-zA-Z]) instead of \b to handle special chars like C++
            $pattern = '/(?<![a-zA-Z])' . $skill . '(?![a-zA-Z])/i';
            if (@preg_match($pattern, $this->_text, $m))
            {
                $matched = $m[0];

                // Avoid duplicates (case-insensitive)
                $isDuplicate = false;
                foreach ($foundSkills as $existing)
                {
                    if (strcasecmp($existing, $matched) === 0)
                    {
                        $isDuplicate = true;
                        break;
                    }
                }
                if (!$isDuplicate)
                {
                    $foundSkills[] = $matched;
                }
            }
        }

        return implode(', ', array_slice($foundSkills, 0, 30));
    }

    /**
     * Extract education information.
     */
    private function _extractEducation($sections)
    {
        $education = array();

        // Check education section
        if (isset($sections['education']))
        {
            $education[] = $sections['education'];
        }
        else
        {
            // Scan for degree patterns in entire text
            $degreePattern = implode('|', self::$_degreePatterns);
            if (preg_match_all('/(?:' . $degreePattern . ')[\s\w,.\-\(\)]+/i', $this->_text, $matches))
            {
                foreach ($matches[0] as $match)
                {
                    $match = trim($match);
                    if (strlen($match) > 5 && strlen($match) < 200)
                    {
                        $education[] = $match;
                    }
                }
            }
        }

        // Also look for university/college/institute names
        if (preg_match_all('/(?:University|College|Institute|School|Academy)\s+(?:of\s+)?[A-Za-z\s,]+/i', $this->_text, $matches))
        {
            foreach ($matches[0] as $match)
            {
                $match = trim($match);
                if (strlen($match) > 10 && strlen($match) < 100)
                {
                    $isDuplicate = false;
                    foreach ($education as $existing)
                    {
                        if (stripos($existing, $match) !== false)
                        {
                            $isDuplicate = true;
                            break;
                        }
                    }
                    if (!$isDuplicate) $education[] = $match;
                }
            }
        }

        // Look for graduation years
        $educationText = implode("\n", $education);
        if (preg_match_all('/(?:20[0-2]\d|19[8-9]\d)\s*[-–]\s*(?:20[0-2]\d|19[8-9]\d|Present|Current)/i', $this->_text, $yearMatches))
        {
            // Years are already captured in context above
        }

        return trim($educationText);
    }

    /**
     * Extract work experience information.
     */
    private function _extractExperience($sections)
    {
        $experience = '';

        if (isset($sections['experience']))
        {
            $experience = $sections['experience'];
        }
        else
        {
            // Try to find experience-like content: company names with date ranges
            $expLines = array();
            $datePattern = '/(?:(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[a-z]*\.?\s+\d{4}|(?:20[0-2]\d|19[8-9]\d))\s*[-–to]+\s*(?:(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[a-z]*\.?\s+\d{4}|(?:20[0-2]\d|19[8-9]\d)|Present|Current)/i';

            foreach ($this->_lines as $i => $line)
            {
                if (preg_match($datePattern, $line))
                {
                    // Include this line and a few surrounding lines
                    for ($j = max(0, $i - 1); $j <= min(count($this->_lines) - 1, $i + 3); $j++)
                    {
                        $l = trim($this->_lines[$j]);
                        if (!empty($l)) $expLines[] = $l;
                    }
                    $expLines[] = ''; // separator
                }
            }

            $experience = implode("\n", $expLines);
        }

        // Limit length
        if (strlen($experience) > 2000)
        {
            $experience = substr($experience, 0, 2000) . '...';
        }

        return trim($experience);
    }

    /**
     * Calculate years of experience from date ranges in resume.
     *
     * @param string $text Resume text
     * @return float Estimated years of experience
     */
    public function estimateYearsOfExperience($text = null)
    {
        if ($text === null) $text = $this->_text;

        $totalMonths = 0;
        $datePattern = '/(?:(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[a-z]*\.?\s+)?(\d{4})\s*[-–to]+\s*(?:(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[a-z]*\.?\s+)?(\d{4}|Present|Current)/i';

        $months = array(
            'jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4,
            'may' => 5, 'jun' => 6, 'jul' => 7, 'aug' => 8,
            'sep' => 9, 'oct' => 10, 'nov' => 11, 'dec' => 12
        );

        if (preg_match_all($datePattern, $text, $matches, PREG_SET_ORDER))
        {
            foreach ($matches as $match)
            {
                $startMonth = !empty($match[1]) ? ($months[strtolower(substr($match[1], 0, 3))] ?? 1) : 1;
                $startYear = intval($match[2]);

                if (strtolower($match[4]) === 'present' || strtolower($match[4]) === 'current')
                {
                    $endYear = intval(date('Y'));
                    $endMonth = intval(date('m'));
                }
                else
                {
                    $endMonth = !empty($match[3]) ? ($months[strtolower(substr($match[3], 0, 3))] ?? 12) : 12;
                    $endYear = intval($match[4]);
                }

                if ($startYear >= 1970 && $startYear <= 2030 && $endYear >= $startYear)
                {
                    $duration = ($endYear - $startYear) * 12 + ($endMonth - $startMonth);
                    if ($duration > 0 && $duration < 600) // sanity: < 50 years
                    {
                        $totalMonths += $duration;
                    }
                }
            }
        }

        return round($totalMonths / 12, 1);
    }

    /**
     * Extract a confidence score for each parsed field.
     *
     * @param array $parsedResult The result from parse()
     * @return array Field => confidence (0.0 to 1.0)
     */
    public function getConfidenceScores($parsedResult)
    {
        $scores = array();

        // Email - very reliable if found
        $scores['email'] = !empty($parsedResult['email_address']) ? 0.95 : 0.0;

        // Phone - reliable if found
        $scores['phone'] = !empty($parsedResult['phone_number']) ? 0.90 : 0.0;

        // Name - moderate confidence (first line heuristic)
        if (!empty($parsedResult['first_name']) && !empty($parsedResult['last_name']))
        {
            $scores['name'] = 0.75;
        }
        else if (!empty($parsedResult['first_name']))
        {
            $scores['name'] = 0.40;
        }
        else
        {
            $scores['name'] = 0.0;
        }

        // Location
        $locScore = 0.0;
        if (!empty($parsedResult['city'])) $locScore += 0.3;
        if (!empty($parsedResult['state'])) $locScore += 0.3;
        if (!empty($parsedResult['zip_code'])) $locScore += 0.2;
        if (!empty($parsedResult['us_address'])) $locScore += 0.2;
        $scores['location'] = $locScore;

        // Skills
        if (!empty($parsedResult['skills']))
        {
            $skillCount = count(explode(',', $parsedResult['skills']));
            $scores['skills'] = min(1.0, $skillCount * 0.1);
        }
        else
        {
            $scores['skills'] = 0.0;
        }

        // Education
        $scores['education'] = !empty($parsedResult['education']) ? 0.70 : 0.0;

        // Experience
        $scores['experience'] = !empty($parsedResult['experience']) ? 0.65 : 0.0;

        // Overall
        $scores['overall'] = array_sum($scores) / count($scores);

        return $scores;
    }
}

?>
