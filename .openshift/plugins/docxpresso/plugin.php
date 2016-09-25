<?php

/*
  Plugin Name: Docxpresso
  Plugin URI: http://www.docxpresso.com/plugins/wp-docxpresso
  Description: Insert content coming from an Office file (.odt)
  Version: 1.5
  Author: No-nonsense Labs
  Author URI: http://www.docxpresso.com
 */

if (is_admin()) {
    include dirname(__FILE__) . '/admin.php';
}

function docxpresso_call($attrs, $content = null) {
    $options = get_option('docxpresso', array());
    $buffer = '';
    if (isset($attrs['file'])) {
        if (isset($attrs['comments'])) {
            $comments = $attrs['comments'];
        } else {
            $comments = false;
        }
        if (isset($attrs['sortTables'])) {
            $sortTables = $attrs['sortTables'];
        } else {
            $sortTables = true;
        }
        if (isset($attrs['sortNumberFormat'])) {
            $sortNumberFormat = $attrs['sortNumberFormat'];
        } else if (isset($options['sortNumberFormat'])){
            $sortNumberFormat = $options['sortNumberFormat'];
        } else {
            $sortNumberFormat = ',.';
        }
        $file = strip_tags($attrs['file']);
        $path = parse_url($file, PHP_URL_PATH);
        $fullPath = $_SERVER['DOCUMENT_ROOT'] . $path;
        if (!file_exists($fullPath)) {
            //this is a hack for servers that do not return the correct
            //$_SERVER['DOCUMENT_ROOT'] value
            $tempArray = explode('wp-content', $path);
            $fullPath = getcwd() . '/wp-content' . $tempArray[1];
        }
        require_once 'CreateDocument.inc';

        $doc = new Docxpresso\createDocument(array('template' => $fullPath));
        $html = $doc->ODF2HTML5('test.html', array('format' => 'single-file', 
                                                   'download' => true, 
                                                   'parseLayout' => false, 
                                                   'parseComments' => $comments,
                                                   'sortNumberFormat' => $sortNumberFormat,
                                                   'sortTables' => $sortTables));
        $buffer = $html;
        unset($doc);
    }
    return $buffer;
}

add_shortcode('docxpresso', 'docxpresso_call');

