<?php
/**
 * Settings for job-application email notifications.
 * Edit the values below, then re-upload this file.
 */

// The address that should receive every application.
define('APPLICATIONS_TO_EMAIL', 'info@codebrige.rw');
define('APPLICATIONS_TO_NAME', 'Codebridge Careers');

// The mailbox this is sent FROM (also used for the applicant's thank-you email).
define('APPLICATIONS_FROM_EMAIL', 'info@codebrige.rw');
define('APPLICATIONS_FROM_NAME', 'Codebridge Careers');

/**
 * SMTP settings — confirmed working.
 */
define('SMTP_ENABLED', true);
define('SMTP_HOST', 'mail.codebrige.rw');
define('SMTP_PORT', 465);
define('SMTP_SECURE', 'ssl');
define('SMTP_USERNAME', 'info@codebrige.rw');
define('SMTP_PASSWORD', 'blueband@');