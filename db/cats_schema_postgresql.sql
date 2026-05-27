/*
 * CATS ATS - PostgreSQL Schema
 * Converted from MySQL/MyISAM to PostgreSQL syntax.
 * Run with: psql -U postgres -d cats_dev -f cats_schema_postgresql.sql
 */

/*Table structure for table access_level */

CREATE TABLE access_level (
  access_level_id INTEGER NOT NULL DEFAULT 0,
  short_description VARCHAR(32) NOT NULL DEFAULT '',
  long_description TEXT NOT NULL,
  PRIMARY KEY (access_level_id)
);

/*Data for the table access_level */

insert into access_level(access_level_id,short_description,long_description) values (0,'Account Disabled','Disabled - The lowest access level. User cannot log in.');
insert into access_level(access_level_id,short_description,long_description) values (100,'Read Only','Read Only - A standard user that can view data on the system in a read-only mode.');
insert into access_level(access_level_id,short_description,long_description) values (200,'Add / Edit','Edit - All lower access, plus the ability to edit information on the system.');
insert into access_level(access_level_id,short_description,long_description) values (300,'Add / Edit / Delete','Delete - All lower access, plus the ability to delete information on the system.');
insert into access_level(access_level_id,short_description,long_description) values (400,'Site Administrator','Site Administrator - All lower access, plus the ability to add, edit, and remove site users, as well as the ability to edit site settings.');
insert into access_level(access_level_id,short_description,long_description) values (500,'Root','Root Administrator - All lower access, plus the ability to add, edit, and remove sites, as well as the ability to assign Site Administrator status to a user.');

/*Table structure for table activity */

CREATE TABLE activity (
  activity_id SERIAL NOT NULL,
  data_item_id INTEGER NOT NULL DEFAULT 0,
  data_item_type INTEGER NOT NULL DEFAULT 0,
  joborder_id INTEGER DEFAULT NULL,
  site_id INTEGER NOT NULL DEFAULT 0,
  entered_by INTEGER NOT NULL DEFAULT 0,
  date_created TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  type INTEGER NOT NULL DEFAULT 0,
  notes TEXT,
  date_modified TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (activity_id)
);

/*Data for the table activity */

/*Table structure for table activity_type */

CREATE TABLE activity_type (
  activity_type_id INTEGER NOT NULL DEFAULT 0,
  short_description VARCHAR(32) NOT NULL DEFAULT '',
  PRIMARY KEY (activity_type_id)
);

/*Data for the table activity_type */

insert into activity_type(activity_type_id,short_description) values (100,'Call');
insert into activity_type(activity_type_id,short_description) values (200,'Email');
insert into activity_type(activity_type_id,short_description) values (300,'Meeting');
insert into activity_type(activity_type_id,short_description) values (400,'Other');
insert into activity_type(activity_type_id,short_description) values (500,'Call (Talked)');
insert into activity_type(activity_type_id,short_description) values (600,'Call (LVM)');
insert into activity_type(activity_type_id,short_description) values (700,'Call (Missed)');

/*Table structure for table attachment */

CREATE TABLE attachment (
  attachment_id SERIAL NOT NULL,
  data_item_id INTEGER NOT NULL DEFAULT 0,
  data_item_type INTEGER NOT NULL DEFAULT 0,
  site_id INTEGER NOT NULL DEFAULT 0,
  title VARCHAR(128) DEFAULT NULL,
  original_filename VARCHAR(255) NOT NULL DEFAULT '',
  stored_filename VARCHAR(255) NOT NULL DEFAULT '',
  content_type VARCHAR(255) DEFAULT NULL,
  resume INTEGER NOT NULL DEFAULT 0,
  text TEXT,
  date_created TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  date_modified TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  profile_image INTEGER DEFAULT 0,
  directory_name VARCHAR(64) DEFAULT NULL,
  md5_sum VARCHAR(40) NOT NULL DEFAULT '',
  file_size_kb INTEGER DEFAULT 0,
  md5_sum_text VARCHAR(40) NOT NULL DEFAULT '',
  PRIMARY KEY (attachment_id)
);

/*Data for the table attachment */

/*Table structure for table calendar_event */

CREATE TABLE calendar_event (
  calendar_event_id SERIAL NOT NULL,
  type INTEGER NOT NULL DEFAULT 0,
  date TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  title TEXT NOT NULL,
  all_day INTEGER NOT NULL DEFAULT 0,
  data_item_id INTEGER NOT NULL DEFAULT -1,
  data_item_type INTEGER NOT NULL DEFAULT -1,
  entered_by INTEGER NOT NULL DEFAULT 0,
  date_created TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  date_modified TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  site_id INTEGER NOT NULL DEFAULT 0,
  joborder_id INTEGER NOT NULL DEFAULT -1,
  description TEXT,
  duration INTEGER NOT NULL DEFAULT 60,
  reminder_enabled INTEGER NOT NULL DEFAULT 0,
  reminder_email TEXT,
  reminder_time INTEGER DEFAULT 0,
  public INTEGER NOT NULL DEFAULT 1,
  PRIMARY KEY (calendar_event_id)
);

/*Data for the table calendar_event */

/*Table structure for table calendar_event_type */

CREATE TABLE calendar_event_type (
  calendar_event_type_id INTEGER NOT NULL DEFAULT 0,
  short_description VARCHAR(32) NOT NULL DEFAULT '',
  icon_image VARCHAR(128) NOT NULL DEFAULT '',
  PRIMARY KEY (calendar_event_type_id)
);

/*Data for the table calendar_event_type */

insert into calendar_event_type(calendar_event_type_id,short_description,icon_image) values (100,'Call','images/phone.gif');
insert into calendar_event_type(calendar_event_type_id,short_description,icon_image) values (200,'Email','images/email.gif');
insert into calendar_event_type(calendar_event_type_id,short_description,icon_image) values (300,'Meeting','images/meeting.gif');
insert into calendar_event_type(calendar_event_type_id,short_description,icon_image) values (400,'Interview','images/interview.gif');
insert into calendar_event_type(calendar_event_type_id,short_description,icon_image) values (500,'Personal','images/personal.gif');
insert into calendar_event_type(calendar_event_type_id,short_description,icon_image) values (600,'Other','');

/*Table structure for table candidate */

CREATE TABLE candidate (
  candidate_id SERIAL NOT NULL,
  site_id INTEGER NOT NULL DEFAULT 0,
  last_name VARCHAR(64) NOT NULL DEFAULT '',
  first_name VARCHAR(64) NOT NULL DEFAULT '',
  middle_name VARCHAR(32) DEFAULT NULL,
  phone_home VARCHAR(40) DEFAULT NULL,
  phone_cell VARCHAR(40) DEFAULT NULL,
  phone_work VARCHAR(40) DEFAULT NULL,
  address TEXT,
  city VARCHAR(64) DEFAULT NULL,
  state VARCHAR(64) DEFAULT NULL,
  zip VARCHAR(16) DEFAULT NULL,
  source VARCHAR(128) DEFAULT NULL,
  date_available TIMESTAMP DEFAULT NULL,
  can_relocate INTEGER NOT NULL DEFAULT 0,
  notes TEXT,
  key_skills TEXT,
  current_employer VARCHAR(128) DEFAULT NULL,
  entered_by INTEGER NOT NULL DEFAULT 0,
  owner INTEGER DEFAULT NULL,
  date_created TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  date_modified TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  email1 VARCHAR(128) DEFAULT NULL,
  email2 VARCHAR(128) DEFAULT NULL,
  web_site VARCHAR(128) DEFAULT NULL,
  import_id INTEGER NOT NULL DEFAULT 0,
  is_hot INTEGER NOT NULL DEFAULT 0,
  eeo_ethnic_type_id INTEGER DEFAULT 0,
  eeo_veteran_type_id INTEGER DEFAULT 0,
  eeo_disability_status VARCHAR(5) DEFAULT '',
  eeo_gender VARCHAR(5) DEFAULT '',
  desired_pay VARCHAR(64) DEFAULT NULL,
  current_pay VARCHAR(64) DEFAULT NULL,
  is_active INTEGER DEFAULT 1,
  is_admin_hidden INTEGER DEFAULT 0,
  best_time_to_call VARCHAR(255) NOT NULL DEFAULT '',
  PRIMARY KEY (candidate_id)
);

/*Data for the table candidate */

/*Table structure for table candidate_duplicates */

CREATE TABLE candidate_duplicates (
  old_candidate_id INTEGER NOT NULL,
  new_candidate_id INTEGER NOT NULL,
  site_id INTEGER NOT NULL,
  PRIMARY KEY (old_candidate_id, new_candidate_id)
);

/*Data for the table candidate_duplicates */

/*Table structure for table candidate_joborder */

CREATE TABLE candidate_joborder (
  candidate_joborder_id SERIAL NOT NULL,
  candidate_id INTEGER NOT NULL DEFAULT 0,
  joborder_id INTEGER NOT NULL DEFAULT 0,
  site_id INTEGER NOT NULL DEFAULT 0,
  status INTEGER NOT NULL DEFAULT 0,
  date_submitted TIMESTAMP DEFAULT NULL,
  date_created TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  date_modified TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  rating_value INTEGER DEFAULT NULL,
  added_by INTEGER DEFAULT NULL,
  PRIMARY KEY (candidate_joborder_id)
);

/*Data for the table candidate_joborder */

/*Table structure for table candidate_joborder_status */

CREATE TABLE candidate_joborder_status (
  candidate_joborder_status_id INTEGER NOT NULL DEFAULT 0,
  short_description VARCHAR(32) NOT NULL DEFAULT '',
  can_be_scheduled INTEGER NOT NULL DEFAULT 0,
  triggers_email INTEGER NOT NULL DEFAULT 1,
  is_enabled INTEGER NOT NULL DEFAULT 1,
  PRIMARY KEY (candidate_joborder_status_id)
);

/*Data for the table candidate_joborder_status */

insert into candidate_joborder_status(candidate_joborder_status_id,short_description,can_be_scheduled,triggers_email,is_enabled) values (100,'No Contact',0,0,1);
insert into candidate_joborder_status(candidate_joborder_status_id,short_description,can_be_scheduled,triggers_email,is_enabled) values (200,'Contacted',0,0,1);
insert into candidate_joborder_status(candidate_joborder_status_id,short_description,can_be_scheduled,triggers_email,is_enabled) values (300,'Qualifying',0,1,1);
insert into candidate_joborder_status(candidate_joborder_status_id,short_description,can_be_scheduled,triggers_email,is_enabled) values (400,'Submitted',0,1,1);
insert into candidate_joborder_status(candidate_joborder_status_id,short_description,can_be_scheduled,triggers_email,is_enabled) values (500,'Interviewing',0,1,1);
insert into candidate_joborder_status(candidate_joborder_status_id,short_description,can_be_scheduled,triggers_email,is_enabled) values (600,'Offered',0,1,1);
insert into candidate_joborder_status(candidate_joborder_status_id,short_description,can_be_scheduled,triggers_email,is_enabled) values (700,'Client Declined',0,0,1);
insert into candidate_joborder_status(candidate_joborder_status_id,short_description,can_be_scheduled,triggers_email,is_enabled) values (800,'Placed',0,1,1);
insert into candidate_joborder_status(candidate_joborder_status_id,short_description,can_be_scheduled,triggers_email,is_enabled) values (0,'No Status',0,0,1);
insert into candidate_joborder_status(candidate_joborder_status_id,short_description,can_be_scheduled,triggers_email,is_enabled) values (650,'Not in Consideration',0,0,1);
insert into candidate_joborder_status(candidate_joborder_status_id,short_description,can_be_scheduled,triggers_email,is_enabled) values (250,'Candidate Responded',0,0,1);

