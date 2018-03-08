<?php
/**
 * @package      CrowdfundingConditions
 * @subpackage   Plugins
 * @author       Todor Iliev
 * @copyright    Copyright (C) 2017 Todor Iliev <todor@itprism.com>. All rights reserved.
 * @license      GNU General Public License version 3 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

$doc->addScript('plugins/crowdfundingpayment/conditions/js/script.js?v=' . rawurlencode($this->version));
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-default">
            <div class="panel-heading">
                <?php echo JText::_('PLG_CROWDFUNDINGPAYMENT_CONDITIONS_TERMS_CONDITIONS');?>
            </div>
            <div class="panel-body">
                <form action="<?php echo JRoute::_('index.php?option=com_crowdfundingdata');?>" method="post" id="js-cfconditions-form" novalidate="novalidate" autocomplete="off">

                    <?php
                    $i = 1;
                    foreach ($conditions as $value) {?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="1" name="conditions<?php echo $i++;?>" required >
                                <?php echo $value; ?>
                            </label>
                        </div>
                    <?php } ?>

                    <?php
                    if ($this->params->get('project_conditions', Prism\Constants::NO) and $files !== null) {
                        foreach ($files as $file) {
                            $mime = array_key_exists('mime', $file['filedata']) ? htmlspecialchars($file['filedata']['mime'], ENT_COMPAT, 'UTF-8') : '';
                            $file['description'] = Joomla\String\StringHelper::trim(strip_tags($file['description']));
                            ?>
                        <div class="checkbox">
                            <label>
                                <input type="checkbox" value="1" name="conditions<?php echo $i++;?>" required>
                                <a href="<?php echo $mediaFolderUri. '/'. htmlspecialchars($file['filename'], ENT_COMPAT, 'UTF-8');?>" type="<?php echo $mime; ?>" download>
                                    <?php echo htmlspecialchars($file['title']); ?>
                                </a>
                                <?php
                                if ($file['description'] !== '') {
                                    echo ' &mdash; ' . htmlspecialchars($file['description']);
                                }
                                ?>
                            </label>
                        </div>
                        <?php }
                    }?>

                </form>

                <p class="alert alert-warning" id="js-cfconditions-alert" style="display: none;">
                    <span class="fa fa-warning"></span>
                    <?php echo JText::_('PLG_CROWDFUNDINGPAYMENT_CONDITIONS_ALERT_MESSAGE'); ?>
                </p>

                <a href="<?php echo $nextStepParams->link;?>" class="btn btn-primary" id="js-cfconditions-btn-continue" role="button">
                    <span class="fa fa-chevron-right"></span>
                    <?php echo JText::_('PLG_CROWDFUNDINGPAYMENT_CONDITIONS_AGREE_CONTINUE_NEXT_STEP'); ?>
                </a>
            </div>
        </div>
    </div>
</div>