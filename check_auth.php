<?php
// Simple auth-check endpoint used by client-side JS.
// Returns JSON: { authenticated: true }
session_start();
header('Content-Type: application/json; charset=utf-8');
$authenticated = isset($_SESSION['customer_id']) && !empty($_SESSION['customer_id']);

// DEBUG: include some session details to help diagnose why session may not persist.
// NOTE: this is temporary for debugging — remove in production.
$sessionKeys = array_keys($_SESSION);
echo json_encode([
	'authenticated' => $authenticated,
	'session_id' => session_id(),
	'session_keys' => $sessionKeys
]);
