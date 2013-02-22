<?php
//  Voice recording filter plugin for Moodle
//  Copyright © 2012  Institut Obert de Catalunya
//
//  This program is free software: you can redistribute it and/or modify
//  it under the terms of the GNU General Public License as published by
//  the Free Software Foundation, either version 3 of the License, or
//  (at your option) any later version.
//
//  This program is distributed in the hope that it will be useful,
//  but WITHOUT ANY WARRANTY; without even the implied warranty of
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
//  GNU General Public License for more details.
//
//  You should have received a copy of the GNU General Public License
//  along with this program.  If not, see <http://www.gnu.org/licenses/>.

/**
 *  Voice recording filtering
 *
 *  This filter will replace any links to a nanogong file with
 *  a nanogong player
 *
 * @package    filter
 * @subpackage voicerecording
 * @copyright  Marc Català  {mcatala@ioc.cat}
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Automatic nanogong embedding filter class.
 *
 *
 * @package    filter
 * @subpackage nanogong
 * @copyright  Marc Català  <mcatala@ioc.cat>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class filter_voicerecording extends moodle_text_filter {

    function filter($text, array $options = array()) {
        global $CFG;

        if (!is_string($text) or empty($text)) {
            // non string data can not be filtered anyway
            return $text;
        }

        $newtext = '';

        $match = '/<a\s[^>]*href="(http(s)?:\/\/[^"]*\.spx)"[^>]*>([^>]*)<\/a>/is';
        if (preg_match($match, $text)) {
            $applet = nanogong_applet("$1");
            $html = preg_replace($match, $applet, $text);
            $newtext = <<<OET
$html
OET;
        }

        if (empty($newtext)) {
            // error or not filtered
            unset($newtext);
            return $text;
        }

        return $newtext;
    }
}


///===========================
/// utility functions

function nanogong_applet($sound_url)
{
    global $CFG;

    $html = '<object type="application/x-java-applet" width="120" height="40" class="voicerecording">'.
             '<param name="code" value="gong.NanoGong"/>'.
             '<param name="archive" value="' . $CFG->wwwroot . '/filter/voicerecording/nanogong.jar"/>'.
             '<param name="SoundFileURL" value="' . $sound_url . '"/>'.
             '<param name="ShowRecordButton" value="false" />'.
             '<param name="ShowSaveButton" value="false" />'.
             '</object>';

    return $html;
}
