<?php
/**
 * Kunena Component
 * @package Kunena.Template.Default20
 * @subpackage Topic
 *
 * @copyright (C) 2008 - 2011 Kunena Team. All rights reserved.
 * @license http://www.gnu.org/copyleft/gpl.html GNU/GPL
 * @link http://www.kunena.org
 **/
defined ( '_JEXEC' ) or die ();
?>
<div id="topic-buttonbar" class="buttonbar">
	<div class="paginationbar">
		<?php echo $this->getPagination(4) ?>
	</div>
	<ul class="message-buttons">
		<!-- User buttons -->
		<?php if (!empty($this->topic_reply)) : ?><li class="button topic-replytopic"><?php echo $this->topic_reply ?></li><?php endif ?>
		<?php if (!empty($this->topic_subscribe)) : ?><li class="button topic-subscribe"><?php echo $this->topic_subscribe ?></li><?php endif ?>
		<?php if (!empty($this->topic_favorite)) : ?><li class="button topic-favorite"><?php echo $this->topic_favorite ?></li><?php endif ?>
		<!-- Moderator buttons -->
		<?php if (!empty($this->topic_lock)) : ?><li class="button topic-lock"><?php echo $this->topic_lock ?></li><?php endif ?>
		<?php if (!empty($this->topic_sticky)) : ?><li class="button topic-sticky"><?php echo $this->topic_sticky ?></li><?php endif ?>
		<?php if (!empty($this->topic_moderate)) : ?><li class="button topic-moderatetopic"><?php echo $this->topic_moderate ?></li><?php endif ?>
		<?php if (!empty($this->topic_delete)) : ?><li class="button topic-delete"><?php echo $this->topic_delete ?></li><?php endif ?>
		<?php if (!empty($this->layout_buttons)) : ?>
		<li><?php echo implode('</li> <li>', $this->layout_buttons) ?></li>
		<?php endif ?>
	</ul>
</div>
<div class="spacer"></div>