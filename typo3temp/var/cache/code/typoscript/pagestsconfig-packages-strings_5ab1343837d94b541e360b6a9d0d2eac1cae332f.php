<?php
return unserialize('a:11:{s:26:"pagesTsConfig-package-core";a:2:{s:8:"filename";s:72:"/home/codebrig/public_html/typo3/sysext/core/Configuration/page.tsconfig";s:7:"content";s:46:"TCEMAIN.translateToMessage = Translate to %s:
";}s:30:"pagesTsConfig-package-filelist";a:2:{s:8:"filename";s:76:"/home/codebrig/public_html/typo3/sysext/filelist/Configuration/page.tsconfig";s:7:"content";s:477:"# Register link handlers
TCEMAIN.linkHandler {
  file {
    handler = TYPO3\\CMS\\Filelist\\LinkHandler\\FileLinkHandler
    label = LLL:EXT:backend/Resources/Private/Language/locallang_browse_links.xlf:file
    displayAfter = page
    scanAfter = page
  }
  folder {
    handler = TYPO3\\CMS\\Filelist\\LinkHandler\\FolderLinkHandler
    label = LLL:EXT:backend/Resources/Private/Language/locallang_browse_links.xlf:folder
    displayAfter = page,file
    scanAfter = page,file
  }
}
";}s:30:"pagesTsConfig-package-frontend";a:2:{s:8:"filename";s:76:"/home/codebrig/public_html/typo3/sysext/frontend/Configuration/page.tsconfig";s:7:"content";s:9260:"TCEFORM.tt_content {
  imageorient {
    types {
      image {
        removeItems = 8,9,10,17,18,25,26
      }
    }
  }
}

mod.wizards.newContentElement.wizardItems {
  common.elements {
    header {
      iconIdentifier = content-header
      title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:common_headerOnly_title
      description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:common_headerOnly_description
      tt_content_defValues {
        CType = header
      }
    }
    text {
      iconIdentifier = content-text
      title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:common_regularText_title
      description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:common_regularText_description
      tt_content_defValues {
        CType = text
      }
    }
    textpic {
      iconIdentifier = content-textpic
      title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:common_textImage_title
      description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:common_textImage_description
      tt_content_defValues {
        CType = textpic
      }
    }
    image {
      iconIdentifier = content-image
      title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:common_imagesOnly_title
      description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:common_imagesOnly_description
      tt_content_defValues {
        CType = image
      }
    }
    textmedia {
      iconIdentifier = content-textmedia
      title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:common_textMedia_title
      description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:common_textMedia_description
      tt_content_defValues {
        CType = textmedia
      }
    }
    bullets {
      iconIdentifier = content-bullets
      title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:common_bulletList_title
      description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:common_bulletList_description
      tt_content_defValues {
        CType = bullets
      }
    }
    table {
      iconIdentifier = content-table
      title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:common_table_title
      description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:common_table_description
      tt_content_defValues {
        CType = table
      }
    }
    uploads {
      iconIdentifier = content-special-uploads
      title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:special_filelinks_title
      description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:special_filelinks_description
      tt_content_defValues {
        CType = uploads
      }
    }
  }
  common.show := addToList(header)
  common.show := addToList(text)
  common.show := addToList(textpic)
  common.show := addToList(image)
  common.show := addToList(textmedia)
  common.show := addToList(bullets)
  common.show := addToList(table)
  common.show := addToList(uploads)

  menu.elements {
    menu_abstract {
      iconIdentifier = content-menu-abstract
      title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu_abstract.title
      description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu_abstract.description
      tt_content_defValues {
        CType = menu_abstract
      }
    }
    menu_categorized_content {
      iconIdentifier = content-menu-categorized
      title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu_categorized_content.title
      description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu_categorized_content.description
      tt_content_defValues {
        CType = menu_categorized_content
      }
    }
    menu_categorized_pages {
      iconIdentifier = content-menu-categorized
      title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu_categorized_pages.title
      description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu_categorized_pages.description
      tt_content_defValues {
        CType = menu_categorized_pages
      }
    }
    menu_pages {
      iconIdentifier = content-menu-pages
      title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu_pages.title
      description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu_pages.description
      tt_content_defValues {
        CType = menu_pages
      }
    }
    menu_subpages {
      iconIdentifier = content-menu-pages
      title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu_subpages.title
      description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu_subpages.description
      tt_content_defValues {
        CType = menu_subpages
      }
    }
    menu_recently_updated {
      iconIdentifier = content-menu-recently-updated
      title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu_recently_updated.title
      description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu_recently_updated.description
      tt_content_defValues {
        CType = menu_recently_updated
      }
    }
    menu_related_pages {
      iconIdentifier = content-menu-related
      title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu_related_pages.title
      description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu_related_pages.description
      tt_content_defValues {
        CType = menu_related_pages
      }
    }
    menu_section {
      iconIdentifier = content-menu-section
      title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu_section.title
      description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu_section.description
      tt_content_defValues {
        CType = menu_section
      }
    }
    menu_section_pages {
      iconIdentifier = content-menu-section
      title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu_section_pages.title
      description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu_section_pages.description
      tt_content_defValues {
        CType = menu_section_pages
      }
    }
    menu_sitemap {
      iconIdentifier = content-menu-sitemap
      title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu_sitemap.title
      description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu_sitemap.description
      tt_content_defValues {
        CType = menu_sitemap
      }
    }
    menu_sitemap_pages {
      iconIdentifier = content-menu-sitemap-pages
      title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu_sitemap_pages.title
      description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu_sitemap_pages.description
      tt_content_defValues {
        CType = menu_sitemap_pages
      }
    }
  }
  menu.show := addToList(menu_abstract)
  menu.show := addToList(menu_categorized_content)
  menu.show := addToList(menu_categorized_pages)
  menu.show := addToList(menu_pages)
  menu.show := addToList(menu_subpages)
  menu.show := addToList(menu_recently_updated)
  menu.show := addToList(menu_related_pages)
  menu.show := addToList(menu_section)
  menu.show := addToList(menu_section_pages)
  menu.show := addToList(menu_sitemap)
  menu.show := addToList(menu_sitemap_pages)

  special.elements {
    html {
      iconIdentifier = content-special-html
      title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:special_plainHTML_title
      description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:special_plainHTML_description
      tt_content_defValues {
        CType = html
      }
    }
    div {
      iconIdentifier = content-special-div
      title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:special_divider_title
      description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:special_divider_description
      tt_content_defValues {
        CType = div
      }
      saveAndClose = true
    }
    shortcut {
      iconIdentifier = content-special-shortcut
      title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:special_shortcut_title
      description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:special_shortcut_description
      tt_content_defValues {
        CType = shortcut
      }
    }
  }
  special.show := addToList(html)
  special.show := addToList(div)
  special.show := addToList(shortcut)
}
";}s:26:"pagesTsConfig-package-form";a:2:{s:8:"filename";s:72:"/home/codebrig/public_html/typo3/sysext/form/Configuration/page.tsconfig";s:7:"content";s:429:"mod.wizards.newContentElement.wizardItems.forms {
  show :=addToList(formframework)
  elements {
    formframework {
      iconIdentifier = content-form
      title = LLL:EXT:form/Resources/Private/Language/locallang.xlf:form_new_wizard_title
      description = LLL:EXT:form/Resources/Private/Language/locallang.xlf:form_new_wizard_description
      tt_content_defValues {
        CType = form_formframework
      }
    }
  }
}
";}s:26:"pagesTsConfig-package-info";a:2:{s:8:"filename";s:72:"/home/codebrig/public_html/typo3/sysext/info/Configuration/page.tsconfig";s:7:"content";s:771:"mod.web_info.fieldDefinitions {
    0 {
        label = LLL:EXT:info/Resources/Private/Language/locallang_webinfo.xlf:pages_0
        fields = title,uid,slug,starttime,endtime,fe_group,target,url,shortcut,shortcut_mode
    }
    1 {
        label = LLL:EXT:info/Resources/Private/Language/locallang_webinfo.xlf:pages_1
        fields = title,uid,###ALL_TABLES###
    }
    2 {
        label = LLL:EXT:info/Resources/Private/Language/locallang_webinfo.xlf:pages_2
        fields = title,uid,lastUpdated,newUntil,cache_timeout,php_tree_stop,TSconfig,is_siteroot
    }
    3 {
        label = LLL:EXT:info/Resources/Private/Language/locallang_webinfo.xlf:pages_layouts
        fields = title,uid,actual_backend_layout,backend_layout,backend_layout_next_level,layout
    }
}
";}s:35:"pagesTsConfig-package-linkvalidator";a:2:{s:8:"filename";s:81:"/home/codebrig/public_html/typo3/sysext/linkvalidator/Configuration/page.tsconfig";s:7:"content";s:1149:"mod.linkvalidator {

  # specify fields to be crawled
  searchFields {
    pages = media,url
    tt_content = bodytext,header_link,records
  }

  # specify link types to be crawled
  linktypes = db,file,external

  linktypesConfig {
    external {

      # User-Agent string is filled with information about the crawling site
      httpAgentName = TYPO3 LinkValidator
      httpAgentUrl =
      httpAgentEmail =

      headers {

      }

      method = HEAD

      range = 0-4048

      # This is the total timeout of the request in
      # seconds.
      # If set, this overrides the timeout in $GLOBALS[\'TYPO3_CONF_VARS\'][\'HTTP\'][\'timeout\'].
      # which defaults to 0.
      #
      # WARNING:
      #   A value 0 means no timeout, which may mean that the request never ends
      #   and can also result in scheduler tasks to run indefinitely.
      timeout = 20
    }
  }

  checkhidden = 0
  showCheckLinkTab = 1
  # recheck | setNeedsRecheck
  actionAfterEditRecord = recheck
  mail {
    fromname = LinkValidator
    fromemail = linkvalidator@example.com
    replytoname =
    replytoemail =
    subject = TYPO3 LinkValidator report
  }
}
";}s:25:"pagesTsConfig-package-seo";a:2:{s:8:"filename";s:71:"/home/codebrig/public_html/typo3/sysext/seo/Configuration/page.tsconfig";s:7:"content";s:431:"mod.web_info.fieldDefinitions {
  seo {
    label = LLL:EXT:seo/Resources/Private/Language/locallang_webinfo.xlf:seo
    fields = title,uid,slug,seo_title,description,no_index,no_follow,canonical_link,sitemap_changefreq,sitemap_priority
  }
  social_media {
    label = LLL:EXT:seo/Resources/Private/Language/locallang_webinfo.xlf:social_media
    fields = title,uid,og_title,og_description,twitter_title,twitter_description
  }
}
";}s:29:"pagesTsConfig-package-backend";a:2:{s:8:"filename";s:75:"/home/codebrig/public_html/typo3/sysext/backend/Configuration/page.tsconfig";s:7:"content";s:2930:"# Register default link handlers
TCEMAIN.linkHandler {
  page {
    handler = TYPO3\\CMS\\Backend\\LinkHandler\\PageLinkHandler
    label = LLL:EXT:backend/Resources/Private/Language/locallang_browse_links.xlf:page
  }
  url {
    handler = TYPO3\\CMS\\Backend\\LinkHandler\\UrlLinkHandler
    label = LLL:EXT:backend/Resources/Private/Language/locallang_browse_links.xlf:extUrl
    displayAfter = page,file,folder
    scanAfter = telephone
  }
  email {
    handler = TYPO3\\CMS\\Backend\\LinkHandler\\MailLinkHandler
    label = LLL:EXT:backend/Resources/Private/Language/locallang_browse_links.xlf:email
    displayAfter = page,file,folder,url
    scanBefore = url
  }
  telephone {
    handler = TYPO3\\CMS\\Backend\\LinkHandler\\TelephoneLinkHandler
    label = LLL:EXT:backend/Resources/Private/Language/locallang_browse_links.xlf:telephone
    displayAfter = page,file,folder,url,mail
    scanBefore = url
  }
}

mod.web_list {
  enableClipBoard = selectable
  tableDisplayOrder {
    be_users.after = be_groups
    sys_filemounts.after = be_users
    sys_file_storage.after = sys_filemounts
    fe_users.after = fe_groups
    fe_users.before = pages
    sys_template.after = pages
    backend_layout.after = pages
    tt_content.after = pages,backend_layout,sys_template
    sys_category.after = tt_content
  }
  searchLevel.items {
    -1 = EXT:core/Resources/Private/Language/locallang_core.xlf:labels.searchLevel.infinite
    0 = EXT:core/Resources/Private/Language/locallang_core.xlf:labels.searchLevel.0
    1 = EXT:core/Resources/Private/Language/locallang_core.xlf:labels.searchLevel.1
    2 = EXT:core/Resources/Private/Language/locallang_core.xlf:labels.searchLevel.2
    3 = EXT:core/Resources/Private/Language/locallang_core.xlf:labels.searchLevel.3
    4 = EXT:core/Resources/Private/Language/locallang_core.xlf:labels.searchLevel.4
  }
}

# dummy placeholders for item groups
mod.wizards {
  newContentElement.wizardItems {
    common.header = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:common
    menu.header = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:menu
    special.header = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:special
    forms.header = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:forms
    plugins.header = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:plugins
    plugins.elements {
      general {
        iconIdentifier = content-plugin
        title = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:plugins_general_title
        description = LLL:EXT:backend/Resources/Private/Language/locallang_db_new_content_el.xlf:plugins_general_description
        tt_content_defValues.CType = list
      }
    }
    plugins.show = *
  }
  newRecord.pages.show {
    pageInside = 1
    pageAfter = 1
    pageSelectPosition = 1
  }
}
";}s:29:"pagesTsConfig-package-felogin";a:2:{s:8:"filename";s:75:"/home/codebrig/public_html/typo3/sysext/felogin/Configuration/page.tsconfig";s:7:"content";s:469:"mod.wizards.newContentElement.wizardItems.forms {
  elements {
    felogin_login {
      iconIdentifier = content-elements-login
      title = LLL:EXT:felogin/Resources/Private/Language/Database.xlf:tt_content.CType.felogin_login.title
      description = LLL:EXT:felogin/Resources/Private/Language/Database.xlf:tt_content.CType.felogin_login.description
      tt_content_defValues {
        CType = felogin_login
      }
    }
  }
  show := addToList(felogin_login)
}
";}s:30:"pagesTsConfig-package-viewpage";a:2:{s:8:"filename";s:76:"/home/codebrig/public_html/typo3/sysext/viewpage/Configuration/page.tsconfig";s:7:"content";s:1508:"mod.web_view.previewFrameWidths {
    1920.label = LLL:EXT:viewpage/Resources/Private/Language/locallang.xlf:computer
    1920.type = desktop
    1920.width = 1920
    1920.height = 1080

    1366.label = LLL:EXT:viewpage/Resources/Private/Language/locallang.xlf:computer
    1366.type = desktop
    1366.width = 1366
    1366.height = 768

    1280.label = LLL:EXT:viewpage/Resources/Private/Language/locallang.xlf:computer
    1280.type = desktop
    1280.width = 1280
    1280.height = 1024

    1024.label = LLL:EXT:viewpage/Resources/Private/Language/locallang.xlf:computer
    1024.type = desktop
    1024.width = 1024
    1024.height = 768

    nexus7.label = Nexus 7
    nexus7.type = tablet
    nexus7.width = 600
    nexus7.height = 960

    nexus6p.label = Nexus 6P
    nexus6p.type = mobile
    nexus6p.width = 411
    nexus6p.height = 731

    ipadpro.label = iPad Pro
    ipadpro.type = tablet
    ipadpro.width = 1024
    ipadpro.height = 1366

    ipadair.label = iPad Air
    ipadair.type = tablet
    ipadair.width = 768
    ipadair.height = 1024

    iphone7plus.label = iPhone 7 Plus
    iphone7plus.type = mobile
    iphone7plus.width = 414
    iphone7plus.height = 736

    iphone6.label = iPhone 6
    iphone6.type = mobile
    iphone6.width = 375
    iphone6.height = 667

    iphone5.label = iPhone 5
    iphone5.type = mobile
    iphone5.width = 320
    iphone5.height = 568

    iphone4.label = iPhone 4
    iphone4.type = mobile
    iphone4.width = 320
    iphone4.height = 480
}
";}s:26:"pagesTsConfig-package-news";a:2:{s:8:"filename";s:73:"/home/codebrig/public_html/typo3conf/ext/news/Configuration/page.tsconfig";s:7:"content";s:136:"mod.linkvalidator.searchFields {
    tx_news_domain_model_news = bodytext,internalurl,externalurl
    tx_news_domain_model_link = uri
}
";}}');
#