<?php
# English
$l['en'][1] = ['Temperature','Outside','Humidity','Runtime'];
$l['en'][2] = ['Celsius','moisture','minutes'];
$l['en'][3] = 'Prerequisite Checks';
$l['en'][4] = 'Setup';
$l['en'][5] = ['Directory', 'is not writable.</br>Make sure that user', 'has write permission.'];
$l['en'][6] = ['is writable by user', 'is not writable by'];
$l['en'][7] = ['PHP module sqlite3 is available', 'PHP module sqlite3 is not installed or loaded'];
$l['en'][8] = ['PHP module curl is available', 'PHP module curl is not installed or loaded'];
$l['en'][9] = ['PHP module simplexml is available', 'PHP module simplexml is not installed or loaded'];
$l['en'][10] = 'Setup thermostat\'s IP/hostname.';
$l['en'][11] = ['Thermostat', 'thermostat hostname/IP address', 'Close', 'Save changes'];
$l['en'][12] = 'Error: writing configuration file.';
$l['en'][13] = 'Can not find thermostat\'s model from API.';
$l['en'][14] = 'API not available.';
$l['en'][15] = 'Or run in demo mode which will load random data.';
$l['en'][16] = ['Random data', 'Number of months (12 months max)', 'Close', 'Enable demo'];
$l['en'][17] = ['Generating data', 'Done'];
$l['en'][18] = ['Confirm data deletion!', 'Close', 'Delete Demo'];
$l['en'][19] = 'Please wait...';

# French
$l['fr'][1] = ['Température','Extérieur','Humidité','Durée'];
$l['fr'][2] = ['Celsius','Humidité','minutes'];
$l['fr'][3] = 'Verification du prérequis';
$l['fr'][4] = 'Installation';
$l['fr'][5] = ['Répertoire', 'n\'est pas accessible en écriture.</br>Assurez-vous que l\'utilisateur', 'a la permission d\'écriture.'];
$l['fr'][6] = ['est accessible en écriture par l\'utilisateur', 'n\'est pas accessible en écriture par'];
$l['fr'][7] = ['Module sqlite3 de PHP est disponible', 'Module sqlite3 de PHP n\'est pas disponible ou n\'est pas chargé'];
$l['fr'][8] = ['Module curl de PHP est disponible', 'Module curl de PHP n\'est pas disponible ou n\'est pas chargé'];
$l['fr'][9] = ['Module simplexml de PHP est disponible', 'Module simplexml de PHP n\'est pas disponible ou n\'est pas chargé'];
$l['fr'][10] = 'Configuration adresse IP/nom domaine du thermostat.';
$l['fr'][11] = ['Thermostat', 'adresse IP/nom domaine du thermostat', 'Fermer', 'Savegarde'];
$l['fr'][12] = 'Erreur: écriture du fichier de configuration.';
$l['fr'][13] = 'Ne peut trouver le modèle du thermostat de l\'API.';
$l['fr'][14] = 'API est indispobible.';
$l['fr'][15] = 'Ou exécutez en mode démo qui chargera des données aléatoires.';
$l['fr'][16] = ['Données aléatoires', 'Nombre de mois (max 12 mois)', 'Fermer', 'Activer la démo'];
$l['fr'][17] = ['Génération de données', 'Terminé'];
$l['fr'][18] = ['Confirmez la suppression des données!', 'Fermer', 'Supprimer la démo'];
$l['fr'][19] = 'Patientez s\'il vous plaît...';

# Detect language
$lang = 'en';
if (array_key_exists('HTTP_ACCEPT_LANGUAGE', $_SERVER)) {
  $lang = substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
}
$accepted= ['fr', 'en'];
$lang = in_array($lang, $accepted) ? $lang : 'en';

?>
