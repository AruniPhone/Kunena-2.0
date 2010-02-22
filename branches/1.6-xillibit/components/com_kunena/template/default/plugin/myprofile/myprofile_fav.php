<?php
/**
* @version $Id$
* Kunena Component
* @package Kunena
*
* @Copyright (C) 2008 - 2010 Kunena Team All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.kunena.com
*
* Based on FireBoard Component
* @Copyright (C) 2006 - 2007 Best Of Joomla All rights reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @link http://www.bestofjoomla.com
*
* Based on Joomlaboard Component
* @copyright (C) 2000 - 2004 TSMF / Jan de Graaff / All Rights Reserved
* @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
* @author TSMF & Jan de Graaff
**/
defined( '_JEXEC' ) or die();


global $total, $limitstart, $limit;
$kunena_db = &JFactory::getDBO();
$kunena_config = CKunenaConfig::getInstance ();

?>
<div class="k_bt_cvr1">
<div class="k_bt_cvr2">
<div class="k_bt_cvr3">
<div class="k_bt_cvr4">
<div class="k_bt_cvr5">
<form action = "<?php echo CKunenaLink::GetMyProfileURL($kunena_config, '','','nofollow', false, 'unfavorite'); ?>" method = "post" name = "postform">
	<input type = "hidden" name = "do" value = "unfavorite"/>
	<table class = "kblocktable" id = "kforumprofile_fav" border = "0" cellspacing = "0" cellpadding = "0" width="100%">
		<thead>
			<tr>
				<th colspan = "3">
					<div class = "ktitle_cover km">
						<span class = "ktitle ks"><?php echo JText::_('COM_KUNENA_USER_FAVORITES'); ?></span>
					</div>
				</th>
			</tr>
		</thead>

		<tbody id = "kuserprofile_tbody">
			<tr class = "ksth">
				<th class = "th-1 ksectiontableheader"><?php echo JText::_('COM_KUNENA_GEN_TOPICS'); ?></th>
				<th class = "th-2 ksectiontableheader" style = "text-align:center; width:25%"><?php echo JText::_('COM_KUNENA_GEN_AUTHOR'); ?></th>
				<th class = "th-3 ksectiontableheader"><?php echo JText::_('COM_KUNENA_GEN_DELETE'); ?></th>
			</tr>

			<?php
			$enum    = 1; //reset value
			$tabclass = array
			(
				"sectiontableentry1",
				"sectiontableentry2"
			);            //alternating row CSS classes

			$k       = 0; //value for alternating rows

			jimport('joomla.html.pagination');
			$pageNav = new JPagination($total, $limitstart, $limit);

			if ($this->kunena_cfavslist > 0)
			{
				foreach ($this->kunena_favslist as $favs)
				{ //get all message details for each favorite
					$kunena_db->setQuery("SELECT * FROM #__fb_messages WHERE id='{$favs->thread}'");
					$favdet = $kunena_db->loadObjectList();
						check_dberror("Unable to load messages.");

					foreach ($favdet as $fav)
					{
						$k = 1 - $k;
			?>
						<tr class="k<?php echo $tabclass[$k]; ?>">
						<td class="td-1" width="73%" align="left"><?php echo $enum; ?>: <?php echo CKunenaLink::GetThreadLink('view', $fav->catid, $fav->id, kunena_htmlspecialchars(stripslashes($fav->subject)),kunena_htmlspecialchars(stripslashes($fav->subject)),'nofollow'); ?></td>

						<td class = "td-2" style = "text-align:center; width:25%"> <?php echo kunena_htmlspecialchars(stripslashes($fav->name)); ?></td>

						<td class = "td-3" width = "1%">
							<input id = "cid<?php echo $enum;?>" name = "cid[]" value = "<?php echo $favs->thread; ?>"  type = "checkbox"/>
						</td>

						</tr>

			<?php
						$enum++;
					}
				}
			?>

				<tr>
					<td colspan = "3" class = "kprofile-bottomnav" style = "text-align:right">
<?php echo JText::_('COM_KUNENA_USRL_DISPLAY_NR'); ?>

<?php
// echo $pageNav->getLimitBox("index.php?option=com_kunena&amp;func=myprofile&amp;do=showfav" . KUNENA_COMPONENT_ITEMID_SUFFIX);
?>

			<input type = "submit" class = "button" value = "<?php echo JText::_('COM_KUNENA_GEN_DELETE');?>"/>
					</td>
				</tr>

			<?php
			}
			else
			{
				echo '<tr class="k' . $tabclass[$k] . '"><td class="td-1" colspan = "3">' . JText::_('COM_KUNENA_USER_NOFAVORITES') . '</td></tr>';
			}
			?>

			<tr><td colspan = "3" class = "kprofile-bottomnav">
					<?php
					// TODO: fxstein - Need to perform SEO cleanup
					echo $pageNav->getPagesLinks("index.php?option=com_kunena&amp;func=myprofile&amp;do=showfav" . KUNENA_COMPONENT_ITEMID_SUFFIX);
					?>

					<br/>
<?php echo $pageNav->getPagesCounter(); ?>
				</td>
			</tr>
		</tbody>
	</table>
</form></div>
</div>
</div>
</div>
</div>