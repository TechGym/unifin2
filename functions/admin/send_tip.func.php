<?php

function fetchTarget($type) {
  global $dbConnect;
  if ($type === "council5") {
    return fetchCouncilOf5();
  }
  if ($type === "council50") {
    return fetchCouncilOf50();
  }
}
