<?php

/**
 * ChartParser
 *
 * @copyright  Copyright(c) No-nonsense Labs (http://www.nononsenselabs.com)
 */

/*
Plugin Name: Docxpresso
Plugin URI: http://www.docxpresso.com
Description: Docxpresso inserts content from a document file (.odt).
Version: 1.0
Author: No-nonsense Labs
License: GPLv2 or later
*/

namespace Docxpresso\ODF2HTML5;


use Docxpresso;

class ChartParser
{
    /**
     * ODF chart axis format
     * 
     * @var array
     * @access public
     * @static
     */
    public static $defaultColorPattern = array( '#5b9bd5',
                                                '#ed7d31',
                                                '#a5a5a5',
                                                '#ffc000',
                                                '#4472c4',
                                                '#70ad47',
                                                '#255e91',
                                                '#9e480e',
                                                '#636363',
                                                '#997300',
                                                '#264478',
                                                '#43682b',
                                                '#7cafdd',
                                                '#f1975a',
                                                '#b7b7b7',
                                                '#ffcd33',
                                                '#698ed0',
                                                '#8cc168',
                                                '#ed7d31',
                                                '#d26012',
                                            );
    /**
     * ODF chart axis format
     * 
     * @var array
     * @access private
     */
    private $_axis;
    /**
     * ODF chart DOMDocument
     * 
     * @var DOMDocument
     * @access private
     */
    private $_chart;
    /**
     * ODF chart DOMXPath
     * 
     * @var DOMDocument
     * @access private
     */
    private $_chartXPath;
    /**
     * ODF chart data
     * 
     * @var array
     * @access private
     */
    private $_data;
    /**
     * Chart global type info
     * 
     * @var array
     * @access private
     */
    private $_globalType;
    /**
     * ODF chart legend format
     * 
     * @var array
     * @access private
     */
    private $_legend;
    /**
     * ODF chart series format
     * 
     * @var array
     * @access private
     */
    private $_series;
    /**
     * ODF chart title data
     * 
     * @var array
     * @access private
     */
    private $_title;
    
    /**
     * Construct
     *
     * @param string $chart
     * @access public
     */
    public function __construct($chart)
    {          
        //initialize variables
        $this->_chart = new \DOMDocument();
        $this->_chart->loadXML($chart);
        $this->_chartXPath = new \DOMXPath($this->_chart);
        $this->_globalType = $this->_getGlobalType();
        $this->_data = $this->_getRawData();
        $this->_legend = $this->_getLegend();
        $this->_axis = $this->_getAxis();
        $this->_series = $this->_getSeries();
        $this->_title = $this->_getTitle(); 
    }
    
    /**
     * Renders the chart data in the selected JS compatible format
     *
     * @param array $options
     * @return mixed
     * @access public
     */
    public function render($options) 
    {
        //By the time being only the c3.js rendering is available
        if ($options['js'] == 'c3.js') {
            $engine = new C3JS($options);
        } else {
            return NULL;
        }
        $engine->setGlobalType($this->_globalType);
        $engine->setData($this->_data);
        $engine->setLegend($this->_legend);
        $engine->setAxis($this->_axis);
        $engine->setSeries($this->_series);
        $engine->setTitle($this->_title);
        $script = $engine->renderScript($this->_chart);
        return $script;
    }
    
    /**
     * Extracts the chart axis format
     *
     * @return mixed
     * @access private
     */
    private function _getAxis() 
    {
        $axisFormat = array();
        $axis = $this->_chart->getElementsByTagName('axis');
        if ($axis->length > 0) {
            foreach ($axis as $ax) {
                $dim = $ax->getAttribute('chart:dimension');
                $name = $ax->getAttribute('chart:name');
                if (empty($name)) {
                    $name = \uniqid();
                }
                $style = $ax->getAttribute('chart:style-name');
                $range= '';
                $categories = $ax->getElementsByTagname('categories');
                if ($categories->length > 0) {
                    $range = $categories->item(0)
                                    ->getAttribute('table:cell-range-address');
                }
                $axisFormat[$name][$dim] = array();
                $axisFormat[$name][$dim]['style'] = $style;
                $axisFormat[$name][$dim]['range'] = $range;
                //look for grid info
                $axisFormat[$name][$dim]['grid'] = array();
                $grids = $ax->getElementsByTagName('grid');
                if ($grids->length > 0) {
                    $grid = $grids->item(0);
                    $class = $grid->getAttribute('chart:class');
                    if (empty($class)) {
                        $class = 'major';
                    }
                    $st = $grid->getAttribute('chart:style-name');
                    $axisFormat[$name][$dim]['grid']['class'] = $class;
                    $axisFormat[$name][$dim]['grid']['style'] = $st;
                }
            }
            return $axisFormat;
        } else {
            return false;
        }
        
    }
      
    /**
     * Gets the chart global type data
     *
     * @return array
     * @access private
     */
    private function _getGlobalType() 
    {
        $result = array();
        $ns = 'urn:oasis:names:tc:opendocument:xmlns:chart:1.0';
        $class = $this->_chart->getElementsByTagNameNS($ns, 'chart')
                              ->item(0)
                              ->getAttribute('chart:class');
        $result['type'] = $class;
        if ($class != 'chart:pie') {
            //we have to distinguish betwen the horizontal an vertical bar
            //cases via the chart:vertical attribute of the chart:plot-area 
            //element
            $style = $this->_chart->getElementsByTagName('plot-area')
                              ->item(0)
                              ->getAttribute('chart:style-name');
            $xpath = new \DOMXPath($this->_chart);
            $query = 'string(//style:style[@style:name="' . $style . '"]';
            $query .= '/style:chart-properties/@chart:vertical)';
            $vertical = $xpath->evaluate($query);
            if ($vertical == 'true') {
                $result['rotated'] = true;
            }
            //we have to distinguish betwen stacked and not stacked charts
            $query = 'string(//style:style[@style:name="' . $style . '"]';
            $query .= '/style:chart-properties/@chart:stacked)';
            $stacked = $xpath->evaluate($query);
            if ($stacked == 'true') {
                $result['stacked'] = true;
            }
            //check if it is percentage stacked
            $query = 'string(//style:style[@style:name="' . $style . '"]';
            $query .= '/style:chart-properties/@chart:percentage)';
            $percentage = $xpath->evaluate($query);
            if ($percentage == 'true') {
                $result['percentage'] = true;
            }
        }
        return $result;
    }
    
