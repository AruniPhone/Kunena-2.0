<?php
/**
 * Kunena Component
 * @package Kunena.Site
 * @subpackage Models
 *
 * @copyright (C) 2008 - 2011 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

/**
 * Common Model for Kunena
 *
 * @since		2.0
 */
class KunenaModelCommon extends KunenaModel {
	protected function populateState() {
		$params = $this->getParameters();
		$this->setState ( 'params', $params );
	}

	public function getAnnouncement() {
		$db = JFactory::getDBO();
		$query = "SELECT * FROM #__kunena_announcement WHERE published='1' ORDER BY created DESC";
		$db->setQuery ( $query, 0, 1 );
		$announcement = $db->loadObject ();
		if (KunenaError::checkDatabaseError()) return;

		return $announcement;
	}
}