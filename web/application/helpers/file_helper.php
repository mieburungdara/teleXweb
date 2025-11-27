<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('get_file_icon')) {
    /**
     * Returns an emoji icon based on the file's MIME type.
     *
     * @param string|null $mime_type
     * @return string
     */
    function get_file_icon($mime_type)
    {
        if (!$mime_type) {
            return '❓'; // Unknown
        }

        if (strpos($mime_type, 'image/') === 0) {
            return '🖼️'; // Image
        }
        if (strpos($mime_type, 'video/') === 0) {
            return '🎬'; // Video
        }
        if (strpos($mime_type, 'audio/') === 0) {
            return '🎵'; // Audio
        }
        if ($mime_type === 'application/pdf') {
            return '📄'; // PDF
        }
        if ($mime_type === 'application/zip' || $mime_type === 'application/x-rar-compressed') {
            return '📦'; // Archive
        }
        if (strpos($mime_type, 'text/') === 0) {
            return '📝'; // Text
        }

        return '📁'; // Generic file
    }
}
