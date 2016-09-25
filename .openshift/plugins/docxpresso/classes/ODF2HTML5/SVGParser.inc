<?php

/**
 * SVGParser
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


/**
 * This class parses teh ODF draw elements to convert them into SVG
 *
 * @package    Docxpresso
 * @subpackage ODF2HTML5
 */

class SVGParser
{
    /**
     * ODF2HTML5 instance
     * 
     * @var ODF2HTML5
     * @access private
     */
    private $_doc;
    /**
     * SVG base node
     * 
     * @var DOMNode
     * @access private
     */
    private $_node;
    
    /**
     * Construct
     *
     * @param ODF2HTML5 $doc
     * @access public
     */
    public function __construct($doc)
    {          
        //initialize variables
        $this->_doc = $doc;
        $this->_drawNodeList = array('dr3d:scene' => true,
                                     'draw:a' => true,
                                     'draw:caption' => true,
                                     'draw:circle' => true,
                                     'draw:connector' => true,
                                     'draw:control' => true,
                                     'draw:custom-shape' => true,
                                     'draw:ellipse' => true, 
                                     'draw:frame' => true,
                                     'draw:g' => true,
                                     'draw:glue-point' => true,
                                     'draw:line' => true,
                                     'draw:measure' => true,
                                     'draw:page-thumbnail' => true,
                                     'draw:path' => true,
                                     'draw:polygon' => true,
                                     'draw:polyline' => true,
                                     'draw:rect' => true,
                                     'draw:regular-polygon' => true,
                                     'office:event-listeners' => true,
                                     'svg:desc' => true, 
                                     'svg:title' => true,
                                     );
        
    }
    
    /**
     * Renders SVG nodes
     *
     * @param DOMNode $svgNode
     * @param DOMNode $odfNode
     * @return DOMNode
     * @access public
     */
    public function render($svgNode, $odfNode) 
    {
        //determine node type
        $type = $odfNode->nodeName;
        switch ($type) {
            case 'draw:g':
                $node = $this->_g($svgNode, $odfNode);
                break;
            case 'draw:custom-shape':
                $node = $this->_customShape($svgNode, $odfNode);
                break;
            default:
                $node = $this->_g($svgNode, $odfNode);
                break;
        }

        return $node;
    }
    
    /**
     * Computes the required svg height or width
     *
     * @param string $dimension it can take the values 'x' or 'y'
     * @param DOMNode $node
     * @param string $offset
     * @return string
     * @access private
     */
    private function _calculateDimension($dimension, $node, $offset = '0pt') 
    {
        //the sources of height information are given by width and height
        //attributes of the (child) nodes, their x and y (positioning) attr.,
        //the transform (translate) property and eventually the associated style
        //properties (we will ignore the last ones by the time being because it
        //do not seem to be used by Open and Libre Office
        if ($dimension == 'x') {
            $dim = 'svg:width';
            $pos = 'svg:x';
        } else if ($dimension == 'y') {
            $dim = 'svg:height';
            $pos = 'svg:y';
        } 
        $dimArray = array();
        $childs = $node->childNodes;
        foreach ($childs as $child) {
            $d = $child->getAttribute($dim);
            $l = $child->getAttribute($pos);
            $trans = $this->_translate($dimension,
                                       $child->getAttribute('draw:transform'));
            $t = $trans[$dimension];
            if (empty($d) && empty($l)){
                $d = $this->_calculateDimension($dimension, $child);
            } else if (empty($d)){
                $d = $this->_calculateDimension($dimension, $child, $l);
            }
            if ($dimension == 'x') {
                $sum = array($d, $t, $offset);
            } else if ($dimension == 'y') {
                $sum = array($d, $l, $t, $offset);
            }
            $dimArray[] = ODF2HTML5::sum($sum, 'pt');
        }
        //get the biggest number in dimArray
        if (\count($dimArray) > 0) {
            return \max($dimArray) . 'pt';
        } else {
            return '0pt';
        }
    }
    
