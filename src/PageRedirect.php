<?php

namespace Concrete\Package\PageRedirect\Src;

defined('C5_EXECUTE') or die("Access Denied.");

use Loader;
use Page;
/**
 * Class that is used to redirect based on a page attribute
 * @package Page Redirect
 * @author Michael Krasnow <mnkras@gmail.com>
 * @category Packages
 * @copyright  Copyright (c) 2012 Michael Krasnow. (http://www.mnkras.com)
 */
class PageRedirect
{
    /**
     * Redirect to a page based on a page attribute
     */
    public function checkRedirect()
    {
        $page = Page::getCurrentPage();
        //get attribute
        $page_selector = $page->getCollectionAttributeValue('page_selector_redirect');
        //start checking if its a valid page
        if ($page_selector > 0) {
            Loader::model('page');
            $npage = Page::getByID($page_selector);
            //more checking
            if (is_object($npage) && !$npage->isError()) {
                //redirect
                header("HTTP/1.1 301 Moved Permanently");
                if (!$npage->isExternalLink()) {
                    $nh = Loader::Helper('navigation');
                    header('Location: ' . $nh->getLinkToCollection($npage, true));
                } else {
                    header('Location: ' . $npage->getCollectionPointerExternalLink());
                    exit;
                }
            }
        }
    }
}