/*Table structure for table candidate_joborder_status_history */

CREATE TABLE candidate_joborder_status_history (
  candidate_joborder_status_history_id SERIAL NOT NULL,
  candidate_id INTEGER NOT NULL DEFAULT 0,
  joborder_id INTEGER NOT NULL DEFAULT 0,
  date TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  status_from INTEGER NOT NULL DEFAULT 0,
  status_to INTEGER NOT NULL DEFAULT 0,
  site_id INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (candidate_joborder_status_history_id)
);

/*Data for the table candidate_joborder_status_history */

/*Table structure for table candidate_jobordrer_status_type */

CREATE TABLE candidate_jobordrer_status_type (
  candidate_status_type_id INTEGER NOT NULL DEFAULT 0,
  short_description VARCHAR(32) NOT NULL DEFAULT '',
  can_be_scheduled INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (candidate_status_type_id)
);

/*Data for the table candidate_jobordrer_status_type */

/*Table structure for table candidate_source */

CREATE TABLE candidate_source (
  source_id SERIAL NOT NULL,
  name VARCHAR(255) DEFAULT NULL,
  site_id INTEGER DEFAULT NULL,
  date_created TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (source_id)
);

/*Data for the table candidate_source */

/*Table structure for table candidate_tag */

CREATE TABLE candidate_tag (
  id SERIAL NOT NULL,
  site_id INTEGER DEFAULT NULL,
  candidate_id INTEGER NOT NULL,
  tag_id INTEGER NOT NULL,
  PRIMARY KEY (id)
);

/*Data for the table candidate_tag */

insert into candidate_tag(id,site_id,candidate_id,tag_id) values (55,1,1,5);
insert into candidate_tag(id,site_id,candidate_id,tag_id) values (56,1,1,78);
insert into candidate_tag(id,site_id,candidate_id,tag_id) values (57,1,1,80);
insert into candidate_tag(id,site_id,candidate_id,tag_id) values (58,1,1,81);

/*Table structure for table career_portal_questionnaire */

CREATE TABLE career_portal_questionnaire (
  career_portal_questionnaire_id SERIAL NOT NULL,
  title VARCHAR(255) NOT NULL DEFAULT '',
  site_id INTEGER NOT NULL DEFAULT 0,
  description VARCHAR(255) DEFAULT NULL,
  is_active BOOLEAN NOT NULL DEFAULT TRUE,
  PRIMARY KEY (career_portal_questionnaire_id)
);

/*Data for the table career_portal_questionnaire */

/*Table structure for table career_portal_questionnaire_answer */

CREATE TABLE career_portal_questionnaire_answer (
  career_portal_questionnaire_answer_id SERIAL NOT NULL,
  career_portal_questionnaire_question_id INTEGER NOT NULL,
  career_portal_questionnaire_id INTEGER NOT NULL,
  text VARCHAR(255) NOT NULL DEFAULT '',
  action_source VARCHAR(128) DEFAULT NULL,
  action_notes TEXT,
  action_is_hot BOOLEAN DEFAULT FALSE,
  action_is_active BOOLEAN DEFAULT FALSE,
  action_can_relocate BOOLEAN DEFAULT FALSE,
  action_key_skills VARCHAR(255) DEFAULT NULL,
  position INTEGER NOT NULL DEFAULT 0,
  site_id INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (career_portal_questionnaire_answer_id)
);

/*Data for the table career_portal_questionnaire_answer */

/*Table structure for table career_portal_questionnaire_history */

CREATE TABLE career_portal_questionnaire_history (
  career_portal_questionnaire_history_id SERIAL NOT NULL,
  site_id INTEGER NOT NULL DEFAULT 0,
  candidate_id INTEGER NOT NULL DEFAULT 0,
  question VARCHAR(255) NOT NULL DEFAULT '',
  answer VARCHAR(255) NOT NULL DEFAULT '',
  questionnaire_title VARCHAR(255) NOT NULL DEFAULT '',
  questionnaire_description VARCHAR(255) NOT NULL DEFAULT '',
  date TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (career_portal_questionnaire_history_id)
);

/*Data for the table career_portal_questionnaire_history */

/*Table structure for table career_portal_questionnaire_question */

CREATE TABLE career_portal_questionnaire_question (
  career_portal_questionnaire_question_id SERIAL NOT NULL,
  career_portal_questionnaire_id INTEGER NOT NULL,
  text VARCHAR(255) NOT NULL DEFAULT '',
  minimum_length INTEGER DEFAULT NULL,
  maximum_length INTEGER DEFAULT NULL,
  required BOOLEAN NOT NULL DEFAULT FALSE,
  position INTEGER NOT NULL DEFAULT 0,
  site_id INTEGER NOT NULL DEFAULT 0,
  type INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (career_portal_questionnaire_question_id)
);

/*Data for the table career_portal_questionnaire_question */

/*Table structure for table career_portal_template */

CREATE TABLE career_portal_template (
  career_portal_template_id SERIAL NOT NULL,
  career_portal_name VARCHAR(255) DEFAULT NULL,
  setting VARCHAR(128) NOT NULL DEFAULT '',
  value TEXT,
  PRIMARY KEY (career_portal_template_id)
);

/*Data for the table career_portal_template */

insert into career_portal_template(career_portal_template_id,career_portal_name,setting,value) values (56,'Blank Page','Left','');
insert into career_portal_template(career_portal_template_id,career_portal_name,setting,value) values (57,'Blank Page','Footer','');
insert into career_portal_template(career_portal_template_id,career_portal_name,setting,value) values (58,'Blank Page','Header','');
insert into career_portal_template(career_portal_template_id,career_portal_name,setting,value) values (59,'Blank Page','Content - Main','');
insert into career_portal_template(career_portal_template_id,career_portal_name,setting,value) values (60,'Blank Page','CSS','');
insert into career_portal_template(career_portal_template_id,career_portal_name,setting,value) values (61,'Blank Page','Content - Search Results','');
insert into career_portal_template(career_portal_template_id,career_portal_name,setting,value) values (62,'Blank Page','Content - Questionnaire','');
insert into career_portal_template(career_portal_template_id,career_portal_name,setting,value) values (63,'Blank Page','Content - Job Details','');
insert into career_portal_template(career_portal_template_id,career_portal_name,setting,value) values (64,'Blank Page','Content - Thanks for your Submission','');
insert into career_portal_template(career_portal_template_id,career_portal_name,setting,value) values (65,'Blank Page','Content - Apply for Position','');
insert into career_portal_template(career_portal_template_id,career_portal_name,setting,value) values (66,'CATS 2.0','Left','');
insert into career_portal_template(career_portal_template_id,career_portal_name,setting,value) values (67,'CATS 2.0','Footer','</div>');
insert into career_portal_template(career_portal_template_id,career_portal_name,setting,value) values (68,'CATS 2.0','Header','<div id="container">\r\n	<div id="logo"><img src="images/careers_cats.gif" alt="IMAGE: CATS Applicant Tracking System Careers Page" /></div>\r\n    <div id="actions">\r\n    	<h2>Shortcuts:</h2>\r\n        <a href="index.php" onmouseover="buttonMouseOver(''returnToMain'',true);" onmouseout="buttonMouseOver(''returnToMain'',false);"><img src="images/careers_return.gif" id="returnToMain" alt="IMAGE: Return to Main" /></a>\r\n<a href="<rssURL>" onmouseover="buttonMouseOver(''rssFeed'',true);" onmouseout="buttonMouseOver(''rssFeed'',false);"><img src="images/careers_rss.gif" id="rssFeed" alt="IMAGE: RSS Feed" /></a>\r\n        <a href="index.php?m=careers&p=showAll" onmouseover="buttonMouseOver(''showAllJobs'',true);" onmouseout="buttonMouseOver(''showAllJobs'',false);"><img src="images/careers_show.gif" id="showAllJobs" alt="IMAGE: Show All Jobs" /></a>\r\n    </div>');
insert into career_portal_template(career_portal_template_id,career_portal_name,setting,value) values (77,'CATS 2.0','Content - Candidate Profile','<div id="careerContent">My Profile</div>');

/*Table structure for table career_portal_template_site */

CREATE TABLE career_portal_template_site (
  career_portal_template_id SERIAL NOT NULL,
  career_portal_name VARCHAR(255) DEFAULT NULL,
  site_id INTEGER NOT NULL,
  setting VARCHAR(128) NOT NULL DEFAULT '',
  value TEXT,
  PRIMARY KEY (career_portal_template_id)
);

/*Data for the table career_portal_template_site */

/*Table structure for table company */

CREATE TABLE company (
  company_id SERIAL NOT NULL,
  site_id INTEGER NOT NULL DEFAULT 0,
  billing_contact INTEGER DEFAULT NULL,
  name VARCHAR(64) NOT NULL DEFAULT '',
  address TEXT,
  city VARCHAR(64) DEFAULT NULL,
  state VARCHAR(64) DEFAULT NULL,
  zip VARCHAR(16) DEFAULT NULL,
  phone1 VARCHAR(40) DEFAULT NULL,
  phone2 VARCHAR(40) DEFAULT NULL,
  url VARCHAR(128) DEFAULT NULL,
  key_technologies TEXT,
  notes TEXT,
  entered_by INTEGER DEFAULT NULL,
  owner INTEGER DEFAULT NULL,
  date_created TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  date_modified TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  is_hot INTEGER DEFAULT NULL,
  fax_number VARCHAR(40) DEFAULT NULL,
  import_id INTEGER DEFAULT NULL,
  default_company INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (company_id)
);

/*Data for the table company */

insert into company(company_id,site_id,billing_contact,name,address,city,state,zip,phone1,phone2,url,key_technologies,notes,entered_by,owner,date_created,date_modified,is_hot,fax_number,import_id,default_company) values (1,1,NULL,'Internal Postings','','','','','','','','','',0,0,'2009-11-19 10:00:20','2009-11-19 10:00:20',0,'',NULL,1);

/*Table structure for table company_department */

CREATE TABLE company_department (
  company_department_id SERIAL NOT NULL,
  name VARCHAR(128) DEFAULT NULL,
  company_id INTEGER NOT NULL,
  site_id INTEGER NOT NULL DEFAULT 0,
  date_created TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  created_by INTEGER DEFAULT NULL,
  PRIMARY KEY (company_department_id)
);

/*Data for the table company_department */

/*Table structure for table contact */