    /**
     * Renders the draw:custom-shape node with all the required attributes
     *
     * @param DOMNode $baseNode
     * @param DOMNode $odfNode
     * @return DOMNode
     * @access private
     */
    private function _customShape($baseNode, $odfNode) 
    {
        $height = $odfNode->getAttribute('svg:height');
        $width = $odfNode->getAttribute('svg:width');
        $x = $odfNode->getAttribute('svg:x');
        $y = $odfNode->getAttribute('svg:y');
        $transform = $odfNode->getAttribute('draw:transform');
        $graphicStyle = $odfNode->getAttribute('draw:style-name');
        //the text style is taken into account when parsing the text:p node
        //$textStyle = $odfNode->getAttribute('draw:text-style-name');
        $style = 'position: absolute; float: none !important;';
        if (!empty($height)) {
            $style .= 'height: ' . $height . ';';
        }
        if (!empty($width)) {
            $style .= 'width: ' . $width . ';';
        }
        if (!empty($x)) {
            $style .= 'margin-left: ' . $x . ';';
        }
        if (!empty($y)) {
            $style .= 'margin-top: ' . $y . ';';
        }
        if (!empty($transform)) {
            $transform = $this->_parseTransform($transform);
            $style .= 'transform: ' . $transform . ';';
            $style .= 'transform-origin: 0% 0%;';
        }
        $baseNode->setAttribute('style', $style);
        if (!empty($graphicStyle)) {
            $baseNode->setAttribute('class', $graphicStyle);
        }
        $geometries = $odfNode->getElementsByTagName('enhanced-geometry');
        if ($geometries->length > 0) {
            $geo = $geometries->item(0);
            $viewBox = $geo->getAttribute('svg:viewBox');
            $svg = $baseNode->ownerDocument->createElement('svg');
            $svgStyle = 'position: absolute; top: 0; left: 0;';
            $svgStyle .= 'padding: 0 !important; border: none !important';
            $svg->setAttribute('style', $svgStyle);
            $svg->setAttribute('preserveAspectRatio', 'none');
            $svg->setAttribute('fill-rule', 'evenodd');
            $svg->setAttribute('class', $graphicStyle);
            if (!empty($viewBox)) {
                $svg->setAttribute('viewBox', $viewBox);
            }
            if (!empty($height)) {
                $svg->setAttribute('height', $height);
            }
            if (!empty($width)) {
                $svg->setAttribute('width', $width);
            }
            $scaleX = 0;
            if (!empty($viewBox) && !empty($width)) {
                $scaleX = $this->_strokeScale($viewBox, $width, 'x');
            }
            $scaleY = 0;
            if (!empty($viewBox) && !empty($height)) {
                $scaleY = $this->_strokeScale($viewBox, $height, 'y');
            }
            if (!empty($scaleX) && !empty($scaleY)) {
                $scale = \floor(($scaleX + $scaleY)/2);
            } else if (!empty($scaleX)) {
                $scale = $scaleX;
            } else if (!empty($scaleY)) {
                $scale = $scaleY;
            } else {
                $scale = 0;
            }
            if (!empty($scale)){
                ODF2HTML5::$strokeScales[$graphicStyle] = $scale;
            }
            $path = $geo->getAttribute('draw:enhanced-path');
            if (!empty($path)) {
                $g = $baseNode->ownerDocument->createElement('g');
                $this->_parseEnhancedPaths($g, $path);
                $svg->appendChild($g);
            }
        }
        $baseNode->appendChild($svg);
        return $baseNode;
    }
    
    /**
     * Renders the draw:g node with all the required attributes
     *
     * @param DOMNode $baseNode
     * @param DOMNode $odfNode
     * @return DOMNode
     * @access private
     */
    private function _g($baseNode, $odfNode) 
    {
        //SVG standard requires always height and width attributes for
        //proper rendering of the <svg> node. Unfortuanetly Open and Libre 
        //Office compute them on real time the required size so we are also 
        //obliged to do so.
        $height = 0;
        $width = 0;
        //First check if the height and width attribute are explicitely defined
        $height = $odfNode->getAttribute('svg:height');
        $width = $odfNode->getAttribute('svg:width');
        if (empty($height)) {
            $height = $this->_calculateDimension('y', $odfNode);
        }
        if (empty($width)) {
            $width = $this->_calculateDimension('x', $odfNode);
        }
        $style = ' float: none !important; left: 0;';
        $style .= 'height: ' . $height . ';';
        //check if there is an text: anchor-type element
        $anchor = $odfNode->getAttribute('text:anchor-type');
        if (empty($anchor)) {
            $style .= 'position: absolute;';
            $style .= 'width: ' . $width . ';';
            //$style .= 'left: 0.588cm;';
        } 
        $x = $odfNode->getAttribute('svg:x');
        if (!empty($x)) {
            $style .= 'left: ' . $x . ';';
        }
        $y = $odfNode->getAttribute('svg:y');
        if (!empty($y)) {
            $style .= 'margin-top: ' . $y . ';';
        }
        //TODO: remove border
        //$style .= 'border: 1px solid red;';
        $baseNode->setAttribute('style', $style);
        $class = $odfNode->getAttribute('draw:style-name');
        $baseNode->setAttribute('class', $class);
        return $baseNode;
    }
    
