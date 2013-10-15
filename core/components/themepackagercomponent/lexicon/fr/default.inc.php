<?php
/**
 * ThemePackagerComponent
 *
 * Copyright 2013 by Mike Schell <mike@modx.com> for MODX, LLC
 *
 * This file is part of ThemePackagerComponent.
 *
 * ThemePackagerComponent is free software; you can redistribute it and/or modify it
 * under the terms of the GNU General Public License as published by the Free
 * Software Foundation; either version 2 of the License, or (at your option) any
 * later version.
 *
 * ThemePackagerComponent is distributed in the hope that it will be useful, but
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or
 * FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more
 * details.
 *
 * You should have received a copy of the GNU General Public License along with
 * ThemePackagerComponent; if not, write to the Free Software Foundation, Inc., 59
 * Temple Place, Suite 330, Boston, MA 02111-1307 USA
 *
 * @package themepackagercomponent
 */
/**
 * French default lexicon topic for ThemePackagerComponent
 *
 * @package themepackagercomponent
 * @subpackage lexicon
 */
$_lang['themepackagercomponent'] = 'ThemePackagerComponent';
$_lang['themepackagercomponent.assets_path'] = 'Répertoire des Assets';
$_lang['themepackagercomponent.assets_path_desc'] = 'Le répertoire dans lequel ce snippet héberge ses fichiers d\'"Assets". Si le répertoire n\'est pas trouvé, il sera ignoré.';
$_lang['themepackagercomponent.chunk'] = 'Chunk';
$_lang['themepackagercomponent.chunk_add'] = 'Ajouter un Chunk';
$_lang['themepackagercomponent.chunk_desc'] = 'Le Chunk à inclure dans le paquet.';
$_lang['themepackagercomponent.chunk_err_ns'] = 'Veuillez choisir un Chunk.';
$_lang['themepackagercomponent.chunk_remove'] = 'Supprimer le Chunk';
$_lang['themepackagercomponent.chunk_remove_confirm'] = 'Êtes vous sûr de vouloir supprimer ce Chunk?';
$_lang['themepackagercomponent.chunks'] = 'Chunks';
$_lang['themepackagercomponent.chunks.intro_msg'] = '<p>Veuillez sélectionner les Chunks à inclure dans le paquet.</p>';
$_lang['themepackagercomponent.core_path'] = 'Répertoire "Core"';
$_lang['themepackagercomponent.core_path_desc'] = 'Le répertoire dans lequel ce snippet héberge ses fichiers "Core". Si le répertoire n\'est pas trouvé, il sera ignoré.';
$_lang['themepackagercomponent.create_new...'] = 'Créer un nouveau profil à partir de cela...';
$_lang['themepackagercomponent.description'] = 'Description';
$_lang['themepackagercomponent.directories'] = 'Répertoires';
$_lang['themepackagercomponent.directories.intro_msg'] = '<p>Veuillez sélectionner les répertoires que vous souhaiter ajouter au paquet principal. Ces répertoires seront transportés et installés dans le répertoire "cible" pour chaque répertoire spécifié.</p><p>Il est important de noter que les utilisateurs du paquet devront s\'assurer que tous les répertoires "cibles" soient accessibles en écriture avant que le gestionnaire de paquets puisse installer le paquet.</p>';
$_lang['themepackagercomponent.directory'] = 'Répertoire';
$_lang['themepackagercomponent.directory_add'] = 'Ajouter un répertoire';
$_lang['themepackagercomponent.directory_add_desc'] = '<p>Veuillez sélectionner un répertoire source et un répertoire cible. Le répertoire source est le répertoire de votre sytème de fichers; le répertoire cible est est l\'endroit dans lequel vous souhaitez installer le répertoire sur le système de fichiers de l\'utilisateur.</p><p>Vous pouvez utiliser les variables suivantes dans chaque champs: {base_path}, {core_path}, {assets_path}.</p><p>Veuillez vous assurer que la cible ne contienne pas le nom du répertoire source; par exemple, si vous voulez que le dossier "assets/test/" soit le répertoire "assets/test/" chez l\'utilisateur, spécifiez la cible en "assets/", <b>et non</b> "assets/test/".</p>';
$_lang['themepackagercomponent.directory_err_ns'] = 'Veuillez spécifier une source et une cible pour le répertoire.';
$_lang['themepackagercomponent.directory_remove'] = 'Supprimer le répertoire';
$_lang['themepackagercomponent.directory_remove_confirm'] = 'Êtes vous sûr de vouloir supprimer ce répertoire?';
$_lang['themepackagercomponent.directory_source'] = 'Source';
$_lang['themepackagercomponent.directory_source_desc'] = 'Le répertoire source à installer à partir de votre système de fichiers.';
$_lang['themepackagercomponent.directory_target'] = 'Cible';
$_lang['themepackagercomponent.directory_target_desc'] = 'Le répertoire cible à installer sur le sytème de fichiers de l\'utilisateur.';
$_lang['themepackagercomponent.export'] = 'Exporter le paquet de transport';
$_lang['themepackagercomponent.intro_msg'] = '<p>Pour créer un paquet de transport, choisissez simplement un nom de catégorie pour votre paquet. Puis spécifiez des Modèles, des Snippets, des Chunks et/ou des sous-paquets que vous souhaitez inclure dans le paquet. Vous pouvez ensuite optionnellement spécifier un lisezmoi et/ou un fichier licence à inclure dans le paquet. Enfin, spécifiez le numéro de version et de statut du paquet de transport.</p>';
$_lang['themepackagercomponent.menu_desc'] = 'Un outil pour créer des paquets de transport simples.';
$_lang['themepackagercomponent.name'] = 'Nom';
$_lang['themepackagercomponent.mypackage'] = 'MonPaquet';
$_lang['themepackagercomponent.package_name'] = 'Nom du paquet';
$_lang['themepackagercomponent.package_name_desc'] = 'Le nom du paquet qui sera créé. Une catégorie sera créée avec ce nom, et tous les éléments du paquets seront assignés à ce nom.';
$_lang['themepackagercomponent.profile_create'] = 'Créer un profil';
$_lang['themepackagercomponent.profile_description_desc'] = 'Une courte description du profil.';
$_lang['themepackagercomponent.profile_name_desc'] = 'Le nom du profil.';
$_lang['themepackagercomponent.profile_remove'] = 'Supprimer le profil';
$_lang['themepackagercomponent.profile_remove_confirm'] = 'Êtes vous sûr de vouloir supprimer ce profil? Cette action est irréversible.';
$_lang['themepackagercomponent.profile_save'] = 'Sauvegarder le profil';
$_lang['themepackagercomponent.profile_saved'] = 'Profil sauvegardé avec succès.';
$_lang['themepackagercomponent.profile_select'] = 'Sélectionnez un profil...';
$_lang['themepackagercomponent.release'] = 'Statut';
$_lang['themepackagercomponent.release_desc'] = 'Le statut du paquet de transport qui sera créé. Exemples: beta1, rc2, pl';
$_lang['themepackagercomponent.release_err_nf'] = 'Veuillez spécifier un statut.';
$_lang['themepackagercomponent.signature'] = 'Signature';
$_lang['themepackagercomponent.subpackage'] = 'Sous-paquet';
$_lang['themepackagercomponent.subpackage_add'] = 'Ajouter un sous-paquet';
$_lang['themepackagercomponent.subpackage_desc'] = 'La signature du sous-paquet que vous souhaitez inclure. Vous devez avoir installé le paquet sur ce système pour le voir dans la liste.';
$_lang['themepackagercomponent.subpackage_err_ns'] = 'Veuillez sélectionner un sous-paquet.';
$_lang['themepackagercomponent.subpackage_remove'] = 'Supprimer le sous-paquet';
$_lang['themepackagercomponent.subpackage_remove_confirm'] = 'Êtes vous sûr de vouloir supprimer ce sous-paquet?';
$_lang['themepackagercomponent.subpackages'] = 'Sous-paquets';
$_lang['themepackagercomponent.subpackages.intro_msg'] = '<p>Veuillez séléctionner les paquets de transport que vous souhaitez inclure au paquet pricipal. Ils seront transportés avec le paquet principal.</p><p>Si une nouvelle version du sous-paquet est trouvée sur le serveur, alors il sera omis; sinon il sera automatiquement installé. Par exemple, si vous incluez le paquet FormIt 1.0.0-rc1, et que le paquet FormIt 2.0.0.-beta1 existe sur le serveur, le sous-paquet sera omis.</p>';
$_lang['themepackagercomponent.template'] = 'Modèle';
$_lang['themepackagercomponent.template_add'] = 'Ajouter un modèle';
$_lang['themepackagercomponent.template_desc'] = 'Le modèle à inclure dans le paquet.';
$_lang['themepackagercomponent.template_err_ns'] = 'Veuillez sélectionner un modèle.';
$_lang['themepackagercomponent.template_err_nf'] = 'Modèle non trouvé!';
$_lang['themepackagercomponent.template_directory_desc'] = 'Le nom du répertoire dans lequel sont situés vos fichiers de modèle. Cela ne peut être qu\'un répertoire. Cela sera installé dans le répertoire assets/templates/.';
$_lang['themepackagercomponent.template_remove'] = 'Supprimer le modèle';
$_lang['themepackagercomponent.template_remove_confirm'] = 'Êtes vous sûr de vouloir supprimer ce modèle?';
$_lang['themepackagercomponent.templates'] = 'Modèles';
$_lang['themepackagercomponent.templates.intro_msg'] = '<p>Veuillez sélectionner les modèles que vous souhaitez inclure dans le paquet. Le script incluera également automatiquement les variables de modèle qui y sont associées.</p><p>Vous pouvez également spécifier un répertoire à inclure pour chaque modèle. Le script copiera automatiquement ce répertoire vers le répertoire assets/templates/ durant l\'installation du paquet. Si le répertoire n\'est pas trouvé, il sera ignoré.</p>';
$_lang['themepackagercomponent.version'] = 'Version';
$_lang['themepackagercomponent.version_desc'] = 'La version du paquet de transport qui sera créé. Exemples: 1.0.0, 2.1, 3.2.5';
$_lang['themepackagercomponent.version_err_nf'] = 'Veuillez spécifier une version.';
$_lang['themepackagercomponent.version_files_err_nf'] = 'Le répertoire des fichiers de modèle n\'existe pas.';

