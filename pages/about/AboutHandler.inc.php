<?php

/**
 * @file paes/about/AboutHandler.inc.php
 *
 * Copyright (c) 2005-2012 Alec Smecher and John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @package pages.about
 * @class AboutHandler
 *
 * Handle requests for the About page.
 *
 */

// $Id$


import('classes.handler.Handler');
import('lib.pkp.classes.form.Form');


class AboutHandler extends Handler {

	public function __construct()
    {
        $this->validate();
        /**
         * TODO check if  route is send form
         */
        import('classes.user.form.ContactForm');
        $this->contactForm = new ContactForm();
        $this->contactForm->readInputData();
        $this->contactForm->initData();
        parent::__construct();
    }

    /**
	 * Display about index page.
	 */
	function index() {
       	$this->setupTemplate(true);
		$templateMgr =& TemplateManager::getManager();
		$site =& Request::getSite();
		$templateMgr->assign('about', $site->getLocalizedSetting('about'));
		$templateMgr->display('about/index.tpl');
	}


	/**
	 * Setup common template variables.
	 * @param $subclass boolean set to true if caller is below this handler in the hierarchy
	 */
	function setupTemplate($subclass = false) {
		$templateMgr =& TemplateManager::getManager();
		if ($subclass) {
            $templateMgr->assign('pageHierarchy', array(array('about', 'navigation.about')));
        }
        parent::setupTemplate();
	}

	/**
	 * Display contact page.
	 */
	function contact($args, $request) {
	       $this->contactForm->display($args, $request);

	}


    /**
     * Save user's new password.
     */
    function sendEmail($args, $request) {

        $this->contactForm->validate();
        $this->contactForm->execute($request);

    	return $this->contact();

      /*  $this->setupTemplate($request, true);

        $user = $request->getUser();
        $site = $request->getSite();

        import('classes.user.form.ContactForm');
        $contactForm = new ContactForm($user, $site);
        $contactForm->readInputData();
        $contactForm->execute($request);


        if ($contactForm->validate()) {
            if ($contactForm->execute($request)) {
                $templateMgr =& TemplateManager::getManager();
                $templateMgr->assign('send', 'true');
                $templateMgr->display('about/contact.tpl');
            }

        } else {
            $contactForm->display($args, $request);
        }*/

    }

	/**
	 * Display about the harvester page.
	 */
	function harvester() {
		$this->setupTemplate(true);
		$templateMgr =& TemplateManager::getManager();
		$templateMgr->display('about/harvester.tpl');
	}
}

?>
