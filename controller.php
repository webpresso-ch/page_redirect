<?php

namespace Concrete\Package\PageRedirect;

defined('C5_EXECUTE') or die('Access Denied.');

use Concrete\Package\PageRedirect\Src\PageRedirect;
use Loader;
use \Concrete\Core\Attribute\Type as AttributeType;
use CollectionAttributeKey;
use Package;
use Concrete\Core\Support\Facade\Events;

/**
 * Class that is used to redirect based on a page attribute
 * @package Page Redirect
 * @author Michael Krasnow <mnkras@gmail.com>
 * @category Packages
 * @copyright  Copyright (c) 2012 Michael Krasnow. (http://www.mnkras.com)
 */
class Controller extends \Concrete\Core\Package\Package
{

    protected $pkgHandle = 'page_redirect';
    protected $appVersionRequired = '5.4.1';
    protected $pkgVersion = '1.4';

    public function getPackageDescription()
    {
        return t("Adds a page attribute that allows you to specify a page to redirect to.");
    }

    public function getPackageName()
    {
        return t("Page Redirect");
    }

    public function install()
    {
        Loader::model('collection_attributes');
        $PageAttrType = AttributeType::getByHandle('page_selector');
        if (!is_object($PageAttrType) || !intval($PageAttrType->getAttributeTypeID())) {
            throw new exception(t('Please install %s before installing this addon.', '<a href="http://www.concrete5.org/marketplace/addons/page-selector-attribute/">Page Selector Attribute</a>'));
            exit;
        }
        $pkg = parent::install();
        CollectionAttributeKey::add('page_selector', array('akHandle' => 'page_selector_redirect', 'akName' => t('Page Redirect'), 'akIsSearchable' => true), $pkg);
    }

    public function on_start()
    {
        $url = Loader::helper('concrete/urls')->getPackageURL(Package::getByHandle('page_redirect'));
        $handler = new PageRedirect();
        Events::addListener('on_start', function ($event) use ($handler) {
            $handler->checkRedirect();
        });
    }
}