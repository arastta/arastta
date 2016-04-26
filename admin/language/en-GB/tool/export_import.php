<?php
/**
 * @package        Arastta eCommerce
 * @copyright      Copyright (C) 2015-2016 Arastta Association. All rights reserved. (arastta.org)
 * @credits        See CREDITS.txt for credits and other copyright notices.
 * @license        GNU General Public License version 3; see LICENSE.txt
 */

// Heading
$_['heading_title']                         = 'Export / Import';

// Text
$_['text_list']                             = 'Export and Import';
$_['text_success']                          = 'Success: You have successfully imported your data!';
$_['text_success_settings']                 = 'Success: You have successfully updated the settings for the Export/Import tool!';
$_['text_export_type_category']             = 'Categories (including category data and filters)';
$_['text_export_type_category_old']         = 'Categories';
$_['text_export_type_product']              = 'Products (including product data, options, specials, discounts, rewards, attributes and filters)';
$_['text_export_type_product_old']          = 'Products (including product data, options, specials, discounts, rewards and attributes)';
$_['text_export_type_option']               = 'Option definitions';
$_['text_export_type_attribute']            = 'Attribute definitions';
$_['text_export_type_filter']               = 'Filter definitions';
$_['text_yes']                              = 'Yes';
$_['text_no']                               = 'No';
$_['text_nochange']                         = 'No server data has been changed.';
$_['text_log_details']                      = 'See also \'System &gt; Error Logs\' for more details.';
$_['text_loading_notifications']            = 'Getting messages';
$_['text_retry']                            = 'Retry';

// Entry
$_['entry_import']                          = 'Import from a XLS, XLSX or ODS spreadsheet file';
$_['entry_export']                          = 'Export requested data to a XLSX spreadsheet file.';
$_['entry_export_type']                     = 'Select what data you want to export:';
$_['entry_range_type']                      = 'Please select the data range you want to export:';
$_['entry_start_id']                        = 'Start id:';
$_['entry_start_index']                     = 'Counts per batch:';
$_['entry_end_id']                          = 'End id:';
$_['entry_end_index']                       = 'The batch number:';
$_['entry_incremental']                     = 'Use incremental Import';
$_['entry_upload']                          = 'File to be uploaded';
$_['entry_settings_use_option_id']          = 'Use <em>option_id</em> instead of <em>option name</em> in worksheets \'ProductOptions\' and \'ProductOptionValues\'';
$_['entry_settings_use_option_value_id']    = 'Use <em>option_value_id</em> instead of <em>option_value name</em> in worksheet \'ProductOptionValues\'';
$_['entry_settings_use_attribute_group_id'] = 'Use <em>attribute_group_id</em> instead of <em>attribute_group name</em> in worksheet \'ProductAttributes\'';
$_['entry_settings_use_attribute_id']       = 'Use <em>attribute_id</em> instead of <em>attribute name</em> in worksheet \'ProductAttributes\'';
$_['entry_settings_use_filter_group_id']    = 'Use <em>filter_group_id</em> instead of <em>filter_group name</em> in worksheets \'ProductFilters\' and \'CategoryFilters\'';
$_['entry_settings_use_filter_id']          = 'Use <em>filter_id</em> instead of <em>filter name</em> in worksheets \'ProductFilters\' and \'CategoryFilters\'';
$_['entry_settings_use_export_cache']       = 'Use phpTemp cache for large Exports (will be slightly slower)';
$_['entry_settings_use_import_cache']       = 'Use phpTemp cache for large Imports (will be slightly slower)';