CREATE TABLE contact (
  contact_id SERIAL NOT NULL,
  company_id INTEGER NOT NULL,
  site_id INTEGER NOT NULL DEFAULT 0,
  last_name VARCHAR(64) NOT NULL DEFAULT '',
  first_name VARCHAR(64) NOT NULL DEFAULT '',
  title VARCHAR(128) DEFAULT NULL,
  email1 VARCHAR(128) DEFAULT NULL,
  email2 VARCHAR(128) DEFAULT NULL,
  phone_work VARCHAR(40) DEFAULT NULL,
  phone_cell VARCHAR(40) DEFAULT NULL,
  phone_other VARCHAR(40) DEFAULT NULL,
  address TEXT,
  city VARCHAR(64) DEFAULT NULL,
  state VARCHAR(64) DEFAULT NULL,
  zip VARCHAR(16) DEFAULT NULL,
  is_hot INTEGER DEFAULT NULL,
  notes TEXT,
  entered_by INTEGER NOT NULL DEFAULT 0,
  owner INTEGER DEFAULT NULL,
  date_created TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  date_modified TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  left_company INTEGER NOT NULL DEFAULT 0,
  import_id INTEGER NOT NULL DEFAULT 0,
  company_department_id INTEGER NOT NULL,
  reports_to INTEGER DEFAULT -1,
  PRIMARY KEY (contact_id)
);

/*Data for the table contact */

/*Table structure for table data_item_type */

CREATE TABLE data_item_type (
  data_item_type_id INTEGER NOT NULL DEFAULT 0,
  short_description VARCHAR(32) NOT NULL DEFAULT '',
  PRIMARY KEY (data_item_type_id)
);

/*Data for the table data_item_type */

insert into data_item_type(data_item_type_id,short_description) values (100,'Candidate');
insert into data_item_type(data_item_type_id,short_description) values (200,'Company');
insert into data_item_type(data_item_type_id,short_description) values (300,'Contact');
insert into data_item_type(data_item_type_id,short_description) values (400,'Job Order');

/*Table structure for table eeo_ethnic_type */

CREATE TABLE eeo_ethnic_type (
  eeo_ethnic_type_id SERIAL NOT NULL,
  type VARCHAR(128) NOT NULL DEFAULT '',
  PRIMARY KEY (eeo_ethnic_type_id)
);

/*Data for the table eeo_ethnic_type */

insert into eeo_ethnic_type(eeo_ethnic_type_id,type) values (1,'American Indian');
insert into eeo_ethnic_type(eeo_ethnic_type_id,type) values (2,'Asian or Pacific Islander');
insert into eeo_ethnic_type(eeo_ethnic_type_id,type) values (3,'Hispanic or Latino');
insert into eeo_ethnic_type(eeo_ethnic_type_id,type) values (4,'Non-Hispanic Black');
insert into eeo_ethnic_type(eeo_ethnic_type_id,type) values (5,'Non-Hispanic White');

/*Table structure for table eeo_veteran_type */

CREATE TABLE eeo_veteran_type (
  eeo_veteran_type_id SERIAL NOT NULL,
  type VARCHAR(128) NOT NULL DEFAULT '',
  PRIMARY KEY (eeo_veteran_type_id)
);

/*Data for the table eeo_veteran_type */

insert into eeo_veteran_type(eeo_veteran_type_id,type) values (1,'No Veteran Status');
insert into eeo_veteran_type(eeo_veteran_type_id,type) values (2,'Eligible Veteran');
insert into eeo_veteran_type(eeo_veteran_type_id,type) values (3,'Disabled Veteran');
insert into eeo_veteran_type(eeo_veteran_type_id,type) values (4,'Eligible and Disabled');

/*Table structure for table email_history */

CREATE TABLE email_history (
  email_history_id SERIAL NOT NULL,
  from_address VARCHAR(128) NOT NULL DEFAULT '',
  recipients TEXT NOT NULL,
  text TEXT,
  user_id INTEGER DEFAULT NULL,
  site_id INTEGER NOT NULL DEFAULT 0,
  date TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (email_history_id)
);

/*Data for the table email_history */

/*Table structure for table email_template */

CREATE TABLE email_template (
  email_template_id SERIAL NOT NULL,
  text TEXT,
  allow_substitution INTEGER NOT NULL DEFAULT 0,
  site_id INTEGER NOT NULL DEFAULT 0,
  tag VARCHAR(255) DEFAULT NULL,
  title VARCHAR(255) DEFAULT NULL,
  possible_variables TEXT,
  disabled INTEGER DEFAULT 0,
  PRIMARY KEY (email_template_id)
);

/*Data for the table email_template */

insert into email_template(email_template_id,text,allow_substitution,site_id,tag,title,possible_variables,disabled) values (20,'* Auto generated message. Please DO NOT reply *\r\n%DATETIME%\r\n\r\nDear %CANDFULLNAME%,\r\n\r\nThis E-Mail is a notification that your status in our database has been changed for the position %JBODTITLE% (%JBODCLIENT%).\r\n\r\nYour previous status was <B>%CANDPREVSTATUS%</B>.\r\nYour new status is <B>%CANDSTATUS%</B>.\r\n\r\nTake care,\r\n%USERFULLNAME%\r\n%SITENAME%',1,1,'EMAIL_TEMPLATE_STATUSCHANGE','Status Changed (Sent to Candidate)','%CANDSTATUS%%CANDOWNER%%CANDFIRSTNAME%%CANDFULLNAME%%CANDPREVSTATUS%%JBODCLIENT%%JBODTITLE%',0);
insert into email_template(email_template_id,text,allow_substitution,site_id,tag,title,possible_variables,disabled) values (28,'%DATETIME%\r\n\r\nDear %CANDOWNER%,\r\n\r\nThis E-Mail is a notification that a Candidate has been assigned to you.\r\n\r\nCandidate Name: %CANDFULLNAME%\r\nCandidate URL: %CANDCATSURL%\r\n\r\nTake care,\r\nCATS \r\n%SITENAME%',1,1,'EMAIL_TEMPLATE_OWNERSHIPASSIGNCANDIDATE','Candidate Assigned (Sent to Assigned Recruiter)','%CANDOWNER%%CANDFIRSTNAME%%CANDFULLNAME%%CANDCATSURL%',0);
insert into email_template(email_template_id,text,allow_substitution,site_id,tag,title,possible_variables,disabled) values (27,'%DATETIME%\r\n\r\nDear %JBODOWNER%,\r\n\r\nThis E-Mail is a notification that a Job Order has been assigned to you.\r\n\r\nJob Order Title: %JBODTITLE%\r\nJob Order Client: %JBODCLIENT%\r\nJob Order ID: %JBODID%\r\nJob Order URL: %JBODCATSURL%\r\n\r\nTake care,\r\nCATS \r\n%SITENAME%',1,1,'EMAIL_TEMPLATE_OWNERSHIPASSIGNJOBORDER','Job Order Assigned (Sent to Assigned Recruiter)','%JBODOWNER%%JBODTITLE%%JBODCLIENT%%JBODCATSURL%%JBODID%',0);
insert into email_template(email_template_id,text,allow_substitution,site_id,tag,title,possible_variables,disabled) values (26,'%DATETIME%\r\n\r\nDear %CONTOWNER%,\r\n\r\nThis E-Mail is a notification that a Contact has been assigned to you.\r\n\r\nContact Name: %CONTFULLNAME%\r\nContact Client: %CONTCLIENTNAME%\r\nContact URL: %CONTCATSURL%\r\n\r\nTake care,\r\nCATS \r\n%SITENAME%',1,1,'EMAIL_TEMPLATE_OWNERSHIPASSIGNCONTACT','Contact Assigned (Sent to Assigned Recruiter)','%CONTOWNER%%CONTFIRSTNAME%%CONTFULLNAME%%CONTCLIENTNAME%%CONTCATSURL%',0);
insert into email_template(email_template_id,text,allow_substitution,site_id,tag,title,possible_variables,disabled) values (25,'%DATETIME%\r\n\r\nDear %CLNTOWNER%,\r\n\r\nThis E-Mail is a notification that a Client has been assigned to you.\r\n\r\nClient Name: %CLNTNAME%\r\nClient URL %CLNTCATSURL%\r\n\r\nTake care,\r\nCATS \r\n%SITENAME%',1,1,'EMAIL_TEMPLATE_OWNERSHIPASSIGNCLIENT','Client Assigned (Sent to Assigned Recruiter)','%CLNTOWNER%%CLNTNAME%%CLNTCATSURL%',0);
insert into email_template(email_template_id,text,allow_substitution,site_id,tag,title,possible_variables,disabled) values (30,'* This is an auto-generated message. Please do not reply. *\r\n%DATETIME%\r\n\r\nDear %CANDFULLNAME%,\r\n\r\nThank you for applying to the %JBODTITLE% position with our online career portal! Your application has been entered into our system and someone will review it shortly.\r\n\r\n--\r\n%SITENAME%',1,1,'EMAIL_TEMPLATE_CANDIDATEAPPLY','Candidate Application Received (Sent to Candidate using Career Portal)','%CANDFIRSTNAME%%CANDFULLNAME%%JBODCLIENT%%JBODTITLE%%JBODOWNER%',0);
insert into email_template(email_template_id,text,allow_substitution,site_id,tag,title,possible_variables,disabled) values (31,'%DATETIME%\r\n\r\nDear %JBODOWNER%,\r\n\r\nThis e-mail is a notification that a candidate has applied to your job order through the online candidate portal.\r\n\r\nJob Order: %JBODTITLE%\r\nCandidate Name: %CANDFULLNAME%\r\nCandidate URL: %CANDCATSURL%\r\nJob Order URL: %JBODCATSURL%\r\n\r\n--\r\nCATS\r\n%SITENAME%',1,1,'EMAIL_TEMPLATE_CANDIDATEPORTALNEW','Candidate Application Received (Sent to Owner of Job Order from Career Portal)','%CANDFIRSTNAME%%CANDFULLNAME%%JBODOWNER%%JBODTITLE%%JBODCLIENT%%JBODCATSURL%%JBODID%%CANDCATSURL%',0);

/*Table structure for table extension_statistics */

CREATE TABLE extension_statistics (
  extension_statistics_id SERIAL NOT NULL,
  extension VARCHAR(128) NOT NULL DEFAULT '',
  action VARCHAR(128) NOT NULL DEFAULT '',
  "user" VARCHAR(128) NOT NULL DEFAULT '',
  date DATE DEFAULT NULL,
  PRIMARY KEY (extension_statistics_id)
);

/*Data for the table extension_statistics */

/*Table structure for table extra_field */

CREATE TABLE extra_field (
  extra_field_id SERIAL NOT NULL,
  data_item_id INTEGER DEFAULT 0,
  field_name VARCHAR(255) DEFAULT NULL,
  value TEXT,
  import_id INTEGER DEFAULT NULL,
  site_id INTEGER DEFAULT 0,
  data_item_type INTEGER DEFAULT 0,
  PRIMARY KEY (extra_field_id)
);

/*Data for the table extra_field */

/*Table structure for table extra_field_settings */

CREATE TABLE extra_field_settings (
  extra_field_settings_id SERIAL NOT NULL,
  field_name VARCHAR(255) DEFAULT NULL,
  import_id INTEGER DEFAULT NULL,
  site_id INTEGER NOT NULL DEFAULT 0,
  date_created TIMESTAMP DEFAULT NULL,
  data_item_type INTEGER DEFAULT 0,
  extra_field_type INTEGER NOT NULL DEFAULT 1,
  extra_field_options TEXT,
  position INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (extra_field_settings_id)
);

/*Data for the table extra_field_settings */

