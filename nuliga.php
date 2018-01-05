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
      $lang->load( 'com_nuliga', JPATH_SITE );

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

        // initialize if necessary
        if ($numMatches)
        {
            JHtml::stylesheet('com_nuliga/nuliga.css', false, true, false);
            $this->layout = new JLayoutFile('table', JPATH_ROOT . '/plugins/content/nuliga/layouts');
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
            if (array_key_exists($this::PARAM_ID, $nuliga_params))
            {
                $model = self::getModelInstance();
                $table = $model->loadNuLigaTable($nuliga_params[$this::PARAM_ID]);
                if ($table)
                {
                    $output = $this->renderNuLigaTable($table, $model);
                    if ($output)
                    {
                        $row->text = preg_replace($regex_all, $output, $row->text, 1);
                    }
                    else
                    {
                        // error: nothing to render
                        $app->enqueueMessage(JText::_('COM_NULIGA_TABLE_RENDERING_FAILURE'), 'warning');
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

    /**
     * Renders a NuLiga table with the plugin layout.
     *
     * @param $table JTable NuLiga table
     * @param $model NuLigaModelNuLiga NuLiga table model to load data from
     * @return string|null rendering output as HTML or null if nothing to render
     */
    protected function renderNuLigaTable($table, $model)
    {
        // render stored remote data
        $items = ($table->type == 1) ? $model->getTeams() : $model->getMatches();
        if ($items)
        {
            $displayData = array(
                'type' => $table->type,
                'items' =>  $items,
                'highlight' => ['TS Bendorf', 'TS Bendorf II', 'TS Bendorf III']
            );
            return $this->layout->render($displayData);
        }
        else
        {
            return null;
        }
    }

    /**
     * Creates a NuLiga table model instance.
     *
     * @return NuLigaModelTable NuLiga table model
     */
    protected static function getModelInstance()
    {
        return JModelLegacy::getInstance('Table', 'NuLigaModel');
    }
}
