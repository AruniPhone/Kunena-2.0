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

// Dont allow direct linking
defined( '_JEXEC' ) or die();


$kunena_db = &JFactory::getDBO();
$kunena_app =& JFactory::getApplication();
$kunena_config =& CKunenaConfig::getInstance();
?>

<?php
if ($kunena_config->showwhoisonline > 0)
{
?>
<div class="k_bt_cvr1">
<div class="k_bt_cvr2">
<div class="k_bt_cvr3">
<div class="k_bt_cvr4">
<div class="k_bt_cvr5">
    <table class = "kblocktable " id="kwhoispage" border = "0" cellspacing = "0" cellpadding = "0" width="100%">
        <thead>
            <tr>
                <th colspan = "4">
                   <div class = "ktitle_cover">
                        <span class="ktitle"><?php echo $kunena_app->getCfg('sitename'); ?> - <?php echo JText::_('COM_KUNENA_WHO_WHOIS_ONLINE'); ?></span>
                    </div>
            </tr>
        </thead>

        <tbody>
            <tr class = "ksth">
                <th class = "th-1 ksectiontableheader">
<?php echo JText::_('COM_KUNENA_WHO_ONLINE_USER'); ?>

                </th>

                <th class = "th-2 ksectiontableheader"><?php echo JText::_('COM_KUNENA_WHO_ONLINE_TIME'); ?>
                </th>

                <th class = "th-3 ksectiontableheader"><?php echo JText::_('COM_KUNENA_WHO_ONLINE_FUNC'); ?>
                </th>
            </tr>

            <?php
            $query = "SELECT w.*, u.id, u.username, f.showOnline FROM #__fb_whoisonline AS w LEFT JOIN #__users AS u ON u.id=w.userid LEFT JOIN #__fb_users AS f ON u.id=f.userid ORDER BY w.time DESC";
            $kunena_db->setQuery($query);
            $users = $kunena_db->loadObjectList();
            check_dberror ( "Unable to load online users." );
            $k = 0; //for alternating rows
            $tabclass = array
            (
                "sectiontableentry1",
                "sectiontableentry2"
            );

            foreach ($users as $user)
            {
                $k = 1 - $k;

                if ($user->userid == 0) {
                    $user->username = JText::_('COM_KUNENA_GUEST');
                } else if ($user->showOnline < 1 && !CKunenaTools::isModerator($kunena_my->id)) {
                	continue;
                }

                $time = date("H:i:s", $user->time + $kunena_config->board_ofset*3600);
            ?>

                <tr class = "k<?php echo $tabclass[$k];?>">
                    <td class = "td-1">
                        <div style = "float: right; width: 14ex;">
                        </div>

                        <span>

                        <?php
                        if ($user->userid == 0) {
                            echo $user->username;
                        }
                        else
                        {
				echo CKunenaLink::GetProfileLink($kunena_config, $user->userid, $user->username);
                        }
                        ?>

                        </span>

                        <?php
                        if (CKunenaTools::isModerator($kunena_my->id))
                        {
                        ?>

                            (<?php echo $user->userip; ?>)

                        <?php
                        }
                        ?>
                    </td>

                    <td class = "td-2" nowrap = "nowrap"><?php echo $time; ?>
                    </td>

                    <td class = "td-3">
                        <strong><a href = "<?php echo $user->link;?>" target = "_blank"><?php echo $user->what; ?></a></strong>
                    </td>
                </tr>

        <?php
            }
        ?>
    </table>
</div>
</div>
</div>
</div>
</div>
<?php
}
else
{
?>

    <div style = "border:1px solid #FF6600; background: #FF9966; padding:20px; text-align:center;">
        <h1>Not Active</h1>
    </div>

<?php
}
?>