insert into extra_field_settings(extra_field_settings_id,field_name,import_id,site_id,date_created,data_item_type,extra_field_type,extra_field_options,position) values (1,'AdminUser',NULL,180,'2005-06-01 00:00:00',200,1,NULL,1);
insert into extra_field_settings(extra_field_settings_id,field_name,import_id,site_id,date_created,data_item_type,extra_field_type,extra_field_options,position) values (2,'UnixName',NULL,180,'2005-06-01 00:00:00',200,1,NULL,2);
insert into extra_field_settings(extra_field_settings_id,field_name,import_id,site_id,date_created,data_item_type,extra_field_type,extra_field_options,position) values (3,'BillingNotes',NULL,180,'2005-06-01 00:00:00',200,1,NULL,3);
insert into extra_field_settings(extra_field_settings_id,field_name,import_id,site_id,date_created,data_item_type,extra_field_type,extra_field_options,position) values (4,'IPAddress',NULL,180,'2005-06-01 00:00:00',300,1,NULL,4);

/*Table structure for table feedback */

CREATE TABLE feedback (
  feedback_id SERIAL NOT NULL,
  user_id INTEGER DEFAULT NULL,
  site_id INTEGER NOT NULL DEFAULT 0,
  date_created TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  subject VARCHAR(255) NOT NULL DEFAULT '',
  reply_to_address VARCHAR(255) NOT NULL DEFAULT '',
  reply_to_name VARCHAR(255) NOT NULL DEFAULT '',
  feedback TEXT NOT NULL,
  archived INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (feedback_id)
);

/*Data for the table feedback */

/*Table structure for table history */

CREATE TABLE history (
  history_id SERIAL NOT NULL,
  data_item_type INTEGER DEFAULT NULL,
  data_item_id INTEGER DEFAULT NULL,
  the_field VARCHAR(64) DEFAULT NULL,
  previous_value TEXT,
  new_value TEXT,
  description VARCHAR(192) DEFAULT NULL,
  set_date TIMESTAMP DEFAULT NULL,
  entered_by INTEGER DEFAULT NULL,
  site_id INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (history_id)
);

/*Data for the table history */

insert into history(history_id,data_item_type,data_item_id,the_field,previous_value,new_value,description,set_date,entered_by,site_id) values (1,200,1,'!newEntry!',NULL,NULL,'(USER) created entry.','2009-11-19 10:00:20',1,1);
insert into history(history_id,data_item_type,data_item_id,the_field,previous_value,new_value,description,set_date,entered_by,site_id) values (2,200,1,'defaultCompany',NULL,'1','(USER) changed field(s): defaultCompany.','2009-11-19 10:00:20',1,1);
insert into history(history_id,data_item_type,data_item_id,the_field,previous_value,new_value,description,set_date,entered_by,site_id) values (3,100,1,'!newEntry!',NULL,NULL,'(USER) created entry.','2009-11-19 10:24:54',1,1);
insert into history(history_id,data_item_type,data_item_id,the_field,previous_value,new_value,description,set_date,entered_by,site_id) values (4,100,1,'(DELETED)',NULL,NULL,'(USER) deleted entry.','2009-11-20 14:29:36',1,1);

/*Table structure for table http_log */

CREATE TABLE http_log (
  log_id SERIAL NOT NULL,
  site_id INTEGER NOT NULL,
  remote_addr CHAR(16) NOT NULL,
  http_user_agent VARCHAR(255) DEFAULT NULL,
  script_filename VARCHAR(255) DEFAULT NULL,
  request_method VARCHAR(16) DEFAULT NULL,
  query_string VARCHAR(255) DEFAULT NULL,
  request_uri VARCHAR(255) DEFAULT NULL,
  script_name VARCHAR(255) DEFAULT NULL,
  log_type INTEGER NOT NULL,
  date TIMESTAMP DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (log_id)
);

/*Data for the table http_log */

/*Table structure for table http_log_types */

CREATE TABLE http_log_types (
  log_type_id INTEGER NOT NULL,
  name VARCHAR(16) NOT NULL,
  description VARCHAR(255) DEFAULT NULL,
  default_log_type BOOLEAN NOT NULL DEFAULT FALSE,
  PRIMARY KEY (log_type_id)
);

/*Data for the table http_log_types */

insert into http_log_types(log_type_id,name,description,default_log_type) values (1,'XML','XML Job Feed',false);

/*Table structure for table import */

CREATE TABLE import (
  import_id SERIAL NOT NULL,
  module_name VARCHAR(255) NOT NULL DEFAULT '',
  reverted INTEGER NOT NULL DEFAULT 0,
  site_id INTEGER NOT NULL DEFAULT 0,
  import_errors TEXT,
  added_lines INTEGER DEFAULT NULL,
  date_created TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (import_id)
);

/*Data for the table import */

/*Table structure for table installtest */

CREATE TABLE installtest (
  id INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (id)
);

/*Data for the table installtest */

/*Table structure for table joborder */

CREATE TABLE joborder (
  joborder_id SERIAL NOT NULL,
  recruiter INTEGER DEFAULT NULL,
  contact_id INTEGER DEFAULT NULL,
  company_id INTEGER DEFAULT NULL,
  entered_by INTEGER NOT NULL DEFAULT 0,
  owner INTEGER DEFAULT NULL,
  site_id INTEGER NOT NULL DEFAULT 0,
  client_job_id VARCHAR(32) DEFAULT NULL,
  title VARCHAR(64) NOT NULL DEFAULT '',
  description TEXT,
  notes TEXT,
  type VARCHAR(64) NOT NULL DEFAULT 'C',
  duration VARCHAR(64) DEFAULT NULL,
  rate_max VARCHAR(255) DEFAULT NULL,
  salary VARCHAR(64) DEFAULT NULL,
  status VARCHAR(64) NOT NULL DEFAULT 'Active',
  is_hot INTEGER NOT NULL DEFAULT 0,
  openings INTEGER DEFAULT NULL,
  city VARCHAR(64) NOT NULL DEFAULT '',
  state VARCHAR(64) NOT NULL DEFAULT '',
  start_date TIMESTAMP DEFAULT NULL,
  date_created TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  date_modified TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  public INTEGER NOT NULL DEFAULT 0,
  company_department_id INTEGER DEFAULT NULL,
  is_admin_hidden INTEGER DEFAULT 0,
  openings_available INTEGER DEFAULT 0,
  questionnaire_id INTEGER DEFAULT NULL,
  import_id INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (joborder_id)
);

/*Data for the table joborder */

/*Table structure for table module_schema */

CREATE TABLE module_schema (
  module_schema_id SERIAL NOT NULL,
  name VARCHAR(64) DEFAULT NULL,
  version INTEGER DEFAULT NULL,
  PRIMARY KEY (module_schema_id)
);

/*Data for the table module_schema */

insert into module_schema(module_schema_id,name,version) values (1,'activity',0);
insert into module_schema(module_schema_id,name,version) values (2,'attachments',0);
insert into module_schema(module_schema_id,name,version) values (3,'calendar',0);
insert into module_schema(module_schema_id,name,version) values (4,'candidates',0);
insert into module_schema(module_schema_id,name,version) values (5,'careers',0);
insert into module_schema(module_schema_id,name,version) values (6,'companies',0);
insert into module_schema(module_schema_id,name,version) values (7,'contacts',0);
insert into module_schema(module_schema_id,name,version) values (8,'export',0);
insert into module_schema(module_schema_id,name,version) values (9,'extension-statistics',1);
insert into module_schema(module_schema_id,name,version) values (10,'graphs',0);
insert into module_schema(module_schema_id,name,version) values (11,'home',0);
insert into module_schema(module_schema_id,name,version) values (12,'import',0);
insert into module_schema(module_schema_id,name,version) values (13,'install',365);
insert into module_schema(module_schema_id,name,version) values (14,'joborders',0);
insert into module_schema(module_schema_id,name,version) values (15,'lists',0);
insert into module_schema(module_schema_id,name,version) values (16,'login',0);
insert into module_schema(module_schema_id,name,version) values (17,'queue',0);
insert into module_schema(module_schema_id,name,version) values (18,'reports',0);
insert into module_schema(module_schema_id,name,version) values (19,'rss',0);
insert into module_schema(module_schema_id,name,version) values (20,'settings',0);
insert into module_schema(module_schema_id,name,version) values (21,'tests',0);
insert into module_schema(module_schema_id,name,version) values (22,'toolbar',0);
insert into module_schema(module_schema_id,name,version) values (23,'wizard',0);
insert into module_schema(module_schema_id,name,version) values (24,'xml',0);

/*Table structure for table mru */

CREATE TABLE mru (
  mru_id SERIAL NOT NULL,
  user_id INTEGER DEFAULT NULL,
  site_id INTEGER NOT NULL DEFAULT 0,
  data_item_type INTEGER NOT NULL DEFAULT 0,
  data_item_text VARCHAR(64) NOT NULL DEFAULT '',
  url VARCHAR(255) NOT NULL DEFAULT '',
  date_created TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  PRIMARY KEY (mru_id)
);

/*Data for the table mru */

/*Table structure for table queue */

CREATE TABLE queue (
  queue_id SERIAL NOT NULL,
  site_id INTEGER NOT NULL,
  task VARCHAR(125) NOT NULL,
  args TEXT,
  priority SMALLINT NOT NULL DEFAULT 5,
  date_created TIMESTAMP NOT NULL,
  date_timeout TIMESTAMP NOT NULL,
  date_completed TIMESTAMP DEFAULT NULL,
  locked BOOLEAN NOT NULL DEFAULT FALSE,
  error BOOLEAN DEFAULT FALSE,
  response VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (queue_id)
);

/*Data for the table queue */

/*Table structure for table saved_list */

CREATE TABLE saved_list (
  saved_list_id SERIAL NOT NULL,
  description VARCHAR(64) NOT NULL DEFAULT '',
  data_item_type INTEGER NOT NULL DEFAULT 0,
  site_id INTEGER NOT NULL DEFAULT 0,
  is_dynamic INTEGER DEFAULT 0,
  datagrid_instance VARCHAR(64) DEFAULT '',
  parameters TEXT,
  created_by INTEGER DEFAULT 0,
  number_entries INTEGER DEFAULT 0,
  date_created TIMESTAMP DEFAULT NULL,
  date_modified TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (saved_list_id)
);

/*Data for the table saved_list */

/*Table structure for table saved_list_entry */

CREATE TABLE saved_list_entry (
  saved_list_entry_id SERIAL NOT NULL,
  saved_list_id INTEGER NOT NULL,
  data_item_type INTEGER NOT NULL DEFAULT 0,
  data_item_id INTEGER NOT NULL DEFAULT 0,
  site_id INTEGER NOT NULL DEFAULT 0,
  date_created TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (saved_list_entry_id)
);

/*Data for the table saved_list_entry */

/*Table structure for table saved_search */

CREATE TABLE saved_search (
  search_id SERIAL NOT NULL,
  data_item_text TEXT,
  url TEXT,
  is_custom INTEGER DEFAULT NULL,
  data_item_type INTEGER DEFAULT NULL,
  user_id INTEGER DEFAULT NULL,
  site_id INTEGER DEFAULT NULL,
  date_created TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (search_id)
);

/*Data for the table saved_search */

/*Table structure for table settings */

