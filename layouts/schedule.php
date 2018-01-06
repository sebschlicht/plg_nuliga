<?php
/**
 * Created by PhpStorm.
 * User: sebschlicht
 * Date: 06.01.18
 * Time: 09:58
 */

// No direct access to this file
defined('JPATH_BASE') or die('Restricted access');

?>
<div class="nuliga">
    <?php if ($displayData['matches']): ?>
        <table>
            <tr>
                <th class="pnlg-weekday"><?php echo JText::_('COM_NULIGA_TABLE_MATCHES_COLUMN_HEADER_WEEKDAY');?></th>
                <th class="pnlg-date"><?php echo JText::_('COM_NULIGA_TABLE_MATCHES_COLUMN_HEADER_DATE');?></th>
                <th class="pnlg-time"><?php echo JText::_('COM_NULIGA_TABLE_MATCHES_COLUMN_HEADER_TIME');?></th>
                <th class="pnlg-hall"><?php echo JText::_('COM_NULIGA_TABLE_MATCHES_COLUMN_HEADER_HALL');?></th>
                <th class="pnlg-nuliga-id"><?php echo JText::_('COM_NULIGA_TABLE_MATCHES_COLUMN_HEADER_NULIGAID');?></th>
                <th class="pnlg-home"><?php echo JText::_('COM_NULIGA_TABLE_MATCHES_COLUMN_HEADER_HOME');?></th>
                <th class="pnlg-guest"><?php echo JText::_('COM_NULIGA_TABLE_MATCHES_COLUMN_HEADER_GUEST');?></th>
                <th class="pnlg-result"><?php echo JText::_('COM_NULIGA_TABLE_MATCHES_COLUMN_HEADER_RESULT');?></th>
            </tr>
            <?php foreach($displayData['matches'] as $match): ?>
                <tr>
                    <?php $hasDate = !empty($match->weekday); ?>
                    <?php if ($hasDate): ?>
                        <td class="pnlg-weekday"><?php echo $match->weekday; ?></td>
                        <td class="pnlg-date"><?php echo $match->date; ?></td>
                        <td class="pnlg-time"><?php echo $match->time; ?></td>
                    <?php else: ?>
                        <td class="pnlg-weekday"></td>
                        <td class="pnlg-date" colspan="2"><?php echo $match->date; ?></td>
                    <?php endif; ?>
                    <td class="pnlg-hall"><?php echo $match->hall; ?></td>
                    <td class="pnlg-nuliga-id"><?php echo $match->nr; ?></td>
                    <td class="pnlg-home"><?php echo $match->home; ?></td>
                    <td class="pnlg-guest"><?php echo $match->guest; ?></td>
                    <td class="pnlg-result"><?php echo $match->goals; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p><?php echo JText::_('COM_NULIGA_TABLE_RENDERING_FAILURE');?></p>
    <?php endif; ?>
</div>
