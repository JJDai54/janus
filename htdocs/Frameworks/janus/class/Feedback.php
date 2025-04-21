<?php
//  ------------------------------------------------------------------------ //
use Xmf\Request;

class Feedback  
{
    public $name    = '';
    public $email   = '';
    public $site    = '';
    public $type    = '';
    public $content = '';

	public function __construct()
	{
	}

    /**
     * @static function &getInstance
     *
     * @param null
     */
    public static function getInstance()
    {
        static $instance = false;
        if (!$instance) {
            $instance = new self();
        }
    }

    /**
     * @public function getFormFeedback:
     * provide form for sending a feedback to module author
     * @return \XoopsThemeForm
     */
    public function getFormFeedback()
    {
    
        $this->name  = $GLOBALS['xoopsUser']->getVar('name');
        $this->email = $GLOBALS['xoopsUser']->getVar('email');
        $this->site  = XOOPS_URL;
 
    
    
    
    
        $moduleDirName      = \basename(\dirname(\dirname(__DIR__)));
        $moduleDirNameUpper = \mb_strtoupper($moduleDirName);
        // Get Theme Form
        \xoops_load('XoopsFormLoader');
        $form = new \XoopsThemeForm(\constant('CO_CARTOUCHES_' . 'FB_FORM_TITLE'), 'formfeedback', 'feedback.php', 'post', true);
        $form->setExtra('enctype="multipart/form-data"');

        $recipient = new \XoopsFormText(\constant('CO_CARTOUCHES_' . 'FB_RECIPIENT'), 'recipient', 50, 255, $GLOBALS['xoopsModule']->getInfo('author_mail'));
        $recipient->setExtra('disabled="disabled"');
        $form->addElement($recipient);
        $your_name = new \XoopsFormText(\constant('CO_CARTOUCHES_' . 'FB_NAME'), 'your_name', 50, 255, $this->name);
        $your_name->setExtra('placeholder="' . \constant('CO_CARTOUCHES_' . 'FB_NAME_PLACEHOLER') . '"');
        $form->addElement($your_name);
        $your_site = new \XoopsFormText(\constant('CO_CARTOUCHES_' . 'FB_SITE'), 'your_site', 50, 255, $this->site);
        $your_site->setExtra('placeholder="' . \constant('CO_CARTOUCHES_' . 'FB_SITE_PLACEHOLER') . '"');
        $form->addElement($your_site);
        $your_mail = new \XoopsFormText(\constant('CO_CARTOUCHES_' . 'FB_MAIL'), 'your_mail', 50, 255, $this->email);
        $your_mail->setExtra('placeholder="' . \constant('CO_CARTOUCHES_' . 'FB_MAIL_PLACEHOLER') . '"');
        $form->addElement($your_mail);

        $fbtypeSelect = new \XoopsFormSelect(\constant('CO_CARTOUCHES_' . 'FB_TYPE'), 'fb_type', $this->type);
        $fbtypeSelect->addOption('', '');
        $fbtypeSelect->addOption(\constant('CO_CARTOUCHES_' . 'FB_TYPE_SUGGESTION'), \constant('CO_CARTOUCHES_' . 'FB_TYPE_SUGGESTION'));
        $fbtypeSelect->addOption(\constant('CO_CARTOUCHES_' . 'FB_TYPE_BUGS'), \constant('CO_CARTOUCHES_' . 'FB_TYPE_BUGS'));
        $fbtypeSelect->addOption(\constant('CO_CARTOUCHES_' . 'FB_TYPE_TESTIMONIAL'), \constant('CO_CARTOUCHES_' . 'FB_TYPE_TESTIMONIAL'));
        $fbtypeSelect->addOption(\constant('CO_CARTOUCHES_' . 'FB_TYPE_FEATURES'), \constant('CO_CARTOUCHES_' . 'FB_TYPE_FEATURES'));
        $fbtypeSelect->addOption(\constant('CO_CARTOUCHES_' . 'FB_TYPE_OTHERS'), \constant('CO_CARTOUCHES_' . 'FB_TYPE_OTHERS'));
        $form->addElement($fbtypeSelect, true);

        $editorConfigs           = [];
        $editorConfigs['name']   = 'fb_content';
        $editorConfigs['value']  = $this->content;
        $editorConfigs['rows']   = 5;
        $editorConfigs['cols']   = 40;
        $editorConfigs['width']  = '100%';
        $editorConfigs['height'] = '400px';
        $moduleHandler           = \xoops_getHandler('module');
        $module                  = $moduleHandler->getByDirname('system');
        $configHandler           = \xoops_getHandler('config');
        $config                  = &$configHandler->getConfigsByCat(0, $module->getVar('mid'));
        $editorConfigs['editor'] = $config['general_editor'];
        $editor                  = new \XoopsFormEditor(\constant('CO_CARTOUCHES_' . 'FB_TYPE_CONTENT'), 'fb_content', $editorConfigs);
        $form->addElement($editor, true);

        $form->addElement(new \XoopsFormHidden('op', 'send'));
        $form->addElement(new \XoopsFormButtonTray('', _SUBMIT, 'submit', '', false));

        return $form;
    }

/**********************************
 * 
 * ****************************** */
function send(){
//echoGPF();
        $your_name  = Request::getString('your_name', '');
        $your_site  = Request::getString('your_site', '');
        $your_mail  = Request::getString('your_mail', '');
        $fb_type    = Request::getString('fb_type', '');
        $fb_content = Request::getText('fb_content', '');
        $fb_content = \str_replace(["\r\n", "\n", "\r"], '<br>', $fb_content); //clean line break from dhtmltextarea

        $title       = \constant('CO_CARTOUCHES_' . 'FB_SEND_FOR') . $GLOBALS['xoopsModule']->getVar('dirname');
        $body        = \constant('CO_CARTOUCHES_' . 'FB_NAME') . ': ' . $your_name . '<br>';
        $body        .= \constant('CO_CARTOUCHES_' . 'FB_MAIL') . ': ' . $your_mail . '<br>';
        $body        .= \constant('CO_CARTOUCHES_' . 'FB_SITE') . ': ' . $your_site . '<br>';
        $body        .= \constant('CO_CARTOUCHES_' . 'FB_TYPE') . ': ' . $fb_type . '<br><br>';
        $body        .= \constant('CO_CARTOUCHES_' . 'FB_TYPE_CONTENT') . ':<br>';
        $body        .= $fb_content;
        $xoopsMailer = xoops_getMailer();
        $xoopsMailer->useMail();
        $xoopsMailer->setToEmails($GLOBALS['xoopsModule']->getInfo('author_mail'));
        $xoopsMailer->setFromEmail($your_mail);
        $xoopsMailer->setFromName($your_name);
        $xoopsMailer->setSubject($title);
        $xoopsMailer->multimailer->isHTML(true);
        $xoopsMailer->setBody($body);
        $ret = $xoopsMailer->send();
        if ($ret) {
            \redirect_header('index.php', 3, \constant('CO_CARTOUCHES_' . 'FB_SEND_SUCCESS'));
        }

        // show form with content again
        $this->name    = $your_name;
        $this->email   = $your_mail;
        $this->site    = $your_site;
        $this->type    = $fb_type;
        $this->content = $fb_content;

        echo '<div style="text-align:center;width: 80%; padding: 10px; border: 2px solid #ff0000; color: #ff0000; margin-right:auto;margin-left:auto;">
            <h3>' . \constant('CO_CARTOUCHES_' . 'FB_SEND_ERROR') . '</h3>
            </div>';
    }

} // ----------- Fin de la Classe ----------------------

?>
