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
                    <th><?php echo JText::_('COM_NULIGA_NULIGA_LEAGUE_COLUMN_HEADER_RANK');?></th>
                    <th><?php echo JText::_('COM_NULIGA_NULIGA_LEAGUE_COLUMN_HEADER_TEAM');?></th>
                    <th><?php echo JText::_('COM_NULIGA_NULIGA_LEAGUE_COLUMN_HEADER_NUMMATCHES');?></th>
                    <th><?php echo JText::_('COM_NULIGA_NULIGA_LEAGUE_COLUMN_HEADER_NUMWINS');?></th>
                    <th><?php echo JText::_('COM_NULIGA_NULIGA_LEAGUE_COLUMN_HEADER_NUMDRAWS');?></th>
                    <th><?php echo JText::_('COM_NULIGA_NULIGA_LEAGUE_COLUMN_HEADER_NUMLOSSES');?></th>
                    <th><?php echo JText::_('COM_NULIGA_NULIGA_LEAGUE_COLUMN_HEADER_GOALS');?></th>
                    <th><?php echo JText::_('COM_NULIGA_NULIGA_LEAGUE_COLUMN_HEADER_GOALDIFF');?></th>
                    <th><?php echo JText::_('COM_NULIGA_NULIGA_LEAGUE_COLUMN_HEADER_POINTS');?></th>
                </tr>
                <?php foreach($displayData['items'] as $team): ?>
                    <tr<?php if (in_array($team->name, $displayData['highlight'])) echo ' class="highlight"'; ?>>
                        <td><?php echo $team->rank; ?></td>
                        <td><?php echo $team->name; ?></td>
                        <td><?php echo $team->numMatches; ?></td>
                        <td><?php echo $team->numWins; ?></td>
                        <td><?php echo $team->numDraws; ?></td>
                        <td><?php echo $team->numLosses; ?></td>
                        <td><?php echo $team->goals; ?></td>
                        <td><?php echo $team->goalDiff; ?></td>
                        <td><?php echo $team->points; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php elseif ($displayData['type'] == 2): ?>
            <table>
                <tr>
                    <th><?php echo JText::_('COM_NULIGA_NULIGA_MATCHES_COLUMN_HEADER_WEEKDAY');?></th>
                    <th><?php echo JText::_('COM_NULIGA_NULIGA_MATCHES_COLUMN_HEADER_DATE');?></th>
                    <th><?php echo JText::_('COM_NULIGA_NULIGA_MATCHES_COLUMN_HEADER_TIME');?></th>
                    <th><?php echo JText::_('COM_NULIGA_NULIGA_MATCHES_COLUMN_HEADER_HALL');?></th>
                    <th><?php echo JText::_('COM_NULIGA_NULIGA_MATCHES_COLUMN_HEADER_NULIGAID');?></th>
                    <th><?php echo JText::_('COM_NULIGA_NULIGA_MATCHES_COLUMN_HEADER_HOME');?></th>
                    <th><?php echo JText::_('COM_NULIGA_NULIGA_MATCHES_COLUMN_HEADER_GUEST');?></th>
                    <th><?php echo JText::_('COM_NULIGA_NULIGA_MATCHES_COLUMN_HEADER_RESULT');?></th>
                </tr>
                <?php foreach($displayData['items'] as $match): ?>
                    <tr>
                        <?php $hasDate = !empty($match->weekday); ?>
                        <?php if ($hasDate): ?>
                            <td><?php echo $match->weekday; ?></td>
                            <td><?php echo $match->date; ?></td>
                            <td><?php echo $match->time; ?></td>
                        <?php else: ?>
                            <td colspan="3"><?php echo $match->date; ?></td>
                        <?php endif; ?>
                        <td><?php echo $match->hall; ?></td>
                        <td><?php echo $match->nr; ?></td>
                        <td><?php echo $match->home; ?></td>
                        <td><?php echo $match->guest; ?></td>
                        <td><?php echo $match->goals; ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    <?php else: ?>
        <p><?php echo JText::_('COM_NULIGA_NULIGA_RENDERING_FAILURE');?></p>
    <?php endif; ?>
</div>
