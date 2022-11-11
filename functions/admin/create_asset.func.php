<?php

/**
 * Validate name
 *
 * Validate a name to get rid of all characters that aren't alphabets
 */
function validateName($name) {
  return preg_match("/^[a-zA-Z0-9]+$/", $name);
}

function validateLimit($limit) {
  return preg_match("/^[0-9]{6,}$/", $limit);
}

function validateLimitMax($limit) {
  $limit = (int) $limit;
  return $limit < 9223372036854775807;
}
function validateSeedPhrase($phrase) {
  return preg_match("/^[a-zA-Z0-9]{56}$/", $phrase);
}

function validateURL($url) {
  return filter_var($url, FILTER_VALIDATE_URL);
}
