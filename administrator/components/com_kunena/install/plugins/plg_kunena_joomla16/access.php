<?php
/**
 * Kunena Plugin
 * @package Kunena.Plugins
 * @subpackage Joomla16
 *
 * @Copyright (C) 2008 - 2011 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();

/**
 * Kunena Access Control for Joomla 1.6+
 */
class KunenaAccessJoomla {
	protected $params = null;

	public function __construct($params) {
		$this->params = $params;
	}

	/**
	 * Get list of supported access types.
	 *
	 * List all access types you want to handle. All names must be less than 20 characters.
	 * Examples: joomla.level, mycomponent.groups, mycomponent.vipusers
	 *
	 * @return array	Supported access types.
	 */
	public function &getAccessTypes() {
		static $accesstypes = array('joomla.level', 'none');
		return $accesstypes;
	}

	/**
	 * Get group name in selected access type.
	 *
	 * @param string	Access type.
	 * @param int		Group id.
	 */
	public function getGroupName($accesstype, $id=null){
		static $groups = array();
		if (!isset($groups[$accesstype])) {
			// Cache all group names.
			$db = JFactory::getDBO ();
			$query = $db->getQuery(true);
			$query->select('id, title');
			if ($accesstype == 'none') {
				$query->from('#__usergroups');
				$db->setQuery((string)$query);
			} elseif ($accesstype == 'joomla.level') {
				$query->from('#__viewlevels');
				$db->setQuery((string)$query);
			} else {
				return '';
			}
			$groups[$accesstype] = $db->loadObjectList('id');
		}
		if ($id !== null) {
			return isset($groups[$accesstype][$id]) ? $groups[$accesstype][$id]->title : '';
		}
		return $groups[$accesstype];
	}

	/**
	 * Get HTML list of the available groups
	 *
	 * @param string	Access type.
	 * @param int		Group id.
	 */
	public function getAccessOptions($accesstype, $category) {
		if (!$accesstype || $accesstype == 'joomla.level') {
			$html ['joomla-level']['access'] = array(
				'title' => JText::_('PLG_KUNENA_JOOMLA_ACCESS_LEVEL_TITLE'),
				'desc' => JText::_('PLG_KUNENA_JOOMLA_ACCESS_LEVEL_DESC') .'<br /><br />'. JText::_('PLG_KUNENA_JOOMLA_ACCESS_LEVEL_DESC_J16'),
				'input' => JHtml::_('access.assetgrouplist', 'access', $category->accesstype == 'joomla.level' ? $category->access : 1)
			);
		}
		if (!$accesstype || $accesstype == 'none') {
			$yesno = array ();
			$yesno [] = JHTML::_ ( 'select.option', 0, JText::_ ( 'COM_KUNENA_ANN_NO' ) );
			$yesno [] = JHTML::_ ( 'select.option', 1, JText::_ ( 'COM_KUNENA_ANN_YES' ) );

			$html ['none']['pub_access'] = array(
				'title' => JText::_('PLG_KUNENA_JOOMLA_ACCESS_GROUP_PRIMARY_TITLE'),
				'desc' => JText::_('PLG_KUNENA_JOOMLA_ACCESS_GROUP_PRIMARY_DESC') .'<br /><br />'.
						JText::_('PLG_KUNENA_JOOMLA_ACCESS_GROUP_PRIMARY_DESC2') .'<br /><br />'.
						JText::_('PLG_KUNENA_JOOMLA_ACCESS_GROUP_PRIMARY_DESC_J16'),
				'input' => JHTML::_ ( 'access.usergroup', 'pub_access', $category->pub_access, 'class="inputbox" size="10"', false)
			);
			$html ['none']['pub_recurse'] = array(
				'title' => JText::_('PLG_KUNENA_JOOMLA_ACCESS_GROUP_PRIMARY_CHILDS_TITLE'),
				'desc' => JText::_('PLG_KUNENA_JOOMLA_ACCESS_GROUP_PRIMARY_CHILDS_DESC'),
				'input' => JHTML::_ ( 'select.genericlist', $yesno, 'pub_recurse', 'class="inputbox" size="1"', 'value', 'text', $category->pub_recurse )
			);
			$html ['none']['admin_access'] = array(
				'title' => JText::_('PLG_KUNENA_JOOMLA_ACCESS_GROUP_SECONDARY_TITLE'),
				'desc' => JText::_('PLG_KUNENA_JOOMLA_ACCESS_GROUP_SECONDARY_DESC') .'<br /><br />'.
						JText::_('PLG_KUNENA_JOOMLA_ACCESS_GROUP_SECONDARY_DESC2') .'<br /><br />'.
						JText::_('PLG_KUNENA_JOOMLA_ACCESS_GROUP_SECONDARY_DESC_J16'),
				'input' => JHTML::_ ( 'access.usergroup', 'admin_access', $category->admin_access, 'class="inputbox" size="10"', false)
			);
			$html ['none']['admin_recurse'] = array(
				'title' => JText::_('PLG_KUNENA_JOOMLA_ACCESS_GROUP_SECONDARY_CHILDS_TITLE'),
				'desc' => JText::_('PLG_KUNENA_JOOMLA_ACCESS_GROUP_SECONDARY_CHILDS_DESC'),
				'input' => JHTML::_ ( 'select.genericlist', $yesno, 'admin_recurse', 'class="inputbox" size="1"', 'value', 'text', $category->admin_recurse )
			);
		}
		return $html;
	}

