<?php
/**
 * Created by PhpStorm.
 * User: sebschlicht
 * Date: 06.11.17
 * Time: 12:19
 */

// no direct access
defined( '_JEXEC' ) or die;

// check if com_nuliga is installed and enabled
if (!JComponentHelper::isEnabled('com_nuliga', true))
{
    throw new Exception(JText::_('PLG_CONTENT_NULIGA_ERROR') . ' ' . JText::_('PLG_CONTENT_NULIGA_COMPONENT_NOT_INSTALLED'), 500);
    return false;
}

// import required component classes
JTable::addIncludePath(JPATH_ADMINISTRATOR . '/components/com_nuliga/tables');
JModelLegacy::addIncludePath(JPATH_SITE.'/components/com_nuliga/models', 'NuLigaModel');

class PlgContentNuLiga extends JPlugin
{
    /**
     * parameter: `team` to enter team mode and specify the team whichs entries are to be injected
     */
    const PARAM_TEAM = 'team';
    
    /**
     * parameter: `view` to specify which team entries are to be injected (match schedule, league table)
     */
    const PARAM_TEAM_VIEW = 'view';

    /**
     * Load the language file on instantiation. Note this is only available in Joomla 3.1 and higher.
     * If you want to support 3.0 series you must override the constructor
     *
     * @var    boolean
     * @since  3.1
     */
    protected $autoloadLanguage = true;

    /**
     * Database object
     *
     * @var    JDatabaseDriver
     * @since  3.3
     */
    protected $db;

    /**
     * @var JLayoutFile layout to render NuLiga tables
     */
    protected $layout;

    /**
     * Creates the NuLiga plugin while loading the component's language files.
    */
    function __construct(&$subject, $config) {
      $lang = JFactory::getLanguage();
      $lang->load('com_nuliga', JPATH_SITE);

      parent::__construct($subject, $config);
    }
  
    /**
     * Plugin that renders a NuLiga table from com_nuliga inside an article.
     *
     * @param   string   $context  The context of the content being passed to the plugin.
     * @param   mixed    &$row     An object with a "text" property
     * @param   mixed    $params   Additional parameters. See {@see PlgContentContent()}.
     * @param   integer  $page     Optional page number. Unused. Defaults to zero.
     *
     * @return  boolean	True on success.
     */
    public function onContentPrepare($context, &$row, $params, $page = 0)
    {
        // don't run this plugin when the content is being indexed
        if ($context == 'com_finder.indexer') {
            return true;
        }

        $app = JFactory::getApplication();

        // scan for plugin usages
        $regex_one		= '/({nuliga\s*)(.*?)(})/si';
        $regex_all		= '/{nuliga\s*.*?}/si';
        $matches 		= array();
        $numMatches	= preg_match_all($regex_all, $row->text, $matches,PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);

        // add component's style directives if necessary
        if ($numMatches)
        {
            JHtml::stylesheet('com_nuliga/nuliga.css', false, true, false);
        }

        // process plugin calls
        for ($i = 0; $i < $numMatches; $i++)
        {
            // load parameters
            $nuliga_params = array();
            $nuliga = $matches[0][$i][0];
            preg_match($regex_one, $nuliga, $nuliga_parts);
            $parts = explode("|", $nuliga_parts[2]);
            foreach ($parts as $key => $value)
            {
                $values = explode('=', $value, 2);
                $nuliga_params[$values[0]] = $values[1];
            }

            // load and render table via model
            if (array_key_exists($this::PARAM_TEAM, $nuliga_params))
            {
                if (array_key_exists($this::PARAM_TEAM_VIEW, $nuliga_params))
                {
                    $teamModel = JModelLegacy::getInstance('Team', 'NuLigaModel');
                    if ($teamModel->loadNuLigaTeam($nuliga_params[$this::PARAM_TEAM]))
                    {
                        $output = $this->renderNuLigaTeam($teamModel, $nuliga_params[$this::PARAM_TEAM_VIEW]);
                        if ($output)
                        {
                            $row->text = preg_replace($regex_all, $output, $row->text, 1);
                        }
                        else
                        {
                            // warn: invalid view
                            $app->enqueueMessage(JText::sprintf('PLG_CONTENT_NULIGA_TEAM_VIEW_INVALID',
                                                                $nuliga_params[$this::PARAM_TEAM_VIEW]), 'warning');
                        }
                    }
                    else
                    {
                        // warn: unknown team
                        $app->enqueueMessage(JText::sprintf('PLG_CONTENT_NULIGA_ERROR_PARAM', $nuliga) . ' '
                            . JText::sprintf('PLG_CONTENT_NULIGA_TEAM_UNKNOWN', $nuliga_params[$this::PARAM_TEAM]), 'warning'); 
                    }
                }
                else
                {
                    // error: view param missing in team mode
                    $app->enqueueMessage(JText::sprintf('PLG_CONTENT_NULIGA_ERROR_PARAM', $nuliga) . ' '
                        . JText::sprintf('PLG_CONTENT_NULIGA_PARAM_MISSING',
                                         'view',
                                         JText::_('PLG_CONTENT_NULIGA_PARAM_MISSING_TEAM_VIEW_REASON')), 'error');
                }
            }
            else
            {
                // error: no param to detect the mode
                $app->enqueueMessage(JText::sprintf('PLG_CONTENT_NULIGA_ERROR_PARAM', $nuliga) . ' '
                    . JText::sprintf('PLG_CONTENT_NULIGA_PARAM_MISSING',
                                     'team',
                                     JText::_('PLG_CONTENT_NULIGA_PARAM_MISSING_MODE_REASON')), 'error');
            }
        }

        return true;
	}
    
    /**
     * Renders a view of a NuLiga team with the appropriate plugin layout.
     *
     * @param $teamModel NuLigaModelTeam NuLiga team model
     * @param $view string view
     * @return string|boolean rendering output or false if the view is unknown
     */
    protected function renderNuLigaTeam($teamModel, $view = 'league')
    {
        if ($view === 'league')
        {
            $this->layout = new JLayoutFile('league', JPATH_ROOT . '/plugins/content/nuliga/layouts');
            $displayData = [
                'leagueteams' => $teamModel->getLeagueTeams(),
                'label' => $teamModel->getTeam()->label
            ];
            return $this->layout->render($displayData);
        }
        elseif ($view === 'schedule')
        {
            $this->layout = new JLayoutFile('schedule', JPATH_ROOT . '/plugins/content/nuliga/layouts');
            $displayData = [
                'matches' => $teamModel->getMatches()
            ];
            return $this->layout->render($displayData);
        }
        else
        {
            return false;
        }
    }
}