// Error
$_['error_permission']                      = 'Warning: You do not have permission to modify Export/Import!';
$_['error_upload']                          = 'Uploaded spreadsheet file has validation errors!';
$_['error_categories_header']               = 'Export/Import: Invalid header in the Categories worksheet';
$_['error_category_filters_header']         = 'Export/Import: Invalid header in the CategoryFilters worksheet';
$_['error_products_header']                 = 'Export/Import: Invalid header in the Products worksheet';
$_['error_additional_images_header']        = 'Export/Import: Invalid header in the AdditionalImages worksheet';
$_['error_specials_header']                 = 'Export/Import: Invalid header in the Specials worksheet';
$_['error_discounts_header']                = 'Export/Import: Invalid header in the Discounts worksheet';
$_['error_rewards_header']                  = 'Export/Import: Invalid header in the Rewards worksheet';
$_['error_product_options_header']          = 'Export/Import: Invalid header in the ProductOptions worksheet';
$_['error_product_option_values_header']    = 'Export/Import: Invalid header in the ProductOptionValues worksheet';
$_['error_options_header']                  = 'Export/Import: Invalid header in the Options worksheet';
$_['error_option_values_header']            = 'Export/Import: Invalid header in the OptionValues worksheet';
$_['error_attribute_groups_header']         = 'Export/Import: Invalid header in the AttributeGroups worksheet';
$_['error_attributes_header']               = 'Export/Import: Invalid header in the Attributes worksheet';
$_['error_filter_groups_header']            = 'Export/Import: Invalid header in the FilterGroups worksheet';
$_['error_filters_header']                  = 'Export/Import: Invalid header in the Filters worksheet';
$_['error_product_options']                 = 'Export/Import: Missing Products worksheet, or Products worksheet not listed before ProductOptions';
$_['error_product_option_values']           = 'Export/Import: Missing Products worksheet, or Products worksheet not listed before ProductOptionValues';
$_['error_product_option_values_2']         = 'Export/Import: Missing ProductOptions worksheet, or ProductOptions worksheet not listed before ProductOptionValues';
$_['error_product_option_values_3']         = 'Export/Import: ProductOptionValues worksheet also expected after a ProductOptions worksheet';
$_['error_additional_images']               = 'Export/Import: Missing Products worksheet, or Products worksheet not listed before AdditionalImages';
$_['error_specials']                        = 'Export/Import: Missing Products worksheet, or Products worksheet not listed before Specials';
$_['error_discounts']                       = 'Export/Import: Missing Products worksheet, or Products worksheet not listed before Discounts';
$_['error_rewards']                         = 'Export/Import: Missing Products worksheet, or Products worksheet not listed before Rewards';
$_['error_product_attributes']              = 'Export/Import: Missing Products worksheet, or Products worksheet not listed before ProductAttributes';
$_['error_attributes']                      = 'Export/Import: Missing AttributeGroups worksheet, or AttributeGroups worksheet not listed before Attributes';
$_['error_attributes_2']                    = 'Export/Import: Attributes worksheet also expected after an AttributeGroups worksheet';
$_['error_category_filters']                = 'Export/Import: Missing Categories worksheet, or Categories worksheet not listed before CategoryFilters';
$_['error_product_filters']                 = 'Export/Import: Missing Products worksheet, or Products worksheet not listed before ProductFilters';
$_['error_filters']                         = 'Export/Import: Missing FilterGroups worksheet, or FilterGroups worksheet not listed before Filters';
$_['error_filters_2']                       = 'Export/Import: Filters worksheet also expected after a FilterGroups worksheet';
$_['error_option_values']                   = 'Export/Import: Missing Options worksheet, or Options worksheet not listed before OptionValues';
$_['error_option_values_2']                 = 'Export/Import: OptionValues worksheet also expected after an Options worksheet';
$_['error_post_max_size']                   = 'File size is greater than %1 (see PHP setting \'post_max_size\')';
$_['error_upload_max_filesize']             = 'File size is greater than %1 (see PHP setting \'upload_max_filesize\')';
$_['error_select_file']                     = 'Please select a file before clicking \'Import\'';
$_['error_id_no_data']                      = 'No data between start-id and end-id.';
$_['error_page_no_data']                    = 'No more data.';
$_['error_param_not_number']                = 'Values for data range must be whole numbers.';
$_['error_upload_name']                     = 'Missing file name for upload';
$_['error_upload_ext']                      = 'Uploaded file has not one of the \'.xls\', \'.xlsx\' or \'.ods\' file name extensions, it might not be a spreadsheet file!';
$_['error_no_news']                         = 'No messages';
$_['error_batch_number']                    = 'Batch number must be greater than 0';
$_['error_min_item_id']                     = 'Start id must be greater than 0';
$_['error_no_news']                         = 'No messages';
$_['error_batch_number']                    = 'Batch number must be greater than 0';
$_['error_min_item_id']                     = 'Start id must be greater than 0';
$_['error_option_name']                     = 'Option \'%1\' is defined multiple times! In the Settings-tab please activate the following: ';
$_['error_option_name']                    .= "Use <em>option_id</em> instead of <em>option name</em> in worksheets 'ProductOptions' and 'ProductOptionValues'";