	/**
	 * Load moderators and administrators for listed categories.
	 *
	 * This function is used to add category administrators and moderators to listed categories. In addition
	 * integration can also add global administrators (catid=0).
	 *
	 * Results may be cached.
	 *
	 * @param array $categories		List of categories, null = all.
	 *
	 * @return array of (catid=>userid)
	 */
	public function &loadCategoryRoles(array $categories = null) {
		$list = array();

		// Currently we have only global administrators in Joomla
		$admins = array_merge($this->getAuthorisedUsers('core.admin', 'com_kunena'), $this->getAuthorisedUsers('core.manage', 'com_kunena'));
		foreach ( $admins as $userid ) {
			$item = new StdClass();
			$item->user_id = (int) $userid;
			$item->category_id = 0;
			$item->role = KunenaForum::ADMINISTRATOR;
			$list[] = $item;
		}
		return $list;
	}

	/**
	 * Authorise list of categories.
	 *
	 * Function accepts array of id indexed KunenaForumCategory objects and removes unauthorised
	 * categories from the list.
	 *
	 * Results for the current user are saved into session.
	 *
	 * @param int $userid			User who needs the authorisation (null=current user, 0=visitor).
	 * @param array $categories		List of categories in access type.
	 *
	 * @return array, where category ids are in the keys.
	 */
	public function &authoriseCategories($userid, array &$categories) {
		$user = JFactory::getUser($userid);

		$accesslevels = (array) $user->authorisedLevels();
		$groups_r = (array) JAccess::getGroupsByUser($user->id, true);
		$groups = (array) JAccess::getGroupsByUser($user->id, false);

		$catlist = array();
		foreach ( $categories as $category ) {
			// Check against Joomla access level
			if ($category->accesstype == 'joomla.level') {
				if ( in_array($category->access, $accesslevels) ) {
					$catlist[$category->id] = $category->id;
				}
			}
			// Check against Joomla user group
			elseif ($category->accesstype == 'none') {
				$pub_access = in_array($category->pub_access, $category->pub_recurse ? $groups_r : $groups);
				$admin_access = in_array($category->admin_access, $category->admin_recurse ? $groups_r : $groups);
				if ($pub_access || $admin_access) {
					$catlist[$category->id] = $category->id;
				}
			}
		}
		return $catlist;
	}