CREATE TABLE settings (
  settings_id SERIAL NOT NULL,
  setting VARCHAR(255) NOT NULL DEFAULT '',
  value VARCHAR(255) DEFAULT NULL,
  site_id INTEGER NOT NULL DEFAULT 0,
  settings_type INTEGER DEFAULT 0,
  PRIMARY KEY (settings_id)
);

/*Data for the table settings */

insert into settings(settings_id,setting,value,site_id,settings_type) values (1,'fromAddress','admin@testdomain.com',1,1);
insert into settings(settings_id,setting,value,site_id,settings_type) values (2,'fromAddress','admin@testdomain.com',180,1);
insert into settings(settings_id,setting,value,site_id,settings_type) values (3,'configured','1',1,1);
insert into settings(settings_id,setting,value,site_id,settings_type) values (4,'configured','1',180,1);

/*Table structure for table site */

CREATE TABLE site (
  site_id SERIAL NOT NULL,
  name VARCHAR(255) NOT NULL DEFAULT '',
  is_demo INTEGER NOT NULL DEFAULT 0,
  user_licenses INTEGER NOT NULL DEFAULT 0,
  entered_by INTEGER NOT NULL DEFAULT 0,
  date_created TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  unix_name VARCHAR(128) DEFAULT NULL,
  company_id INTEGER DEFAULT NULL,
  is_free INTEGER DEFAULT NULL,
  account_active INTEGER NOT NULL DEFAULT 1,
  account_deleted INTEGER NOT NULL DEFAULT 0,
  reason_disabled TEXT,
  time_zone INTEGER DEFAULT 0,
  time_format_24 INTEGER DEFAULT 0,
  date_format_ddmmyy INTEGER DEFAULT 0,
  default_phone_country_code VARCHAR(8) NOT NULL DEFAULT '+1',
  is_hr_mode INTEGER DEFAULT 0,
  file_size_kb INTEGER DEFAULT 0,
  page_views BIGINT DEFAULT 0,
  page_view_days INTEGER DEFAULT 0,
  last_viewed_day DATE DEFAULT NULL,
  first_time_setup SMALLINT DEFAULT 0,
  localization_configured INTEGER DEFAULT 0,
  agreed_to_license INTEGER DEFAULT 0,
  limit_warning BOOLEAN NOT NULL DEFAULT FALSE,
  PRIMARY KEY (site_id)
);

/*Data for the table site */

insert into site(site_id,name,is_demo,user_licenses,entered_by,date_created,unix_name,company_id,is_free,account_active,account_deleted,reason_disabled,time_zone,time_format_24,date_format_ddmmyy,default_phone_country_code,is_hr_mode,file_size_kb,page_views,page_view_days,last_viewed_day,first_time_setup,localization_configured,agreed_to_license,limit_warning) values (1,'testdomain.com',0,0,0,'2005-06-01 00:00:00',NULL,NULL,0,1,0,NULL,2,0,1,'+1',0,0,574,1,'2009-11-19',0,0,1,0);
insert into site(site_id,name,is_demo,user_licenses,entered_by,date_created,unix_name,company_id,is_free,account_active,account_deleted,reason_disabled,time_zone,time_format_24,date_format_ddmmyy,default_phone_country_code,is_hr_mode,file_size_kb,page_views,page_view_days,last_viewed_day,first_time_setup,localization_configured,agreed_to_license,limit_warning) values (180,'CATS_ADMIN',0,0,0,'2005-06-01 00:00:00','catsadmin',NULL,0,1,0,NULL,2,0,1,'+1',0,0,0,0,NULL,0,0,0,0);

/*Table structure for table sph_counter */

CREATE TABLE sph_counter (
  counter_id INTEGER NOT NULL,
  max_doc_id INTEGER NOT NULL,
  PRIMARY KEY (counter_id)
);

/*Data for the table sph_counter */

/*Table structure for table system */

CREATE TABLE system (
  system_id BIGINT NOT NULL DEFAULT 0,
  uid BIGINT DEFAULT NULL,
  available_version INTEGER DEFAULT 0,
  date_version_checked TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  available_version_description TEXT,
  disable_version_check INTEGER DEFAULT 0,
  PRIMARY KEY (system_id)
);

/*Data for the table system */

insert into system(system_id,uid,available_version,date_version_checked,available_version_description,disable_version_check) values (0,2618174,900,'2009-11-19 00:00:00','',1);

/*Table structure for table tag */

CREATE TABLE tag (
  tag_id SERIAL NOT NULL,
  tag_parent_id INTEGER DEFAULT NULL,
  title VARCHAR(255) DEFAULT NULL,
  description VARCHAR(500) DEFAULT NULL,
  site_id INTEGER DEFAULT NULL,
  date_created TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (tag_id)
);

/*Data for the table tag */

insert into tag(tag_id,tag_parent_id,title,description,site_id,date_created) values (1,NULL,'tag1','-',1,'2009-11-19 10:24:02');
insert into tag(tag_id,tag_parent_id,title,description,site_id,date_created) values (5,1,'tag13','-',1,'2009-11-19 12:06:08');
insert into tag(tag_id,tag_parent_id,title,description,site_id,date_created) values (77,1,'test tag','-',1,'2009-11-20 13:13:35');
insert into tag(tag_id,tag_parent_id,title,description,site_id,date_created) values (78,NULL,'tag2','-',1,'2009-11-20 13:13:42');
insert into tag(tag_id,tag_parent_id,title,description,site_id,date_created) values (79,78,'tag21','-',1,'2009-11-20 13:13:47');
insert into tag(tag_id,tag_parent_id,title,description,site_id,date_created) values (80,78,'tag22','-',1,'2009-11-20 13:13:50');
insert into tag(tag_id,tag_parent_id,title,description,site_id,date_created) values (81,78,'tag23','-',1,'2009-11-20 13:13:52');

/*Table structure for table user */

CREATE TABLE "user" (
  user_id SERIAL NOT NULL,
  site_id INTEGER NOT NULL DEFAULT 0,
  user_name VARCHAR(64) NOT NULL DEFAULT '',
  email VARCHAR(128) DEFAULT NULL,
  password VARCHAR(128) NOT NULL DEFAULT '',
  access_level INTEGER NOT NULL DEFAULT 100,
  can_change_password INTEGER NOT NULL DEFAULT 1,
  is_test_user INTEGER NOT NULL DEFAULT 0,
  last_name VARCHAR(40) NOT NULL DEFAULT '',
  first_name VARCHAR(40) NOT NULL DEFAULT '',
  is_demo INTEGER DEFAULT 0,
  categories VARCHAR(192) DEFAULT NULL,
  session_cookie VARCHAR(256) DEFAULT NULL,
  pipeline_entries_per_page INTEGER DEFAULT 15,
  column_preferences TEXT,
  force_logout INTEGER DEFAULT 0,
  title VARCHAR(64) DEFAULT '',
  phone_work VARCHAR(64) DEFAULT '',
  phone_cell VARCHAR(64) DEFAULT '',
  phone_other VARCHAR(64) DEFAULT '',
  address TEXT,
  notes TEXT,
  company VARCHAR(255) DEFAULT NULL,
  city VARCHAR(64) DEFAULT NULL,
  state VARCHAR(64) DEFAULT NULL,
  zip_code VARCHAR(16) DEFAULT NULL,
  country VARCHAR(128) DEFAULT NULL,
  can_see_eeo_info INTEGER DEFAULT 0,
  PRIMARY KEY (user_id)
);

/*Data for the table user */