$_['error_option_value_name']               = 'Option value \'%1\' is defined multiple times within its option! ';
$_['error_option_value_name']              .= 'In the Settings-tab please activate the following: ';
$_['error_option_value_name']              .= "Use <em>option_value_id</em> instead of <em>option_value name</em> in worksheet 'ProductOptionValues'";
$_['error_attribute_group_name']            = 'AttributeGroup \'%1\' is defined multiple times! ';
$_['error_attribute_group_name']           .= 'In the Settings-tab please activate the following: ';
$_['error_attribute_group_name']           .= "Use <em>attribute_group_id</em> instead of <em>attribute_group name</em> in worksheets 'ProductAttributes'";
$_['error_attribute_name']                  = 'Attribute \'%1\' is defined multiple times within its attribute group! ';
$_['error_attribute_name']                 .= 'In the Settings-tab please activate the following: ';
$_['error_attribute_name']                 .= "Use <em>attribute_id</em> instead of <em>attribute name</em> in worksheet 'ProductAttributes'";
$_['error_filter_group_name']               = 'FilterGroup \'%1\' is defined multiple times! ';
$_['error_filter_group_name']              .= 'In the Settings-tab please activate the following:';
$_['error_filter_group_name']              .= "Use <em>filter_group_id</em> instead of <em>filter_group name</em> in worksheets 'ProductFilters'";
$_['error_filter_name']                     = 'Filter \'%1\' is defined multiple times within its filter group! ';
$_['error_filter_name']                    .= 'In the Settings-tab please activate the following: ';
$_['error_filter_name']                    .= "Use <em>filter_id</em> instead of <em>filter name</em> in worksheet 'ProductFilters'";
$_['error_missing_customer_group']                      = 'Export/Import: Missing customer_groups in worksheet \'%1\'!';
$_['error_invalid_customer_group']                      = 'Export/Import: Undefined customer_group \'%2\' used in worksheet \'%1\;!';
$_['error_missing_product_id']                          = 'Export/Import: Missing product_ids in worksheet \'%1\'!';
$_['error_missing_option_id']                           = 'Export/Import: Missing option_ids in worksheet \'%1\'!';
$_['error_invalid_option_id']                           = 'Export/Import: Undefined option_id \'%2\' used in worksheet \'%1\'!';
$_['error_missing_option_name']                         = 'Export/Import: Missing option_names in worksheet \'%1\'!';
$_['error_invalid_product_id_option_id']                = 'Export/Import: Option_id \'%3\' not specified for product_id \'%2\' in worksheet \'%4\', but it is used in worksheet \'%1\'!';
$_['error_missing_option_value_id']                     = 'Export/Import: Missing option_value_ids in worksheet \'%1\'!';
$_['error_invalid_option_id_option_value_id']           = 'Export/Import: Undefined option_value_id \'%3\' for option_id \'%2\' used in worksheet \'%1\'!';
$_['error_missing_option_value_name']                   = 'Export/Import: Missing option_value_names in worksheet \'%1\'!';
$_['error_invalid_option_id_option_value_name']         = 'Export/Import: Undefined option_value_name \'%3\' for option_id \'%2\' used in worksheet \'%1\'!';
$_['error_invalid_option_name']                         = 'Export/Import: Undefined option_name \'%2\' used in worksheet \'%1\'!';
$_['error_invalid_product_id_option_name']              = 'Export/Import: Option_name \'%3\' not specified for product_id \'%2\' in worksheet \'%4\', but it is used in worksheet \'%1\'!';
$_['error_invalid_option_name_option_value_id']         = 'Export/Import: Undefined option_value_id \'%3\' for option_name \'%2\' used in worksheet \'%1\'!';
$_['error_invalid_option_name_option_value_name']       = 'Export/Import: Undefined option_value_name \'%3\' for option_name \'%2\' used in worksheet \'%1\'!';
$_['error_missing_attribute_group_id']                  = 'Export/Import: Missing attribute_group_ids in worksheet \'%1\'!';
$_['error_invalid_attribute_group_id']                  = 'Export/Import: Undefined attribute_group_id \'%2\' used in worksheet \'%1\'!';
$_['error_missing_attribute_group_name']                = 'Export/Import: Missing attribute_group_names in worksheet \'%1\'!';
$_['error_missing_attribute_id']                        = 'Export/Import: Missing attribute_ids in worksheet \'%1\'!';
$_['error_invalid_attribute_group_id_attribute_id']     = 'Export/Import: Undefined attribute_id \'%3\' for attribute_group_id \'%2\' used in worksheet \'%1\'!';
$_['error_missing_attribute_name']                      = 'Export/Import: Missing attribute_names in worksheet \'%1\'!';
$_['error_invalid_attribute_group_id_attribute_name']   = 'Export/Import: Undefined attribute_name \'%3\' for option_id \'%2\' used in worksheet \'%1\'!';
$_['error_invalid_attribute_group_name']                = 'Export/Import: Undefined attribute_group_name \'%2\' used in worksheet \'%1\'!';
$_['error_invalid_attribute_group_name_attribute_id']   = 'Export/Import: Undefined attribute_id \'%3\' for attribute_group_name \'%2\' used in worksheet \'%1\'!';
$_['error_invalid_attribute_group_name_attribute_name'] = 'Export/Import: Undefined attribute_name \'%3\' for attribute_group_name \'%2\' used in worksheet \'%1\'!';
$_['error_missing_filter_group_id']                     = 'Export/Import: Missing filter_group_ids in worksheet \'%1\'!';
$_['error_invalid_filter_group_id']                     = 'Export/Import: Undefined filter_group_id \'%2\' used in worksheet \'%1\'!';
$_['error_missing_filter_group_name']                   = 'Export/Import: Missing filter_group_names in worksheet \'%1\'!';
$_['error_missing_filter_id']                           = 'Export/Import: Missing filter_ids in worksheet \'%1\'!';
$_['error_invalid_filter_group_id_filter_id']           = 'Export/Import: Undefined filter_id \'%3\' for filter_group_id \'%2\' used in worksheet \'%1\'!';
$_['error_missing_filter_name']                         = 'Export/Import: Missing filter_names in worksheet \'%1\'!';
$_['error_invalid_filter_group_id_filter_name']         = 'Export/Import: Undefined filter_name \'%3\' for option_id \'%2\' used in worksheet \'%1\'!';
$_['error_invalid_filter_group_name']                   = 'Export/Import: Undefined filter_group_name \'%2\' used in worksheet \'%1\'!';
$_['error_invalid_filter_group_name_filter_id']         = 'Export/Import: Undefined filter_id \'%3\' for filter_group_name \'%2\' used in worksheet \'%1\'!';
$_['error_invalid_filter_group_name_filter_name']       = 'Export/Import: Undefined filter_name \'%3\' for filter_group_name \'%2\' used in worksheet \'%1\'!';
$_['error_invalid_product_id']                          = 'Export/Import: Invalid product_id \'%2\' used in worksheet \'%1\'!';
$_['error_duplicate_product_id']                        = 'Export/Import: Duplicate product_id \'%2\' used in worksheet \'%1\'!';
$_['error_unlisted_product_id']                         = 'Export/Import: Worksheet \'%1\' cannot use product_id \'%2\' because it is not listed in worksheet \'Products\'!';
$_['error_filter_not_supported']                        = 'Export/Import: Filters are not supported in your Arastta version!';
// Tabs
$_['tab_import']                            = 'Import';
$_['tab_export']                            = 'Export';
$_['tab_settings']                          = 'Settings';

// Button labels
$_['button_import']                         = 'Import';
$_['button_export']                         = 'Export';
$_['button_settings']                       = 'Update Settings';
$_['button_export_id']                      = 'By id range';
$_['button_export_page']                    = 'By batches';

// Help
$_['help_range_type']                       = '(Optional, leave empty if not needed)';
$_['help_incremental_yes']                  = '(Update and/or add data)';
$_['help_incremental_no']                   = '(Delete all old data before Import)';
$_['help_import']                           = 'Spreadsheet can have categories, products, attribute definitions, option definitions, or filter definitions. ';
$_['help_import_old']                       = 'Spreadsheet can have categories, products, attribute definitions, or option definitions. ';
$_['help_format']                           = 'Do an Export first to see the exact format of the worksheets!';
