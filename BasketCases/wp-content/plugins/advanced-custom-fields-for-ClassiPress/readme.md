# Advanced Custom Fields for ClassiPress #

Contributors: Artem Frolov (dikiyforester) http://forums.appthemes.com/members/dikiyforester/

Tags: custom fields, extra profile fields

Tested up to: ClassiPress 3.2 - 3.3.3, WordPress 3.5.1+

Stable tag: 2.3.4

Release date: 03/13/2014

-----------------------------------------------------------------------------------------------------------------------




## Description ##


The Advanced Custom Fields for ClassiPress Plugin is a powerful tool for extending the functional of ClassiPress theme.

It allows you to add and completely control additional profile fields  and ad fields.
Also ACF Plugin  allows to interact fields profile with fields ads and vice versa.


### Plugin adds the most requested themes features. Read the list of ACF Plugin features  and you will see it: ###

* Adding extra Profile fields

* Different types of profile fields

* Formats of ads and profile fields (including Date Field with fully customizable Datepicker and validation of input values)

* Transform of ads and profile fields values (Capitalize, UPERCASE, lowercase)

* Private Field (If you set this property, then this field will see only the author, and administrators. Ordinary visitors and search engines will not see this field)

* Limit the number of input symbols, words, collocations

* Validation of the formats and the limitations of form fields  (Double-check the profile fields during registration and editing.
Checking occurs on the client side, and then on the server side. This provides advanced protection for your site from uninvited guests.
A field validation of the ads on the client side allows the user to obtain accurate and structured information on the ad.)

* Custom validation messages (edit messages in your language and help your users fill forms properly)

* The default values for all fields (default value for the field of ads may be the value of the field profile)

* Inherit values​of the profile field in the ad field

* Control output fields on the various pages and forms (Registration user form, Edit profile form, Author's page, Single ad page and Single ad page separated area,
Poster tab in Ad sidebar, User sidebar, Archives pages, Add new ad form,  Edit ad form)

* Ability to add custom CSS styles to fields output.

* Ability to add the patch CSS files to be compatible with the style of your child theme.

* Plugin settings can be exported to INI file, or imported from another file.



Installing ACF Plugin will save you from modifying the code ClassiPress and tedious transition to the new version of the theme.

ACF Plugin contains a very detailed help section for each property and method.  Tooltips will assist you customize the plugin.

Also, I'm starting to record a video with instructions to various examples of the use plugin, which a great many.
You can already see some of them on this page  http://www.screenr.com/user/dikiyforester

I plan to update the plugin for each new version of the ClassiPress, step by step adding more and more new features and enhancements.

-----------------------------------------------------------------------------------------------------------------------

## Plugin backend interface ##

ACF Plugin page can be accessed from the main administration menu ClassiPress-> ACF options.



Plugin settings is divided into four tabs with tables of settings:

  * Profile Fields - add and manage profile fields

  * Validation Error Messages - edit messages

  * Ad Fields - ad fields management

  * Export/Import/Clear Settings

  * Datepicker settings

To save the settings on each tab, click Save Changes button.

For detailed information about each tab please read the specific Help Page.

-----------------------------------------------------------------------------------------------------------------------

## Installation/Update ##


This ACFCP plugin was developed for a specific version of ClassiPress!
After every ClassiPress update, the plugin's source code needs to be reviewed.
This may take some time, depending on what has changed with Classipress new release.

Be prepared that if you upgrade ClassiPress before the release of a compatible ACF plugin
 - the plugin will turn off itself (in order to avoid potential errors).
The plugin will reactivate itself on the condition that it's version is compatible with the version of ClassiPress.




### Manual installation: ###

  * Download the Plugin zip file `advanced-custom-fields-classipress.zip`.

  * Extract the contents of the zip file.

  * Upload the contents of the zip file to the `wp-content/plugins/` folder
    of your WordPress installation.

  * Then activate the ACF plugin from plugins page.

  * Find the menu `ClassiPress` on the WordPress administration page. Select the lower point `ACF options` and proceed to configure the plugin.



### Manual update: ###


  * Export ACF plugin settings to the INI file (just in case)

  * Deactivate installed plugin from plugins page.

  * Delete existing ACF Plugin folder from the `wp-content/plugins/` folder of your WordPress installation

  * Repeat all steps from manual install (see above)

  * Then activate the ACF plugin from plugins page.


