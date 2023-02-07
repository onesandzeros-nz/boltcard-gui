<?php

namespace {

    use SilverStripe\CMS\Controllers\RootURLController;
    use SilverStripe\CMS\Model\SiteTree;
    use SilverStripe\ErrorPage\ErrorPage;
    use SilverStripe\ORM\DB;
    use SilverStripe\Versioned\Versioned;

    class Page extends SiteTree
    {
        private static $db = [];

        private static $has_one = [];

        public function requireDefaultRecords()
        {
            parent::requireDefaultRecords();

            // default pages
            if (static::class === self::class) {
                $defaultHomepage = RootURLController::config()->get('default_homepage_link');
                if (!SiteTree::get_by_link($defaultHomepage)) {
                    $homepage = new Page();
                    $homepage->Title = _t(__CLASS__.'.DEFAULTHOMETITLE', 'Home');
                    $homepage->Content = _t(__CLASS__.'.DEFAULTHOMECONTENT', '<p>Boltcard</p>');
                    $homepage->URLSegment = $defaultHomepage;
                    $homepage->Sort = 1;
                    $homepage->write();
                    $homepage->copyVersionToStage(Versioned::DRAFT, Versioned::LIVE);
                    $homepage->flushCache();
                    DB::alteration_message('Home page created', 'created');
                }

                //error pages
                $defaultPages = array(
                    array(
                        'ErrorCode' => 404,
                        'Title' => _t('SilverStripe\\ErrorPage\\ErrorPage.DEFAULTERRORPAGETITLE', 'Page not found'),
                        'Content' => _t(
                            'SilverStripe\\ErrorPage\\ErrorPage.DEFAULTERRORPAGECONTENT',
                            '<p>Sorry, it seems you were trying to access a page that doesn\'t exist.</p>'
                            . '<p>Please check the spelling of the URL you were trying to access and try again.</p>'
                        )
                    ),
                    array(
                        'ErrorCode' => 500,
                        'Title' => _t('SilverStripe\\ErrorPage\\ErrorPage.DEFAULTSERVERERRORPAGETITLE', 'Server error'),
                        'Content' => _t(
                            'SilverStripe\\ErrorPage\\ErrorPage.DEFAULTSERVERERRORPAGECONTENT',
                            '<p>Sorry, there was a problem with handling your request.</p>'
                        )
                    )
                );

                foreach ($defaultPages as $defaultData) {
                    $code = $defaultData['ErrorCode'];

                    /** @var ErrorPage $page */
                    $page = ErrorPage::get()->find('ErrorCode', $code);
                    if (!$page) {
                        $page = ErrorPage::create();
                        $page->update($defaultData);
                        $page->write();
                    }

                    // Ensure page is published at latest version
                    if (!$page->isLiveVersion()) {
                        $page->publishSingle();
                    }

                    // Check if static files are enabled
                    if (!ErrorPage::config()->get('enable_static_file')) {
                        return;
                    }

                    // Force create or refresh of static page
                    $staticExists = $page->hasStaticPage();
                    $success = $page->writeStaticPage();
                    if (!$success) {
                        DB::alteration_message(
                            sprintf('%s error page could not be created. Please check permissions', $code),
                            'error'
                        );
                    } elseif ($staticExists) {
                        DB::alteration_message(
                            sprintf('%s error page refreshed', $code),
                            'created'
                        );
                    } else {
                        DB::alteration_message(
                            sprintf('%s error page created', $code),
                            'created'
                        );
                    }
                }
            }
        }
    }
}