    /**
     * Chop an enhanced path into its simple path components and inserts
     * the corresponding <path> elements into the given <g> element
     *
     * @param DOMNode $g
     * @param string $paths
     * @return DOMNode
     * @access private
     */
    private function _parseEnhancedPaths($g, $paths) 
    {
        $pathArray = \explode('N', $paths);
        foreach ($pathArray as $path) {
            if (!empty($path)) {
                $pathNode = $g->ownerDocument->createElement('path');
                if (\strpos($path, 'S') !== false) {
                    $path = \str_replace('S', '', $path);
                    $pathNode->setAttribute('stroke', 'none');
                }
                if (\strpos($path, 'F') !== false) {
                    $path = \str_replace('F', '', $path);
                    $pathNode->setAttribute('fill', 'none');
                }
                $pathNode->setAttribute('d', $path);
                $g->appendChild($pathNode);
            }
        }
        return $g;        
    }
    
    /**
     * Sets the transform parameter following CSS standards
     *
     * @param string $transform
     * @return string
     * @access private
     */
    private function _parseTransform($transform) 
    {
        $trans = $this->_translate($transform);
        $rot = $this->_rotate($transform);
        $result = '';
        $result .= 'translate(' . $trans['x'] . ',' . $trans['y'] . ') ';
        if (!empty($rot)) {
            $result .= 'rotate(' . $rot . 'deg)';
        }
        
        return $result . ';';
    }
    
    /**
     * Computes the stroke width scale factor depending on the viewBox and
     * viewPort dimensions
     *
     * @param string $viewBox
     * @param string $dim
     * @param string $axis
     * @return int
     * @access private
     */
    private function _strokeScale($viewBox, $dim, $axis) 
    {
        $pixels = ODF2HTML5::convertUnits('px', $dim);
        $regex = '/(([0-9]+)[^\d]*)/';
        preg_match_all($regex, $viewBox, $matches);
        if(isset($matches[2]) && $axis == 'x' && !empty($pixels)) {
            return \floor(($matches[2][2] - $matches[2][0])/$pixels);
        } else if(isset($matches[2]) && $axis == 'y' && !empty($pixels)) {
            return \floor(($matches[2][3] - $matches[2][1])/$pixels);
        } else {
            return 0;
        }
    }
    
    /**
     * Extracts the rotation parameters of a SVG transform
     *
     * @param string $transform
     * @return string
     * @access private
     */
    private function _rotate($transform) 
    {
        if (empty($transform)) {
            return 0;
        }
        $regex = '/rotate\s*\(\s*([a-z0-9\-\.]*)\s*\)/i';
        preg_match_all($regex, $transform, $matches);
        if (!empty($matches[1][0])) {
            //TODO: understand the factor of '60'
            return $matches[1][0] * 60 * (-1);
        }  else {
            return 0;
        }
        
    }
    
    /**
     * Extracts the translation parameters of a SVG transform
     *
     * @param string $transform
     * @return array
     * @access private
     */
    private function _translate($transform) 
    {
        if (empty($transform)) {
            return array('x' => 0, 'y' => 0);
        }
        $regex = '/translate\s*\(\s*([a-z0-9\-\.]*)\s+([a-z0-9\-\.]*)\s*\)/i';
        preg_match_all($regex, $transform, $matches);
        if (!empty($matches[1][0]) && empty($matches[2][0])) {
            return array('x' => $matches[1][0], 'y' => 0);
        } else if (!empty($matches[1][0]) && !empty($matches[2][0])) {
            return array('x' => $matches[1][0], 'y' => $matches[2][0]);
        } else {
            return array('x' => 0, 'y' => 0);
        }
        
    }
    

}