-----------------------------------------------------------------------------------------------------------------------


## Changelog ##

### 2.3.4 - 03/13/2014 ###

#### fixed: ####

* Fixed styles in Ad Details area

* Save URL on user registration

#### added: ####

* Compatibility with Simply Responsive child theme

-----------------------------------------------------------------------------------------------------------------------

### 2.3.3.1 - 01/06/2014 ###

#### fixed: ####

* Version compare bug on enqueue scripts

* Error notices on server side fields validation and plugin activation

-----------------------------------------------------------------------------------------------------------------------

### 2.3.3 - 01/04/2014 ###

#### fixed: ####

* No Author details

* wp_register_style was called incorrectly in admin area

* Using deprecated function get_the_author_id()

-----------------------------------------------------------------------------------------------------------------------

### 2.3.2 - 01/02/2014 ###

#### added: ####

* Compatibility with ClassiPress 3.3.2

#### fixed: ####

* Avoid space in username

* Server-side validation of non-latin strings

* Avatar in ad loop disappear

* “Full Details” area is not translatable (changed to “Ad Details”)

* WPML translation of “Full Details” area fields

* Client-side validation of numbers

-----------------------------------------------------------------------------------------------------------------------

### 2.3.1 - 08/30/2013 ###

#### fixed: ####

* Drag&Drop profile fields on Settings tab breaks table

* Preserve standard ad field values if they are hidden on edit form (`post_title`, `tags_input`, `post_content`)

* Subtabs area lost styles

* Tooltip script not loaded with CP3.3.1

* js validation of drop-downs for user fields

* Fixed styles enqueue


#### added: ####

* WPML compatibility

-----------------------------------------------------------------------------------------------------------------------

### 2.3 - 05/27/2013 ###

#### fixed: ####

* Most Popular Tab Section on Homepage is missing information.

* Responsive Registration form width

* ACF filters issue fixed

* Ad fields values API control

* Missing ACFCP menu in CP3.2

* No ACFCP effect on Ad forms in CP3.2

* Import settings returns Error


#### added: ####

* Ability to add new Datepicker fields through API

* New action hook 'acf_enqueue_form_styles' - allows to add styles on the forms after ACFCP styles

* New action hook 'acf_enqueue_listing_styles' - allows to add styles on Singe Ad page after ACFCP styles

* New action hook 'acf_enqueue_form_scripts' - allows to add scripts on the forms after ACFCP scripts

* Translatable ACFCP backend. Added ACFCP text domain

* Added Russian language pack

* Completed Help Section


-----------------------------------------------------------------------------------------------------------------------

### 2.2.1 - 05/01/2013 ###

#### fixes: ####

* Add New Custom field error fixed

* Responsive Registration form styles added

* ACF filters issue fixed

#### changes: ####

