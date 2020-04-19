<?php
import('lib.pkp.classes.form.Form');
import('lib.pkp.classes.mail.PKPMailTemplate');

class ContactForm extends Form
{

    public function __construct()
    {
        parent::Form('about/contact.tpl');
        import('lib.pkp.classes.captcha.CaptchaManager');
        $captchaManager = new CaptchaManager();
        $this->captchaEnabled = ($captchaManager->isEnabled() && Config::getVar('captcha', 'captcha_on_register')) ? true : false;
        // Validation checks for this form
        $this->addCheck(new FormValidator($this, 'username', 'required', 'admin.settings.form.contactNameRequired'));
        $this->addCheck(new FormValidatorEmail($this, 'contactemail', 'required', 'admin.settings.form.contactEmailRequired'));
        $this->addCheck(new FormValidator($this, 'message', 'required', 'user.profile.form.contactmessageRequired'));
        if ($this->captchaEnabled) {
            $this->addCheck(new FormValidatorCaptcha($this, 'captcha', 'captchaId', 'common.captchaField.badCaptcha'));
        }
        $this->addCheck(new FormValidatorPost($this));
    }


    /**
     * Display the form.
     */
    function display()
    {
        $templateMgr =& TemplateManager::getManager();
        if ($this->captchaEnabled) {
            import('lib.pkp.classes.captcha.CaptchaManager');
            $captchaManager = new CaptchaManager();
            $captcha = $captchaManager->createCaptcha();
            if ($captcha) {
                $templateMgr->assign('captchaEnabled', $this->captchaEnabled);
                $this->setData('captchaId', $captcha->getId());
            }
        }
        parent::display();
    }

    /**
     * Assign form data to contact -submitted data.
     */
    function readInputData()
    {
        $contactVars = array(
            'username',
            'contactemail',
            'message',
            'phone',
        );
        if ($this->captchaEnabled) {
            $contactVars[] = 'captchaId';
            $contactVars[] = 'captcha';
        }
        $this->readUserVars($contactVars);
    }


    function execute()
    {
        $site =& Request::getSite();
        import('classes.mail.MailTemplate');
        $mail = new MailTemplate('CONTACT_FORM');
        $mail->setFrom($site->getLocalizedContactEmail(), $site->getLocalizedContactName());

        $mail->assignParams(array(
            'username' => $this->getData('username'),
            'phone' => $this->getData('phone'), // Prevent mailer abuse via long passwords
            'message' => $this->getData('message'),
        ));
        $mail->addRecipient($this->getData('contactemail'), $this->getData('username'));
        $mail->addBcc($site->getLocalizedContactEmail(), $site->getLocalizedContactName());
        if ($mail->send()) {
            return true;
        }
        unset($mail);
    }
}