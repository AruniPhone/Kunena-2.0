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

JHTML::_('behavior.formvalidation');
JHTML::_('behavior.tooltip');
?>
		<div id="kmodtopic" class="ksection">
			<h2 class="kheader"><a rel="kmod-detailsbox"><?php echo !isset($this->mesid) ? JText::_('COM_KUNENA_BUTTON_MODERATE_MESSAGE') : JText::_('COM_KUNENA_TITLE_MODERATE_TOPIC') ?></a></h2>
			<p class="kheader-desc">Category: <strong><?php echo $this->escape($this->category->name) ?></strong></p>
			<div class="kdetailsbox kmod-detailsbox" id="kmod-detailsbox" >
				<form action="<?php echo KunenaRoute::_('index.php?option=com_kunena') ?>" name="myform" method="post">
				<ul class="kmod-postlist">
					<li>
						<ul class="kposthead">
							<li class="kposthead-replytitle"><h3><?php echo !isset($this->mesid) ? $this->escape($this->message->subject) : $this->escape($this->topic->subject)  ?></h3></li>
						</ul>

						<div class="kmod-container">
						<?php if (isset($this->message)) : ?>
							<p><?php echo JText::_('COM_KUNENA_MODERATION_TITLE_SELECTED') ?>:</p>
							<div class="kmoderate-message">
								<h4><?php echo $this->message->subject ?></h4>
								<div class="kmessage-timeby">
									<span class="kmessage-time" title="<?php echo KunenaDate::getInstance($this->message->time)->toKunena('config_post_dateformat_hover'); ?>">
										<?php echo JText::_('COM_KUNENA_POSTED_AT')?> <?php echo KunenaDate::getInstance($this->message->time)->toKunena('config_post_dateformat'); ?>
									</span>
								<span class="kmessage-by"><?php echo JText::_('COM_KUNENA_BY')?> <?php echo CKunenaLink::GetProfileLink($this->message->userid, $this->message->name) ?></span></div>
								<div class="kmessage-avatar"><?php echo KunenaFactory::getAvatarIntegration()->getLink(KunenaFactory::getUser($this->message->userid)); ?></div>
								<div class="kmessage-msgtext"><?php echo KunenaHtmlParser::stripBBCode ($this->message->message, 300) ?></div>
								<div class="clr"></div>
							</div>

							<p>
							<?php echo JText::_('COM_KUNENA_MODERATE_THIS_USER') ?>:
								<strong><?php echo CKunenaLink::GetProfileLink($this->message->userid, $this->escape($this->message->name).' ('.$this->message->userid.')') ?></strong>
							</p>

							<ul>
								<li><label for="kmoderate-mode-selected" class="hasTip" title="<?php echo JText::_('COM_KUNENA_MODERATION_MOVE_SELECTED') ?> :: "><input type="radio" value="0" checked="checked" name="mode" id="kmoderate-mode-selected"><?php echo JText::_('COM_KUNENA_MODERATION_MOVE_SELECTED') ?></label></li>
								<li><label for="kmoderate-mode-newer" class="hasTip" title="<?php echo JText::sprintf ( 'COM_KUNENA_MODERATION_MOVE_NEWER', $this->escape($this->replies) ) ?> :: "><input type="radio" value="2" name="mode" id="kmoderate-mode-newer"><?php echo JText::sprintf ( 'COM_KUNENA_MODERATION_MOVE_NEWER', $this->escape($this->replies) ) ?></label></li>
							</ul>
							<br/>
							<?php else: ?>
							 <label><?php echo JText::_('COM_KUNENA_MODERATION_DEST') ?>:</label>
							<?php endif; ?>

							<div class="modcategorieslist">
								<label for="kmod_categories1"><?php echo JText::_('COM_KUNENA_MODERATION_DEST_CATEGORY') ?>:</label>
								<?php echo $this->categorylist ?>

							</div>

							<div class="modtopicslist">
								<label for="kmod_targettopic1"><?php echo JText::_('COM_KUNENA_MODERATION_DEST_TOPIC') ?>:</label>
								<?php echo $this->topiclist ?>
							</div>

							<div class="kmod_subject">
								<label for="kmod_topicsubject1"><?php echo JText::_('COM_KUNENA_MODERATION_TITLE_DEST_SUBJECT') ?>:</label>
								<input id="kmod_topicsubject1" type="text" value="<?php echo !isset($this->mesid) ? $this->escape($this->message->subject) : $this->escape($this->topic->subject)  ?>" name="subject[1]" class="input hasTip" size="50" title="<?php echo JText::_('COM_KUNENA_MODERATION_TITLE_DEST_SUBJECT') ?> :: <?php echo JText::_('COM_KUNENA_MODERATION_TITLE_DEST_ENTER_SUBJECT') ?>" />
							</div>

							<div class="clr"></div>
							<label for="kmod_shadow1" class="hasTip" title="<?php echo JText::_('COM_KUNENA_MODERATION_TOPIC_SHADOW') ?> :: "><input id="kmod_shadow1" type="checkbox" value="1" name="shadow[1]"><?php echo JText::_('COM_KUNENA_MODERATION_TOPIC_SHADOW') ?></label>
						</div>

					</li>

				</ul>

				<div class="kpost-buttons">
					<button title="<?php echo (JText::_('COM_KUNENA_EDITOR_HELPLINE_SUBMIT'));?>" type="submit" class="kbutton"> <?php echo JText::_('COM_KUNENA_GEN_CONTINUE') ?> </button>
					<button onclick="javascript:window.history.back();" title="<?php echo (JText::_('COM_KUNENA_EDITOR_HELPLINE_CANCEL'));?>" type="button" class="kbutton"> <?php echo JText::_('COM_KUNENA_CANCEL') ?> </button>
				</div>

				<div class="clr"></div>
				<input type="hidden" name="view" value="topic" />
				<input type="hidden" name="task" value="move" />
				<input type="hidden" name="catid" value="<?php echo $this->category->id; ?>" />
				<input type="hidden" name="id" value="<?php echo $this->topic->id; ?>" />
				<?php if (isset($this->message)) : ?>
				<input type="hidden" name="mesid" value="<?php echo $this->message->id; ?>" />
				<?php endif; ?>
				<?php echo JHTML::_( 'form.token' ); ?>
			</form>
			</div>
		</div>