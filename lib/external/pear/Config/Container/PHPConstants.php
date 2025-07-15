<?php
/**
 * Part of the PEAR Config package
 *
 * PHP Version 4
 *
 * @category Configuration
 * @package  Config
 * @author   Phillip Oertel <me@phillipoertel.com>
 * @license  http://www.php.net/license PHP License
 * @version  SVN: $Id: PHPConstants.php 306571 2010-12-22 06:50:39Z cweiske $
 * @link     http://pear.php.net/package/Config
 */
require_once 'Config/Container.php';

/**
 * Config parser for PHP constant files
 *
 * @category Configuration
 * @package  Config
 * @author   Phillip Oertel <me@phillipoertel.com>
 * @license  http://www.php.net/license PHP License
 * @link     http://pear.php.net/package/Config
 */
class Config_Container_PHPConstants extends Config_Container
{
    /**
     * Valid config options:
     * - "lowercase" - boolean - config names are lowercased when reading them
     *
     * @var  array
     */
    var $options = array(
        'lowercase' => false
    );

    /**
     * Constructor
     *
     * @param string $options (optional)Options to be used by renderer
     *
     * @access public
     */
    function __construct($options = array())
    {
        $this->options = array_merge($this->options, $options);
    } // end constructor

    /**
     * Parses the data of the given configuration file
     *
     * @param string $datasrc Path to the configuration file
     * @param object &$obj    Reference to a config object
     *
     * @return mixed PEAR_ERROR, if error occurs or true if ok
     *
     * @access public
     */
    function &parseDatasrc($datasrc, &$obj)
    {
        $return = true;

        if (!file_exists($datasrc)) {
            return PEAR::raiseError(
                'Datasource file does not exist.',
                null, PEAR_ERROR_RETURN
            );
        }

        $fileContent = file_get_contents($datasrc, true);

        if (!$fileContent) {
            return PEAR::raiseError(
                "File '$datasrc' could not be read.",
                null, PEAR_ERROR_RETURN
            );
        }

        $rows = explode("\n", $fileContent);
        for ($i=0, $max=count($rows); $i<$max; $i++) {
            $line = $rows[$i];

            //blanks?

            // sections
            if (preg_match("/^\/\/\s*$/", $line)) {
                preg_match("/^\/\/\s*(.+)$/", $rows[$i+1], $matches);
                $obj->container->createSection(trim($matches[1]));
                $i += 2;
                continue;
            }

            // comments
            if (preg_match("/^\/\/\s*(.+)$/", $line, $matches)
                || preg_match("/^#\s*(.+)$/", $line, $matches)
            ) {
                $obj->container->createComment(trim($matches[1]));
                continue;
            }

            // directives
            $regex = "/^\s*define\s*\('([A-Z1-9_]+)',\s*'*(.[^\']*)'*\)/";
            preg_match($regex, $line, $matches);
            if (!empty($matches)) {
                $name = trim($matches[1]);
                if ($this->options['lowercase']) {
                    $name = strtolower($name);
                }
                $obj->container->createDirective(
                    $name, trim($matches[2])
                );
            }
        }

        return $return;

    } // end func parseDatasrc

    /**
     * Returns a formatted string of the object
     *
     * @param object $obj Container object to be output as string
     *
     * @return string
     *
     * @access public
     */
    function toString($obj, $options = array())
    {
        $string = '';

        switch ($obj->type)
        {
        case 'blank':
            $string = "\n";
            break;

        case 'comment':
            $string = '// '.$obj->content."\n";
            break;

        case 'directive':
            $content = $obj->content;
            // don't quote numeric values, true/false and constants
            if (is_bool($content)) {
                $content = var_export($content, true);
            } else if (!is_numeric($content)
                && !in_array($content, array('false', 'true'))
                && !preg_match('/^[A-Z_]+$/', $content)
            ) {
                $content = "'" . str_replace("'", '\\\'', $content) . "'";
            }
            $string = 'define('
                . '\'' . strtoupper($obj->name) . '\''
                . ', ' . $content . ');'
                . chr(10);
            break;

        case 'section':
            if (!$obj->isRoot()) {
                $string  = chr(10);
                $string .= '//'.chr(10);
                $string .= '// '.$obj->name.chr(10);
                $string .= '//'.chr(10);
            }
            if (count($obj->children) > 0) {
                for ($i = 0, $max = count($obj->children); $i < $max; $i++) {
                    $string .= $this->toString($obj->getChild($i));
                }
            }
            break;
        default:
            $string = '';
        }
        return $string;
    } // end func toString

    /**
     * Writes the configuration to a file
     *
     * @param mixed  $datasrc Info on datasource such as path to the file
     * @param string $obj    Configuration object to write
     *
     * @return mixed PEAR_Error on failure or boolean true if all went well
     *
     * @access public
     */
    function writeDatasrc($datasrc, $obj, $options = array())
    {
        $fp = @fopen($datasrc, 'w');
        if (!$fp) {
            return PEAR::raiseError(
                'Cannot open datasource for writing.',
                1, PEAR_ERROR_RETURN
            );
        }

        $string  = "<?php";
        $string .= "\n\n";
        $string .= '/**' . chr(10);
        $string .= ' *' . chr(10);
        $string .= ' * AUTOMATICALLY GENERATED CODE - DO NOT EDIT BY HAND' . chr(10);
        $string .= ' *' . chr(10);
        $string .= '**/' . chr(10);
        $string .= $this->toString($obj);
        $string .= "\n?>"; // <? : Fix my syntax coloring

        $len = strlen($string);
        @flock($fp, LOCK_EX);
        @fwrite($fp, $string, $len);
        @flock($fp, LOCK_UN);
        @fclose($fp);

        // need an error check here

        return true;
    } // end func writeDatasrc


} // end class Config_Container_PHPConstants

?>
