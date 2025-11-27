<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class LanguageLoader {

    function load_language()
    {
        $CI =& get_instance();
        $CI->load->helper('language');
        $CI->config->load('config'); // Load config to get available_languages

        $site_language = $CI->session->userdata('site_language');
        
        if ($site_language && array_key_exists($site_language, $CI->config->item('available_languages'))) {
            $CI->lang->load('app', $site_language);
        } else {
            // Default to 'english' if no preference or invalid preference
            $CI->session->set_userdata('site_language', 'english');
            $CI->lang->load('app', 'english');
        }
    }
}