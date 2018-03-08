<?php
/**
 * @package         CrowdfundingConditions
 * @subpackage      Plugins
 * @author          Todor Iliev
 * @copyright       Copyright (C) 2017 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license         http://www.gnu.org/licenses/gpl-3.0.en.html GNU/GPL
 */

use Joomla\Registry\Registry;
use Joomla\String\StringHelper;

// no direct access
defined('_JEXEC') or die;

jimport('Crowdfunding.init');
jimport('Crowdfundingfiles.init');

/**
 * Crowdfunding Payment Conditions Plugin
 *
 * @package        CrowdfundingConditions
 * @subpackage     Plugins
 */
class plgCrowdfundingPaymentConditions extends Crowdfunding\Payment\Plugin
{
    protected $autoloadLanguage = true;

    /**
     * @var JApplicationSite
     */
    protected $app;

    protected $form;

    protected $name;
    protected $version    = '1.0';

    protected $itemId = 0;
    protected $terms;

    /**
     * @var Registry
     */
    public $params;

    /**
     * This method prepares a payment gateway - buttons, forms,...
     * That gateway will be displayed on the summary page as a payment option.
     *
     * @param string    $context This string gives information about that where it has been executed the trigger.
     * @param stdClass  $item    A project data.
     * @param stdClass  $nextStepParams Parameters of the next step (task, layout, link).
     * @param Registry  $params  The parameters of the component
     *
     * @throws \RuntimeException
     * @throws \UnexpectedValueException
     * @throws \InvalidArgumentException
     *
     * @return null|string
     */
    public function onPreparePaymentStep($context, $item, $nextStepParams, $params)
    {
        if (strcmp('com_crowdfunding.payment.step.conditions', $context) !== 0) {
            return null;
        }

        if ($this->app->isAdmin()) {
            return null;
        }

        $doc = JFactory::getDocument();
        /**  @var $doc JDocumentHtml */

        // Check document type
        $docType = $doc->getType();
        if (strcmp('html', $docType) !== 0) {
            return null;
        }

        // Load jQuery
        JHtml::_('jquery.framework');
        JHtml::_('Prism.ui.parsley');

        // Get the path for the layout file
        $path = JPath::clean(JPluginHelper::getLayoutPath('crowdfundingpayment', 'conditions'));

        $conditions = $this->prepareConditions();

        // Prepare project owner conditions.
        $files = null;
        if ($this->params->get('project_conditions', Prism\Constants::NO)) {
            if (JComponentHelper::isInstalled('com_crowdfundingfiles')) {
                $files = new Crowdfundingfiles\File\Files(JFactory::getDbo());

                $keys = array(
                    'project_id' => (int)$item->id,
                    'section'    => 'conditions'
                );

                $files->load($keys);

                if (count($files) > 0) {
                    $mediaFolderUri = CrowdfundingFilesHelper::getMediaFolderUri(JUri::root(), $item->user_id);
                }
            }
        }

        // Render the form.
        ob_start();
        include $path;
        $html = ob_get_clean();

        return $html;
    }

    protected function prepareConditions()
    {
        $results    = array();
        $conditions = array(
            $this->params->get('conditions1'),
            $this->params->get('conditions2'),
            $this->params->get('conditions3'),
            $this->params->get('conditions4'),
            $this->params->get('conditions5')
        );

        foreach ($conditions as $value) {
            $v = StringHelper::trim(strip_tags($value));
            if ($v !== '') {
                $results[] = $value;
            }
        }

        return $results;
    }

    /**
     * Return information about a step on the payment wizard.
     *
     * @param string $context
     *
     * @return null|array
     */
    public function onPrepareWizardSteps($context)
    {

        if (strcmp('com_crowdfunding.payment.wizard', $context) !== 0) {
            return null;
        }

        if ($this->app->isAdmin()) {
            return null;
        }

        $doc = JFactory::getDocument();
        /**  @var $doc JDocumentHtml */

        // Check document type
        $docType = $doc->getType();
        if (strcmp('html', $docType) !== 0) {
            return null;
        }

        return array(
            'title'   => JText::_('PLG_CROWDFUNDINGPAYMENT_CONDITIONS_CONDITIONS'),
            'context' => 'conditions',
            'allowed' => Prism\Constants::YES
        );
    }
}
