<?php

if (!function_exists('ensure_secure_directory')) {
    function ensure_secure_directory(string $dirPath): void
    {
        if (!is_dir($dirPath)) {
            mkdir($dirPath, 0755, true);
        }

        $indexPath = rtrim($dirPath, '/\\') . DIRECTORY_SEPARATOR . 'index.html';
        if (!file_exists($indexPath)) {
            file_put_contents($indexPath, '<!DOCTYPE html><html><head><title>403 Forbidden</title></head><body><h1>Directory access is forbidden.</h1></body></html>');
        }

        $htaccessPath = rtrim($dirPath, '/\\') . DIRECTORY_SEPARATOR . '.htaccess';
        if (!file_exists($htaccessPath)) {
            $htaccessContent = "<FilesMatch \"\.(php|php5|php7|phtml|exe|pl|py|cgi)$\">\n"
                . "    Order Deny,Allow\n"
                . "    Deny from all\n"
                . "</FilesMatch>\n"
                . "Options -Indexes\n";
            file_put_contents($htaccessPath, $htaccessContent);
        }
    }
}
