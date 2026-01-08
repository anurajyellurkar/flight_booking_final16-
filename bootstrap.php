<?php
// Start output buffering to prevent header errors
ob_start();

// Start session once, everywhere
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