insert into "user"(user_id,site_id,user_name,email,password,access_level,can_change_password,is_test_user,last_name,first_name,is_demo,categories,session_cookie,pipeline_entries_per_page,column_preferences,force_logout,title,phone_work,phone_cell,phone_other,address,notes,company,city,state,zip_code,country,can_see_eeo_info) values (1,1,'admin','admin@testdomain.com','admin',500,1,0,'Administrator','CATS',0,NULL,'CATS=e29233aabf2cdb71373582023ff9747e',15,NULL,0,'','','','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0);
insert into "user"(user_id,site_id,user_name,email,password,access_level,can_change_password,is_test_user,last_name,first_name,is_demo,categories,session_cookie,pipeline_entries_per_page,column_preferences,force_logout,title,phone_work,phone_cell,phone_other,address,notes,company,city,state,zip_code,country,can_see_eeo_info) values (1250,180,'cats@rootadmin','0','cantlogin',0,0,0,'Automated','CATS',0,NULL,NULL,15,NULL,0,'','','','',NULL,NULL,NULL,NULL,NULL,NULL,NULL,0);

/*Table structure for table user_login */

CREATE TABLE user_login (
  user_login_id SERIAL NOT NULL,
  user_id INTEGER DEFAULT NULL,
  site_id INTEGER NOT NULL DEFAULT 0,
  ip VARCHAR(128) NOT NULL DEFAULT '',
  user_agent VARCHAR(255) DEFAULT NULL,
  date TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  successful INTEGER NOT NULL DEFAULT 0,
  host VARCHAR(255) DEFAULT NULL,
  date_refreshed TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (user_login_id)
);

/*Data for the table user_login */

insert into user_login(user_login_id,user_id,site_id,ip,user_agent,date,successful,host,date_refreshed) values (1,1,1,'127.0.0.1','Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.9.1.5) Gecko/20091102 Firefox/3.5.5','2009-11-19 09:59:57',1,'127.0.0.1','2009-11-20 14:29:36');

/*Table structure for table word_verification */

CREATE TABLE word_verification (
  word_verification_ID SERIAL NOT NULL,
  word VARCHAR(28) NOT NULL DEFAULT '',
  PRIMARY KEY (word_verification_ID)
);

/*Data for the table word_verification */

/*Table structure for table xml_feed_submits */

CREATE TABLE xml_feed_submits (
  feed_id SERIAL NOT NULL,
  feed_site VARCHAR(75) NOT NULL,
  feed_url VARCHAR(255) NOT NULL,
  date_last_post DATE NOT NULL,
  PRIMARY KEY (feed_id)
);

/*Data for the table xml_feed_submits */

/*Table structure for table xml_feeds */

CREATE TABLE xml_feeds (
  xml_feed_id SERIAL NOT NULL,
  name VARCHAR(50) NOT NULL,
  description VARCHAR(255) DEFAULT NULL,
  website VARCHAR(255) DEFAULT NULL,
  post_url VARCHAR(255) NOT NULL,
  success_string VARCHAR(255) NOT NULL,
  xml_template_name VARCHAR(255) NOT NULL,
  PRIMARY KEY (xml_feed_id)
);

/*Data for the table xml_feeds */

insert into xml_feeds(xml_feed_id,name,description,website,post_url,success_string,xml_template_name) values (1,'Indeed','Indeed.com job search engine.','http://www.indeed.com','http://www.indeed.com/jsp/includejobs.jsp','Thank you for submitting your XML job feed','indeed');
insert into xml_feeds(xml_feed_id,name,description,website,post_url,success_string,xml_template_name) values (2,'SimplyHired','SimplyHired.com job search engine','http://www.simplyhired.com','http://www.simplyhired.com/confirmation.php','Thanks for Contacting Us','simplyhired');

/*Table structure for table zipcodes */

CREATE TABLE zipcodes (
  zipcode INTEGER NOT NULL DEFAULT 0,
  city TEXT NOT NULL,
  state VARCHAR(2) NOT NULL DEFAULT '',
  areacode SMALLINT NOT NULL DEFAULT 0,
  PRIMARY KEY (zipcode)
);

/*Data for the table zipcodes */

-- =====================================================================
-- UPGRADE: 0.5.0 to 0.5.1
-- =====================================================================

DROP TABLE IF EXISTS feedback;
ALTER TABLE candidate ADD COLUMN date_available TIMESTAMP;
ALTER TABLE "user" ALTER COLUMN user_name SET NOT NULL;
ALTER TABLE "user" ALTER COLUMN password SET NOT NULL;
UPDATE access_level SET long_description = 'Delete - All lower access, plus the ability to delete information on the system.' WHERE access_level_id = 300;

-- =====================================================================
-- UPGRADE: 0.5.1 to 0.5.2
-- =====================================================================

ALTER TABLE "user" DROP COLUMN IF EXISTS is_beta_tester;

DROP TABLE IF EXISTS address_parser_failures;

-- =====================================================================
-- UPGRADE: 0.5.2 to 0.5.5
-- =====================================================================

ALTER TABLE activity DROP INDEX IF EXISTS IDX_activity1;
-- (Index drops skipped - not applicable in PostgreSQL upgrade context)

CREATE TABLE IF NOT EXISTS admin_user (
  user_id SERIAL NOT NULL,
  user_name VARCHAR(40) NOT NULL DEFAULT '',
  password VARCHAR(10) NOT NULL DEFAULT '',
  disabled INTEGER NOT NULL DEFAULT 0,
  can_change_password INTEGER NOT NULL DEFAULT 1,
  last_name VARCHAR(40) NOT NULL DEFAULT '',
  first_name VARCHAR(40) NOT NULL DEFAULT '',
  PRIMARY KEY (user_id)
);

CREATE TABLE IF NOT EXISTS admin_user_login (
  user_login_id SERIAL NOT NULL,
  user_id INTEGER NOT NULL DEFAULT 0,
  ip VARCHAR(128) NOT NULL DEFAULT '',
  user_agent VARCHAR(255) DEFAULT NULL,
  date TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  successful INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (user_login_id)
);

CREATE TABLE IF NOT EXISTS candidate_status_type (
  candidate_status_type_id INTEGER NOT NULL DEFAULT 0,
  short_description VARCHAR(32) NOT NULL DEFAULT '',
  can_be_scheduled INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (candidate_status_type_id)
);

CREATE TABLE IF NOT EXISTS feedback (
  feedback_id SERIAL NOT NULL,
  user_id INTEGER NOT NULL DEFAULT 0,
  site_id INTEGER NOT NULL DEFAULT 0,
  date_created TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  subject VARCHAR(255) NOT NULL DEFAULT '',
  reply_to_address VARCHAR(255) NOT NULL DEFAULT '',
  reply_to_name VARCHAR(255) NOT NULL DEFAULT '',
  feedback TEXT NOT NULL,
  archived INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (feedback_id)
);

ALTER TABLE activity ADD COLUMN IF NOT EXISTS site_id INTEGER NOT NULL DEFAULT 1;
ALTER TABLE attachment ADD COLUMN IF NOT EXISTS site_id INTEGER NOT NULL DEFAULT 1;
ALTER TABLE calendar_event ADD COLUMN IF NOT EXISTS site_id INTEGER NOT NULL DEFAULT 1;
ALTER TABLE candidate ADD COLUMN IF NOT EXISTS can_relocate INTEGER NOT NULL DEFAULT 0;
ALTER TABLE candidate ADD COLUMN IF NOT EXISTS current_employer VARCHAR(128) DEFAULT NULL;
ALTER TABLE candidate ADD COLUMN IF NOT EXISTS email1 VARCHAR(128) DEFAULT NULL;
ALTER TABLE candidate ADD COLUMN IF NOT EXISTS email2 VARCHAR(128) DEFAULT NULL;
ALTER TABLE candidate ADD COLUMN IF NOT EXISTS web_site VARCHAR(128) DEFAULT NULL;
ALTER TABLE candidate_joborder ADD COLUMN IF NOT EXISTS site_id INTEGER NOT NULL DEFAULT 1;
ALTER TABLE "user" ADD COLUMN IF NOT EXISTS site_id INTEGER NOT NULL DEFAULT 1;
ALTER TABLE "user" ADD COLUMN IF NOT EXISTS email VARCHAR(128) DEFAULT NULL;
ALTER TABLE user_login ADD COLUMN IF NOT EXISTS site_id INTEGER NOT NULL DEFAULT 1;

INSERT INTO site VALUES (1, 'default_site', 0, 0, 0, NOW(), NULL, NULL, NULL, 1, 0, NULL, 0, 0, 0, '+1', 0, 0, 0, 0, NULL, 0, 0, 0, false) ON CONFLICT DO NOTHING;
UPDATE access_level SET access_level_id = -1 WHERE short_description = 'Disabled';

-- =====================================================================
-- UPGRADE: 0.5.5 to 0.6.x
-- =====================================================================

ALTER TABLE joborder ADD COLUMN IF NOT EXISTS public INTEGER NOT NULL DEFAULT 0;

CREATE TABLE IF NOT EXISTS candidate_foreign (
  alien_id SERIAL NOT NULL,
  assoc_id INTEGER DEFAULT NULL,
  field_name VARCHAR(255) DEFAULT NULL,
  value TEXT,
  import_id INTEGER DEFAULT NULL,
  PRIMARY KEY (alien_id)
);

CREATE TABLE IF NOT EXISTS client_foreign (
  alien_id SERIAL NOT NULL,
  assoc_id INTEGER DEFAULT NULL,
  field_name VARCHAR(255) DEFAULT NULL,
  value TEXT,
  import_id INTEGER DEFAULT NULL,
  PRIMARY KEY (alien_id)
);

CREATE TABLE IF NOT EXISTS contact_foreign (
  alien_id SERIAL NOT NULL,
  assoc_id INTEGER DEFAULT NULL,
  field_name VARCHAR(255) DEFAULT NULL,
  value TEXT,
  import_id INTEGER DEFAULT NULL,
  PRIMARY KEY (alien_id)
);

ALTER TABLE candidate ADD COLUMN IF NOT EXISTS import_id INTEGER NOT NULL DEFAULT 0;
ALTER TABLE contact ADD COLUMN IF NOT EXISTS import_id INTEGER NOT NULL DEFAULT 0;

INSERT INTO system (system_id, uid, date_version_checked) VALUES (0, 0, '2001-01-01') ON CONFLICT DO NOTHING;

ALTER TABLE calendar_event ADD COLUMN IF NOT EXISTS duration INTEGER NOT NULL DEFAULT 60;
ALTER TABLE calendar_event ADD COLUMN IF NOT EXISTS reminder_enabled INTEGER NOT NULL DEFAULT 0;
ALTER TABLE calendar_event ADD COLUMN IF NOT EXISTS reminder_email VARCHAR(255) DEFAULT '';
ALTER TABLE calendar_event ADD COLUMN IF NOT EXISTS reminder_time INTEGER NOT NULL DEFAULT 0;

UPDATE calendar_event SET duration = 60;

DROP TABLE IF EXISTS calendar_event_type;
CREATE TABLE calendar_event_type (
  calendar_event_type_id INTEGER NOT NULL DEFAULT 0,
  short_description VARCHAR(32) NOT NULL DEFAULT '',
  icon_image VARCHAR(255) DEFAULT NULL,
  PRIMARY KEY (calendar_event_type_id)
);
INSERT INTO calendar_event_type VALUES (100, 'Call', 'images/phone.gif');
INSERT INTO calendar_event_type VALUES (200, 'Email', 'images/email.gif');
INSERT INTO calendar_event_type VALUES (300, 'Meeting', 'images/meeting.gif');
INSERT INTO calendar_event_type VALUES (400, 'Interview', 'images/interview.gif');
INSERT INTO calendar_event_type VALUES (500, 'Personal', 'images/personal.gif');
INSERT INTO calendar_event_type VALUES (600, 'Other', NULL);

ALTER TABLE system ADD COLUMN IF NOT EXISTS schema_version INTEGER NOT NULL DEFAULT 0;
UPDATE system SET schema_version = 497;

UPDATE system SET schema_version = 501;
ALTER TABLE joborder ALTER COLUMN rate_max TYPE VARCHAR(255);

UPDATE system SET schema_version = 510;
CREATE TABLE IF NOT EXISTS saved_search (
  search_id SERIAL NOT NULL,
  data_item_text TEXT,
  url TEXT,
  is_custom INTEGER DEFAULT NULL,
  data_item_type INTEGER DEFAULT NULL,
  user_id INTEGER DEFAULT NULL,
  site_id INTEGER DEFAULT NULL,
  date_created TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (search_id)
);

UPDATE system SET schema_version = 518;
CREATE TABLE IF NOT EXISTS hot_list (
  hot_list_id SERIAL NOT NULL,
  hot_list_description VARCHAR(64) NOT NULL,
  hot_list_type INTEGER NOT NULL DEFAULT 0,
  site_id INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (hot_list_id)
);
ALTER TABLE candidate ADD COLUMN IF NOT EXISTS is_hot INTEGER NOT NULL DEFAULT 0;

UPDATE system SET schema_version = 520;
DROP TABLE IF EXISTS candidate_joborder_status;
CREATE TABLE candidate_joborder_status (
  candidate_status_id INTEGER NOT NULL DEFAULT 0,
  short_description VARCHAR(32) NOT NULL DEFAULT '',
  can_be_scheduled INTEGER NOT NULL DEFAULT 0,
  triggers_email INTEGER NOT NULL DEFAULT 1,
  is_enabled INTEGER NOT NULL DEFAULT 1,
  PRIMARY KEY (candidate_status_id)
);
INSERT INTO candidate_joborder_status VALUES (100, 'No Contact', 0, 1, 1);
INSERT INTO candidate_joborder_status VALUES (200, 'Contacted', 0, 1, 1);
INSERT INTO candidate_joborder_status VALUES (300, 'Negotiating', 0, 1, 1);
INSERT INTO candidate_joborder_status VALUES (400, 'Submitted', 0, 1, 1);
INSERT INTO candidate_joborder_status VALUES (500, 'Interviewing', 0, 1, 1);
INSERT INTO candidate_joborder_status VALUES (600, 'Offered', 0, 1, 1);
INSERT INTO candidate_joborder_status VALUES (700, 'Passed On', 0, 1, 1);
INSERT INTO candidate_joborder_status VALUES (800, 'Placed', 0, 1, 1);

CREATE TABLE IF NOT EXISTS candidate_joborder_status_history (
  candidate_joborder_status_history_id SERIAL NOT NULL,
  candidate_id INTEGER NOT NULL DEFAULT 0,
  joborder_id INTEGER NOT NULL DEFAULT 0,
  date TIMESTAMP NOT NULL DEFAULT '1000-01-01 00:00:00',
  status_from INTEGER NOT NULL DEFAULT 0,
  status_to INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (candidate_joborder_status_history_id)
);

ALTER TABLE candidate_joborder ADD COLUMN IF NOT EXISTS status INTEGER NOT NULL DEFAULT 0;

UPDATE system SET schema_version = 530;
DROP TABLE IF EXISTS client_department;
CREATE TABLE IF NOT EXISTS client_department (
  department_id SERIAL NOT NULL,
  name VARCHAR(128) DEFAULT NULL,
  client_id INTEGER NOT NULL,
  site_id INTEGER NOT NULL,
  date_created TIMESTAMP NOT NULL,
  created_by INTEGER DEFAULT NULL,
  PRIMARY KEY (department_id)
);

UPDATE system SET schema_version = 534;
DROP TABLE IF EXISTS candidate_source;
CREATE TABLE candidate_source (
  source_id SERIAL NOT NULL,
  name VARCHAR(255) DEFAULT NULL,
  site_id INTEGER DEFAULT NULL,
  date_created TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (source_id)
);

UPDATE system SET schema_version = 539;
ALTER TABLE contact ADD COLUMN IF NOT EXISTS department_id INTEGER NOT NULL DEFAULT 0;

UPDATE system SET schema_version = 552;
ALTER TABLE calendar_event ADD COLUMN IF NOT EXISTS public INTEGER NOT NULL DEFAULT 1;

UPDATE system SET schema_version = 555;
DROP TABLE IF EXISTS dashboard_module;
CREATE TABLE IF NOT EXISTS dashboard_module (
  dashboard_module_id SERIAL NOT NULL,
  object VARCHAR(255) DEFAULT NULL,
  name VARCHAR(255) DEFAULT NULL,
  function VARCHAR(255) DEFAULT NULL,
  title VARCHAR(255) DEFAULT NULL,
  description TEXT,
  preview_image VARCHAR(255) DEFAULT NULL,
  paramater_CSV TEXT,
  paramater_defaults TEXT,
  PRIMARY KEY (dashboard_module_id)
);

DROP TABLE IF EXISTS dashboard_component;
CREATE TABLE IF NOT EXISTS dashboard_component (
  dashboard_component_id SERIAL NOT NULL,
  module_name VARCHAR(255) DEFAULT NULL,
  module_paramaters TEXT,
  site_id INTEGER DEFAULT NULL,
  column_number INTEGER DEFAULT NULL,
  position INTEGER DEFAULT NULL,
  PRIMARY KEY (dashboard_component_id)
);

UPDATE system SET schema_version = 556;
ALTER TABLE candidate_joborder_status RENAME COLUMN candidate_status_id TO candidate_joborder_status_id;
INSERT INTO candidate_joborder_status VALUES (0, 'No Status', 0, 0, 1);

UPDATE system SET schema_version = 563;

UPDATE system SET schema_version = 567;
UPDATE candidate_joborder SET status = 400 WHERE status = 0;
TRUNCATE TABLE candidate_joborder_status_history;
ALTER TABLE candidate_joborder_status_history ADD COLUMN IF NOT EXISTS site_id INTEGER NOT NULL DEFAULT 0;
ALTER TABLE candidate_joborder DROP COLUMN IF EXISTS submitted;

UPDATE system SET schema_version = 572;
ALTER TABLE hot_list RENAME COLUMN hot_list_description TO description;
ALTER TABLE hot_list RENAME COLUMN hot_list_type TO data_item_type;

UPDATE system SET schema_version = 587;
CREATE TABLE IF NOT EXISTS calendar_settings (
  calendar_settings_id SERIAL NOT NULL,
  the_field VARCHAR(255) NOT NULL DEFAULT '',
  the_value VARCHAR(255) DEFAULT NULL,
  site_id INTEGER NOT NULL DEFAULT 0,
  entered_by INTEGER DEFAULT NULL,
  PRIMARY KEY (calendar_settings_id)
);

UPDATE system SET schema_version = 593;
CREATE TABLE IF NOT EXISTS candidate_foreign_settings (
  alien_id SERIAL NOT NULL,
  field_name VARCHAR(255) DEFAULT NULL,
  import_id INTEGER DEFAULT NULL,
  site_id INTEGER NOT NULL DEFAULT 0,
  date_created TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (alien_id)
);

UPDATE system SET schema_version = 595;
CREATE TABLE IF NOT EXISTS client_foreign_settings (
  alien_id SERIAL NOT NULL,
  field_name VARCHAR(255) DEFAULT NULL,
  import_id INTEGER DEFAULT NULL,
  site_id INTEGER NOT NULL DEFAULT 0,
  date_created TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (alien_id)
);

UPDATE system SET schema_version = 596;
CREATE TABLE IF NOT EXISTS contact_foreign_settings (
  alien_id SERIAL NOT NULL,
  field_name VARCHAR(255) DEFAULT NULL,
  import_id INTEGER DEFAULT NULL,
  site_id INTEGER NOT NULL DEFAULT 0,
  date_created TIMESTAMP DEFAULT NULL,
  PRIMARY KEY (alien_id)
);

UPDATE system SET schema_version = 601;
ALTER TABLE candidate DROP COLUMN IF EXISTS hot_list_id;
CREATE TABLE IF NOT EXISTS hot_list_entries (
  hot_list_id INTEGER NOT NULL DEFAULT 0,
  data_item_type INTEGER NOT NULL DEFAULT 0,
  data_item_id INTEGER NOT NULL DEFAULT 0
);

UPDATE system SET schema_version = 614;
ALTER TABLE user_login ADD COLUMN IF NOT EXISTS host VARCHAR(255) DEFAULT NULL;

UPDATE system SET schema_version = 673;
ALTER TABLE dashboard_module RENAME COLUMN paramater_CSV TO parameter_CSV;
ALTER TABLE dashboard_module RENAME COLUMN paramater_defaults TO parameter_defaults;
ALTER TABLE dashboard_component RENAME COLUMN module_paramaters TO module_parameters;

UPDATE system SET schema_version = 752;
CREATE TABLE IF NOT EXISTS email_template (
  email_template_id SERIAL NOT NULL,
  text TEXT,
  allow_substitution INTEGER NOT NULL DEFAULT 0,
  site_id INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (email_template_id)
);

UPDATE system SET schema_version = 753;
ALTER TABLE email_template ADD COLUMN IF NOT EXISTS tag VARCHAR(255) DEFAULT NULL;
ALTER TABLE email_template ADD COLUMN IF NOT EXISTS title VARCHAR(255) DEFAULT NULL;

UPDATE system SET schema_version = 760;
ALTER TABLE email_template ADD COLUMN IF NOT EXISTS possible_variables TEXT;

UPDATE system SET schema_version = 763;
ALTER TABLE candidate_joborder ADD COLUMN IF NOT EXISTS rating_value INTEGER DEFAULT NULL;

UPDATE system SET schema_version = 796;
INSERT INTO candidate_joborder_status VALUES (650, 'N/A', 0, 1, 1) ON CONFLICT DO NOTHING;
UPDATE candidate_joborder_status SET short_description = 'Rejected by Client' WHERE candidate_joborder_status_id = 700;
UPDATE candidate_joborder_status SET triggers_email = 0 WHERE candidate_joborder_status_id IN (650, 700);

UPDATE system SET schema_version = 800;
INSERT INTO candidate_joborder_status VALUES (250, 'Candidate Responded', 0, 1, 1) ON CONFLICT DO NOTHING;
UPDATE candidate_joborder_status SET triggers_email = 0 WHERE candidate_joborder_status_id = 200;
ALTER TABLE "user" ADD COLUMN IF NOT EXISTS is_demo INTEGER DEFAULT 0;

UPDATE system SET schema_version = 802;
ALTER TABLE email_template ADD COLUMN IF NOT EXISTS disabled INTEGER DEFAULT 0;

UPDATE system SET schema_version = 803;
UPDATE candidate_joborder_status SET short_description = 'Client Declined' WHERE candidate_joborder_status_id = 700;
UPDATE candidate_joborder_status SET short_description = 'Not in Consideration' WHERE candidate_joborder_status_id = 650;

UPDATE system SET schema_version = 804;
ALTER TABLE contact ALTER COLUMN left_company SET NOT NULL;
ALTER TABLE contact ALTER COLUMN left_company SET DEFAULT 0;

UPDATE system SET schema_version = 890;
CREATE TABLE IF NOT EXISTS mailer_settings (
  mailer_settings_id SERIAL NOT NULL,
  setting VARCHAR(255) NOT NULL DEFAULT '',
  value VARCHAR(255) DEFAULT NULL,
  site_id INTEGER NOT NULL DEFAULT 0,
  entered_by INTEGER DEFAULT NULL,
  PRIMARY KEY (mailer_settings_id)
);

UPDATE system SET schema_version = 900;
DROP TABLE IF EXISTS candidate_status_type;
DROP TABLE IF EXISTS candidate_joborder_status_type;

UPDATE system SET schema_version = 902;
ALTER TABLE system DROP COLUMN IF EXISTS local_version;

UPDATE system SET schema_version = 903;
DROP TABLE IF EXISTS email_history;
CREATE TABLE email_history (
  email_sent_id SERIAL NOT NULL,
  from_addr VARCHAR(128) DEFAULT NULL,
  to_addr VARCHAR(192) DEFAULT NULL,
  text TEXT,
  user_id INTEGER NOT NULL DEFAULT 0,
  site_id INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (email_sent_id)
);

UPDATE system SET schema_version = 904;
ALTER TABLE email_history ADD COLUMN IF NOT EXISTS date TIMESTAMP DEFAULT NULL;

-- =====================================================================
-- UPGRADE: 0.6.x to 0.7.0
-- =====================================================================

UPDATE system SET schema_version = 949;
CREATE TABLE IF NOT EXISTS history (
  history_id SERIAL NOT NULL,
  data_item_type INTEGER DEFAULT NULL,
  data_item_id INTEGER DEFAULT NULL,
  the_field VARCHAR(64) DEFAULT NULL,
  previous_value TEXT,
  new_value TEXT,
  description VARCHAR(192) DEFAULT NULL,
  set_date TIMESTAMP DEFAULT NULL,
  entered_by INTEGER DEFAULT NULL,
  site_id INTEGER NOT NULL DEFAULT 0,
  PRIMARY KEY (history_id)
);

UPDATE system SET schema_version = 950;
ALTER TABLE site ADD COLUMN IF NOT EXISTS unix_name VARCHAR(128) DEFAULT NULL;
ALTER TABLE site ADD COLUMN IF NOT EXISTS client_id INTEGER DEFAULT NULL;

UPDATE system SET schema_version = 951;
CREATE TABLE IF NOT EXISTS word_verification (
  word_verification_ID SERIAL NOT NULL,
  word VARCHAR(28) NOT NULL DEFAULT '',
  PRIMARY KEY (word_verification_ID)
);

UPDATE system SET schema_version = 952;
ALTER TABLE "user" ADD COLUMN IF NOT EXISTS categories VARCHAR(192) DEFAULT NULL;

UPDATE system SET schema_version = 953;
ALTER TABLE attachment ADD COLUMN IF NOT EXISTS profile_image INTEGER DEFAULT 0;

UPDATE system SET schema_version = 955;
ALTER TABLE "user" ADD COLUMN IF NOT EXISTS session_cookie VARCHAR(48) DEFAULT NULL;

UPDATE system SET schema_version = 957;
ALTER TABLE candidate_foreign ADD COLUMN IF NOT EXISTS site_id INTEGER DEFAULT 0;
ALTER TABLE client_foreign ADD COLUMN IF NOT EXISTS site_id INTEGER DEFAULT 0;
ALTER TABLE contact_foreign ADD COLUMN IF NOT EXISTS site_id INTEGER DEFAULT 0;

UPDATE system SET schema_version = 960;
ALTER TABLE site ADD COLUMN IF NOT EXISTS account_deleted INTEGER NOT NULL DEFAULT 0;

UPDATE system SET schema_version = 962;
ALTER TABLE site ADD COLUMN IF NOT EXISTS reason_disabled TEXT DEFAULT NULL;

UPDATE system SET schema_version = 1087;
-- email_history rename: email_sent_id -> email_history_id already handled above via table drop/recreate

UPDATE system SET schema_version = 1088;
ALTER TABLE user_login ADD COLUMN IF NOT EXISTS date_refreshed TIMESTAMP DEFAULT NULL;

UPDATE system SET schema_version = 1134;
ALTER TABLE joborder ADD COLUMN IF NOT EXISTS department_id INTEGER NOT NULL DEFAULT 0;

UPDATE system SET schema_version = 1143;
CREATE TABLE IF NOT EXISTS module_schema (
  module_schema_id SERIAL NOT NULL,
  name VARCHAR(64) DEFAULT NULL,
  version INTEGER DEFAULT NULL,
  PRIMARY KEY (module_schema_id)
);

UPDATE system SET schema_version = 1200;

-- =====================================================================
-- UPGRADE: 0.9.4 to 0.9.5
-- =====================================================================

/* new column in joborder table for import */
ALTER TABLE joborder
ADD COLUMN IF NOT EXISTS import_id INTEGER NOT NULL DEFAULT 0;

/* new table for candidate de-duplication */
CREATE TABLE IF NOT EXISTS candidate_duplicates (
  old_candidate_id INTEGER NOT NULL,
  new_candidate_id INTEGER NOT NULL,
  site_id INTEGER NOT NULL,
  PRIMARY KEY (old_candidate_id, new_candidate_id)
);

ALTER TABLE candidate
ALTER COLUMN web_site TYPE VARCHAR(352);

-- =====================================================================
-- UPGRADE: add-teams-meeting-link
-- =====================================================================

-- Add Microsoft Teams meeting link column to calendar_event table
-- This allows storing Teams meeting URLs for scheduled calls/meetings

ALTER TABLE calendar_event
ADD COLUMN IF NOT EXISTS teams_meeting_link TEXT NULL DEFAULT NULL;

-- =====================================================================
-- UPGRADE: interview-feedback
-- =====================================================================

/* Neutara ATS - Interview Feedback & Stage Tracking */

CREATE TABLE IF NOT EXISTS interview_feedback (
  feedback_id SERIAL NOT NULL,
  calendar_event_id INTEGER NOT NULL,
  candidate_id INTEGER NOT NULL,
  joborder_id INTEGER NOT NULL DEFAULT 0,
  interviewer_user_id INTEGER NOT NULL,
  interview_stage VARCHAR(32) NOT NULL DEFAULT 'L1',
  overall_rating INTEGER DEFAULT NULL,
  technical_rating INTEGER DEFAULT NULL,
  communication_rating INTEGER DEFAULT NULL,
  cultural_fit_rating INTEGER DEFAULT NULL,
  problem_solving_rating INTEGER DEFAULT NULL,
  strengths TEXT,
  weaknesses TEXT,
  notes TEXT,
  recommendation VARCHAR(50) DEFAULT NULL,
  status VARCHAR(50) NOT NULL DEFAULT 'pending',
  site_id INTEGER NOT NULL,
  date_created TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  date_modified TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (feedback_id)
);

-- =====================================================================
-- UPGRADE: candidate-documents
-- =====================================================================

-- Candidate Document Upload Portal
-- Creates tables for upload tokens and document tracking

CREATE TABLE IF NOT EXISTS candidate_upload_token (
    token_id SERIAL NOT NULL,
    candidate_id INTEGER NOT NULL,
    site_id INTEGER NOT NULL DEFAULT 1,
    token VARCHAR(64) NOT NULL,
    created_by INTEGER NOT NULL,
    created_date TIMESTAMP NOT NULL,
    expires_date TIMESTAMP NOT NULL,
    is_active BOOLEAN NOT NULL DEFAULT TRUE,
    max_uploads INTEGER NOT NULL DEFAULT 20,
    upload_count INTEGER NOT NULL DEFAULT 0,
    PRIMARY KEY (token_id),
    UNIQUE (token)
);

CREATE TABLE IF NOT EXISTS candidate_document (
    document_id SERIAL NOT NULL,
    candidate_id INTEGER NOT NULL,
    site_id INTEGER NOT NULL DEFAULT 1,
    token_id INTEGER DEFAULT NULL,
    document_type VARCHAR(50) NOT NULL DEFAULT 'other',
    original_filename VARCHAR(255) NOT NULL,
    stored_filename VARCHAR(255) NOT NULL,
    directory_name VARCHAR(255) NOT NULL,
    file_size_kb INTEGER NOT NULL DEFAULT 0,
    content_type VARCHAR(100) NOT NULL DEFAULT 'application/octet-stream',
    uploaded_date TIMESTAMP NOT NULL,
    status VARCHAR(50) NOT NULL DEFAULT 'pending',
    notes TEXT,
    PRIMARY KEY (document_id)
);

-- =====================================================================
-- UPGRADE: meeting-credentials
-- =====================================================================

-- Meeting Credentials Database Migration
-- Allows storing meeting platform credentials in database (configurable via Admin UI)

-- Create meeting_credentials table for storing platform API credentials
CREATE TABLE IF NOT EXISTS meeting_credentials (
    credential_id SERIAL NOT NULL,
    site_id INTEGER NOT NULL DEFAULT 1,
    platform VARCHAR(50) NOT NULL,
    credential_key VARCHAR(100) NOT NULL,
    credential_value TEXT,
    is_encrypted BOOLEAN NOT NULL DEFAULT TRUE,
    date_created TIMESTAMP NOT NULL,
    date_modified TIMESTAMP NOT NULL,
    PRIMARY KEY (credential_id),
    UNIQUE (site_id, platform, credential_key)
);

-- Add setting for credential storage mode
INSERT INTO settings (setting, value, site_id)
SELECT 'meeting_credentials_source', 'database', site_id
FROM site
WHERE NOT EXISTS (
    SELECT 1 FROM settings
    WHERE setting = 'meeting_credentials_source'
    AND settings.site_id = site.site_id
);

-- =====================================================================
-- UPGRADE: meeting-fields
-- =====================================================================

-- Upgrade script to add meeting-related fields to calendar_event table

-- Add attendee_email column to store the email of the meeting attendee
ALTER TABLE calendar_event
ADD COLUMN IF NOT EXISTS attendee_email VARCHAR(255) NULL DEFAULT NULL;

-- Add meeting_link column to store the meeting URL (Teams/Zoom/Google Meet)
ALTER TABLE calendar_event
ADD COLUMN IF NOT EXISTS meeting_link VARCHAR(500) NULL DEFAULT NULL;

-- Add meeting_platform column to store which platform was used
ALTER TABLE calendar_event
ADD COLUMN IF NOT EXISTS meeting_platform VARCHAR(50) NULL DEFAULT NULL;

-- =====================================================================
-- UPGRADE: interview-types
-- =====================================================================

-- Upgrade script to add L1, L2, L3, HR Interview types

-- Update existing Interview type to L1 Interview
UPDATE calendar_event_type
SET short_description = 'L1 Interview'
WHERE calendar_event_type_id = 400;

-- Add L2 Interview type
INSERT INTO calendar_event_type (calendar_event_type_id, short_description, icon_image)
VALUES (410, 'L2 Interview', 'images/interview.gif')
ON CONFLICT (calendar_event_type_id) DO UPDATE SET short_description = 'L2 Interview';

-- Add L3 Interview type
INSERT INTO calendar_event_type (calendar_event_type_id, short_description, icon_image)
VALUES (420, 'L3 Interview', 'images/interview.gif')
ON CONFLICT (calendar_event_type_id) DO UPDATE SET short_description = 'L3 Interview';

-- Add HR Interview type
INSERT INTO calendar_event_type (calendar_event_type_id, short_description, icon_image)
VALUES (430, 'HR Interview', 'images/interview.gif')
ON CONFLICT (calendar_event_type_id) DO UPDATE SET short_description = 'HR Interview';

-- =====================================================================
-- UPGRADE: user-roles
-- =====================================================================

-- Add role and interviewer_type columns to user table

-- Add role column (admin, recruiter, interviewer)
ALTER TABLE "user" ADD COLUMN IF NOT EXISTS role VARCHAR(20) DEFAULT 'recruiter';

-- Add interviewer_type column (L1, L2, L3, HR) - only used when role is 'interviewer'
ALTER TABLE "user" ADD COLUMN IF NOT EXISTS interviewer_type VARCHAR(10) DEFAULT NULL;

-- Update existing admin users (access_level 500 or 400) to have admin role
UPDATE "user" SET role = 'admin' WHERE access_level >= 400;

-- =====================================================================
-- UPGRADE: custom-statuses
-- =====================================================================

-- Custom Pipeline Status Migration
-- Adds custom recruitment workflow statuses

-- Screen Select (ID: 1000)
INSERT INTO candidate_joborder_status
(candidate_joborder_status_id, short_description, can_be_scheduled, triggers_email, is_enabled)
VALUES (1000, 'Screen Select', 0, 0, 1)
ON CONFLICT DO NOTHING;

-- Screen Reject (ID: 1010)
INSERT INTO candidate_joborder_status
(candidate_joborder_status_id, short_description, can_be_scheduled, triggers_email, is_enabled)
VALUES (1010, 'Screen Reject', 0, 0, 1)
ON CONFLICT DO NOTHING;

-- L1 Select (ID: 1020)
INSERT INTO candidate_joborder_status
(candidate_joborder_status_id, short_description, can_be_scheduled, triggers_email, is_enabled)
VALUES (1020, 'L1 Select', 0, 0, 1)
ON CONFLICT DO NOTHING;

-- L2 Select (ID: 1030)
INSERT INTO candidate_joborder_status
(candidate_joborder_status_id, short_description, can_be_scheduled, triggers_email, is_enabled)
VALUES (1030, 'L2 Select', 0, 0, 1)
ON CONFLICT DO NOTHING;

-- L3 Select (ID: 1040)
INSERT INTO candidate_joborder_status
(candidate_joborder_status_id, short_description, can_be_scheduled, triggers_email, is_enabled)
VALUES (1040, 'L3 Select', 0, 0, 1)
ON CONFLICT DO NOTHING;

-- Offer Release (ID: 1050)
INSERT INTO candidate_joborder_status
(candidate_joborder_status_id, short_description, can_be_scheduled, triggers_email, is_enabled)
VALUES (1050, 'Offer Release', 0, 0, 1)
ON CONFLICT DO NOTHING;

-- Onboarded (ID: 1060)
INSERT INTO candidate_joborder_status
(candidate_joborder_status_id, short_description, can_be_scheduled, triggers_email, is_enabled)
VALUES (1060, 'Onboarded', 0, 0, 1)
ON CONFLICT DO NOTHING;

-- No Show (ID: 1070)
INSERT INTO candidate_joborder_status
(candidate_joborder_status_id, short_description, can_be_scheduled, triggers_email, is_enabled)
VALUES (1070, 'No Show', 0, 0, 1)
ON CONFLICT DO NOTHING;
