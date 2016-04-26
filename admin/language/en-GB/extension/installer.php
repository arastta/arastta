<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

// Heading
$_['heading_title']          = 'Installer';

// Text
$_['text_success']           = 'Success: You have installed your add-on!';
$_['text_uninstall_success'] = 'Success: You have uninstalled your add-on!';
$_['text_unzip']             = 'Extracting files!';
$_['text_ftp']               = 'Copying files!';
$_['text_sql']               = 'Running SQL!';
$_['text_xml']               = 'Applying modifications!';
$_['text_php']               = 'Running PHP!';
$_['text_json']              = 'Reading JSON!';
$_['text_remove']            = 'Removing temporary files!';
$_['text_clear']             = 'Success: You have cleared all temporary files!';
$_['text_modification']      = 'Mofidication: This process may take long time, The modification is being scripted. Please wait!';

// Entry
$_['entry_upload']           = 'Upload File';
$_['entry_overwrite']        = 'Files that will be overwritten';
$_['entry_progress']         = 'Progress';

// Help
$_['help_upload']            = 'Requires a modification file with extension ( ".ocmod.zip", ".zip" ) or ( ".ocmod.xml", ".xml" ) .';

// Error
$_['error_permission']       = 'Warning: You do not have permission to modify extensions!';
$_['error_temporary']        = 'Warning: There are some temporary files that require deleting. Click the clear button to remove them!';
$_['error_download']         = 'File could not be downloaded!';
$_['error_upload']           = 'File could not be uploaded!';
$_['error_filetype']         = 'Invalid file type!';
$_['error_file']             = 'File could not be found!';
$_['error_unzip']            = 'Zip file could not be opened!';
$_['error_code']             = 'Modification requires a unique ID code!';
$_['error_exists']           = 'Modification %s is using the same ID code as the one you are trying to upload! If you want to override Modification file. Please click Continue button.';
$_['error_directory']        = 'Directory containing files to be uploaded could not be found!';
$_['error_ftp_status']       = 'FTP needs to be enabled in the settings';
$_['error_ftp_connection']   = 'Could not connect as %s:%s';
$_['error_ftp_login']        = 'Could not login as %s';
$_['error_ftp_root']         = 'Could not set root directory as %s';
$_['error_ftp_directory']    = 'Could not change to directory %s';
$_['error_ftp_file']         = 'Could not upload file %s';
$_['error_copy_xmls_file']   = 'XML dosen\'t install,Please check file!';
$_['error_uninstall_already']= 'Uninstalled already!';
$_['error_zip']              = 'Warning: ZIP extension needs to be loaded on your server! Please, ask your hosting company for further help.';
$_['error_xml']              = 'Warning: XML extension needs to be loaded on your server! Please, ask your hosting company for further help.';
$_['error_json_1']           = 'Maximum stack depth exceeded!';
$_['error_json_2']           = 'Underflow or the nodes mismatch!';
$_['error_json_3']           = 'Unexpected control character found!';
$_['error_json_4']           = 'Syntax error, malformed JSON!';
$_['error_json_5']           = 'Malformed UTF-8 characters, possibly incorrectly encoded!';
$_['error_language_exist']   = 'Language already installed!';
