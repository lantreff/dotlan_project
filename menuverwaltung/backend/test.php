<?php
$json = '[
  {
    "label": "Hauptmenu",
    "max": 5,
    "allowedTypes": [
      "hauptmenu"
    ],
    "eintrage": [
      {
        "name": "mickey",
        "type": "hauptmenu"
      },
      {
        "name": "fuck",
        "type": "hauptmenu"
      }
    ]
  },
  {
    "label": "Orga",
    "max": 6,
    "allowedTypes": [
      "orga"
    ],
    "eintrage": [
      {
        "name": "eins",
        "type": "orga"
      },
      {
        "name": "blabla",
        "type": "orga"
      }
    ]
  }
]';

print_r(json_decode($json));