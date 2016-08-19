<?php
/**
 * @package     Joomla.Plugin
 * @subpackage  Editors-xtd.article
 *
 * @copyright   Copyright (C) 2005 - 2016 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

/**
 * Editor Article buton
 *
 * @since  1.5
 */
class PlgButtonFilemanager extends JPlugin
{

  /**
   * Load the language file on instantiation.
   *
   * @var    boolean
   * @since  3.1
   */
  protected $autoloadLanguage = true;

  /**
   * Display the button
   *
   * @param   string  $name  The name of the button to add
   *
   * @return array A four element array of (article_id, article_title, category_id, object)
   */
  public function onDisplay($name)
  {

    $app = JFactory::getApplication();
    $user = JFactory::getUser();
    $extension = $app->input->get('option');

    if (  $user->authorise('core.edit', $asset)
      ||  $user->authorise('core.create', $asset)
      ||  (count($user->getAuthorisedCategories($asset, 'core.create')) > 0)
      ||  ($user->authorise('core.edit.own', $asset) && $author == $user->id)
      ||  (count($user->getAuthorisedCategories($extension, 'core.edit')) > 0)
      ||  (count($user->getAuthorisedCategories($extension, 'core.edit.own')) > 0 && $author == $user->id))
    {

      $site_path = preg_replace('/\/+$/','/',JUri::root().'/') . 'plugins/editors-xtd/filemanager/filemanager/';
      $doc = JFactory::getDocument();
      $doc->addScript($site_path . 'plugin.filemanager.js', 'text/javascript');
      $doc->addScriptDeclaration("
        jQuery(document).ready(function(){
          tinymce.settings.external_filemanager_path = '". $site_path ."'
          tinymce.settings.toolbar1 = String(tinymce.settings.toolbar1).replace(/\s+/g,',');
          if (!/,image/.test(tinymce.settings.toolbar1))
            tinymce.settings.toolbar1 = String(tinymce.settings.toolbar1).replace(/(,unlink)/,'$1,image');
          if (!/,media/.test(tinymce.settings.toolbar1))
            tinymce.settings.toolbar1 = String(tinymce.settings.toolbar1).replace(/(,image)/,'$1,media');
          if (typeof tinymce.settings.toolbar2 == 'undefined') {
            tinymce.settings.toolbar2 = String(tinymce.settings.toolbar1).replace(/(^.*)(button-0.*$)/,'$2');
            tinymce.settings.toolbar1 = String(tinymce.settings.toolbar1).replace(/(^.*)(button-0.*$)/,'$1');
          }
          tinymce.settings.plugins += ' image media filemanager';
        });
        ");
      return false;

      $link = '../plugins/editors-xtd/filemanager/filemanager/dialog.php?akey=key';
      $button = new JObject;
      $button->modal = true;
      $button->class = 'btn';
      $button->link = $link;
      $button->text = JText::_('PLG_FILEMANAGER_BUTTON_FILEMANAGER');
      $button->name = 'file';
      $button->options = "{handler: 'iframe', size: {x: 960, y: 500}}";
      return $button;

    }
    return false;

  }

}
