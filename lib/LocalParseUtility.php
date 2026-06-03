<?php
/**
 * Neutara ATS
 * Professional Resume Parsing Engine
 *
 * Advanced local parsing engine that extracts structured data from resume text.
 * Designed to match professional ATS systems like Cephal.
 *
 * Features:
 * - Multi-strategy name extraction (handles Indian names, ALL CAPS, etc.)
 * - Comprehensive email extraction (multiple emails, obfuscated formats)
 * - Advanced phone parsing (US, Indian, international formats)
 * - Indian and US location/address parsing
 * - LinkedIn/GitHub/Portfolio URL extraction
 * - Current employer detection
 * - Skills extraction with 200+ technology keywords
 * - Education and experience section parsing
 * - Years of experience calculation
 */

class LocalParseUtility
{
    private $_text;
    private $_lines;
    private $_cleanText;

    /* Common skill keywords for matching - expanded list */
    private static $_skillPatterns = array(
        // Programming Languages
        'PHP', 'Java', 'Python', 'JavaScript', 'TypeScript', 'C\+\+', 'C#', 'Ruby', 'Go',
        'Swift', 'Kotlin', 'Rust', 'Scala', 'Perl', 'R', 'MATLAB', 'Objective-C',
        'Visual Basic', 'VB\.NET', 'Dart', 'Lua', 'Haskell', 'Elixir', 'Clojure',
        'Groovy', 'Shell', 'Bash', 'PowerShell', 'Assembly', 'COBOL', 'Fortran',
        // Web Frontend
        'HTML', 'HTML5', 'CSS', 'CSS3', 'SASS', 'SCSS', 'LESS', 'React', 'React\.js', 'ReactJS',
        'Angular', 'AngularJS', 'Vue', 'Vue\.js', 'VueJS', 'Svelte', 'Node', 'Node\.js', 'NodeJS',
        'Express', 'Express\.js', 'Next', 'Next\.js', 'NextJS', 'Nuxt', 'Nuxt\.js',
        'jQuery', 'Bootstrap', 'Tailwind', 'Material UI', 'Chakra UI', 'Ant Design',
        'Redux', 'MobX', 'Zustand', 'Webpack', 'Vite', 'Babel', 'ESLint', 'Prettier',
        // Web Backend
        'WordPress', 'Django', 'Flask', 'FastAPI', 'Laravel', 'Spring', 'Spring Boot',
        'ASP\.NET', 'ASP\.NET Core', '\.NET', '\.NET Core', 'Ruby on Rails', 'Rails',
        'Symfony', 'CodeIgniter', 'CakePHP', 'Yii', 'Zend', 'Express', 'Koa', 'NestJS',
        'GraphQL', 'REST', 'REST API', 'RESTful', 'SOAP', 'gRPC', 'WebSocket',
        // Databases
        'MySQL', 'PostgreSQL', 'Postgres', 'MongoDB', 'Redis', 'Oracle', 'SQL Server',
        'MSSQL', 'SQLite', 'Cassandra', 'DynamoDB', 'Elasticsearch', 'MariaDB',
        'Firebase', 'Firestore', 'CouchDB', 'Neo4j', 'InfluxDB', 'TimescaleDB',
        'SQL', 'NoSQL', 'PL/SQL', 'T-SQL', 'Database', 'RDBMS',
        // Cloud & DevOps
        'AWS', 'Amazon Web Services', 'Azure', 'Microsoft Azure', 'GCP', 'Google Cloud',
        'Google Cloud Platform', 'Docker', 'Kubernetes', 'K8s', 'Jenkins', 'CI/CD',
        'Terraform', 'Ansible', 'Chef', 'Puppet', 'Nginx', 'Apache', 'Tomcat',
        'Linux', 'Unix', 'Ubuntu', 'CentOS', 'RedHat', 'RHEL', 'Windows Server',
        'Git', 'GitHub', 'GitLab', 'Bitbucket', 'SVN', 'Subversion',
        'CircleCI', 'Travis CI', 'GitHub Actions', 'Azure DevOps', 'Bamboo',
        'Heroku', 'Netlify', 'Vercel', 'DigitalOcean', 'Linode', 'Cloudflare',
        'Lambda', 'EC2', 'S3', 'RDS', 'CloudFront', 'ECS', 'EKS', 'Fargate',
        // Data & AI/ML
        'Machine Learning', 'ML', 'Deep Learning', 'DL', 'Artificial Intelligence', 'AI',
        'TensorFlow', 'PyTorch', 'Keras', 'Scikit-learn', 'sklearn',
        'Pandas', 'NumPy', 'SciPy', 'Matplotlib', 'Seaborn', 'Plotly',
        'NLP', 'Natural Language Processing', 'Computer Vision', 'CV',
        'Data Science', 'Data Analysis', 'Data Analytics', 'Data Engineering',
        'Big Data', 'Hadoop', 'Spark', 'Apache Spark', 'Kafka', 'Apache Kafka',
        'Tableau', 'Power BI', 'Looker', 'Metabase', 'Superset',
        'ETL', 'Data Pipeline', 'Data Warehouse', 'Snowflake', 'Redshift', 'BigQuery',
        'OpenAI', 'GPT', 'LLM', 'Transformers', 'BERT', 'Hugging Face',
        // Mobile Development
        'Android', 'iOS', 'React Native', 'Flutter', 'Xamarin', 'Ionic',
        'Swift', 'SwiftUI', 'Objective-C', 'Kotlin', 'Java Android',
        'Mobile Development', 'Cross-platform', 'Cordova', 'PhoneGap',
        // Testing
        'Unit Testing', 'Integration Testing', 'E2E Testing', 'End-to-End',
        'Selenium', 'Cypress', 'Jest', 'Mocha', 'Chai', 'Jasmine', 'Karma',
        'PyTest', 'JUnit', 'TestNG', 'Mockito', 'PHPUnit', 'RSpec',
        'Postman', 'SoapUI', 'JMeter', 'LoadRunner', 'Gatling',
        'TDD', 'BDD', 'Test Driven', 'Behavior Driven',
        // Tools & Methodologies
        'Agile', 'Scrum', 'Kanban', 'Waterfall', 'SAFe', 'Lean',
        'JIRA', 'Confluence', 'Trello', 'Asana', 'Monday', 'Notion',
        'Microservices', 'SOA', 'Service Oriented', 'Event Driven',
        'Design Patterns', 'SOLID', 'DRY', 'KISS', 'Clean Code', 'Clean Architecture',
        'OOP', 'Object Oriented', 'Functional Programming', 'FP',
        'API', 'API Design', 'API Development', 'Swagger', 'OpenAPI',
        // Security
        'Cybersecurity', 'Security', 'OWASP', 'Penetration Testing', 'Pen Testing',
        'Encryption', 'SSL', 'TLS', 'OAuth', 'OAuth2', 'JWT', 'SAML', 'SSO',
        'Firewall', 'VPN', 'IAM', 'RBAC', 'Security Audit',
        // Business & Soft Skills
        'Project Management', 'Product Management', 'Business Analysis',
        'SAP', 'Salesforce', 'CRM', 'ERP', 'HubSpot', 'Zoho',
        'Excel', 'PowerPoint', 'Word', 'Google Sheets', 'Google Docs',
        'Communication', 'Leadership', 'Problem Solving', 'Team Management',
        'Teamwork', 'Collaboration', 'Time Management', 'Critical Thinking',
        'Six Sigma', 'PMP', 'ITIL', 'Prince2', 'PMI', 'Certified',
        'Accounting', 'Finance', 'Marketing', 'Sales', 'Customer Service',
        'SEO', 'SEM', 'Google Analytics', 'Google Ads', 'Facebook Ads',
        'Content Marketing', 'Social Media', 'Digital Marketing',
        // Design
        'Figma', 'Sketch', 'Adobe XD', 'InVision', 'Zeplin',
        'Adobe', 'Photoshop', 'Illustrator', 'InDesign', 'After Effects', 'Premiere',
        'UI', 'UX', 'UI/UX', 'User Interface', 'User Experience',
        'Wireframing', 'Prototyping', 'Responsive Design', 'Mobile First',
        'AutoCAD', 'SolidWorks', 'CATIA', 'Revit', '3D Modeling',
        // Specific Technologies
        'SAP HANA', 'SAP ABAP', 'SAP Fiori', 'ServiceNow', 'Workday',
        'Splunk', 'Datadog', 'New Relic', 'Grafana', 'Prometheus',
        'RabbitMQ', 'ActiveMQ', 'ZeroMQ', 'MQTT', 'Message Queue',
        'Blockchain', 'Ethereum', 'Solidity', 'Web3', 'Smart Contracts',
        'AR', 'VR', 'Augmented Reality', 'Virtual Reality', 'Unity', 'Unreal Engine'
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

    /* Indian states and union territories */
    private static $_indianStates = array(
        'Andhra Pradesh', 'AP', 'Arunachal Pradesh', 'Assam', 'Bihar', 'Chhattisgarh', 'CG',
        'Goa', 'Gujarat', 'GJ', 'Haryana', 'HR', 'Himachal Pradesh', 'HP',
        'Jharkhand', 'JH', 'Karnataka', 'KA', 'Kerala', 'KL',
        'Madhya Pradesh', 'MP', 'Maharashtra', 'MH', 'Manipur', 'Meghalaya',
        'Mizoram', 'Nagaland', 'Odisha', 'OR', 'Punjab', 'PB',
        'Rajasthan', 'RJ', 'Sikkim', 'Tamil Nadu', 'TN', 'Telangana', 'TS',
        'Tripura', 'Uttar Pradesh', 'UP', 'Uttarakhand', 'UK', 'West Bengal', 'WB',
        'Delhi', 'New Delhi', 'NCR', 'Chandigarh', 'Puducherry', 'Pondicherry',
        'Jammu and Kashmir', 'J&K', 'Ladakh', 'Andaman and Nicobar', 'Lakshadweep',
        'Dadra and Nagar Haveli', 'Daman and Diu'
    );

    /* Major Indian cities */
    private static $_indianCities = array(
        'Mumbai', 'Bombay', 'Delhi', 'New Delhi', 'Bangalore', 'Bengaluru',
        'Hyderabad', 'Chennai', 'Madras', 'Kolkata', 'Calcutta',
        'Pune', 'Ahmedabad', 'Jaipur', 'Lucknow', 'Kanpur',
        'Nagpur', 'Indore', 'Thane', 'Bhopal', 'Visakhapatnam', 'Vizag',
        'Patna', 'Vadodara', 'Baroda', 'Ghaziabad', 'Ludhiana',
        'Agra', 'Nashik', 'Faridabad', 'Meerut', 'Rajkot',
        'Varanasi', 'Srinagar', 'Aurangabad', 'Dhanbad', 'Amritsar',
        'Navi Mumbai', 'Allahabad', 'Prayagraj', 'Ranchi', 'Howrah',
        'Coimbatore', 'Jabalpur', 'Gwalior', 'Vijayawada', 'Jodhpur',
        'Madurai', 'Raipur', 'Kota', 'Chandigarh', 'Guwahati',
        'Solapur', 'Hubli', 'Dharwad', 'Mysore', 'Mysuru', 'Tiruchirappalli',
        'Trichy', 'Bareilly', 'Moradabad', 'Tiruppur', 'Jalandhar',
        'Aligarh', 'Bhubaneswar', 'Salem', 'Warangal', 'Guntur',
        'Bhiwandi', 'Saharanpur', 'Gorakhpur', 'Bikaner', 'Amravati',
        'Noida', 'Greater Noida', 'Gurgaon', 'Gurugram', 'Secunderabad',
        'Kochi', 'Cochin', 'Thiruvananthapuram', 'Trivandrum', 'Mangalore', 'Mangaluru'
    );

    /* Education degree patterns */
    private static $_degreePatterns = array(
        'Ph\.?D\.?', 'Doctorate', 'Doctor of Philosophy',
        'M\.?B\.?A\.?', 'Master of Business Administration',
        'Master(?:\'?s)?(?:\s+(?:of|in)\s+\w+(?:\s+\w+)?)?',
        'M\.?S\.?', 'M\.?A\.?', 'M\.?Tech\.?', 'M\.?E\.?', 'M\.?C\.?A\.?',
        'M\.?Sc\.?', 'M\.?Com\.?', 'M\.?Phil\.?', 'M\.?Ed\.?',
        'B\.?Tech\.?', 'B\.?E\.?', 'B\.?S\.?', 'B\.?A\.?', 'B\.?C\.?A\.?',
        'B\.?Sc\.?', 'B\.?Com\.?', 'B\.?B\.?A\.?', 'B\.?Ed\.?', 'B\.?Arch\.?',
        'Bachelor(?:\'?s)?(?:\s+(?:of|in)\s+\w+(?:\s+\w+)?)?',
        'Associate(?:\'?s)?(?:\s+(?:of|in)\s+\w+)?',
        'Diploma', 'Post Graduate Diploma', 'PG Diploma', 'PGDM', 'PGDBA',
        'Certificate', 'Certification', 'Professional Certificate',
        'High School', 'Higher Secondary', 'Secondary School',
        'GED', 'HSC', 'SSC', 'SSLC', 'CBSE', 'ICSE', 'ISC',
        '10th', '12th', 'Class X', 'Class XII', 'Intermediate',
        'Computer Science', 'Information Technology', 'IT',
        'Electronics', 'Electrical', 'Mechanical', 'Civil',
        'Engineering', 'Science', 'Commerce', 'Arts',
        'Business Administration', 'Management', 'Finance', 'Marketing'
    );

    /* Section header patterns */
    private static $_sectionHeaders = array(
        'education'  => '/^(?:EDUCATION|ACADEMIC|QUALIFICATION|DEGREE|SCHOLASTIC|EDUCATIONAL\s*BACKGROUND)/i',
        'experience' => '/^(?:EXPERIENCE|EMPLOYMENT|WORK\s*HISTORY|PROFESSIONAL\s*EXPERIENCE|CAREER|JOB\s*HISTORY|WORK\s*EXPERIENCE)/i',
        'skills'     => '/^(?:SKILLS|TECHNICAL\s*SKILLS|KEY\s*SKILLS|CORE\s*COMPETENC|PROFICIENC|EXPERTISE|TECHNOLOGIES|TECHNICAL\s*EXPERTISE|SKILL\s*SET)/i',
        'summary'    => '/^(?:SUMMARY|OBJECTIVE|PROFILE|ABOUT\s*ME|PROFESSIONAL\s*SUMMARY|CAREER\s*OBJECTIVE|EXECUTIVE\s*SUMMARY)/i',
        'projects'   => '/^(?:PROJECTS|KEY\s*PROJECTS|NOTABLE\s*PROJECTS|ACADEMIC\s*PROJECTS|PERSONAL\s*PROJECTS)/i',
        'certifications' => '/^(?:CERTIFICATIONS?|LICENSES?|CREDENTIALS|PROFESSIONAL\s*CERTIFICATIONS?|AWARDS?)/i',
        'contact'    => '/^(?:CONTACT|PERSONAL\s*(?:INFO|DETAILS)|ADDRESS|CONTACT\s*(?:INFO|DETAILS|INFORMATION))/i',
        'languages'  => '/^(?:LANGUAGES?|LANGUAGE\s*PROFICIENCY)/i',
        'interests'  => '/^(?:INTERESTS?|HOBBIES|EXTRA\s*CURRICULAR)/i',
        'references' => '/^(?:REFERENCES?|REFEREES?)/i'
    );

    /* Common Indian name patterns */
    private static $_commonIndianFirstNames = array(
        'Aarav', 'Aditya', 'Akash', 'Amit', 'Anand', 'Anil', 'Anjali', 'Ankit', 'Ankita',
        'Arjun', 'Arun', 'Ashish', 'Ashok', 'Ashoka', 'Deepak', 'Deepika', 'Gaurav',
        'Harish', 'Harsh', 'Jyoti', 'Karan', 'Kavita', 'Krishna', 'Kumar', 'Lakshmi',
        'Mahesh', 'Manish', 'Meera', 'Mohan', 'Mukesh', 'Naveen', 'Navya', 'Neha',
        'Nikhil', 'Nisha', 'Pooja', 'Pradeep', 'Prakash', 'Pranav', 'Priya', 'Priyanka',
        'Rahul', 'Raj', 'Rajesh', 'Rakesh', 'Ramesh', 'Ravi', 'Rohit', 'Sachin',
        'Sandeep', 'Sanjay', 'Santosh', 'Satish', 'Shivani', 'Shyam', 'Sneha', 'Srinivas',
        'Sunil', 'Sunita', 'Suresh', 'Swati', 'Tanvi', 'Varun', 'Vijay', 'Vikram',
        'Vinay', 'Vinod', 'Vishal', 'Vivek', 'Yogesh', 'Harish', 'Jyoshitha', 'Voggu'
    );

    /* Words to skip when identifying names */
    private static $_skipWords = array(
        'resume', 'cv', 'curriculum', 'vitae', 'profile', 'summary',
        'objective', 'experience', 'education', 'skills', 'contact', 'address',
        'phone', 'email', 'mobile', 'linkedin', 'github', 'portfolio', 'website',
        'bachelor', 'master', 'university', 'college', 'school', 'company', 'institute',
        'manager', 'developer', 'engineer', 'designer', 'analyst', 'senior', 'staff',
        'junior', 'lead', 'head', 'director', 'intern', 'trainee', 'associate',
        'about', 'professional', 'career', 'work', 'history', 'personal', 'details',
        'technical', 'core', 'competencies', 'qualification', 'academic',
        'software', 'hardware', 'system', 'application', 'project', 'team',
        'january', 'february', 'march', 'april', 'may', 'june', 'july',
        'august', 'september', 'october', 'november', 'december',
        'present', 'current', 'till', 'date', 'from', 'to'
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
        $this->_cleanText = $this->_cleanTextForParsing($text);

        $result = array(
            'first_name'       => '',
            'last_name'        => '',
            'job_title'        => '',
            'us_address'       => '',
            'city'             => '',
            'state'            => '',
            'zip_code'         => '',
            'email_address'    => '',
            'phone_number'     => '',
            'skills'           => '',
            'education'        => '',
            'experience'       => '',
            'current_employer' => '',
            'linkedin'         => '',
            'github'           => '',
            'website'          => '',
            'years_experience' => 0
        );

        // Extract in order of reliability
        $result['email_address'] = $this->_extractEmail();
        $result['phone_number']  = $this->_extractPhone();
        $result['job_title']     = $this->_extractJobTitle();

        // Extract social/professional links
        $links = $this->_extractLinks();
        $result['linkedin'] = $links['linkedin'];
        $result['github']   = $links['github'];
        $result['website']  = $links['website'];

        // Extract name using multiple strategies
        $nameResult = $this->_extractName();
        $result['first_name'] = $nameResult['first'];
        $result['last_name']  = $nameResult['last'];

        // Extract location
        $locationResult = $this->_extractLocation();
        $result['us_address'] = $locationResult['address'];
        $result['city']       = $locationResult['city'];
        $result['state']      = $locationResult['state'];
        $result['zip_code']   = $locationResult['zip'];

        // Extract sections
        $sections = $this->_extractSections();

        // Extract structured data from sections
        $result['skills']           = $this->_extractSkills($sections);
        $result['education']        = $this->_extractEducation($sections);
        $result['experience']       = $this->_extractExperience($sections);
        $employerResult             = $this->_extractCurrentEmployerAndTitle($sections);
        $result['current_employer'] = $employerResult['employer'];
        if (empty($result['job_title'])) {
            $result['job_title'] = $employerResult['title'];
        }
        $result['years_experience'] = $this->estimateYearsOfExperience();

        return $result;
    }

    /**
     * Clean text for better parsing - remove garbage characters from PDF extraction
     */
    private function _cleanTextForParsing($text)
    {
        // Remove null bytes and other control characters
        $text = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F]/', '', $text);
        
        // Normalize whitespace
        $text = preg_replace('/[ \t]+/', ' ', $text);
        
        // Remove repeated special characters (often from PDF extraction)
        $text = preg_replace('/([^\w\s])\1{3,}/', '$1', $text);
        // Remove backslashes that can break regex patterns
        $text = str_replace('\\', ' ', $text);
        
        return $text;
    }

