<?php
/**
 * Configuration for webgrind
 * @author Jacob Oettinger
 * @author Joakim Nygård
 */

namespace li3_perf\extensions\webgrind\library;

class Config {
    
    // Do not edit!
    // override in local config file 'config.php'
    private static $_defaults = array(
        /**
         * Automatically check if a newer version of webgrind is available for download
         */
        'checkVersion' => true,
        
        /**
         * Writable dir for information storage.
         * If empty, will use system tmp folder or xdebug tmp
         */
        'storageDir' => '',
        
        /**
         * Suffix for preprocessed files
         */
        'preprocessedSuffix' => '.webgrind',
        
        /**
         * Path to python executable
         */
        'pythonExecutable' => '/usr/bin/python',
        
        /**
         * Path to graphviz dot executable
         */
        'dotExecutable' => '/usr/bin/dot',
        
        /**
         * sprintf compatible format for generating links to source files..
         * %1$s will be replaced by the full path name of the file
         * %2$d will be replaced by the linenumber
         */
        'fileUrlFormat' => 'index.php?op=fileviewer&file=%1$s#line%2$d',
        
        /**
         * format of the trace drop down list
         * default is: invokeurl (tracefile_name) [tracefile_size]
         * the following options will be replaced:
         *   %i - invoked url
         *   %f - trace file name
         *   %s - size of trace file
         *   %m - modified time of file name (in dateFormat specified above)
         */
        'traceFileListFormat' => '%i (%f) [%s]',
        
        /**
         * Regex that matches the trace files generated by xdebug
         */
        'xdebugOutputFormat' => '/^cachegrind\.out\..+$/',
        
        /**
         * Directory to search for trace files
         */
        'xdebugOutputDir' => '/tmp',
        
        
        'hideWebgrindProfiles' => true,
        'defaultTimezone' => 'Europe/Copenhagen',
        'dateFormat' => 'Y-m-d H:i:s',
        'defaultCostformat' => 'percent', // 'percent', 'usec' or 'msec'
        'defaultFunctionPercentage' => 90,
        'defaultHideInternalFunctions' => false,
    );
    
    private $_dict = array();



    public function __construct($config_file = null) {
        if (isset($config_file)) {
            $this->load($config_file);
        }
    }



    public function load($file) {
        $config = require $file;
        $config = array_replace(self::$_defaults, $config);
        $this->_dict = array_intersect_key($config, self::$_defaults);

        // parse xdebugOutputFormat
        $xdebugOutputFormat = ini_get('xdebug.profiler_output_name');
        if (!empty($xdebugOutputFormat)) {
            $this->_dict['xdebugOutputFormat'] = '/^'.preg_replace('/(%[^%])+/', '.+', $xdebugOutputFormat).'$/';
        }
        
        // parse xdebugOutputDir
        $dir = ini_get('xdebug.profiler_output_dir');
        if (!empty($dir)) {
            $this->_dict['xdebugOutputDir'] = realpath($dir);
        } else {
            $this->_dict['xdebugOutputDir'] = realpath($this->_dict['xdebugOutputDir']);
        }
        
        // parse storageDir
        if (!empty($this->_dict['storageDir'])) {
            $this->_dict['storageDir'] = realpath($this->_dict['storageDir']);
        } elseif (!function_exists('sys_get_temp_dir') || !is_writable(sys_get_temp_dir())) {
            # use xdebug setting
            $this->_dict['storageDir'] = $this->_dict['xdebugOutputDir'];
        } else {
            $this->_dict['storageDir'] = realpath(sys_get_temp_dir());
        }
    }



    public function __set($name, $value) {
        $this->_dict[$name] = $value;
    }

    public function __get($name) {
        return isset($this->_dict[$name]) ?
            $this->_dict[$name] : null;
    }

    public function __isset($name) {
        return isset($this->_dict[$name]);
    }

    public function __unset($name) {
        unset($this->_dict[$name]);
    }
}
?>