	/**
	 * Authorise list of userids to topic or category.
	 *
	 * @param	mixed	Category or topic.
	 * @param	array	list(allow, deny).
	 */
	public function &authoriseUsers(KunenaDatabaseObject $topic, array &$userids) {
		if (empty($userids)) {
			return;
		}

		$allow = $deny = array();
		$category = $topic->getCategory();
		if ($category->accesstype == 'joomla.level') {
			// Check against Joomla access levels
			$groups = $this->getGroupsByViewLevel($category->access);
			$allow = $this->getUsersByGroup($groups, true, $userids);
		} elseif ($category->accesstype == 'none') {
			if ($category->pub_access <= 0) return;
			// Check against Joomla user groups
			$public = $this->getUsersByGroup($category->pub_access, $category->pub_recurse, $userids);
			$admin = $category->admin_access && $category->admin_access != $category->pub_access ? $this->getUsersByGroup($category->admin_access, $category->admin_recurse, $userids) : array();
			$allow = array_merge ( $public, $admin );
		}
		return array($allow, $deny);
	}

	// Internal functions

	/**
	 * Method to return a list of groups which have view level (derived from Joomla 1.6)
	 *
	 * @param	integer	$userId	Id of the user for which to get the list of authorised view levels.
	 *
	 * @return	array	List of view levels for which the user is authorised.
	 */
	protected function getGroupsByViewLevel($viewlevel) {
		// Only load the view levels once.
		if (empty(self::$viewLevels)) {
			// Get a database object.
			$db = JFactory::getDBO();

			// Build the base query.
			$query = $db->getQuery(true);
			$query->select('id, rules');
			$query->from('`#__viewlevels`');

			// Set the query for execution.
			$db->setQuery((string) $query);

			// Build the view levels array.
			foreach ($db->loadAssocList() as $level) {
				self::$viewLevels[$level['id']] = (array) json_decode($level['rules']);
			}
		}
		return isset(self::$viewLevels[$viewlevel]) ? self::$viewLevels[$viewlevel] : array();
	}

	/**
	 * Method to return a list of user Ids contained in a Group (derived from Joomla 1.6)
	 *
	 * @param	int		$groupId	The group Id
	 * @param	boolean	$recursive	Recursively include all child groups (optional)
	 *
	 * @return	array
	 */
	protected function getUsersByGroup($groupId, $recursive = false, $inUsers = array()) {
		// Get a database object.
		$db = JFactory::getDbo();

		$test = $recursive ? '>=' : '=';

		if (empty($groupId)) {
			return array();
		}
		if (is_array($groupId)) {
			$groupId = implode(',', $groupId);
		}
		$inUsers = implode(',', $inUsers);

		// First find the users contained in the group
		$query	= $db->getQuery(true);
		$query->select('DISTINCT(user_id)');
		$query->from('#__usergroups as ug1');
		$query->join('INNER','#__usergroups AS ug2 ON ug2.lft'.$test.'ug1.lft AND ug1.rgt'.$test.'ug2.rgt');
		$query->join('INNER','#__user_usergroup_map AS m ON ug2.id=m.group_id');
		$query->where("ug1.id IN ({$groupId})");
		if ($inUsers) $query->where("user_id IN ({$inUsers})");

		$db->setQuery($query);

		$result = (array) $db->loadResultArray();

		// Clean up any NULL values, just in case
		JArrayHelper::toInteger($result);

		return $result;
	}

	protected function getAuthorisedUsers($action, $asset = null) {
		$action = strtolower(preg_replace('#[\s\-]+#', '.', trim($action)));
		$asset  = strtolower(preg_replace('#[\s\-]+#', '.', trim($asset)));

		// Default to the root asset node.
		if (empty($asset)) {
			$asset = 1;
		}

		// Get all asset rules
		$rules = JAccess::getAssetRules ( $asset, true );
		$data = $rules->getData ();

		// Get all action rules for the asset
		$groups = array ();
		if (!empty($data [$action])) {
			$groups = $data [$action]->getData ();
		}

		// Split groups into allow and deny list
		$allow = array ();
		$deny = array ();
		foreach ( $groups as $groupid => $access ) {
			if ($access) {
				$allow[] = $groupid;
			} else {
				$deny[] = $groupid;
			}
		}

		// Get userids
		if ($allow) {
			// These users can do the action
			$allow = $this->getUsersByGroup ( $allow, true );
		}
		if ($deny) {
			// But these users have explicit deny for the action
			$deny = $this->getUsersByGroup ( $deny, true );
		}

		// Remove denied users from allowed users list
		return array_diff ( $allow, $deny );
	}
}
