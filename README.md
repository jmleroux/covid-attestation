# Générateur d'attestation COVID Q4 2020

_En français pour une fois, car cela ne concerne que la France_

Ce générateur essaie humblement de combler un manque fonctionnel du générateur officiel que vous pouvez utiliser
à cette adresse : https://media.interieur.gouv.fr/deplacement-covid-19/

Il permet de sauvegarder un lien personnalisé après rempli vos informations personnelles afin de générer cette
attestation en deux clics.

Aucune information n'est stockée, aucun cookie n'est généré. Il s'agit juste d'un raccourci sur votre navigateur.

## Pour les développeurs

L'application se veut très simple à tester et à modifier sur une plateforme linux avec docker-compose.

Vous pouvez l'installer avec la commande `make setup`.

Les tests sont lancés avec `make tests` et vous pouvez généré le code coverage avec `make coverage`.

