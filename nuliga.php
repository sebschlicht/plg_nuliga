<?php
/**
 * Created by PhpStorm.
 * User: sebschlicht
 * Date: 06.11.17
 * Time: 12:19
 */

// no direct access
defined( '_JEXEC' ) or die;

class PlgContentNuLiga extends JPlugin
{
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

        // scan for plugin usages
        $regex_one		= '/({nuliga\s*)(.*?)(})/si';
        $regex_all		= '/{nuliga\s*.*?}/si';
        $matches 		= array();
        $numMatches	= preg_match_all($regex_all, $row->text, $matches,PREG_OFFSET_CAPTURE | PREG_PATTERN_ORDER);

        // initialize component helpers if necessary
        // TODO remove what can be done inside there
        $document = JFactory::getDocument();
        $document->addStyleSheet(JUri::root() . 'media/com_nuliga/css/nuliga.css');

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

            // render table
            $output = '';
            if (array_key_exists('id', $nuliga_params))
            {
                // TODO render table via frontend model
                $output = '<p class="nuliga">I will render! But not today...</p>';

                // TODO add error if table id unknown / rendering failed
            }
            else
            {
                // TODO add error if table id missing
                $output = 'Error: NuLiga table id missing!';
            }
            $row->text = preg_replace($regex_all, $output, $row->text, 1);
        }

        return true;
	}
}
