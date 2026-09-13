<?php
return array (
  'BE' => 
  array (
    'debug' => false,
    'explicitADmode' => 'explicitAllow',
    'installToolPassword' => '$argon2i$v=19$m=65536,t=16,p=1$QmVZRkFSUmRrSTVsbjZRaw$3J+C6AOn8mr8gSDl5WpTP4wfZiMAeslPb7s0RCiNkfc',
    'loginSecurityLevel' => 'normal',
    'passwordHashing' => 
    array (
      'className' => 'TYPO3\\CMS\\Core\\Crypto\\PasswordHashing\\Argon2iPasswordHash',
      'options' => 
      array (
      ),
    ),
  ),
  'DB' => 
  array (
    'Connections' => 
    array (
      'Default' => 
      array (
        'charset' => 'utf8mb4',
        'dbname' => 'codebrig_codebrige',
        'driver' => 'mysqli',
        'host' => 'localhost',
        'password' => 'Codebrig+250',
        'port' => 3306,
        'user' => 'codebrig_codebrig',
      ),
    ),
  ),
  'EXTENSIONS' => 
  array (
    'backend' => 
    array (
      'backendFavicon' => '',
      'backendLogo' => 'fileadmin/site/resources/public/images/Logo1_final.jpeg',
      'loginBackgroundImage' => '',
      'loginFootnote' => '',
      'loginHighlightColor' => '#1a1a4d',
      'loginLogo' => 'fileadmin/site/resources/public/images/Logo1_final.jpeg',
      'loginLogoAlt' => 'CodeBridge',
    ),
    'extensionmanager' => 
    array (
      'automaticInstallation' => '1',
      'offlineMode' => '0',
    ),
  ),
  'FE' => 
  array (
    'cacheHash' => 
    array (
      'enforceValidation' => true,
    ),
    'debug' => false,
    'disableNoCacheParameter' => true,
    'passwordHashing' => 
    array (
      'className' => 'TYPO3\\CMS\\Core\\Crypto\\PasswordHashing\\Argon2iPasswordHash',
      'options' => 
      array (
      ),
    ),
  ),
  'LOG' => 
  array (
    'TYPO3' => 
    array (
      'CMS' => 
      array (
        'deprecations' => 
        array (
          'writerConfiguration' => 
          array (
            'notice' => 
            array (
              'TYPO3\\CMS\\Core\\Log\\Writer\\FileWriter' => 
              array (
                'disabled' => true,
              ),
            ),
          ),
        ),
      ),
    ),
  ),
  'MAIL' => 
  array (
    'transport' => 'smtp',
    'transport_sendmail_command' => '',
    'transport_smtp_encrypt' => false,
    'transport_smtp_password' => '',
    'transport_smtp_server' => 'localhost:25',
    'transport_smtp_username' => '',
  ),
  'SYS' => 
  array (
    'caching' => 
    array (
      'cacheConfigurations' => 
      array (
        'hash' => 
        array (
          'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
        ),
        'imagesizes' => 
        array (
          'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
          'options' => 
          array (
            'compression' => true,
          ),
        ),
        'pages' => 
        array (
          'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
          'options' => 
          array (
            'compression' => true,
          ),
        ),
        'pagesection' => 
        array (
          'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
          'options' => 
          array (
            'compression' => true,
          ),
        ),
        'rootline' => 
        array (
          'backend' => 'TYPO3\\CMS\\Core\\Cache\\Backend\\Typo3DatabaseBackend',
          'options' => 
          array (
            'compression' => true,
          ),
        ),
      ),
    ),
    'devIPmask' => '',
    'displayErrors' => 0,
    'encryptionKey' => 'b2a1932259f97e015d7da170c65dbe2d03e1127901f951d4bbebbc63346999e5c079edaab4c6bcc1e1da21e1780e3942',
    'exceptionalErrors' => 4096,
    'features' => 
    array (
      'felogin.extbase' => true,
      'fluidBasedPageModule' => true,
      'rearrangedRedirectMiddlewares' => true,
      'unifiedPageTranslationHandling' => true,
      'yamlImportsFollowDeclarationOrder' => true,
    ),
    'sitename' => 'MHA Tech',
    'systemMaintainers' => 
    array (
      0 => 1,
    ),
  ),
);
