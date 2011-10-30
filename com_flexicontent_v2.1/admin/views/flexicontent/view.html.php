<?php
/**
 * @version 1.5 stable $Id: view.html.php 370 2010-07-21 04:55:46Z enjoyman $
 * @package Joomla
 * @subpackage FLEXIcontent
 * @copyright (C) 2009 Emmanuel Danan - www.vistamedia.fr
 * @license GNU/GPL v2
 * 
 * FLEXIcontent is a derivative work of the excellent QuickFAQ component
 * @copyright (C) 2008 Christoph Lukes
 * see www.schlu.net for more information
 *
 * FLEXIcontent is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );

jimport( 'joomla.application.component.view');

/**
 * HTML View class for the FLEXIcontent View
 *
 * @package Joomla
 * @subpackage FLEXIcontent
 * @since 1.0
 */
class FLEXIcontentViewFlexicontent extends JView{
	/**
	 * Creates the Entrypage
	 *
	 * @since 1.0
	 */
	function display( $tpl = null ) {
		$this->buttons		= $this->get('Buttons');
		$this->permission   = FLEXIcontentHelperPermission::getActions();
		
		$this->addToolbar();
		parent::display($tpl);
	}
	
	/**
	 * Creates the buttons view
	 *
	 * @param string $link targeturl
	 * @param string $image path to image
	 * @param string $text image description
	 * @param boolean $modal 1 for loading in modal
	 */
	protected function quickiconButton( $link, $image, $text, $modal = 0, $modaliframe = 1 )
	{
		//initialise variables
		$lang 		= & JFactory::getLanguage();
  		?>
		<div style="float:<?php echo ($lang->isRTL()) ? 'right' : 'left'; ?>;" class="icon-wrapper">
			<div class="icon">
				<?php
				if ($modal == 1) {
					JHTML::_('behavior.modal');
					$rel = $modaliframe?" rel=\"{handler: 'iframe', size: {x: 900, y: 500}}\"":'';
				?>
					<a href="<?php echo $link; ?>" style="cursor:pointer" class="modal"<?php echo $rel;?>>
				<?php
				} else {
				?>
					<a href="<?php echo $link; ?>">
				<?php
				}
					echo JHTML::_('image', 'media/com_flexicontent/images/'.$image, $text );
				?>
					<span><?php echo $text; ?></span>
				</a>
			</div>
		</div>
		<?php
	}
	
	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		$document	= & JFactory::getDocument();
		$state		= $this->get('State');
		$canDo		= FLEXIcontentHelperPermission::getActions();

		$document->addStyleSheet(JURI::root(true).'/media/com_flexicontent/css/flexicontentbackend.css');

		JToolBarHelper::title(JText::_('FLEXI_DASHBOARD'), 'flexicontent');

		if ($canDo->get('core.admin')) {
			JToolBarHelper::preferences('com_flexicontent', '550', '850', 'Configuration');
		}
	}
}
?>
