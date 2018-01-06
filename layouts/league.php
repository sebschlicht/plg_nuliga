<?php
/**
 * Created by PhpStorm.
 * User: sebschlicht
 * Date: 06.01.18
 * Time: 09:56
 */

// No direct access to this file
defined('JPATH_BASE') or die('Restricted access');

?>
<div class="nuliga">
    <?php if ($displayData['leagueteams']): ?>
        <table>
            <tr>
                <th class="pnlg-rank"><?php echo JText::_('COM_NULIGA_TABLE_LEAGUE_COLUMN_HEADER_RANK');?></th>
                <th class="pnlg-team"><?php echo JText::_('COM_NULIGA_TABLE_LEAGUE_COLUMN_HEADER_TEAM');?></th>
                <th class="pnlg-num-matches"><?php echo JText::_('COM_NULIGA_TABLE_LEAGUE_COLUMN_HEADER_NUMMATCHES');?></th>
                <th class="pnlg-num-wins"><?php echo JText::_('COM_NULIGA_TABLE_LEAGUE_COLUMN_HEADER_NUMWINS');?></th>
                <th class="pnlg-num-draws"><?php echo JText::_('COM_NULIGA_TABLE_LEAGUE_COLUMN_HEADER_NUMDRAWS');?></th>
                <th class="pnlg-num-losses"><?php echo JText::_('COM_NULIGA_TABLE_LEAGUE_COLUMN_HEADER_NUMLOSSES');?></th>
                <th class="pnlg-goals"><?php echo JText::_('COM_NULIGA_TABLE_LEAGUE_COLUMN_HEADER_GOALS');?></th>
                <th class="pnlg-goal-diff"><?php echo JText::_('COM_NULIGA_TABLE_LEAGUE_COLUMN_HEADER_GOALDIFF');?></th>
                <th class="pnlg-points"><?php echo JText::_('COM_NULIGA_TABLE_LEAGUE_COLUMN_HEADER_POINTS');?></th>
            </tr>
            <?php foreach($displayData['leagueteams'] as $team): ?>
                <tr<?php if (in_array($team->name, $displayData['highlight'])) echo ' class="highlight"'; ?>>
                    <td class="pnlg-rank"><?php echo $team->rank; ?></td>
                    <td class="pnlg-team"><?php echo $team->name; ?></td>
                    <td class="pnlg-num-matches"><?php echo $team->numMatches; ?></td>
                    <td class="pnlg-num-wins"><?php echo $team->numWins; ?></td>
                    <td class="pnlg-num-draws"><?php echo $team->numDraws; ?></td>
                    <td class="pnlg-num-losses"><?php echo $team->numLosses; ?></td>
                    <td class="pnlg-goals"><?php echo $team->goals; ?></td>
                    <td class="pnlg-goal-diff"><?php echo $team->goalDiff; ?></td>
                    <td class="pnlg-points"><?php echo $team->points; ?></td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php else: ?>
        <p><?php echo JText::_('COM_NULIGA_TABLE_RENDERING_FAILURE');?></p>
    <?php endif; ?>
</div>
