<?php
/**
 * @version 1.0 $Id: minigallery.php 714 2011-07-29 06:27:11Z ggppdk $
 * @package Joomla
 * @subpackage FLEXIcontent
 * @subpackage plugin.file
 * @copyright (C) 2009 Emmanuel Danan - www.vistamedia.fr
 * @license GNU/GPL v2
 *
 * FLEXIcontent is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 */
defined( '_JEXEC' ) or die( 'Restricted access' );

//jimport('joomla.plugin.plugin');
jimport('joomla.event.plugin');

class plgFlexicontent_fieldsMinigallery extends JPlugin
{
	function plgFlexicontent_fieldsMinigallery( &$subject, $params )
	{
		parent::__construct( $subject, $params );
		JPlugin::loadLanguage('plg_flexicontent_fields_minigallery', JPATH_ADMINISTRATOR);
	}
	function onAdvSearchDisplayField(&$field, &$item) {
		plgFlexicontent_fieldsMinigallery::onDisplayField($field, $item);
	}

	function onDisplayField(&$field, $item)
	{
		$field->label = JText::_($field->label);
		// execute the code only if the field type match the plugin type
		if($field->field_type != 'minigallery') return;

		// some parameter shortcuts
		$size				= $field->parameters->get( 'size', 30 ) ;

		$document		= & JFactory::getDocument();
		$flexiparams 	=& JComponentHelper::getParams('com_flexicontent');
		$mediapath		= $flexiparams->get('media_path', 'components/com_flexicontent/medias');
		$app			= & JFactory::getApplication();
		$client			= $app->isAdmin() ? '../' : '';
		$clientpref		= $app->isAdmin() ? '' : 'administrator/';
		$required 			= $field->parameters->get( 'required', 0 ) ;
		$required 	= $required ? ' required' : '';

		$js = "
		function randomString() {
			var chars = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXTZabcdefghiklmnopqrstuvwxyz';
			var string_length = 6;
			var randomstring = '';
			for (var i=0; i<string_length; i++) {
				var rnum = Math.floor(Math.random() * chars.length);
				randomstring += chars.substring(rnum,rnum+1);
			}
			return randomstring;
		}

		function qfSelectFile".$field->id."(id, file) {
			var name 	= 'a_name'+id;
			var ixid 	= randomString();			
			var li 		= document.createElement('li');
			var thumb	= document.createElement('img');
			var hid		= document.createElement('input');
			var span	= document.createElement('span');
			var img		= document.createElement('img');
			
			var filelist = document.getElementById('sortables_".$field->id."');
			if(file.substring(0,7)!='http://')
				file = '".str_replace('\\','/', JPATH_ROOT)."/".$mediapath."/'+file;
			$(li).addClass('minigallery');
			$(thumb).addClass('thumbs');
			$(span).addClass('drag".$field->id."');
			
			var button = document.createElement('input');
			button.type = 'button';
			button.name = 'removebutton_'+id;
			button.id = 'removebutton_'+id;
			$(button).addClass('fcbutton');
			$(button).addEvent('click', function() { deleteField".$field->id."(this) });
			button.value = '".JText::_( 'FLEXI_REMOVE_FILE' )."';
			
			thumb.src = '".JURI::root()."components/com_flexicontent/librairies/phpthumb/phpThumb.php?src='+file+'&w=100&h=100&zc=1';
			thumb.alt ='".JText::_( 'FLEXI_CLICK_TO_DRAG' )."';
			
			hid.type = 'hidden';
			//hid.name = 'custom[".$field->name."]['+ixid+']';
			hid.name = 'custom[".$field->name."][]';
			hid.value = id;
			hid.id = ixid;
			
			img.src = '".JURI::root()."administrator/components/com_flexicontent/assets/images/move3.png';
			img.alt ='".JText::_( 'FLEXI_CLICK_TO_DRAG' )."';
			
			filelist.appendChild(li);
			li.appendChild(thumb);
			li.appendChild(button);
			li.appendChild(hid);
			li.appendChild(span);
			span.appendChild(img);
			
			new Sortables($('sortables_".$field->id."'), {
				'constrain': true,
				'clone': true,
				'handle': '.drag".$field->id."'
			});			
		}
		
			function deleteField".$field->id."(el) {
				var field	= $(el);
				var row		= field.getParent();
				var fx = new Fx.Morph(row, {duration: 300, transition: Fx.Transitions.linear});
				
				fx.start({
					'height': 0,
					'opacity': 0			
				}).chain(function(){
					row.remove();
				});
			}
		";
		$document->addScriptDeclaration($js);

			//add the drag and drop sorting feature
			$js = "
			window.addEvent('domready', function(){
				new Sortables($('sortables_".$field->id."'), {
					'constrain': true,
					'clone': true,
					'handle': '.drag".$field->id."'
					});			
				});
			";
			//$document->addScript( JURI::root().'administrator/components/com_flexicontent/assets/js/sortables.js' );
			$document->addScriptDeclaration($js);

			$css = '
			#sortables_'.$field->id.' { margin: 0 0 10px 0; padding: 0px; list-style: none; white-space: nowrap; }
			#sortables_'.$field->id.' li {
				list-style: none;
				height: 100px;
				padding-top:10px;
				}
			#sortables_'.$field->id.' li img.thumbs {
				border: 1px solid silver;
				padding: 0;
				margin: 0 0 -5px 0;
				}
			#sortables_'.$field->id.' li input { cursor: text;}
			#sortables_'.$field->id.' li input.fcbutton, .fcbutton { cursor: pointer; margin-left: 3px; }
			span.drag'.$field->id.' img {
				margin: -4px 8px;
				cursor: move;
			}
			';
			$document->addStyleDeclaration($css);

			$move 	= JHTML::image ( JURI::root().'administrator/components/com_flexicontent/assets/images/move3.png', JText::_( 'FLEXI_CLICK_TO_DRAG' ) );
				
		JHTML::_('behavior.modal', 'a.modal_'.$field->id);

		$i = 0;
		$field->html = '<ul id="sortables_'.$field->id.'">';
		
		if($field->value) {
			foreach($field->value as $file) {
				$field->html .= '<li>';
				$filename = $this->getFileName( $file );
				$img_path = $filename->filename;
				if(substr($filename->filename, 0, 7)!='http://')
					$img_path = JPATH_ROOT . DS . $mediapath . DS . $filename->filename;
				$src = JURI::root() . 'components/com_flexicontent/librairies/phpthumb/phpThumb.php?src=' . $img_path . '&w=100&h=100&zc=1';

				$field->html .= '<img class="thumbs" src="'.$src.'"/>';
				$field->html .= '<input type="hidden" id="a_id'.$i.'" name="custom['.$field->name.']['.$i.']" value="'.$file.'" />';
				$field->html .= '<input class="inputbox fcbutton" type="button" onclick="deleteField'.$field->id.'(this);" value="'.JText::_( 'FLEXI_REMOVE_FILE' ).'" />';
				$field->html .= '<span class="drag'.$field->id.'">'.$move.'</span>';
				$field->html .= '</li>';
				$i++;
			}
		}

		$linkfrel = JURI::root().'administrator/index.php?option=com_flexicontent&amp;view=fileselement&amp;tmpl=component&amp;layout=image&amp;filter_secure=M&amp;index='.$i.'&amp;field='.$field->id.'&amp;'.JUtility::getToken().'=1';
		$field->html .= "
		</ul>
		<div class=\"button-add\">
			<div class=\"blank\">
				<a class=\"modal_".$field->id."\" title=\"".JText::_( 'FLEXI_ADD_FILE' )."\" href=\"".$linkfrel."\" rel=\"{handler: 'iframe', size: {x:window.getSize().x-100, y: window.getSize().y-100}}\">".JText::_( 'FLEXI_ADD_FILE' )."</a>
			</div>
		</div>
		";
	}


	function onDisplayFieldValue(&$field, $item, $values=null, $prop='display')
	{
		$field->label = JText::_($field->label);
		// execute the code only if the field type match the plugin type
		if($field->field_type != 'minigallery') return;

		$values = $values ? $values : $field->value ;
		
		$mainframe = &JFactory::getApplication();
		
		$document		= & JFactory::getDocument();
		$flexiparams 	=& JComponentHelper::getParams('com_flexicontent');
		$mediapath		= $flexiparams->get('media_path', 'components/com_flexicontent/medias');

		// some parameter shortcuts
		$thumbposition		= $field->parameters->get( 'thumbposition', 3 ) ;
		$w_l				= $field->parameters->get( 'w_l', 450 ) ;
		$h_l				= $field->parameters->get( 'h_l', 300 ) ;
		$w_s				= $field->parameters->get( 'w_s', 100 ) ;
		$h_s				= $field->parameters->get( 'h_s', 66 ) ;
		
		switch ($thumbposition) {
			case 1: // top
			$marginpos = 'top';
			$marginval = $h_s;
			break;

			case 2: // left
			$marginpos = 'left';
			$marginval = $w_s;
			break;

			case 4: // right
			$marginpos = 'right';
			$marginval = $w_s;
			break;

			case 3:
			default : // bottom
			$marginpos = 'bottom';
			$marginval = $h_s;
			break;
		}
		
		$scroll_thumbnails = $field->parameters->get( 'scroll_thumbnails', 1 ) ;
		switch ($thumbposition) {
			case 1: // top
			case 3:	default : // bottom
			$rows = ceil( (count($values) * ($w_s+8) ) / $w_l );  // thumbnail rows
			echo $rows;
			$series = ($scroll_thumbnails) ? 1: $rows;
			$series_size = ($h_s+8) * $series;
			break;

			case 2: // left
			case 4: // right
			$cols = ceil( (count($values) * ($h_s+8) ) / $h_l );  // thumbnail columns
			echo $cols; 
			$series = ($scroll_thumbnails) ? 1: $cols;
			$series_size = ($w_s+8) * $series;
			break;
		}
		
		static $js_and_css_added = false;
		
		$slideshowtype = $field->parameters->get( 'slideshowtype', 'Flash' );// default is normal slideshow
		$slideshowClass = 'Slideshow';
	  
		if ($values)
		{
			$htmltag_id = "slideshowContainer_".$field->name."_".$item->id;
			$slidethumb = "slideshowThumbnail_".$field->name."_".$item->id;
			if (!$js_and_css_added) {
			  // this allows you to override the default css files
			  $csspath = JPATH_ROOT.'/templates/'.$mainframe->getTemplate().'/css/minigallery.css';
			  if(file_exists($csspath)) {
			  	$document->addStyleSheet(JURI::root().'templates/'.$mainframe->getTemplate().'/css/minigallery.css');
			  }else{
			  	$document->addStyleSheet(JURI::root().'plugins/flexicontent_fields/minigallery/css/minigallery.css');
			  }
			  JHTML::_('behavior.mootools');
			  $document->addScript(JURI::root().'plugins/flexicontent_fields/minigallery/js/slideshow.js');
			  if($slideshowtype!='slideshow') {
			  	$document->addScript(JURI::root().'plugins/flexicontent_fields/minigallery/js/slideshow.'.strtolower($slideshowtype).'.js');
			  	$slideshowClass .= '.'.$slideshowtype;
			  }
			}
			$js_and_css_added = true;

			$transition = $field->parameters->get( 'transition', 'back' );
			$t_dir = $field->parameters->get( 't_dir', 'in' );
			$thumbnails = $field->parameters->get( 'thumbnails', '1' );
			$thumbnails = $thumbnails ? 'true' : 'false';
			$controller = $field->parameters->get( 'controller', '1' );
			$controller = $controller ? 'true' : 'false';
			$otheroptions = $field->parameters->get( 'otheroptions', '' );
			
			$css = "
			#$htmltag_id {
				width: ".$w_l."px;
				height: ".$h_l."px;
				margin-".$marginpos.": ".(($marginval+8)*$series)."px;
			}
			";
			
			/*
			if ($thumbposition == 1 || $thumbposition == 3) {
				$css .= "#thumbnails { width: ".$w_l."px; }";
			}
			if ($thumbposition == 2 || $thumbposition == 4) {
				$css .= ".$htmltag_id { float: left; } #{$htmltag_id}-thumbnails { float: left; width: ".($w_s + 10)."px; }";
			}*/
			
			if ($thumbposition==2 || $thumbposition==4) {
				$css .= "div .slideshow-thumbnails { ".$marginpos.": -".($series_size+4)."px; height: 100%; width: ".($series_size+4)."px; top:0px; }";
				$css .= "div .slideshow-thumbnails ul { width: ".$series_size."px; }";
				$css .= "div .slideshow-thumbnails ul li {  }";
			} else if ($thumbposition==1 || $thumbposition==3) {
				$css .= "div .slideshow-thumbnails { ".$marginpos.": -".($series_size+4)."px; height: ".$series_size."px; }";
				if ($series > 1) $css .= "div .slideshow-thumbnails ul { width:100%!important; }";
				$css .= "div .slideshow-thumbnails ul li { float: left!important;}";
			} else { // inside TODO
				$css .= "div .slideshow-thumbnails { ".$marginpos.": -".($marginval+8)."px; height: ".($h_s+8)."px; top:0px; z-index:100; }";
				$css .= "div .slideshow-thumbnails ul { width: 100%!important;}";
				$css .= "div .slideshow-thumbnails ul li { float: left!important;}";
			}
			$document->addStyleDeclaration($css);

			$otheroptions .= ($otheroptions?','.$otheroptions:'');
			$js = "
		  	window.addEvent('domready',function(){
				var options = {
					delay: ".$field->parameters->get( 'delay', 4000 ).",
					hu:'{$mediapath}/',
					transition:'{$transition}:{$t_dir}',
					duration: ".$field->parameters->get( 'duration', 1000 ).", 
					width: {$w_l},
					height: {$h_l},
					thumbnails: {$thumbnails},
					controller: {$controller}
					{$otheroptions}
				}
				show = new {$slideshowClass}('{$htmltag_id}', null, options);
			});
			";
			$document->addScriptDeclaration($js);
			
			$display = array();
			$thumbs = array();
			
			$usecaptions = (int)$field->parameters->get( 'usecaptions', 1 );
			$captions = '';
			if($usecaptions===2)
				$captions = $field->parameters->get( 'customcaptions', 'This is a caption' );
			$field->{$prop}  = '';
			//$field->{$prop} .= ($thumbposition > 2) ? '<div id="'.$htmltag_id.'" class="'.$htmltag_id.'"></div>' : '';
			$field->{$prop} .= '<div id="'.$htmltag_id.'" class="slideshow">';
			//$field->{$prop} .= '<div id="thumbnails">';
			$n = 0;
			foreach ($values as $value) {
				$filename = $this->getFileName( $value );
				if ($filename) {
					$img_path = $filename->filename;
					if(substr($filename->filename,0,7)!='http://') {
						$img_path = JURI::root(true) . '/' . $mediapath . '/' . $filename->filename;
					}
					$srcs 		= JURI::root().'components/com_flexicontent/librairies/phpthumb/phpThumb.php?src=' . $img_path . '&w='.$w_s.'&h='.$h_s.'&zc=1';
					$srcb 		= JURI::root().'components/com_flexicontent/librairies/phpthumb/phpThumb.php?src=' . $img_path . '&w='.$w_l.'&h='.$h_l.'&zc=1';
					
					//$display[] = "<a href=\"".$srcb."\"><img src=\"".$srcb."\" id=\"".$htmltag_id.'-'.$n."\" alt=\"xxx\" border=\"0\" /></a>\n";
					if($usecaptions===1) $captions = $filename->altname;
		$display[] = "<a href=\"javascript:;\"><img src=\"".$srcb."\" id=\"".$htmltag_id.'_'.$n."\" alt=\"{$captions}\" border=\"0\" /></a>\n";
		$thumbs[] = "<li><a href=\"#{$htmltag_id}_{$n}\"><img src=\"{$srcs}\" border=\"0\" /></a></li>\n";
					$n++;
				}
			}
			
			$field->{$prop} .= '<div class="slideshow-images">';
			$field->{$prop} .= implode("\n", $display);
			$field->{$prop} .= "</div>\n";
			$field->{$prop} .= '<div class="slideshow-thumbnails">';
			$field->{$prop} .= '<ul>'.implode("\n", $thumbs).'</ul>';
			$field->{$prop} .= "</div>\n";
			$field->{$prop} .= "</div><div class=\"clr\"></div><div class=\"clear\"></div>\n";
			//$field->{$prop} .= ($thumbposition < 3) ? '<div id="'.$htmltag_id.'" class="'.$htmltag_id.'"></div>' : '';
		}
	}
	

	function onBeforeSaveField($field, &$post, $file)
	{
		// execute the code only if the field type match the plugin type
		if($field->field_type != 'minigallery') return;
		if(!$post) return;

		$mainframe =& JFactory::getApplication();
		
		$post = array_unique($post);
	}


	function getFileName( $value )
	{
		$db =& JFactory::getDBO();

		$query = 'SELECT filename, altname, ext'
				. ' FROM #__flexicontent_files'
				. ' WHERE id = '. (int) $value
				;
		$db->setQuery($query);
		$filename = $db->loadObject();

		return $filename;
	}
	

	function addIcon( &$file )
	{
		switch ($file->ext)
		{
			// Image
			case 'jpg':
			case 'png':
			case 'gif':
			case 'xcf':
			case 'odg':
			case 'bmp':
			case 'jpeg':
				$file->icon = JURI::root().'components/com_flexicontent/assets/images/mime-icon-16/image.png';
			break;

			// Non-image document
			default:
				$icon = JPATH_SITE.DS.'components'.DS.'com_flexicontent'.DS.'assets'.DS.'images'.DS.'mime-icon-16'.DS.$file->ext.'.png';
				if (file_exists($icon)) {
					$file->icon = JURI::root().'components/com_flexicontent/assets/images/mime-icon-16/'.$file->ext.'.png';
				} else {
					$file->icon = JURI::root().'components/com_flexicontent/assets/images/mime-icon-16/unknown.png';
				}
			break;
		}
		return $file;
	}
}
