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
     * parameter: id
     */
    const PARAM_ID = 'id';

    /**
     * @var com_nuliga model instance
     */
    protected static $model;

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

        // initialize if necessary
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

            // load and render table
            if (array_key_exists($this::PARAM_ID, $nuliga_params))
            {
                $table = $this->loadNuLigaTable($nuliga_params[$this::PARAM_ID]);
                if ($table)
                {
                    $output = $this->renderNuLigaTable($table);
                    if ($output)
                    {
                        $row->text = preg_replace($regex_all, $output, $row->text, 1);
                    }
                    else
                    {
                        // TODO when does this happen?
                    }
                }
                else
                {
                    // error: table unknown
                    $app->enqueueMessage(JText::sprintf('PLG_CONTENT_NULIGA_ERROR_PARAM', $nuliga) . ' '
                        . JText::sprintf('PLG_CONTENT_NULIGA_TABLE_UNKNOWN', $nuliga_params[$this::PARAM_ID]), 'warning');
                }
            }
            else
            {
                // error: table id param missing
                $app->enqueueMessage(JText::sprintf('PLG_CONTENT_NULIGA_ERROR_PARAM', $nuliga) . ' '
                    . JText::sprintf('PLG_CONTENT_NULIGA_PARAM_MISSING', 'id'), 'error');
            }
        }

        return true;
	}

	protected function loadNuLigaTable($id)
    {
        // load table via component model
        return self::getModelInstance()->loadNuLigaTable($id);
    }

    protected function renderNuLigaTable($table)
    {
        // render stored remote data
        $layout = new JLayoutFile('nuliga', JPATH_ROOT . '/plugins/content/nuliga/layouts');
        $model = self::getModelInstance();

        $items = ($table->type == 1) ? $model->getTeams() : $model->getMatches();
        if ($items)
        {
            $displayData = array(
                'type' => $table->type,
                'items' =>  $items,
                'highlight' => ['TS Bendorf', 'TS Bendorf II', 'TS Bendorf III']
            );
            return $layout->render($displayData);
        }
        else
        {
            // TODO error: failed to load teams/matches
            return '';
        }
    }

    protected static function getModelInstance()
    {
        // load a model instance, if necessary
        if (empty(self::$model))
        {
            self::$model = JModelLegacy::getInstance('NuLiga', 'NuLigaModel');
        }
        return self::$model;
    }
}