* ClassiPress 3.2 now supported. (For people who can't update CP to newest version)

-----------------------------------------------------------------------------------------------------------------------


### 2.2 - 04/28/2013 ###

#### fixes: ####

* Edit profile fields in Admin dashboard

* Removed theme name check (strange `Don't panic!` behavior).

#### changes: ####

* Added action `acf_update_user_meta` before profile field update|save

* Added filter to ACFCP Settings tabs

* Improved ACFCP Settings API

* Added Display Option `Edit profile admin form`.
To display the profile field on the edit form in the admin area,
regardless of the edit form fields in the front-end

* Same ACFCP version for ClassiPress 3.2.1 and 3.3

* Added AppThemes Product ID for autoupdates.

-----------------------------------------------------------------------------------------------------------------------

### 2.1.1 - 04/24/2013 ###

#### fixes: ####

* Fixed incompatibility with PHP5.2

-----------------------------------------------------------------------------------------------------------------------

### 2.1 - 04/21/2013 ###

#### changes: ####

* Compability with ClassiPress 3.3

#### fixes: ####

* Validation error messages for `range*` limitations methods can contains placeholders.

-----------------------------------------------------------------------------------------------------------------------

### 2.0 - 04/21/2013 ###

#### changes: ####

* new ACF functions framework

* new DNA framework for backend Options manage

* Help section removed. Will be added in new maintance release

* Whole code rewrited!


#### new features: ####

* Backend Options manage API:
	- Allows add new properties for Profile and Ad fileds
	- Allows add new sub tabs to Options pages

* Fields Visibility API:
	- Allows add new custom restrictions (with any conditions) on fields visibility on various places.

* API for adding new custom Formats and Limitations.

* New visibility option `Featured Field`
	- Allows display field only on Fetured Ads

* New visibility option `For Logged-In`
	- Allows display field only for logged-in users

* New Limitations:
	- `minchoice` - Makes multi checkboxes element require given minimum number of choices
	- `maxchoice` - Makes multi checkboxes element require given maximum number of choices
	- `rangechoice` - Makes multi checkboxes element require given range number of choices

* New Datepicker properties for limitation selectable dates:
	- `Max date`
	- `Min date`
	- `range Year`

* Added Ad fields server side validation


#### fixes: ####

* Profile details now clickable

* Store 0 values for profile fileds

-----------------------------------------------------------------------------------------------------------------------

### 1.1.2 - 12/12/2012 ###

#### fixes: ####

* Warning message on drop-downs, checkboxes and radio-buttons on Registration and Edit profile forms

* Validation message on Registration and Edit profile forms go down after second appearing

-----------------------------------------------------------------------------------------------------------------------

### 1.1.1 - 12/10/2012 ###

#### fixes: ####

* In the loop displays only one item of multiple checkboxes.
		Now, if you have on ad-loop some `checkbox` field with multiple values, these values displays separated by comma.

#### changes: ####

* Provided compability with new Appthemes ad form-builders, thats allow not affect `New Ad` and `Edit Ad` forms html markup.

#### added: ####

* Added styles for Headline childtheme registration form.

-----------------------------------------------------------------------------------------------------------------------

### 1.1 - 11/04/2012 ###

#### fixes: ####

* Display `required asterisks` for fields, if there is a limitation on the minimum input greater than 0.

* Fields on Ad loop dublicated in Random Ads Tab

* Profile fields causing Warning in Ad loop

* Description area in Edit Ad form Is out of form

* ReCaptcha appear in the middle when using custom fields in Registration form


#### changes: ####

* Changed folder structure

* Removed author.php and tpl-edit-item.php templates from plugin (now used core templates).

* Changed installation procedure.


#### new features: ####

* New option `Text Transform` allows to transform text to Capitalize, UPPERCASE and lowercase before saving in database.
  Useful for saving the Titles or other fields with the certain capitalization of text.

* Administrator can edit all profile fields, even if some fields disabled in Display options.

* New display option for Ad fields - `Single ad content` - display Ad fields in the ad single page in separated style-customizable content area.

* New display option for Profile fields - `User sidebar` - display fields in the users's sidebar (Widget `Account information`).

* New display option for Profile fields - `Single ad sidebar` - display fields in the Single ad sidebar (tab `Poster`).

* Ability to CSS-Stylize certain fields in all display areas. So you can add images instead titles and anything else.

* Ability to add CSS patches to plugin folder for style-compability with any child-theme.


-----------------------------------------------------------------------------------------------------------------------

### 1.0.2 - 09/30/2012 ###

#### fixes: ####

* `No ad details found` bug on Single ad listing in ClassiPress 3.1.8

* Profile fields `first_name` and `last_name` duplicated in DB if filled in registration form

* Allow HTML in field descriptions

* Problems when submit `Add new user` form in admin dashboard (required hidden fields)

* Tab indexes in registration form not sequential


#### added: ####

Added new action hooks (usage look in help section):

* `acf_loop_top($post)` add action after title in loop ad meta (in line with author and category)

* `acf_loop_bottom($post)` add action after description in loop ad meta (in line with posted and total viewed)


-----------------------------------------------------------------------------------------------------------------------

### 1.0.1 - 09/16/2012 ###

#### fixes: ####

* Error using ACF on Loop Ad

* Having issue with custom field showing in listing

* No need to set permissions to file acf_export.ini. (Now file is created on the fly during the export. Export folder is deleted from the project.)

* No need to replace author's and edit ad templates. (Now this templates loads from plugin's folder).


-----------------------------------------------------------------------------------------------------------------------

### 1.0 - 07/22/2012 ###

* Release.

