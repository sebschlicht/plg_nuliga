# Joomla! NuLiga Plugin

This is a Joomla! plugin to display NuLiga tables in articles.

**Important**: The [NuLiga component](https://github.com/sebschlicht/com_nuliga) is required to make this plugin work.
Thus the plugin is as compatible as the component (Joomla! 3.0+).

## License

This plugin is licensed under the MIT License; see [LICENSE](https://github.com/sebschlicht/plg_nuliga/blob/master/LICENSE).

## Installation

Simply download the latest [release](https://github.com/sebschlicht/plg_nuliga/releases) and install the package via the extension manager.
The extension supports the update manager, thus you will be notified about updates by your Joomla! instance automatically.

## Usage

In an article, use the following syntax to display a table of a team:

    {nuliga|team=ID|view=VIEW}

**Parameters**:

* `ID`: the id of the respective team
* `VIEW`: [the view](#team-views) which you want to display

**Examples**:

* `{nuliga|team=1|view=league}`: displays the league table of the team with id 1
* `{nuliga|team=3|view=schedule}`: displays the match schedule of the team with id 3

### Team Views

The following table lists which views are available and what information they will show.

Value    | Description
-------- | -----------
league   | The league view shows a ranked table of all teams in the team's league, along with their current results.
schedule | The schedule view shows all past and scheduled matches of the team in this season, along with their date, location and result.
