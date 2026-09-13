<?php
return array (
  'web' => 
  array (
    'labels' => 'LLL:EXT:core/Resources/Private/Language/locallang_mod_web.xlf',
    'iconIdentifier' => 'modulegroup-web',
    'navigationComponent' => '@typo3/backend/page-tree/page-tree-element',
    'packageName' => 'typo3/cms-core',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/core/Classes/../',
  ),
  'file' => 
  array (
    'labels' => 'LLL:EXT:core/Resources/Private/Language/locallang_mod_file.xlf',
    'iconIdentifier' => 'modulegroup-file',
    'navigationComponent' => '@typo3/backend/tree/file-storage-tree-container',
    'packageName' => 'typo3/cms-core',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/core/Classes/../',
  ),
  'site' => 
  array (
    'labels' => 'LLL:EXT:core/Resources/Private/Language/locallang_mod_site.xlf',
    'workspaces' => 'live',
    'iconIdentifier' => 'modulegroup-site',
    'packageName' => 'typo3/cms-core',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/core/Classes/../',
  ),
  'user' => 
  array (
    'labels' => 'LLL:EXT:core/Resources/Private/Language/locallang_mod_usertools.xlf',
    'iconIdentifier' => 'modulegroup-user',
    'appearance' => 
    array (
      'renderInModuleMenu' => false,
    ),
    'packageName' => 'typo3/cms-core',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/core/Classes/../',
  ),
  'tools' => 
  array (
    'labels' => 'LLL:EXT:core/Resources/Private/Language/locallang_mod_admintools.xlf',
    'iconIdentifier' => 'modulegroup-tools',
    'packageName' => 'typo3/cms-core',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/core/Classes/../',
  ),
  'system' => 
  array (
    'labels' => 'LLL:EXT:core/Resources/Private/Language/locallang_mod_system.xlf',
    'iconIdentifier' => 'modulegroup-system',
    'packageName' => 'typo3/cms-core',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/core/Classes/../',
  ),
  'help' => 
  array (
    'labels' => 'LLL:EXT:core/Resources/Private/Language/locallang_mod_help.xlf',
    'iconIdentifier' => 'modulegroup-help',
    'appearance' => 
    array (
      'renderInModuleMenu' => false,
    ),
    'packageName' => 'typo3/cms-core',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/core/Classes/../',
  ),
  'media_management' => 
  array (
    'parent' => 'file',
    'access' => 'user',
    'path' => '/module/file/list',
    'iconIdentifier' => 'module-filelist',
    'labels' => 'LLL:EXT:filelist/Resources/Private/Language/locallang_mod_file_list.xlf',
    'aliases' => 
    array (
      0 => 'file_FilelistList',
    ),
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Filelist\\Controller\\FileListController::handleRequest',
      ),
    ),
    'moduleData' => 
    array (
      'displayThumbs' => true,
      'clipBoard' => true,
      'sort' => 'file',
      'reverse' => false,
      'viewMode' => 'tiles',
    ),
    'packageName' => 'typo3/cms-filelist',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/filelist/',
  ),
  'system_dbint' => 
  array (
    'parent' => 'system',
    'access' => 'admin',
    'workspaces' => 'live',
    'path' => '/module/system/dbint',
    'iconIdentifier' => 'module-dbint',
    'labels' => 
    array (
      'title' => 'LLL:EXT:lowlevel/Resources/Private/Language/locallang.xlf:module.dbint.title',
      'shortDescription' => 'LLL:EXT:lowlevel/Resources/Private/Language/locallang.xlf:module.dbint.shortDescription',
      'description' => 'LLL:EXT:lowlevel/Resources/Private/Language/locallang.xlf:module.dbint.description',
    ),
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Lowlevel\\Controller\\DatabaseIntegrityController::handleRequest',
      ),
    ),
    'packageName' => 'typo3/cms-lowlevel',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/lowlevel/',
  ),
  'system_config' => 
  array (
    'parent' => 'system',
    'access' => 'admin',
    'workspaces' => 'live',
    'path' => '/module/system/config',
    'iconIdentifier' => 'module-config',
    'labels' => 
    array (
      'title' => 'LLL:EXT:lowlevel/Resources/Private/Language/locallang.xlf:module.configuration.title',
      'shortDescription' => 'LLL:EXT:lowlevel/Resources/Private/Language/locallang.xlf:module.configuration.shortDescription',
      'description' => 'LLL:EXT:lowlevel/Resources/Private/Language/locallang.xlf:module.configuration.description',
    ),
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Lowlevel\\Controller\\ConfigurationController::indexAction',
      ),
    ),
    'moduleData' => 
    array (
      'tree' => '',
    ),
    'packageName' => 'typo3/cms-lowlevel',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/lowlevel/',
  ),
  'web_FormFormbuilder' => 
  array (
    'parent' => 'web',
    'access' => 'user',
    'iconIdentifier' => 'module-form',
    'inheritNavigationComponentFromMainModule' => false,
    'labels' => 'LLL:EXT:form/Resources/Private/Language/locallang_module.xlf',
    'extensionName' => 'Form',
    'controllerActions' => 
    array (
      'TYPO3\\CMS\\Form\\Controller\\FormManagerController' => 
      array (
        0 => 'index',
        1 => 'show',
        2 => 'create',
        3 => 'duplicate',
        4 => 'references',
        5 => 'delete',
      ),
      'TYPO3\\CMS\\Form\\Controller\\FormEditorController' => 
      array (
        0 => 'index',
        1 => 'saveForm',
        2 => 'renderFormPage',
      ),
    ),
    'packageName' => 'typo3/cms-form',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/form/',
  ),
  'tools_toolsmaintenance' => 
  array (
    'parent' => 'tools',
    'access' => 'systemMaintainer',
    'path' => '/module/tools/maintenance',
    'iconIdentifier' => 'module-install-maintenance',
    'labels' => 'LLL:EXT:install/Resources/Private/Language/ModuleInstallMaintenance.xlf',
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Install\\Controller\\BackendModuleController::maintenanceAction',
      ),
    ),
    'routeOptions' => 
    array (
      'sudoMode' => 
      array (
        'group' => 'systemMaintainer',
        'lifetime' => 
        \TYPO3\CMS\Backend\Security\SudoMode\Access\AccessLifetime::medium,
      ),
    ),
    'packageName' => 'typo3/cms-install',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/install/Classes/../',
  ),
  'tools_toolssettings' => 
  array (
    'parent' => 'tools',
    'access' => 'systemMaintainer',
    'path' => '/module/tools/settings',
    'iconIdentifier' => 'module-install-settings',
    'labels' => 'LLL:EXT:install/Resources/Private/Language/ModuleInstallSettings.xlf',
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Install\\Controller\\BackendModuleController::settingsAction',
      ),
    ),
    'routeOptions' => 
    array (
      'sudoMode' => 
      array (
        'group' => 'systemMaintainer',
        'lifetime' => 
        \TYPO3\CMS\Backend\Security\SudoMode\Access\AccessLifetime::medium,
      ),
    ),
    'packageName' => 'typo3/cms-install',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/install/Classes/../',
  ),
  'tools_toolsupgrade' => 
  array (
    'parent' => 'tools',
    'access' => 'systemMaintainer',
    'path' => '/module/tools/upgrade',
    'iconIdentifier' => 'module-install-upgrade',
    'labels' => 'LLL:EXT:install/Resources/Private/Language/ModuleInstallUpgrade.xlf',
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Install\\Controller\\BackendModuleController::upgradeAction',
      ),
    ),
    'routeOptions' => 
    array (
      'sudoMode' => 
      array (
        'group' => 'systemMaintainer',
        'lifetime' => 
        \TYPO3\CMS\Backend\Security\SudoMode\Access\AccessLifetime::medium,
      ),
    ),
    'packageName' => 'typo3/cms-install',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/install/Classes/../',
  ),
  'tools_toolsenvironment' => 
  array (
    'parent' => 'tools',
    'access' => 'systemMaintainer',
    'path' => '/module/tools/environment',
    'iconIdentifier' => 'module-install-environment',
    'labels' => 'LLL:EXT:install/Resources/Private/Language/ModuleInstallEnvironment.xlf',
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Install\\Controller\\BackendModuleController::environmentAction',
      ),
    ),
    'routeOptions' => 
    array (
      'sudoMode' => 
      array (
        'group' => 'systemMaintainer',
        'lifetime' => 
        \TYPO3\CMS\Backend\Security\SudoMode\Access\AccessLifetime::medium,
      ),
    ),
    'packageName' => 'typo3/cms-install',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/install/Classes/../',
  ),
  'web_info' => 
  array (
    'parent' => 'web',
    'access' => 'user',
    'path' => '/module/web/info',
    'iconIdentifier' => 'module-info',
    'labels' => 'LLL:EXT:info/Resources/Private/Language/locallang_mod_web_info.xlf',
    'navigationComponent' => '@typo3/backend/page-tree/page-tree-element',
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Info\\Controller\\InfoModuleController::handleRequest',
      ),
    ),
    'packageName' => 'typo3/cms-info',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/info/',
  ),
  'web_info_overview' => 
  array (
    'parent' => 'web_info',
    'access' => 'user',
    'path' => '/module/web/info/overview',
    'iconIdentifier' => 'module-info',
    'labels' => 
    array (
      'title' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:mod_tx_cms_webinfo_page',
    ),
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Info\\Controller\\PageInformationController::handleRequest',
      ),
    ),
    'moduleData' => 
    array (
      'pages' => '0',
      'depth' => 0,
    ),
    'packageName' => 'typo3/cms-info',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/info/',
  ),
  'web_info_translations' => 
  array (
    'parent' => 'web_info',
    'access' => 'user',
    'path' => '/module/web/info/translations',
    'iconIdentifier' => 'module-info',
    'labels' => 
    array (
      'title' => 'LLL:EXT:frontend/Resources/Private/Language/locallang_tca.xlf:mod_tx_cms_webinfo_lang',
    ),
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Info\\Controller\\TranslationStatusController::handleRequest',
      ),
    ),
    'moduleData' => 
    array (
      'depth' => 0,
      'lang' => 0,
    ),
    'packageName' => 'typo3/cms-info',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/info/',
  ),
  'web_linkvalidator' => 
  array (
    'parent' => 'web',
    'position' => 
    array (
      'after' => 'web_info',
    ),
    'access' => 'user',
    'workspaces' => 'live',
    'path' => '/module/page/link-reports',
    'iconIdentifier' => 'module-linkvalidator',
    'labels' => 'LLL:EXT:linkvalidator/Resources/Private/Language/Module/locallang_mod.xlf',
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Linkvalidator\\Controller\\LinkValidatorController',
      ),
    ),
    'moduleData' => 
    array (
      'action' => 'report',
    ),
    'packageName' => 'typo3/cms-linkvalidator',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/linkvalidator/',
  ),
  'system_reports' => 
  array (
    'parent' => 'system',
    'access' => 'admin',
    'path' => '/module/system/reports',
    'iconIdentifier' => 'module-reports',
    'labels' => 'LLL:EXT:reports/Resources/Private/Language/locallang.xlf',
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Reports\\Controller\\ReportController::handleRequest',
      ),
    ),
    'packageName' => 'typo3/cms-reports',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/reports/',
  ),
  'site_redirects' => 
  array (
    'parent' => 'site',
    'position' => 
    array (
      'after' => 'site_configuration',
    ),
    'access' => 'user',
    'path' => '/module/site/redirects',
    'iconIdentifier' => 'module-redirects',
    'labels' => 'LLL:EXT:redirects/Resources/Private/Language/locallang_module_redirect.xlf',
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Redirects\\Controller\\ManagementController::handleRequest',
      ),
    ),
    'packageName' => 'typo3/cms-redirects',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/redirects/',
  ),
  'user_setup' => 
  array (
    'parent' => 'user',
    'access' => 'user',
    'path' => '/module/user/setup',
    'iconIdentifier' => 'module-setup',
    'labels' => 'LLL:EXT:setup/Resources/Private/Language/locallang_mod.xlf',
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Setup\\Controller\\SetupModuleController::mainAction',
      ),
    ),
    'packageName' => 'typo3/cms-setup',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/setup/',
  ),
  'dashboard' => 
  array (
    'position' => 
    array (
      'before' => '*',
    ),
    'standalone' => true,
    'access' => 'user',
    'path' => '/module/dashboard',
    'iconIdentifier' => 'module-dashboard',
    'labels' => 'LLL:EXT:dashboard/Resources/Private/Language/locallang_mod.xlf',
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Dashboard\\Controller\\DashboardController::handleRequest',
      ),
    ),
    'packageName' => 'typo3/cms-dashboard',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/dashboard/Classes/../',
  ),
  'web_layout' => 
  array (
    'parent' => 'web',
    'position' => 
    array (
      'before' => '*',
    ),
    'access' => 'user',
    'path' => '/module/web/layout',
    'iconIdentifier' => 'module-page',
    'labels' => 'LLL:EXT:backend/Resources/Private/Language/locallang_mod.xlf',
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Backend\\Controller\\PageLayoutController::mainAction',
      ),
    ),
    'moduleData' => 
    array (
      'function' => 1,
      'language' => 0,
      'showHidden' => true,
    ),
    'packageName' => 'typo3/cms-backend',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/backend/Classes/../',
  ),
  'web_list' => 
  array (
    'parent' => 'web',
    'position' => 
    array (
      'after' => 'page_preview',
    ),
    'access' => 'user',
    'path' => '/module/web/list',
    'iconIdentifier' => 'module-list',
    'labels' => 'LLL:EXT:core/Resources/Private/Language/locallang_mod_web_list.xlf',
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Backend\\Controller\\RecordListController::mainAction',
      ),
    ),
    'moduleData' => 
    array (
      'clipBoard' => true,
      'searchBox' => false,
      'collapsedTables' => 
      array (
      ),
    ),
    'packageName' => 'typo3/cms-backend',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/backend/Classes/../',
  ),
  'site_configuration' => 
  array (
    'parent' => 'site',
    'position' => 
    array (
      'before' => '*',
    ),
    'access' => 'admin',
    'path' => '/module/site/configuration',
    'iconIdentifier' => 'module-sites',
    'labels' => 'LLL:EXT:backend/Resources/Private/Language/locallang_siteconfiguration_module.xlf',
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Backend\\Controller\\SiteConfigurationController::overviewAction',
      ),
      'edit' => 
      array (
        'target' => 'TYPO3\\CMS\\Backend\\Controller\\SiteConfigurationController::editAction',
      ),
      'save' => 
      array (
        'target' => 'TYPO3\\CMS\\Backend\\Controller\\SiteConfigurationController::saveAction',
        'methods' => 
        array (
          0 => 'POST',
        ),
      ),
      'delete' => 
      array (
        'target' => 'TYPO3\\CMS\\Backend\\Controller\\SiteConfigurationController::deleteAction',
        'methods' => 
        array (
          0 => 'POST',
        ),
      ),
    ),
    'packageName' => 'typo3/cms-backend',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/backend/Classes/../',
  ),
  'about' => 
  array (
    'parent' => 'help',
    'position' => 
    array (
      'before' => '*',
    ),
    'access' => 'user',
    'path' => '/module/help/about',
    'iconIdentifier' => 'module-about',
    'labels' => 'LLL:EXT:backend/Resources/Private/Language/Modules/about.xlf',
    'aliases' => 
    array (
      0 => 'help_AboutAbout',
    ),
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Backend\\Controller\\AboutController::handleRequest',
      ),
    ),
    'packageName' => 'typo3/cms-backend',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/backend/Classes/../',
  ),
  'pagetsconfig' => 
  array (
    'parent' => 'site',
    'access' => 'admin',
    'path' => '/module/pagetsconfig',
    'iconIdentifier' => 'module-tsconfig',
    'labels' => 
    array (
      'title' => 'LLL:EXT:backend/Resources/Private/Language/locallang_pagetsconfig.xlf:module.pagetsconfig.title',
      'description' => 'LLL:EXT:backend/Resources/Private/Language/locallang_pagetsconfig.xlf:module.pagetsconfig.description',
      'shortDescription' => 'LLL:EXT:backend/Resources/Private/Language/locallang_pagetsconfig.xlf:module.pagetsconfig.shortDescription',
    ),
    'navigationComponent' => '@typo3/backend/page-tree/page-tree-element',
    'packageName' => 'typo3/cms-backend',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/backend/Classes/../',
  ),
  'pagetsconfig_pages' => 
  array (
    'parent' => 'pagetsconfig',
    'access' => 'admin',
    'path' => '/module/pagetsconfig/records',
    'iconIdentifier' => 'module-tsconfig',
    'labels' => 
    array (
      'title' => 'LLL:EXT:backend/Resources/Private/Language/locallang_pagetsconfig.xlf:module.pagetsconfig_pages',
    ),
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Backend\\Controller\\PageTsConfig\\PageTsConfigRecordsOverviewController::handleRequest',
      ),
    ),
    'packageName' => 'typo3/cms-backend',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/backend/Classes/../',
  ),
  'pagetsconfig_active' => 
  array (
    'parent' => 'pagetsconfig',
    'access' => 'admin',
    'path' => '/module/pagetsconfig/active',
    'iconIdentifier' => 'module-tsconfig',
    'labels' => 
    array (
      'title' => 'LLL:EXT:backend/Resources/Private/Language/locallang_pagetsconfig.xlf:module.pagetsconfig_active',
    ),
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Backend\\Controller\\PageTsConfig\\PageTsConfigActiveController::handleRequest',
      ),
    ),
    'moduleData' => 
    array (
      'sortAlphabetically' => true,
      'displayComments' => true,
      'displayConstantSubstitutions' => true,
      'pageTsConfigConditions' => 
      array (
      ),
    ),
    'packageName' => 'typo3/cms-backend',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/backend/Classes/../',
  ),
  'pagetsconfig_includes' => 
  array (
    'parent' => 'pagetsconfig',
    'access' => 'admin',
    'path' => '/module/pagetsconfig/includes',
    'iconIdentifier' => 'module-tsconfig',
    'labels' => 
    array (
      'title' => 'LLL:EXT:backend/Resources/Private/Language/locallang_pagetsconfig.xlf:module.pagetsconfig_includes',
    ),
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Backend\\Controller\\PageTsConfig\\PageTsConfigIncludesController::indexAction',
      ),
      'source' => 
      array (
        'target' => 'TYPO3\\CMS\\Backend\\Controller\\PageTsConfig\\PageTsConfigIncludesController::sourceAction',
      ),
      'sourceWithIncludes' => 
      array (
        'target' => 'TYPO3\\CMS\\Backend\\Controller\\PageTsConfig\\PageTsConfigIncludesController::sourceWithIncludesAction',
      ),
    ),
    'moduleData' => 
    array (
      'pageTsConfigConditions' => 
      array (
      ),
    ),
    'packageName' => 'typo3/cms-backend',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/backend/Classes/../',
  ),
  'tools_csp' => 
  array (
    'parent' => 'tools',
    'access' => 'systemMaintainer',
    'iconIdentifier' => 'module-security',
    'labels' => 'LLL:EXT:backend/Resources/Private/Language/Modules/content-security-policy.xlf',
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Backend\\Security\\ContentSecurityPolicy\\CspModuleController::mainAction',
      ),
    ),
    'packageName' => 'typo3/cms-backend',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/backend/Classes/../',
  ),
  'system_BelogLog' => 
  array (
    'parent' => 'system',
    'access' => 'user',
    'iconIdentifier' => 'module-belog',
    'labels' => 'LLL:EXT:belog/Resources/Private/Language/locallang_mod.xlf',
    'extensionName' => 'Belog',
    'controllerActions' => 
    array (
      'TYPO3\\CMS\\Belog\\Controller\\BackendLogController' => 
      array (
        0 => 'list',
        1 => 'deleteMessage',
      ),
    ),
    'packageName' => 'typo3/cms-belog',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/belog/',
  ),
  'permissions_pages' => 
  array (
    'parent' => 'system',
    'position' => 
    array (
      'before' => '*',
    ),
    'access' => 'admin',
    'path' => '/module/system/permissions',
    'iconIdentifier' => 'module-permission',
    'navigationComponent' => '@typo3/backend/page-tree/page-tree-element',
    'labels' => 'LLL:EXT:beuser/Resources/Private/Language/locallang_mod_permission.xlf',
    'aliases' => 
    array (
      0 => 'system_BeuserTxPermission',
    ),
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Beuser\\Controller\\PermissionController::handleRequest',
      ),
    ),
    'packageName' => 'typo3/cms-beuser',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/beuser/',
  ),
  'backend_user_management' => 
  array (
    'parent' => 'system',
    'position' => 
    array (
      'after' => 'permissions_pages',
    ),
    'access' => 'admin',
    'iconIdentifier' => 'module-beuser',
    'labels' => 'LLL:EXT:beuser/Resources/Private/Language/locallang_mod.xlf',
    'extensionName' => 'Beuser',
    'aliases' => 
    array (
      0 => 'system_BeuserTxBeuser',
    ),
    'controllerActions' => 
    array (
      'TYPO3\\CMS\\Beuser\\Controller\\BackendUserController' => 
      array (
        0 => 'index',
        1 => 'list',
        2 => 'show',
        3 => 'addToCompareList',
        4 => 'removeFromCompareList',
        5 => 'removeAllFromCompareList',
        6 => 'compare',
        7 => 'online',
        8 => 'terminateBackendUserSession',
        9 => 'initiatePasswordReset',
        10 => 'groups',
        11 => 'addGroupToCompareList',
        12 => 'removeGroupFromCompareList',
        13 => 'removeAllGroupsFromCompareList',
        14 => 'compareGroups',
        15 => 'filemounts',
      ),
    ),
    'packageName' => 'typo3/cms-beuser',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/beuser/',
  ),
  'tools_ExtensionmanagerExtensionmanager' => 
  array (
    'parent' => 'tools',
    'access' => 'systemMaintainer',
    'iconIdentifier' => 'module-extensionmanager',
    'labels' => 'LLL:EXT:extensionmanager/Resources/Private/Language/locallang_mod.xlf',
    'extensionName' => 'Extensionmanager',
    'controllerActions' => 
    array (
      'TYPO3\\CMS\\Extensionmanager\\Controller\\ListController' => 
      array (
        0 => 'index',
        1 => 'unresolvedDependencies',
        2 => 'ter',
        3 => 'showAllVersions',
        4 => 'distributions',
      ),
      'TYPO3\\CMS\\Extensionmanager\\Controller\\ActionController' => 
      array (
        0 => 'toggleExtensionInstallationState',
        1 => 'installExtensionWithoutSystemDependencyCheck',
        2 => 'removeExtension',
        3 => 'downloadExtensionZip',
        4 => 'reloadExtensionData',
      ),
      'TYPO3\\CMS\\Extensionmanager\\Controller\\DownloadController' => 
      array (
        0 => 'checkDependencies',
        1 => 'installFromTer',
        2 => 'installExtensionWithoutSystemDependencyCheck',
        3 => 'installDistribution',
        4 => 'updateExtension',
        5 => 'updateCommentForUpdatableVersions',
      ),
      'TYPO3\\CMS\\Extensionmanager\\Controller\\UpdateFromTerController' => 
      array (
        0 => 'updateExtensionListFromTer',
      ),
      'TYPO3\\CMS\\Extensionmanager\\Controller\\UploadExtensionFileController' => 
      array (
        0 => 'form',
        1 => 'extract',
      ),
      'TYPO3\\CMS\\Extensionmanager\\Controller\\DistributionController' => 
      array (
        0 => 'show',
      ),
      'TYPO3\\CMS\\Extensionmanager\\Controller\\ExtensionComposerStatusController' => 
      array (
        0 => 'list',
        1 => 'detail',
      ),
    ),
    'routeOptions' => 
    array (
      'sudoMode' => 
      array (
        'group' => 'systemMaintainer',
        'lifetime' => 
        \TYPO3\CMS\Backend\Security\SudoMode\Access\AccessLifetime::medium,
      ),
    ),
    'moduleData' => 
    array (
      'filter' => '',
    ),
    'packageName' => 'typo3/cms-extensionmanager',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/extensionmanager/',
  ),
  'web_ts' => 
  array (
    'parent' => 'site',
    'access' => 'admin',
    'path' => '/module/web/ts',
    'iconIdentifier' => 'module-template',
    'labels' => 
    array (
      'title' => 'LLL:EXT:tstemplate/Resources/Private/Language/locallang.xlf:module.typoscript.title',
      'shortDescription' => 'LLL:EXT:tstemplate/Resources/Private/Language/locallang.xlf:module.typoscript.shortDescription',
      'description' => 'LLL:EXT:tstemplate/Resources/Private/Language/locallang.xlf:module.typoscript.description',
    ),
    'navigationComponent' => '@typo3/backend/page-tree/page-tree-element',
    'packageName' => 'typo3/cms-tstemplate',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/tstemplate/',
  ),
  'web_typoscript_recordsoverview' => 
  array (
    'parent' => 'web_ts',
    'access' => 'admin',
    'path' => '/module/web/typoscript/records-overview',
    'iconIdentifier' => 'module-template',
    'labels' => 
    array (
      'title' => 'LLL:EXT:tstemplate/Resources/Private/Language/locallang.xlf:submodules.option.templateRecordsOverview',
    ),
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Tstemplate\\Controller\\TemplateRecordsOverviewController::handleRequest',
      ),
    ),
    'packageName' => 'typo3/cms-tstemplate',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/tstemplate/',
  ),
  'web_typoscript_constanteditor' => 
  array (
    'parent' => 'web_ts',
    'access' => 'admin',
    'path' => '/module/web/typoscript/constant-editor',
    'iconIdentifier' => 'module-template',
    'labels' => 
    array (
      'title' => 'LLL:EXT:tstemplate/Resources/Private/Language/locallang.xlf:submodules.option.constantEditor',
    ),
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Tstemplate\\Controller\\ConstantEditorController::handleRequest',
      ),
    ),
    'moduleData' => 
    array (
      'selectedTemplatePerPage' => 
      array (
      ),
      'selectedCategory' => '',
    ),
    'packageName' => 'typo3/cms-tstemplate',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/tstemplate/',
  ),
  'web_typoscript_infomodify' => 
  array (
    'parent' => 'web_ts',
    'access' => 'admin',
    'path' => '/module/web/typoscript/overview',
    'iconIdentifier' => 'module-template',
    'labels' => 
    array (
      'title' => 'LLL:EXT:tstemplate/Resources/Private/Language/locallang.xlf:submodules.option.infoModify',
    ),
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Tstemplate\\Controller\\InfoModifyController::handleRequest',
      ),
    ),
    'moduleData' => 
    array (
      'selectedTemplatePerPage' => 
      array (
      ),
    ),
    'packageName' => 'typo3/cms-tstemplate',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/tstemplate/',
  ),
  'typoscript_active' => 
  array (
    'parent' => 'web_ts',
    'access' => 'admin',
    'path' => '/module/typoscript/active',
    'iconIdentifier' => 'module-template',
    'labels' => 
    array (
      'title' => 'LLL:EXT:tstemplate/Resources/Private/Language/locallang.xlf:submodules.option.active',
    ),
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Tstemplate\\Controller\\ActiveTypoScriptController::indexAction',
      ),
      'edit' => 
      array (
        'target' => 'TYPO3\\CMS\\Tstemplate\\Controller\\ActiveTypoScriptController::editAction',
      ),
      'update' => 
      array (
        'target' => 'TYPO3\\CMS\\Tstemplate\\Controller\\ActiveTypoScriptController::updateAction',
        'methods' => 
        array (
          0 => 'POST',
        ),
      ),
    ),
    'moduleData' => 
    array (
      'sortAlphabetically' => true,
      'displayConstantSubstitutions' => true,
      'displayComments' => true,
      'selectedTemplatePerPage' => 
      array (
      ),
      'constantConditions' => 
      array (
      ),
      'setupConditions' => 
      array (
      ),
    ),
    'packageName' => 'typo3/cms-tstemplate',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/tstemplate/',
  ),
  'web_typoscript_analyzer' => 
  array (
    'parent' => 'web_ts',
    'access' => 'admin',
    'path' => '/module/web/typoscript/analyzer',
    'iconIdentifier' => 'module-template',
    'labels' => 
    array (
      'title' => 'LLL:EXT:tstemplate/Resources/Private/Language/locallang.xlf:submodules.option.templateAnalyzer',
    ),
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Tstemplate\\Controller\\TemplateAnalyzerController::indexAction',
      ),
      'source' => 
      array (
        'target' => 'TYPO3\\CMS\\Tstemplate\\Controller\\TemplateAnalyzerController::sourceAction',
      ),
      'sourceWithIncludes' => 
      array (
        'target' => 'TYPO3\\CMS\\Tstemplate\\Controller\\TemplateAnalyzerController::sourceWithIncludesAction',
      ),
    ),
    'moduleData' => 
    array (
      'selectedTemplatePerPage' => 
      array (
      ),
      'constantConditions' => 
      array (
      ),
      'setupConditions' => 
      array (
      ),
    ),
    'packageName' => 'typo3/cms-tstemplate',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/tstemplate/',
  ),
  'page_preview' => 
  array (
    'parent' => 'web',
    'position' => 
    array (
      'after' => 'web_layout',
    ),
    'access' => 'user',
    'path' => '/module/web/viewpage',
    'iconIdentifier' => 'module-viewpage',
    'labels' => 'LLL:EXT:viewpage/Resources/Private/Language/locallang_mod.xlf',
    'aliases' => 
    array (
      0 => 'web_ViewpageView',
    ),
    'routes' => 
    array (
      '_default' => 
      array (
        'target' => 'TYPO3\\CMS\\Viewpage\\Controller\\ViewModuleController::handleRequest',
      ),
    ),
    'moduleData' => 
    array (
      'language' => 0,
    ),
    'packageName' => 'typo3/cms-viewpage',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3/sysext/viewpage/',
  ),
  'web_newsAdministration' => 
  array (
    'parent' => 'web',
    'position' => 
    array (
      'after' => '*',
    ),
    'access' => 'user,group',
    'path' => '/module/web/NewsAdministration/',
    'iconIdentifier' => 'ext-news-module-administration',
    'labels' => 'LLL:EXT:news/Resources/Private/Language/locallang_modadministration.xlf',
    'extensionName' => 'News',
    'controllerActions' => 
    array (
      'GeorgRinger\\News\\Controller\\AdministrationController' => 
      array (
        0 => 'index',
        1 => 'newNews',
        2 => 'newCategory',
        3 => 'newTag',
        4 => 'newsPidListing',
        5 => 'donate',
      ),
    ),
    'packageName' => 'georgringer/news',
    'absolutePackagePath' => '/home/codebrig/public_html/typo3conf/ext/news/',
  ),
);
#