<?php
/**
 * @version 1.5 stable $Id: default.php 363 2010-07-05 15:40:10Z Matthieu.26 $
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

defined( '_JEXEC' ) or die( 'Restricted access' );

?>
<div id="cpanel" class="width-70 fltlft">
	<?php foreach ($this->buttons as $button) : ?>
		<?php if (!$button['access'] || $this->permission->get($button['access'])) : ?>
			<?php echo $this->quickiconButton($button['link'], $button['icon'], $button['text'], isset($button['modal']) ? 1 : 0); ?>
		<?php endif; ?>
	<?php endforeach; ?>
	<div class="clr"></div>
</div>