    /**
     * Extracts the chart legend format
     *
     * @return mixed
     * @access private
     */
    private function _getLegend() 
    {
        $pos = array('start' => 'right', //c3JS does not support left pos.
                     'end' => 'right',
                     'bottom' => 'bottom',
                     'top' => 'top',
                     'bottom-end' => 'bottom',
                     'top-end' => 'top',
                     'bottom-start' => 'bottom',
                     'top-start' => 'top',);
        $legendFormat = array();
        $legends = $this->_chart->getElementsByTagName('legend');
        if ($legends->length > 0) {
            $legend = $legends->item(0);
            $position = $legend->getAttribute('chart:legend-position');
            if (!empty($position)) {
                $legendFormat['pos'] = $pos[$position];
            } else {
                $legendFormat['pos'] = 'bottom';
            }
            $legendFormat['align'] = $legend
                                     ->getAttribute('chart:legend-align');
            $legendFormat['x'] = $legend->getAttribute('svg:x');
            $legendFormat['y'] = $legend->getAttribute('svg:y');
            $legendFormat['style'] = $legend->getAttribute('chart:style-name');
            return $legendFormat;
        } else {
            return false;
        }
        
    }
    
    /**
     * Extracts the chart row data
     *
     * @return array
     * @access private
     */
    private function _getRawData() 
    {
        $rawData = array();
        $tables = $this->_chart->getElementsByTagName('table');
        foreach ($tables as $table) {
            $name = $table->getAttribute('table:name');
            $rawData[$name] = array();
            $rowGroups = $table->childNodes;
            $rowCounter = 0;
            foreach ($rowGroups as $rowsGroup) {
                $rowName = $rowsGroup->nodeName;
                if ($rowName == 'table:table-header-rows'
                    || $rowName == 'table:table-rows') {
                    $rows = $rowsGroup->childNodes;
                    foreach ($rows as $row){
                        $rowCounter++;
                        $rawData[$name][$rowCounter] = array();
                        $cellCounter = 0;
                        $cells = $row->childNodes;
                        foreach ($cells as $cell) {
                            $cellCounter++;
                            $letter = ODF2HTML5::rowLetter($cellCounter);
                            $value = $cell->getAttribute('office:value');
                            if (empty($value)) {
                                $value = $cell->nodeValue;
                                if (empty($value)) {
                                    $value = ' ';
                                }
                            }
                            $rawData[$name][$rowCounter][$letter] = $value;
                        }
                    }
                } else if ($rowName == 'table:table-row'){
                    //The charts generated by Word do not generate the
                    //wrapping table:table-rows element
                    $rowCounter++;
                    $rawData[$name][$rowCounter] = array();
                    $cellCounter = 0;
                    $cells = $rowsGroup->childNodes;
                    foreach ($cells as $cell) {
                        $cellCounter++;
                        $letter = ODF2HTML5::rowLetter($cellCounter);
                        $value = $cell->getAttribute('office:value');
                        if (empty($value)) {
                            $value = $cell->nodeValue;
                        }
                        $rawData[$name][$rowCounter][$letter] = $value;
                    }   
                }
            }
        }
        
        return $rawData;
    }
    
    /**
     * Extracts the chart series format
     *
     * @return array
     * @access private
     */
    private function _getSeries() 
    {
        $seriesFormat = array();
        $series= $this->_chart->getElementsByTagName('series');
        $counter = 0;
        foreach ($series as $ser) {
            $class = $ser->getAttribute('chart:class');
            $style = $ser->getAttribute('chart:style-name');
            $label = $ser->getAttribute('chart:label-cell-address');
            $range = $ser->getAttribute('chart:values-cell-range-address');
            $temp = \explode('.$', $label);
            $name = \array_shift($temp);
            $seriesFormat[$name][$counter] = array();
            $seriesFormat[$name][$counter]['class'] = $class;
            $seriesFormat[$name][$counter]['style'] = $style;
            $seriesFormat[$name][$counter]['label'] = $label;
            $seriesFormat[$name][$counter]['range'] = $range;
            $seriesFormat[$name][$counter]['data-points'] = array();
            //look for data point styles
            $points = $ser->getElementsByTagName('data-point');
            foreach ($points as $point) {
                $seriesFormat[$name][$counter]['data-points'][] = 
                        $point->getAttribute('chart:style-name');
            }
            $counter++;
        }
        return $seriesFormat;       
    }
    
    /**
     * Extracts the chart title data
     *
     * @return array
     * @access private
     */
    private function _getTitle() 
    {
        $data = array();
        $titles= $this->_chart->getElementsByTagName('title');
        if ($titles->length > 0) {
            $title = $titles->item(0);
            $data['style'] = $title->getAttribute('chart:style-name');
            $data['text'] = $title->nodeValue;
        }
        return $data;       
    }
     

}