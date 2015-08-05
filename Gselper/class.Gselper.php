<?php
/**
 * MIT License
 * ===========
 *
 * Copyright (c) 2012 Pierre Romera <hello@pirhoo.com>
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be included
 * in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS
 * OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
 * IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY
 * CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE
 * SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 *
 * @package     hello@pirhoo.com
 * @subpackage  Gselper
 * @author      Pierre Romera <hello@pirhoo.com>
 * @copyright   2012 Pierre Romera.
 * @license     http://www.opensource.org/licenses/mit-license.php  MIT License
 * @link        http://pirhoo.com
 * @version     1.0
 */
Class Gselper {
    
    
    // options
    private $option = array(
        "autoLoad"   => true, // load the document automaticly
        "key"        => "",   // document key
        "worksheet"  => "od6",   // document worksheet (od5, od6, etc)
        "onComplete" => false // function to call after the document loading (and parsing)
    );

    // document loaded
    private $gDoc = array();
    // data extracted from the document
    private $data = array();
    
    
    /**
     * Contructor     
     * 
     * @access public
     * @return Object
     */
    public function __construct($p_option) {

        // record options
        foreach($p_option as $key => $option) {

            // if the option exist
            if( isset($this->option[$key]) ) {
                // we record it
                $this->option[$key] = $option;

            }
        }
    
        if( !! $this->option["autoLoad"] ) $this->load( $this->option["onComplete"] );
        
        return $this;
    }
    
        
    /**
     * Return true if the document is loaded (else return false)
     * @access public
     * @return Boolean 
     */
    public function isLoaded() {
        return !! count($this->data) > 0;
    }
    
    
    /**
     * Load the document and store data
     * @public     
     * 
     * @param Function $p_callback
     * @return Object
     */
    public function load($p_callback) {
        
        // key required
        if($this->option["key"] == "" || !is_string($this->option["key"]) ) return false;
        // worksheet required too
        if($this->option["worksheet"] == "" || !is_string($this->option["worksheet"]) ) return false;
        
        $docUrl = "http://spreadsheets.google.com/feeds/list/" . $this->option["key"] . "/" . $this->option["worksheet"] . "/public/values?alt=json";        
        $gDoc = json_decode( file_get_contents( $docUrl ) );

        if($gDoc) {
            $this->gDoc = $gDoc;
        }

            
        // parse the document (to extract data)
        if( !! $this->parse() ) { 
            // callback function
            if( is_callable($p_callback) ){
                $p_callback($this);
            } 
        }
        
        return $this;
    }
    
    /**
     * Parse document to organise it
     * @access public     
     * @return Object
     */
    function parse() {

        if( !is_object($this->gDoc)
        ||  !is_object($this->gDoc->feed)
        ||  !is_array($this->gDoc->feed->entry)       
        ||  count($this->gDoc->feed->entry) == 0) return false;
                
        
        // empty the data table
        $this->data = array();

        
        // forn each entry
        foreach( $this->gDoc->feed->entry as $key => $entry) {

            $row = array();

            // for each property of the current entry
            foreach($entry as $propName => $prop) { 
                    
                // if the property is a cell
                if( preg_match('/^(gsx\$)(.+)/', $propName) ) {                          
                    $name = preg_replace('/^(gsx\$)(.+)/', '$2', $propName);                        
                    // extract the name and the value
                    $textProperty = '$t';
                    if( !empty($name) ) $row[ $name ] = $prop->$textProperty;
                } 
            }

            // record the row
            $this->data[] = $row;                
        }                
        
        return $this;
    }    
    
    /**
     * Return a line from the data set or the data set itself
     * @access public     
     * @param Integer $index optional
     * @return Array or Object or Boolean
     */
    public function get($index = -1) {        
        
        // there are no index specified, we get all of the data
        if( $index <= -1 )
            // if data set is loaded
            return $this->isLoaded() ? $this->data : false;                    
        
        // there are an index specified, we get just the right line
        else if(is_numeric($index) && $index < count($this->data) )
            // if data set is loaded
            return $this->isLoaded() ? $this->data[$index] : false;            
        
        else return false;
    }
    

    public function getGDoc() {
        return $this->gDoc;
    }

    public function setGDoc($p_gDoc) {
        $this->gDoc = $p_gDoc;
    }

    public function getData() {
        return $this->data;
    }

    public function setData($p_data) {
        $this->data = $p_data;
    }


}

?>