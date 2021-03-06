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
 * English default lexicon topic for ThemePackagerComponent
 *
 * @package themepackagercomponent
 * @subpackage lexicon
 */
$_lang['themepackagercomponent'] = 'ThemePackagerComponent';
$_lang['themepackagercomponent.advanced_title'] = 'Advanced';
$_lang['themepackagercomponent.advanced.settings'] = 'System/Context Settings';
$_lang['themepackagercomponent.advanced.settings_desc'] = 'Enter a comma-separated list of System or Context settings to be included with your Theme.';
$_lang['themepackagercomponent.advanced.settings_invalid'] = 'This does not appear to be a comma-separated list of Setting keys.';
$_lang['themepackagercomponent.assets_path'] = 'Assets Path';
$_lang['themepackagercomponent.assets_path_desc'] = 'The directory that this Snippet hosts its assets files in. If it is not found, it will be ignored.';
$_lang['themepackagercomponent.chunk'] = 'Chunk';
$_lang['themepackagercomponent.chunk_add'] = 'Add Chunk';
$_lang['themepackagercomponent.chunk_desc'] = 'The Chunk to package in.';
$_lang['themepackagercomponent.chunk_err_ns'] = 'Select a Chunk.';
$_lang['themepackagercomponent.chunk_remove'] = 'Remove Chunk';
$_lang['themepackagercomponent.chunk_remove_confirm'] = 'Are you sure you want to remove this Chunk?';
$_lang['themepackagercomponent.chunks'] = 'Chunks';
$_lang['themepackagercomponent.chunks.intro_msg'] = '<p>Select Chunks you wish to include in your theme package.</p>';
$_lang['themepackagercomponent.core_path'] = 'Core Path';
$_lang['themepackagercomponent.core_path_desc'] = 'The directory that this Snippet hosts its core files in. If it is not found, it will be ignored.';
$_lang['themepackagercomponent.create_new...'] = 'Create New Profile from this...';
$_lang['themepackagercomponent.description'] = 'Description';
$_lang['themepackagercomponent.directories'] = 'Files';
$_lang['themepackagercomponent.directories.intro_msg'] = '<p>Select any directories you want added to the main Package. They will be transported and installed to the "target" path for each directory you specify. It is important to note that your users will have to ensure that all the "target" directories are writable before Package Management can install the directories to them.</p>';
$_lang['themepackagercomponent.directory'] = 'Directory';
$_lang['themepackagercomponent.directory_add'] = 'Add Directory';
$_lang['themepackagercomponent.directory_add_desc'] = '<p>Select a source directory and a target directory. The source directory is the directory on your filesystem; the target is the location in which you want to install the directory on the user\'s system.</p><p>You can use the following placeholders in either field: {base_path}, {core_path}, {assets_path}.</p><p>Make sure the target does not include the name of the source directory; ie, if you want the folder "assets/test/" to end up as the user\'s "assets/test/" directory, make the target be "assets/", <b>not</b> "assets/test/".</p>';
$_lang['themepackagercomponent.directory_err_ns'] = 'Specify a source and target for the directory.';
$_lang['themepackagercomponent.directory_remove'] = 'Remove Directory';
$_lang['themepackagercomponent.directory_remove_confirm'] = 'Are you sure you want to remove this Directory?';
$_lang['themepackagercomponent.directory_source'] = 'Source';
$_lang['themepackagercomponent.directory_source_desc'] = 'The target directory to install from on your system.';
$_lang['themepackagercomponent.directory_target'] = 'Target';
$_lang['themepackagercomponent.directory_target_desc'] = 'The target directory to install into on the user\'s system.';
$_lang['themepackagercomponent.elements'] = 'Elements';
$_lang['themepackagercomponent.enduser_option_desc'] = 'These are options that can be presented to the user when they install your Theme though MODX Revolution\'s Package Management feature. These options cannot currently be presented when installed via MODX Cloud, but you can control the default behavior.';
$_lang['themepackagercomponent.enduser_option_merge_label'] = 'Option - Add / Overwrite';
$_lang['themepackagercomponent.enduser_option_merge_desc'] = 'If checked, the end user will be presented with the choice of whether to simply Add the contents of your theme package to their site, or to Overwrite their site with your package contents. Choose the default behavior that you want if you\'re not giving the user that choice, or for when the user installs your package via MODX Cloud.';
$_lang['themepackagercomponent.enduser_option_samplecontent_label'] = 'Option - Sample Content';
$_lang['themepackagercomponent.enduser_option_samplecontent_desc'] = 'The Resources you choose to add  to your theme package are considered as "Sample Content" from the end user\'s perspective. Checking this option gives your end user the choice to install the "Sample Content" or not. Choose the default behavior if you\'re not giving your users that option, or for when the user installs your package via MODX Cloud.';
$_lang['themepackagercomponent.all_elements'] = 'Elements, Subpackages and Resources';
$_lang['themepackagercomponent.all_elements_desc'] = 'Include all Templates, Template Variables, Chunks, Snippets, Plugins, Resources and Sub-packages (except for this theme packager extra) from this MODX instance.';
$_lang['themepackagercomponent.all_files'] = 'Files';
$_lang['themepackagercomponent.all_files_desc'] = 'Include all non-MODX files from the site root, and any non-core directories in the theme package.';
$_lang['themepackagercomponent.export'] = 'Export Transport Package';
$_lang['themepackagercomponent.intro_msg'] = '<p>To create a Transport Package, start by choosing a name, version and release for the package. Then choose elements (Templates, Chunks, Subpackages, Resources, Files) to include the Package. Finally, decide which options you would like to make available to the end user when they install the package.</p>';
$_lang['themepackagercomponent.install_options_title'] = 'Install Options for the End User';
$_lang['themepackagercomponent.menu_desc'] = 'A tool for creating simple Transport Packages.';
$_lang['themepackagercomponent.name'] = 'Name';
$_lang['themepackagercomponent.mypackage'] = 'MyPackage';
$_lang['themepackagercomponent.package'] = 'Package';
$_lang['themepackagercomponent.package_name'] = 'Package Name';
$_lang['themepackagercomponent.package_name_desc'] = 'The name of the Package that will be created. A Category will be created with this name, and all packaged Elements will be assigned to it.';
$_lang['themepackagercomponent.plugins'] = 'Plugins';
$_lang['themepackagercomponent.plugins.intro_msg'] = '<p>Select any custom Plugins that you would like to add to your theme package.</p>';
$_lang['themepackagercomponent.profile_create'] = 'Create Profile';
$_lang['themepackagercomponent.profile_description_desc'] = 'A short description of the Profile.';
$_lang['themepackagercomponent.profile_name_desc'] = 'The name of the Profile.';
$_lang['themepackagercomponent.profile_options'] = 'Profile Options';
$_lang['themepackagercomponent.profile_remove'] = 'Remove Profile';
$_lang['themepackagercomponent.profile_remove_confirm'] = 'Are you sure you want to permanently remove this Profile?';
$_lang['themepackagercomponent.profile_save'] = 'Save Profile';
$_lang['themepackagercomponent.profile_saved'] = 'Profile successfully saved.';
$_lang['themepackagercomponent.profile_select'] = 'Select a Profile...';
$_lang['themepackagercomponent.release'] = 'Release';
$_lang['themepackagercomponent.release_desc'] = 'The release of the Transport Package that will be created. Examples: beta1, rc2, pl';
$_lang['themepackagercomponent.release_err_nf'] = 'Specify a release.';
$_lang['themepackagercomponent.resources'] = 'Resources';
$_lang['themepackagercomponent.resources_field'] = 'Resources';
$_lang['themepackagercomponent.resources_field_desc'] = 'You may wish to include certain Resources along with your Theme for use as "Demo Content". Enter a comma separated list of Resource ids.';
$_lang['themepackagercomponent.resources_field_invalid'] = 'This is not a comma-separated list of resource ids';
$_lang['themepackagercomponent.signature'] = 'Signature';
$_lang['themepackagercomponent.snippets'] = 'Snippets';
$_lang['themepackagercomponent.snippets.intro_msg'] = '<p>Select any custom Snippets to add to your theme package. (Again, you don\'t need to select any that are part of any sub-packages you\'re including.)</p>';
$_lang['themepackagercomponent.subpackage'] = 'Subpackage';
$_lang['themepackagercomponent.subpackage_namespace'] = 'Namespace';
$_lang['themepackagercomponent.subpackage_namespace_desc'] = 'Provide a Namespace to ensure that the namespace, core and assets files for this Subpackage get packaged.';
$_lang['themepackagercomponent.subpackage_add'] = 'Add Subpackage';
$_lang['themepackagercomponent.subpackage_desc'] = 'The signature of the Subpackage to add in. You must have installed the Transport Package on this system to see it in this list.';
$_lang['themepackagercomponent.subpackage_err_ns'] = 'Select a Subpackage.';
$_lang['themepackagercomponent.subpackage_remove'] = 'Remove Subpackage';
$_lang['themepackagercomponent.subpackage_remove_confirm'] = 'Are you sure you want to remove this Subpackage?';
$_lang['themepackagercomponent.subpackages'] = 'Subpackages';
$_lang['themepackagercomponent.subpackages.intro_msg'] = '<p>Select any Transport Packages you want added to the main Package. They will be transported with the main Package.</p><p>If a newer version of the subpackage is found on the server, then it will be skipped; otherwise, it will be automatically installed. For example, if you package in FormIt 1.0.0-rc1, and FormIt 2.0.0-beta1 is on the server, the subpackage will be skipped.</p>';
$_lang['themepackagercomponent.template'] = 'Template';
$_lang['themepackagercomponent.template_add'] = 'Add Template';
$_lang['themepackagercomponent.template_desc'] = 'The Template to package in.';
$_lang['themepackagercomponent.template_err_ns'] = 'Select a Template.';
$_lang['themepackagercomponent.template_err_nf'] = 'Template not found!';
$_lang['themepackagercomponent.templates'] = 'Templates';
$_lang['themepackagercomponent.templates.intro_msg'] = '<p>Select the Templates you want in your theme package. Template Variables assigned to these Templates will be packaged as well.</p>';
$_lang['themepackagercomponent.unload_profile'] = 'Unload Profile';
$_lang['themepackagercomponent.version'] = 'Version';
$_lang['themepackagercomponent.version_desc'] = 'The version of the Transport Package that will be created. Examples: 1.0.0, 2.1, 3.2.5';
$_lang['themepackagercomponent.version_err_nf'] = 'Specify a version.';
$_lang['themepackagercomponent.version_files_err_nf'] = 'The template files directory specified does not exist.';

