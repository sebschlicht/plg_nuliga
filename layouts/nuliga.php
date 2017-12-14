<?php
/**
 * Created by PhpStorm.
 * User: sebschlicht
 * Date: 08.11.17
 * Time: 11:42
 */

// No direct access to this file
defined('JPATH_BASE') or die('Restricted access');

?>
<div class="nuliga">
    <?php if ($displayData['items']): ?>
        <?php if ($displayData['type'] == 1): ?>
            <table>
                <tr>
                    <th class="pnlg-rank"><?php echo JText::_('COM_NULIGA_NULIGA_LEAGUE_COLUMN_HEADER_RANK');?></th>
                    <th class="pnlg-team"><?php echo JText::_('COM_NULIGA_NULIGA_LEAGUE_COLUMN_HEADER_TEAM');?></th>
                    <th class="pnlg-num-matches"><?php echo JText::_('COM_NULIGA_NULIGA_LEAGUE_COLUMN_HEADER_NUMMATCHES');?></th>
                    <th class="pnlg-num-wins"><?php echo JText::_('COM_NULIGA_NULIGA_LEAGUE_COLUMN_HEADER_NUMWINS');?></th>
                    <th class="pnlg-num-draws"><?php echo JText::_('COM_NULIGA_NULIGA_LEAGUE_COLUMN_HEADER_NUMDRAWS');?></th>
                    <th class="pnlg-num-losses"><?php echo JText::_('COM_NULIGA_NULIGA_LEAGUE_COLUMN_HEADER_NUMLOSSES');?></th>
                    <th class="pnlg-goals"><?php echo JText::_('COM_NULIGA_NULIGA_LEAGUE_COLUMN_HEADER_GOALS');?></th>
                    <th class="pnlg-goal-diff"><?php echo JText::_('COM_NULIGA_NULIGA_LEAGUE_COLUMN_HEADER_GOALDIFF');?></th>
                    <th class="pnlg-points"><?php echo JText::_('COM_NULIGA_NULIGA_LEAGUE_COLUMN_HEADER_POINTS');?></th>
                </tr>
                <?php foreach($displayData['items'] as $team): ?>
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
        <?php elseif ($displayData['type'] == 2): ?>
            <table>
                <tr>
                    <th class="pnlg-weekday"><?php echo JText::_('COM_NULIGA_NULIGA_MATCHES_COLUMN_HEADER_WEEKDAY');?></th>
                    <th class="pnlg-date"><?php echo JText::_('COM_NULIGA_NULIGA_MATCHES_COLUMN_HEADER_DATE');?></th>
                    <th class="pnlg-time"><?php echo JText::_('COM_NULIGA_NULIGA_MATCHES_COLUMN_HEADER_TIME');?></th>
                    <th class="pnlg-hall"><?php echo JText::_('COM_NULIGA_NULIGA_MATCHES_COLUMN_HEADER_HALL');?></th>
                    <th class="pnlg-nuliga-id"><?php echo JText::_('COM_NULIGA_NULIGA_MATCHES_COLUMN_HEADER_NULIGAID');?></th>
                    <th class="pnlg-home"><?php echo JText::_('COM_NULIGA_NULIGA_MATCHES_COLUMN_HEADER_HOME');?></th>
                    <th class="pnlg-guest"><?php echo JText::_('COM_NULIGA_NULIGA_MATCHES_COLUMN_HEADER_GUEST');?></th>
                    <th class="pnlg-result"><?php echo JText::_('COM_NULIGA_NULIGA_MATCHES_COLUMN_HEADER_RESULT');?></th>
                </tr>
                <?php foreach($displayData['items'] as $match): ?>
                    <tr>
                        <?php $hasDate = !empty($match->weekday); ?>
                        <?php if ($hasDate): ?>
                            <td class="pnlg-weekday"><?php echo $match->weekday; ?></td>
                            <td class="pnlg-date"><?php echo $match->date; ?></td>
                            <td class="pnlg-time"><?php echo $match->time; ?></td>
                        <?php else: ?>
                            <td class="pnlg-date" colspan="3"><?php echo $match->date; ?></td>
                        <?php endif; ?>
                        <td class="pnlg-hall"><?php echo $match->hall; ?></td>
                        <td class="pnlg-nuliga-id"><?php echo $match->nr; ?></td>
                        <td class="pnlg-home"><?php echo $match->home; ?></td>
                        <td class="pnlg-guest"><?php echo $match->guest; ?></td>
                        <td class="pnlg-result"><?php echo $match->goals; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    <?php else: ?>
        <p><?php echo JText::_('COM_NULIGA_NULIGA_RENDERING_FAILURE');?></p>
    <?php endif; ?>
</div>