    /**
     * Extract email address from resume text.
     * Handles multiple formats including obfuscated emails.
     */
    private function _extractEmail()
    {
        // Standard email pattern
        $patterns = array(
            // Standard email
            '/[a-zA-Z0-9._%+\-]+@[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}/',
            // Email with spaces around @
            '/[a-zA-Z0-9._%+\-]+\s*@\s*[a-zA-Z0-9.\-]+\.[a-zA-Z]{2,}/',
            // Obfuscated: "email at domain dot com"
            '/([a-zA-Z0-9._%+\-]+)\s+(?:at|AT)\s+([a-zA-Z0-9.\-]+)\s+(?:dot|DOT)\s+([a-zA-Z]{2,})/',
            // Email with [at] and [dot]
            '/([a-zA-Z0-9._%+\-]+)\s*\[at\]\s*([a-zA-Z0-9.\-]+)\s*\[dot\]\s*([a-zA-Z]{2,})/i'
        );

        foreach ($patterns as $i => $pattern)
        {
            if (preg_match($pattern, $this->_text, $matches))
            {
                if ($i >= 2 && count($matches) >= 4) {
                    // Reconstruct obfuscated email
                    return strtolower(trim($matches[1] . '@' . $matches[2] . '.' . $matches[3]));
                }
                $email = strtolower(trim($matches[0]));
                // Remove any spaces
                $email = preg_replace('/\s+/', '', $email);
                
                // Validate it looks like a real email
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    return $email;
                }
            }
        }

        return '';
    }

    /**
     * Extract phone number from resume text.
     * Handles US, Indian, and international formats.
     */
    private function _extractPhone()
    {
        $patterns = array(
            // Indian mobile: +91 XXXXX XXXXX or +91-XXXXX-XXXXX
            '/\+91[\s\-]?\d{5}[\s\-]?\d{5}/',
            // Indian mobile without country code: 10 digits starting with 6-9
            '/\b[6-9]\d{9}\b/',
            // Indian with country code variations
            '/(?:0091|\+91|91)[\s\-\.]?\d{10}/',
            // US format: +1 (XXX) XXX-XXXX
            '/\+?1?[\s\-\.]?\(?\d{3}\)?[\s\-\.]?\d{3}[\s\-\.]?\d{4}/',
            // International: +XX XXXX XXXXXX
            '/\+\d{1,3}[\s\-]?\d{4,5}[\s\-]?\d{4,6}/',
            // Generic 10-digit
            '/\b\d{3}[\s\-\.]?\d{3}[\s\-\.]?\d{4}\b/',
            // With Phone/Mobile/Tel label
            '/(?:Phone|Mobile|Tel|Cell|Contact)[\s:]*([+\d\s\-\.\(\)]{10,20})/i'
        );

        foreach ($patterns as $i => $pattern)
        {
            if (preg_match($pattern, $this->_text, $matches))
            {
                $phone = isset($matches[1]) ? $matches[1] : $matches[0];
                $phone = trim($phone);

                // Validate digit count (10-15 digits)
                $digitCount = strlen(preg_replace('/[^\d]/', '', $phone));
                if ($digitCount < 10 || $digitCount > 15) {
                    continue;
                }

                // Normalize: keep digits, +, spaces, hyphens, parentheses; collapse extra spaces
                $phone = preg_replace('/[^\d\+\(\)\s\-]/', '', $phone);
                $phone = preg_replace('/\s+/', ' ', trim($phone));
                return $phone;
            }
        }

        return '';
    }

    /**
     * Extract LinkedIn, GitHub, and other professional links.
     */
    private function _extractLinks()
    {
        $result = array('linkedin' => '', 'github' => '', 'website' => '');

        // LinkedIn
        if (preg_match('/(?:https?:\/\/)?(?:www\.)?linkedin\.com\/in\/([a-zA-Z0-9\-_]+)/i', $this->_text, $matches)) {
            $result['linkedin'] = 'https://linkedin.com/in/' . $matches[1];
        } elseif (preg_match('/linkedin[\s:]*([a-zA-Z0-9\-_\/]+)/i', $this->_text, $matches)) {
            $username = trim($matches[1], '/');
            if (!empty($username) && strpos($username, '.com') === false) {
                $result['linkedin'] = 'https://linkedin.com/in/' . $username;
            }
        }

        // GitHub
        if (preg_match('/(?:https?:\/\/)?(?:www\.)?github\.com\/([a-zA-Z0-9\-_]+)/i', $this->_text, $matches)) {
            $result['github'] = 'https://github.com/' . $matches[1];
        } elseif (preg_match('/github[\s:]*([a-zA-Z0-9\-_]+)/i', $this->_text, $matches)) {
            $username = trim($matches[1]);
            if (!empty($username) && strpos($username, '.com') === false) {
                $result['github'] = 'https://github.com/' . $username;
            }
        }

        // Portfolio/Website (excluding LinkedIn and GitHub)
        if (preg_match('/(?:portfolio|website|web|site)[\s:]*(?:https?:\/\/)?([a-zA-Z0-9\-_.]+\.[a-zA-Z]{2,}[^\s]*)/i', $this->_text, $matches)) {
            $url = $matches[1];
            if (strpos($url, 'linkedin') === false && strpos($url, 'github') === false) {
                $result['website'] = (strpos($url, 'http') === 0) ? $url : 'https://' . $url;
            }
        }

        return $result;
    }

    /**
     * Extract candidate name using multiple strategies.
     */
    private function _extractName()
    {
        $result = array('first' => '', 'last' => '');

        // Strategy 1: Look for name in first 5 non-empty lines
        $nameFromLines = $this->_extractNameFromFirstLines();
        if (!empty($nameFromLines['first'])) {
            return $nameFromLines;
        }

        // Strategy 2: Look for ALL CAPS name pattern
        $nameFromCaps = $this->_extractNameFromCaps();
        if (!empty($nameFromCaps['first'])) {
            return $nameFromCaps;
        }

        // Strategy 3: Look for name near email
        $nameNearEmail = $this->_extractNameNearEmail();
        if (!empty($nameNearEmail['first'])) {
            return $nameNearEmail;
        }

        // Strategy 4: Look for common Indian first names
        $nameFromIndian = $this->_extractIndianName();
        if (!empty($nameFromIndian['first'])) {
            return $nameFromIndian;
        }

        // Strategy 5: Extract from "Name:" label
        $nameFromLabel = $this->_extractNameFromLabel();
        if (!empty($nameFromLabel['first'])) {
            return $nameFromLabel;
        }

        return $result;
    }

    /**
     * Extract name from first few lines of resume.
     */
    private function _extractNameFromFirstLines()
    {
        $result = array('first' => '', 'last' => '');
        $lineCount = 0;

        foreach ($this->_lines as $line)
        {
            $line = trim($line);
            if (empty($line)) continue;

            $lineCount++;
            if ($lineCount > 10) break;

            // Skip lines that look like contact info, headers, or addresses
            if (preg_match('/@|http|www\.|phone|tel:|mobile:|linkedin|github|\d{5,}|resume|curriculum|objective|summary|profile|address|fax/i', $line)) {
                continue;
            }

            // Skip section headers
            $isHeader = false;
            foreach (self::$_sectionHeaders as $pattern) {
                if (preg_match($pattern, $line)) {
                    $isHeader = true;
                    break;
                }
            }
            if ($isHeader) continue;

            // For longer lines, try to extract just a name portion at the start
            if (strlen($line) > 60) {
                // If line starts with what looks like a name before a separator
                if (preg_match('/^([A-Z][a-z]+(?:\s+[A-Z][a-z]+){1,3})\s*[\|\-–,]/', $line, $m)) {
                    $candidate = $m[1];
                    $words = explode(' ', $candidate);
                    if (count($words) >= 2 && count($words) <= 4) {
                        $result['first'] = $this->_formatName($words[0]);
                        $result['last']  = $this->_formatName($words[count($words) - 1]);
                        return $result;
                    }
                }
                continue;
            }

            // Clean the line — allow apostrophe and hyphen in names
            $cleanLine = preg_replace('/[^A-Za-z\s\.\-\']/', ' ', $line);
            $cleanLine = preg_replace('/\s+/', ' ', trim($cleanLine));

            // Split into words
            $words = preg_split('/\s+/', $cleanLine);
            $words = array_filter($words, function($w) {
                return preg_match('/^[A-Za-z\.\-\']{2,}$/u', $w);
            });
            $words = array_values($words);

            // Filter out skip words
            $validWords = array();
            foreach ($words as $word) {
                if (!in_array(strtolower($word), self::$_skipWords)) {
                    $validWords[] = $word;
                }
            }

            // Check for valid name pattern (2-4 words, first word starts with capital)
            if (count($validWords) >= 2 && count($validWords) <= 4) {
                if (preg_match('/^[A-Z]/', $validWords[0])) {
                    // All words should start with a capital (Title Case) or be ALL CAPS
                    $allCapitalized = true;
                    foreach ($validWords as $w) {
                        if (!preg_match('/^[A-Z]/', $w)) {
                            $allCapitalized = false;
                            break;
                        }
                    }
                    if ($allCapitalized) {
                        $result['first'] = $this->_formatName($validWords[0]);
                        $result['last']  = $this->_formatName($validWords[count($validWords) - 1]);
                        return $result;
                    }
                }
            }

            // Single capitalized word in first 3 lines might be first name
            if (count($validWords) == 1 && $lineCount <= 3) {
                if (preg_match('/^[A-Z][a-z]{1,}$/', $validWords[0])) {
                    $result['first'] = $this->_formatName($validWords[0]);
                    return $result;
                }
            }
        }

        return $result;
    }

    /**
     * Extract name from ALL CAPS pattern — search in first 20 lines only.
     */
    private function _extractNameFromCaps()
    {
        $result = array('first' => '', 'last' => '');

        // Search only in the first 20 lines to avoid matching section headers deep in document
        $searchLines = array_slice($this->_lines, 0, 20);
        $searchText  = implode("\n", $searchLines);

        // Match 2-4 ALL CAPS words (2+ chars each), not preceded/followed by lowercase
        if (preg_match('/(?:^|\n)\s*([A-Z]{2,})(?:\s+([A-Z]{2,})){1,2}(?:\s|$)/m', $searchText, $matches)) {
            $parts = preg_split('/\s+/', trim($matches[0]));
            $parts = array_filter($parts, function($p) {
                return preg_match('/^[A-Z]{2,}$/', $p);
            });
            $parts = array_values($parts);

            if (count($parts) >= 2) {
                $first = $parts[0];
                $last  = $parts[count($parts) - 1];

                // Validate not skip words or common section headers
                $headerWords = array('RESUME', 'CV', 'CURRICULUM', 'VITAE', 'PROFILE', 'SUMMARY',
                                     'EXPERIENCE', 'EDUCATION', 'SKILLS', 'OBJECTIVE', 'CONTACT',
                                     'REFERENCES', 'PROJECTS', 'CERTIFICATIONS', 'AWARDS');
                if (!in_array($first, $headerWords) && !in_array($last, $headerWords) &&
                    !in_array(strtolower($first), self::$_skipWords) &&
                    !in_array(strtolower($last),  self::$_skipWords)) {
                    $result['first'] = $this->_formatName($first);
                    $result['last']  = $this->_formatName($last);
                    return $result;
                }
            }
        }

        return $result;
    }

    /**
     * Extract name from near email address.
     */
    private function _extractNameNearEmail()
    {
        $result = array('first' => '', 'last' => '');
        $email = $this->_extractEmail();

        if (empty($email)) return $result;

        $emailPos = stripos($this->_text, $email);
        if ($emailPos === false) return $result;

        // Look at text before email (within 300 chars)
        $beforeEmail = substr($this->_text, max(0, $emailPos - 300), min($emailPos, 300));

        // Find capitalized name pattern before email
        if (preg_match('/([A-Z][a-z]+)\s+([A-Z][a-z]+)(?:\s+([A-Z][a-z]+))?\s*$/', trim($beforeEmail), $matches)) {
            $first = $matches[1];
            $last = isset($matches[3]) ? $matches[3] : $matches[2];

            if (!in_array(strtolower($first), self::$_skipWords) &&
                !in_array(strtolower($last), self::$_skipWords)) {
                $result['first'] = $first;
                $result['last'] = $last;
                return $result;
            }
        }

        // Try to extract from email username
        $emailParts = explode('@', $email);
        $username = $emailParts[0];

        // Common patterns: firstname.lastname, firstnamelastname, firstname_lastname
        if (preg_match('/^([a-z]+)[._]([a-z]+)$/i', $username, $matches)) {
            $result['first'] = $this->_formatName($matches[1]);
            $result['last'] = $this->_formatName($matches[2]);
            return $result;
        }

        return $result;
    }

    /**
     * Extract Indian name patterns.
     */
    private function _extractIndianName()
    {
        $result = array('first' => '', 'last' => '');

        // Look for common Indian first names followed by another word
        $firstNamePattern = implode('|', self::$_commonIndianFirstNames);

        if (preg_match('/\b(' . $firstNamePattern . ')\s+([A-Z][a-z]+)/i', $this->_text, $matches)) {
            $result['first'] = $this->_formatName($matches[1]);
            $result['last'] = $this->_formatName($matches[2]);
            return $result;
        }

        return $result;
    }

    /**
     * Extract name from "Name:" label — handles mixed case, multiple words.
     */
    private function _extractNameFromLabel()
    {
        $result = array('first' => '', 'last' => '');

        // Match "Name:", "Full Name:", "Candidate Name:", "Applicant Name:" etc.
        // Captures 2-4 words that follow (Title Case or ALL CAPS)
        if (preg_match(
            '/(?:Full\s*Name|Candidate\s*Name|Applicant\s*Name|Name)\s*[:]\s*([A-Za-z][A-Za-z\'\-\.]+(?:\s+[A-Za-z][A-Za-z\'\-\.]+){1,3})/i',
            $this->_text,
            $matches
        )) {
            $fullName = trim($matches[1]);
            $words = preg_split('/\s+/', $fullName);
            $words = array_filter($words, function($w) { return strlen($w) >= 2; });
            $words = array_values($words);
            if (count($words) >= 2) {
                $result['first'] = $this->_formatName($words[0]);
                $result['last']  = $this->_formatName($words[count($words) - 1]);
            } elseif (count($words) === 1) {
                $result['first'] = $this->_formatName($words[0]);
            }
        }

        return $result;
    }

    /**
     * Format name properly (capitalize first letter, lowercase rest).
     */
    private function _formatName($name)
    {
        return ucfirst(strtolower(trim($name)));
    }

    /**
     * Extract location (address, city, state, zip).
     * Handles both US and Indian formats.
     */
    private function _extractLocation()
    {
        $result = array('address' => '', 'city' => '', 'state' => '', 'zip' => '');

        // Try Indian location first (more specific patterns)
        $indianResult = $this->_extractIndianLocation();
        if (!empty($indianResult['city']) || !empty($indianResult['state'])) {
            return $indianResult;
        }

        // US-style: City, ST ZIP
        if (preg_match('/([A-Za-z\s]+),\s*([A-Z]{2})\s+(\d{5}(?:-\d{4})?)/', $this->_text, $matches)) {
            $result['city']  = trim($matches[1]);
            $result['state'] = $matches[2];
            $result['zip']   = $matches[3];
            return $result;
        }

        // City, State (full name)
        $statePattern = implode('|', array_merge(
            array_keys(self::$_usStates),
            array_values(self::$_usStates)
        ));

        if (preg_match('/([A-Za-z\s]+),\s*(' . $statePattern . ')\b/i', $this->_text, $matches)) {
            $result['city']  = trim($matches[1]);
            $result['state'] = trim($matches[2]);
        }

        // Street address
        if (preg_match('/\d+\s+[A-Za-z]+\s+(?:Street|St|Avenue|Ave|Boulevard|Blvd|Drive|Dr|Road|Rd|Lane|Ln|Way|Court|Ct|Circle|Place|Pl|Terrace)\b\.?/i', $this->_text, $matches)) {
            $result['address'] = trim($matches[0]);
        }

        // ZIP code
        if (empty($result['zip']) && preg_match('/\b(\d{5}(?:-\d{4})?)\b/', $this->_text, $matches)) {
            $pos = strpos($this->_text, $matches[0]);
            $context = substr($this->_text, max(0, $pos - 30), 60);
            if (!preg_match('/phone|tel|fax|mobile|cell/i', $context)) {
                $result['zip'] = $matches[1];
            }
        }

        return $result;
    }

    /**
     * Extract Indian location patterns.
     */
    private function _extractIndianLocation()
    {
        $result = array('address' => '', 'city' => '', 'state' => '', 'zip' => '');

        // Indian cities
        $cityPattern = implode('|', self::$_indianCities);
        if (preg_match('/\b(' . $cityPattern . ')\b/i', $this->_text, $matches)) {
            $result['city'] = $this->_formatName($matches[1]);
        }

        // Indian states
        $statePattern = implode('|', self::$_indianStates);
        if (preg_match('/\b(' . $statePattern . ')\b/i', $this->_text, $matches)) {
            $result['state'] = trim($matches[1]);
        }

        // Indian PIN code (6 digits)
        if (preg_match('/\b(\d{6})\b/', $this->_text, $matches)) {
            $pin = $matches[1];
            // Validate it's in Indian PIN range (1xxxxx to 9xxxxx)
            if ($pin[0] >= '1' && $pin[0] <= '9') {
                $pos = strpos($this->_text, $pin);
                $context = substr($this->_text, max(0, $pos - 30), 60);
                if (!preg_match('/phone|tel|mobile|cell/i', $context)) {
                    $result['zip'] = $pin;
                }
            }
        }

        // City, State pattern for India
        if (empty($result['city']) && preg_match('/([A-Za-z\s]+),\s*(' . $statePattern . ')/i', $this->_text, $matches)) {
            $result['city'] = trim($matches[1]);
            $result['state'] = trim($matches[2]);
        }

        return $result;
    }

    /**
     * Extract job title from resume (header area and experience section).
     */
    private function _extractJobTitle()
    {
        // Common job title keywords — used to detect title lines
        $titleKeywords = '/\b(engineer|developer|analyst|designer|manager|director|consultant|specialist|architect|administrator|officer|executive|coordinator|associate|lead|head|president|vice|senior|junior|intern|trainee|programmer|scientist|researcher|technician|officer|recruiter|hr|human\s+resources|marketing|sales|finance|accountant|auditor|qa|tester|devops|cloud|data|product|project|program|scrum|agile|full\s*stack|front.?end|back.?end|mobile|android|ios|security|network|system|business|operations|support|customer|service|success|growth|content|seo|digital|ui|ux|graphic|software|hardware|embedded|mechanical|civil|electrical|chemical|biomedical|pharmacist|nurse|doctor|physician|lawyer|legal|teacher|professor|instructor|trainer|writer|editor|journalist|translator)\b/i';

        // Strategy 1: "Title:" label
        if (preg_match('/(?:current\s+)?(?:designation|title|position|role|job\s+title)\s*[:]\s*([A-Za-z][A-Za-z0-9\s\/\-\+\.&]{3,60})/i', $this->_text, $m)) {
            return trim($m[1]);
        }

        // Strategy 2: Look in first 10 lines for a line that looks like a job title
        // (short line, contains a title keyword, not an email/phone/address line)
        $lineCount = 0;
        foreach ($this->_lines as $line) {
            $line = trim($line);
            if (empty($line)) continue;
            $lineCount++;
            if ($lineCount > 10) break;

            // Skip contact info lines
            if (preg_match('/@|http|www\.|phone|tel:|mobile:|linkedin|github|\d{6,}/i', $line)) continue;
            // Skip lines that look like names (already extracted) or headers
            if ($lineCount <= 2) continue;
            // Must contain a title keyword and be short
            if (strlen($line) < 5 || strlen($line) > 80) continue;
            if (preg_match($titleKeywords, $line)) {
                // Reject lines that contain pipe-separated multi-field data
                if (substr_count($line, '|') >= 2) continue;
                return $line;
            }
        }

        // Strategy 3: Look in experience section — first line before a company/date
        if (!empty($this->_lines)) {
            $sections = $this->_extractSections();
            if (isset($sections['experience'])) {
                $expLines = preg_split('/\r?\n/', $sections['experience']);
                foreach ($expLines as $line) {
                    $line = trim($line);
                    if (empty($line)) continue;
                    if (strlen($line) < 5 || strlen($line) > 80) continue;
                    if (preg_match($titleKeywords, $line) &&
                        !preg_match('/\d{4}/', $line) &&
                        !preg_match('/(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)/i', $line)) {
                        return $line;
                    }
                    break; // Only check first non-empty line of experience section
                }
            }
        }

        return '';
    }

    /**
     * Extract current employer AND job title from resume (combined for accuracy).
     */
    private function _extractCurrentEmployerAndTitle($sections)
    {
        $result = array('employer' => '', 'title' => '');

        // Look for "Currently working at" patterns in full text
        $employerPatterns = array(
            '/(?:currently|presently)\s+(?:working|employed)\s+(?:at|with|in)\s+([A-Za-z0-9\s&\-\.]+?)(?:\s+as|\s+since|\.|\,|$)/i',
            '/(?:working|employed)\s+(?:at|with)\s+([A-Za-z0-9\s&\-\.]+?)\s+(?:since|from)\s+/i',
            '/(?:current\s+)?employer[\s:]+([A-Za-z0-9\s&\-\.]+?)(?:\s*[\n,]|$)/i',
            '/(?:company|organization)[\s:]+([A-Za-z0-9\s&\-\.]+?)(?:\s*[\n,]|$)/i',
        );

        foreach ($employerPatterns as $pattern) {
            if (preg_match($pattern, $this->_text, $matches)) {
                $employer = trim($matches[1]);
                if (strlen($employer) > 2 && strlen($employer) < 100) {
                    $result['employer'] = $employer;
                    break;
                }
            }
        }

        // Job title keywords for detection
        $titleKw = '/\b(engineer|developer|analyst|designer|manager|director|consultant|specialist|architect|administrator|coordinator|lead|head|executive|programmer|scientist|researcher|technician|officer|recruiter|accountant|qa|tester|devops|cloud|data|product|project|scrum|full\s*stack|front.?end|back.?end|mobile|security|network|system|business|operations|support|growth|content|seo|digital|ui|ux|graphic|software|hardware|embedded|writer|editor)\b/i';

        // Look in experience section — pair title + company lines
        if (isset($sections['experience'])) {
            $expLines = preg_split('/\r?\n/', $sections['experience']);
            $datePattern = '/(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[a-z]*\.?\s+\d{4}|(?:20[0-2]\d|19[8-9]\d)\s*[-–to]/i';

            $prevLine = '';
            foreach ($expLines as $line) {
                $line = trim($line);
                if (empty($line)) continue;

                $hasDate = preg_match($datePattern, $line);

                // Line with date: likely "Company Name  |  Jan 2020 - Present"
                if ($hasDate) {
                    // Extract company from before the date
                    $company = preg_split('/\s*[-–|]\s*(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec|\d{4})/i', $line)[0];
                    $company = trim($company);
                    // If previous line had a title keyword, it was the job title
                    if (!empty($prevLine) && preg_match($titleKw, $prevLine) && strlen($prevLine) < 80) {
                        if (empty($result['title']))   $result['title']   = $prevLine;
                        if (empty($result['employer']) && strlen($company) > 2 && strlen($company) < 80) {
                            $result['employer'] = $company;
                        }
                        break;
                    }
                    // If this line itself contains a company-like string (no title keywords)
                    if (!preg_match($titleKw, $company) && strlen($company) > 2 && strlen($company) < 80) {
                        if (empty($result['employer'])) $result['employer'] = $company;
                    }
                }

                // Line looks like a job title (has title keyword, no date, short)
                if (!$hasDate && preg_match($titleKw, $line) && strlen($line) < 80 && strlen($line) > 3) {
                    if (empty($result['title'])) $result['title'] = $line;
                }

                $prevLine = $line;

                // Stop after finding both
                if (!empty($result['employer']) && !empty($result['title'])) break;
            }
        }

        // Fallback: scan full text for company name in first experience entry
        if (empty($result['employer']) && isset($sections['experience'])) {
            $expText = $sections['experience'];
            $lines   = preg_split('/\r?\n/', $expText);
            foreach ($lines as $line) {
                $line = trim($line);
                if (empty($line)) continue;
                if (preg_match('/^([A-Za-z0-9\s&\-\.]+?)(?:\s*[-–|]\s*|\s+\(|\s+(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec|20\d{2}|19\d{2}))/i', $line, $m)) {
                    $company = trim($m[1]);
                    if (!preg_match($titleKw, $company) && strlen($company) > 2 && strlen($company) < 80) {
                        $result['employer'] = $company;
                        break;
                    }
                }
            }
        }

        return $result;
    }

    /**
     * Identify and extract resume sections by their headers.
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
                $testLine = preg_replace('/[\-_=:]+$/', '', $trimmedLine);
                $testLine = trim($testLine);

                if (preg_match($pattern, $testLine) && strlen($testLine) < 60) {
                    $foundSection = $sectionName;
                    break;
                }
            }

            if ($foundSection !== null) {
                if (!empty($sectionContent)) {
                    $sections[$currentSection] = implode("\n", $sectionContent);
                }
                $currentSection = $foundSection;
                $sectionContent = array();
            } else {
                $sectionContent[] = $trimmedLine;
            }
        }

        if (!empty($sectionContent)) {
            $sections[$currentSection] = implode("\n", $sectionContent);
        }

        return $sections;
    }

    /**
     * Extract skills from resume text.
     */
    private function _extractSkills($sections)
    {
        $foundSkills = array();

        // First: check skills section
        if (isset($sections['skills'])) {
            $skillText = $sections['skills'];
            $candidates = preg_split('/[,|;•·\n\t]+/', $skillText);

            foreach ($candidates as $candidate) {
                $candidate = trim($candidate);
                $candidate = preg_replace('/^[\d\.\)\-\*\#\:]+\s*/', '', $candidate);
                $candidate = trim($candidate);

                if (!empty($candidate) && strlen($candidate) >= 2 && strlen($candidate) < 60) {
                    // Skip if it looks like a sentence
                    if (str_word_count($candidate) <= 4) {
                        $foundSkills[] = $candidate;
                    }
                }
            }
        }

        // Second: scan for known skill keywords
        foreach (self::$_skillPatterns as $skill) {
            $pattern = '/(?<![a-zA-Z])' . $skill . '(?![a-zA-Z])/i';
            if (@preg_match($pattern, $this->_text, $m)) {
                $matched = $m[0];

                // Avoid duplicates
                $isDuplicate = false;
                foreach ($foundSkills as $existing) {
                    if (strcasecmp(trim($existing), trim($matched)) === 0) {
                        $isDuplicate = true;
                        break;
                    }
                }
                if (!$isDuplicate) {
                    $foundSkills[] = $matched;
                }
            }
        }

        // Remove duplicates and limit
        $foundSkills = array_unique(array_map('trim', $foundSkills));
        $foundSkills = array_slice($foundSkills, 0, 40);

        return implode(', ', $foundSkills);
    }

    /**
     * Extract education information.
     */
    private function _extractEducation($sections)
    {
        $education = array();

        if (isset($sections['education'])) {
            $education[] = $sections['education'];
        } else {
            // Scan for degree patterns
            $degreePattern = implode('|', self::$_degreePatterns);
            if (preg_match_all('/(?:' . $degreePattern . ')[\s\w,.\-\(\)]+/i', $this->_text, $matches)) {
                foreach ($matches[0] as $match) {
                    $match = trim($match);
                    if (strlen($match) > 5 && strlen($match) < 250) {
                        $education[] = $match;
                    }
                }
            }
        }

        // Look for university/college names
        if (preg_match_all('/(?:University|College|Institute|School|Academy|IIT|IIM|NIT|BITS)\s+(?:of\s+)?[A-Za-z\s,]+/i', $this->_text, $matches)) {
            foreach ($matches[0] as $match) {
                $match = trim($match);
                if (strlen($match) > 10 && strlen($match) < 120) {
                    $isDuplicate = false;
                    foreach ($education as $existing) {
                        if (stripos($existing, $match) !== false) {
                            $isDuplicate = true;
                            break;
                        }
                    }
                    if (!$isDuplicate) $education[] = $match;
                }
            }
        }

        $educationText = implode("\n", $education);

        // Limit length
        if (strlen($educationText) > 1500) {
            $educationText = substr($educationText, 0, 1500) . '...';
        }

        return trim($educationText);
    }

    /**
     * Extract work experience information.
     */
    private function _extractExperience($sections)
    {
        $experience = '';

        if (isset($sections['experience'])) {
            $experience = $sections['experience'];
        } else {
            // Find experience-like content with date ranges
            $expLines = array();
            $datePattern = '/(?:(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[a-z]*\.?\s+\d{4}|(?:20[0-2]\d|19[8-9]\d))\s*[-–to]+\s*(?:(?:Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[a-z]*\.?\s+\d{4}|(?:20[0-2]\d|19[8-9]\d)|Present|Current|Till\s*Date)/i';

            foreach ($this->_lines as $i => $line) {
                if (preg_match($datePattern, $line)) {
                    for ($j = max(0, $i - 1); $j <= min(count($this->_lines) - 1, $i + 4); $j++) {
                        $l = trim($this->_lines[$j]);
                        if (!empty($l)) $expLines[] = $l;
                    }
                    $expLines[] = '';
                }
            }

            $experience = implode("\n", $expLines);
        }

        // Limit length
        if (strlen($experience) > 2500) {
            $experience = substr($experience, 0, 2500) . '...';
        }

        return trim($experience);
    }

    /**
     * Calculate years of experience from date ranges in resume.
     */
    public function estimateYearsOfExperience($text = null)
    {
        if ($text === null) $text = $this->_text;

        $totalMonths = 0;
        $datePattern = '/(?:(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[a-z]*\.?\s+)?(\d{4})\s*[-–to]+\s*(?:(Jan|Feb|Mar|Apr|May|Jun|Jul|Aug|Sep|Oct|Nov|Dec)[a-z]*\.?\s+)?(\d{4}|Present|Current|Till\s*Date)/i';

        $months = array(
            'jan' => 1, 'feb' => 2, 'mar' => 3, 'apr' => 4,
            'may' => 5, 'jun' => 6, 'jul' => 7, 'aug' => 8,
            'sep' => 9, 'oct' => 10, 'nov' => 11, 'dec' => 12
        );

        if (preg_match_all($datePattern, $text, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $startMonth = !empty($match[1]) ? ($months[strtolower(substr($match[1], 0, 3))] ?? 1) : 1;
                $startYear = intval($match[2]);

                $endValue = strtolower(trim($match[4]));
                if (in_array($endValue, array('present', 'current', 'till date'))) {
                    $endYear = intval(date('Y'));
                    $endMonth = intval(date('m'));
                } else {
                    $endMonth = !empty($match[3]) ? ($months[strtolower(substr($match[3], 0, 3))] ?? 12) : 12;
                    $endYear = intval($match[4]);
                }

                if ($startYear >= 1970 && $startYear <= 2030 && $endYear >= $startYear) {
                    $duration = ($endYear - $startYear) * 12 + ($endMonth - $startMonth);
                    if ($duration > 0 && $duration < 600) {
                        $totalMonths += $duration;
                    }
                }
            }
        }

        return round($totalMonths / 12, 1);
    }

    /**
     * Get confidence scores for parsed fields.
     */
    public function getConfidenceScores($parsedResult)
    {
        $scores = array();

        $scores['email'] = !empty($parsedResult['email_address']) ? 0.95 : 0.0;
        $scores['phone'] = !empty($parsedResult['phone_number']) ? 0.90 : 0.0;

        if (!empty($parsedResult['first_name']) && !empty($parsedResult['last_name'])) {
            $scores['name'] = 0.80;
        } elseif (!empty($parsedResult['first_name'])) {
            $scores['name'] = 0.45;
        } else {
            $scores['name'] = 0.0;
        }

        $locScore = 0.0;
        if (!empty($parsedResult['city'])) $locScore += 0.35;
        if (!empty($parsedResult['state'])) $locScore += 0.35;
        if (!empty($parsedResult['zip_code'])) $locScore += 0.15;
        if (!empty($parsedResult['us_address'])) $locScore += 0.15;
        $scores['location'] = $locScore;

        if (!empty($parsedResult['skills'])) {
            $skillCount = count(explode(',', $parsedResult['skills']));
            $scores['skills'] = min(1.0, $skillCount * 0.08);
        } else {
            $scores['skills'] = 0.0;
        }

        $scores['education'] = !empty($parsedResult['education']) ? 0.75 : 0.0;
        $scores['experience'] = !empty($parsedResult['experience']) ? 0.70 : 0.0;
        $scores['employer'] = !empty($parsedResult['current_employer']) ? 0.65 : 0.0;

        $scores['overall'] = array_sum($scores) / count($scores);

        return $scores;
    }
}

?>
