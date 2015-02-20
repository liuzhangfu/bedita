# ChangeLog

## Version 3.5.1 - Corylus

### User-visible changes

* [#589](https://github.com/bedita/bedita/issues/589) New section can not be saved in position "None"
* [#590](https://github.com/bedita/bedita/issues/590) Non-administrators backend users are able remove "fixed" property
* [#575](https://github.com/bedita/bedita/issues/575) Area view - 'publisher' and 'rights' compare twice
* [#588](https://github.com/bedita/bedita/issues/588) Remove "fixed" property
* [#572](https://github.com/bedita/bedita/issues/572) Fatal Error on logout
* [#600](https://github.com/bedita/bedita/issues/600) Handle Multimeda `saveAjax` errors

### Frontend changes

* [#587](https://github.com/bedita/bedita/issues/587) Handle homePage tpl
* [#585](https://github.com/bedita/bedita/issues/585) Enhancement of showUnauthorized data
* [#537](https://github.com/bedita/bedita/issues/537) Automatic Card creation. - Always new Card when in frontend context

### Developer-visible changes

* [#573](https://github.com/bedita/bedita/issues/573) Automatically create Card when creating a new User
* [#579](https://github.com/bedita/bedita/issues/579) beEmbedMedia->object video does not use URLonly when passed
* [#596](https://github.com/bedita/bedita/issues/596) Error on saving categories with non ascii characters
* [#597](https://github.com/bedita/bedita/issues/597) Some parts of backend are exposed to CSRF attacks
* [#593](https://github.com/bedita/bedita/issues/593) Data JSON export - recursion errors
* [#584](https://github.com/bedita/bedita/issues/584) JSON export enhancement - 'types' and 'all' options
* [#578](https://github.com/bedita/bedita/issues/578) Memory exhausted when creating new Section in large BE installations
* [#583](https://github.com/bedita/bedita/issues/583) section priority order/value in import and export
* [#591](https://github.com/bedita/bedita/issues/591) Security: XSS attack on Newsletter mail groups
* [#586](https://github.com/bedita/bedita/issues/586) SQL upgrade from 3.2.x to >= 3.3.x
* [#561](https://github.com/bedita/bedita/issues/561) default JSON import/export logic
* [#571](https://github.com/bedita/bedita/issues/571) release script: frontends *.php.sample has to be moved to *.php
* [#519](https://github.com/bedita/bedita/issues/519) module forward method refactor
* [#598](https://github.com/bedita/bedita/issues/598) BeAppModel should look in DB for ObjectType ID if not present in configuration


## Version 3.5.0 - Corylus

### User-visible changes

* [#559](https://github.com/bedita/bedita/issues/559) Some UI functions not working when offline
* [#490](https://github.com/bedita/bedita/issues/490) Create multimedia object with large files doesn't show any feedback to user
* [#510](https://github.com/bedita/bedita/issues/510) Dashboard Publication box ends up at the bottom of the page on certain resolutions
* [#539](https://github.com/bedita/bedita/issues/539) custom views for object relations in backend 
* [#535](https://github.com/bedita/bedita/issues/535) Every click generates "save warning" in events
* [#528](https://github.com/bedita/bedita/issues/528) empty relation params displayed as '[' backend UI
* [#518](https://github.com/bedita/bedita/issues/518) show preview as default
* [#520](https://github.com/bedita/bedita/issues/520) - list objects: custom details and elements
* [#512](https://github.com/bedita/bedita/issues/512) toolbar pagination: "prev" page link before "next"
* [#509](https://github.com/bedita/bedita/issues/509) admin: user custom properties display error
* [#508](https://github.com/bedita/bedita/issues/508) add TIFF and other image formats support
* [#506](https://github.com/bedita/bedita/issues/506) / Section and area view should display all available relations
* [#500](https://github.com/bedita/bedita/issues/500) Addressbook module detail view has many UI problems
* object position: count num of places in tab
* addressbook: fix list objects custom prop height
* height of message Div don't affect clicks anymore
* multimedia list: default 50 items / added filename in list view and some small fix
* let user select nicknames and id from sortable objects

### Frontend changes

* [#530](https://github.com/bedita/bedita/issues/530) Use Cake cache reading objects
* [#401](https://github.com/bedita/bedita/issues/401) Bad error message "Error: Session key does not exist" 
* [#546](https://github.com/bedita/bedita/issues/546) Handle user session when it is configured to not automatically start
* [#516](https://github.com/bedita/bedita/issues/516) release script: include new frontends in package
* [#493](https://github.com/bedita/bedita/issues/493) review debug.example.com reference frontend
* [#515](https://github.com/bedita/bedita/issues/515) frontend: load secondary relations via config
* [#491](https://github.com/bedita/bedita/issues/491) frontend examples/reference revision
* [#431](https://github.com/bedita/bedita/issues/431) ShowUnauthorized = true, don't affect attached media
* facebook metadata: link rel=image_src, fix og:image and remove og:app_id

### Developer-visible changes

* [#566](https://github.com/bedita/bedita/issues/566) Validate/escape db fields to avoid XSS attack
* [#567](https://github.com/bedita/bedita/issues/567) Add validation rule on userid field
* [#563](https://github.com/bedita/bedita/issues/563) Standard verbose mode
* [#565](https://github.com/bedita/bedita/pull/565) #482 Objects' saving fix.
* [#482](https://github.com/bedita/bedita/issues/482) Saving objects without passing "status"
* [#564](https://github.com/bedita/bedita/issues/564) Deleting tree branch. - Added root check.
* [#559](https://github.com/bedita/bedita/issues/559) Some UI functions not working when offline
* [#447](https://github.com/bedita/bedita/issues/447) BeurlHelper::getUrl() wrongs to build url for BEdita Modules Plugin
* [#503](https://github.com/bedita/bedita/issues/503) add link to media file in multimedia items
* [#519](https://github.com/bedita/bedita/issues/519) module forward method refactor
* [#487](https://github.com/bedita/bedita/issues/487) search priority: "title" field more important than "nickname"
* [#538](https://github.com/bedita/bedita/issues/538) log: remove stack trace from error.log and add exception parameter to log otherwise
* [#560](https://github.com/bedita/bedita/issues/560) hash jobs: use simple values as params
* [#548](https://github.com/bedita/bedita/issues/548) add new related object by URL
* [#530](https://github.com/bedita/bedita/issues/530) Use Cake cache reading objects
* [#553](https://github.com/bedita/bedita/issues/553) missing media type creating new image object
* [#557](https://github.com/bedita/bedita/pull/557) Issue/555 categories fix
* [#555](https://github.com/bedita/bedita/issues/555) Backend users can create/edit/delete any category from any backend module
* [#554](https://github.com/bedita/bedita/issues/554) advanced properties view missing for sections/publications
* [#552](https://github.com/bedita/bedita/issues/552) error in setup and admin / custom relations - 'Wrong PHP code'
* [#536](https://github.com/bedita/bedita/issues/536) multimedia: missing file should cause warning to user
* [#549](https://github.com/bedita/bedita/issues/549) Zoom level of geotag not working on google maps
* [#454](https://github.com/bedita/bedita/issues/454) external pluggable authentication system: OAuth implementation
* [#551](https://github.com/bedita/bedita/pull/551) #546 managed no automatic session start
* [#516](https://github.com/bedita/bedita/issues/516) release script: include new frontends in package
* [#546](https://github.com/bedita/bedita/issues/546) Handle user session when it is configured to not automatically start
* [#547](https://github.com/bedita/bedita/pull/547) Issue/545 update relations params
* [#545](https://github.com/bedita/bedita/issues/545) Relations: updating relations parameters
* [#544](https://github.com/bedita/bedita/issues/544) Duration: input field has to handle minutes and seconds 
* [#541](https://github.com/bedita/bedita/issues/541) Filter "count_relations" groups all rows in result
* [#543](https://github.com/bedita/bedita/issues/543) SVG presentation in be_thumb
* [#542](https://github.com/bedita/bedita/issues/542) shell: dbadmin rebuildIndex -> select object type
* [#525](https://github.com/bedita/bedita/issues/525) Error overriding multimedia file
* [#539](https://github.com/bedita/bedita/issues/539) custom views for object relations in backend 
* [#532](https://github.com/bedita/bedita/issues/532) searching words with accented letters/quotes is not working
* [#533](https://github.com/bedita/bedita/issues/533) Duplicate results on query filter 
* [#531](https://github.com/bedita/bedita/issues/531) Invalidate objects cache from backend
* [#529](https://github.com/bedita/bedita/issues/529) SQL optimizations on BEAppModel::findObjects
* [#494](https://github.com/bedita/bedita/issues/494) shell: init new frontend from reference
* [#493](https://github.com/bedita/bedita/issues/493) review debug.example.com reference frontend
* [#522](https://github.com/bedita/bedita/issues/522) remove unused $config['userVersion']
* [#518](https://github.com/bedita/bedita/issues/518) fix epic editor default options
* [#515](https://github.com/bedita/bedita/issues/515) frontend: load secondary relations via config
* [#284](https://github.com/bedita/bedita/issues/284) versioning: error if "user_created" null
* [#513](https://github.com/bedita/bedita/issues/513) permissions: element without params as default
* [#511](https://github.com/bedita/bedita/issues/511) hash jobs: override default messages with local instance messages
and frontend messages (if present)
* [#495](https://github.com/bedita/bedita/issues/495) Investigate on multiple vulnerabilities
* [#507](https://github.com/bedita/bedita/issues/507) Relation label clears gallery description
* [#508](https://github.com/bedita/bedita/issues/508) add TIFF and other image formats support
* [#506](https://github.com/bedita/bedita/issues/506) / Section and area view should display all available relations
* [#486](https://github.com/bedita/bedita/issues/486) String search result pagination doesn't work
* [#492](https://github.com/bedita/bedita/issues/492) Adding "poster" relation to handle thumbnails/previews/cover of BEobjects
* [#500](https://github.com/bedita/bedita/issues/500) Addressbook module detail view has many UI problems
* [#497](https://github.com/bedita/bedita/issues/497) Remove obsolete help online code
* [#149](https://github.com/bedita/bedita/issues/149) addressbook: company_name, is_user in obj list
* [#496](https://github.com/bedita/bedita/issues/496) recover password error - backend
* [#491](https://github.com/bedita/bedita/issues/491) frontend examples/reference revision
* [#483](https://github.com/bedita/bedita/issues/483) Bulk action change status does not work
* shell: bedita checkApp check frontend.cfg.php presence
* add HTTPS in youtube matching reg exp
* add BeditaServiceUnavailableException (503)
* shell dbadmin: clonePublication add section categories
* enhance error log in case of 'unlogged' or 'unauthorized' access in FrontendController::getPath()
* update cakephp to 1.3.19
* reverse ckeditor update, restored 4.0.1


## Version 3.4.0 - Corylus

### User-visible changes

* [#479](https://github.com/bedita/bedita/issues/479) Ckeditor does not allow inserting span elements
* [#445](https://github.com/bedita/bedita/issues/445) Handle in UI multiple options for relations' properties
* [#477](https://github.com/bedita/bedita/issues/477) Handle quickitem creation in dashboard
* [#471](https://github.com/bedita/bedita/issues/471) users: add users to group from group detail view
* [#456](https://github.com/bedita/bedita/issues/456) Multimedia upload: multiupload of files in modal window
* [#473](https://github.com/bedita/bedita/issues/473) fix BEdita home and html layout
* [#455](https://github.com/bedita/bedita/issues/455) Quickitem: create new objects in modal window
* [#466](https://github.com/bedita/bedita/issues/466) Publications: contents order error on drag'n'drop with newst contents first
* [#464](https://github.com/bedita/bedita/issues/464) Custom properties list missing in some module
* [#442](https://github.com/bedita/bedita/issues/442) Add custom properties to module filters tab
* [#297](https://github.com/bedita/bedita/issues/297) ui: merge Relations and Multimedia elements
* [#450](https://github.com/bedita/bedita/issues/450) Load Tree tab in object detail view via ajax
* starting [#416](https://github.com/bedita/bedita/issues/416) font-icon embedding for object types
* [#458](https://github.com/bedita/bedita/issues/458) Persistent checked items in modal window 
* [#369](https://github.com/bedita/bedita/issues/369) Gallery as *attach*
* #297 fix and [#244](https://github.com/bedita/bedita/issues/244) some inline editing
* [#432](https://github.com/bedita/bedita/issues/432) Filtering objects in index view and new filters
* [#378](https://github.com/bedita/bedita/issues/378) Events: create a new calendar view

### Frontend changes


### Developer-visible changes

* [#465](https://github.com/bedita/bedita/issues/465) Missing apidocs for Shell classes
* [#445](https://github.com/bedita/bedita/issues/445) Handle in UI multiple options for relations' properties
* [#471](https://github.com/bedita/bedita/issues/471) users: add users to group from group detail view
* [#394](https://github.com/bedita/bedita/issues/394) Events: add week days selection in calendar
* [#475](https://github.com/bedita/bedita/issues/475) empty dateItems in DB
* [#467](https://github.com/bedita/bedita/issues/467) empty GeoTag items in DB
* [#453](https://github.com/bedita/bedita/issues/453) addressbook: paginate "promote as user modal"
* [#472](https://github.com/bedita/bedita/issues/472) bedita exceptions for http status 401, 403, 404 and 500
* [#454](https://github.com/bedita/bedita/issues/454) external pluggable authentication system: OAuth implementation
* [#443](https://github.com/bedita/bedita/issues/443) Multimedia: tree filtering should affect attached media
* [#469](https://github.com/bedita/bedita/issues/469) Upgrade to CakePHP 1.3.18 and Smarty 3.1.18
* [#466](https://github.com/bedita/bedita/issues/466) Publications: contents order error on drag'n'drop with newst contents first
* [#464](https://github.com/bedita/bedita/issues/464) Custom properties list missing in some module
* [#462](https://github.com/bedita/bedita/issues/462) Statistics filtered by tree gives warnings
* [#459](https://github.com/bedita/bedita/issues/459) merged form_assoc_object view in the modal and minor fix
* [#460](https://github.com/bedita/bedita/issues/460) Deleting a user- group and updating permissions
* [#463](https://github.com/bedita/bedita/issues/463) Webkit bug with CKEditor adds styled spans
* [#450](https://github.com/bedita/bedita/issues/450) Load Tree tab in object detail view via ajax
* [#456](https://github.com/bedita/bedita/issues/456) Multimedia upload: multiupload of files in modal window
* [#428](https://github.com/bedita/bedita/issues/428) Upgrade to jQuery 2
* [#455](https://github.com/bedita/bedita/issues/455) Quickitem: create new objects in modal window
* [#297](https://github.com/bedita/bedita/issues/297) ui: merge Relations and Multimedia elements
* [#457](https://github.com/bedita/bedita/issues/457) Use localStorage instead of cookies to track tabs to open
* [#420](https://github.com/bedita/bedita/issues/420) Add and handle relations properties
* [#451](https://github.com/bedita/bedita/issues/451) BeHash component error checking the existence of a method
* [#244](https://github.com/bedita/bedita/issues/244) in progress
* [#432](https://github.com/bedita/bedita/issues/432) Filtering objects in index view and new filters
* [#448](https://github.com/bedita/bedita/issues/448) remove references to bedita.sys.php
* [#449](https://github.com/bedita/bedita/issues/449) bedita shell remove unused media files, add url for missing
* [#378](https://github.com/bedita/bedita/issues/378) Events: create a new calendar view
* [#446](https://github.com/bedita/bedita/issues/446) xhprof profiler integration


## Version 3.3 - Corylus

### User-visible changes

* [#432](https://github.com/bedita/bedita/issues/432) Filtering objects in index view and new filters
* [#417](https://github.com/bedita/bedita/issues/417) fix hideFields control
* [#408](https://github.com/bedita/bedita/issues/408) Objects listing enhancement
* [#427](https://github.com/bedita/bedita/issues/427) added colophon widget + edit in Admin / configuration
* [#133](https://github.com/bedita/bedita/issues/133) advanced search using strings
* [#423](https://github.com/bedita/bedita/issues/423) User Groups: adding permissions from the group detail view
* [#312](https://github.com/bedita/bedita/issues/312) admin: object relations configuration in UI
* [#433](https://github.com/bedita/bedita/issues/433) Can not insert a relation after already saved relations delete
* [#424](https://github.com/bedita/bedita/issues/424) Add possibility to create interactive images
* [#421](https://github.com/bedita/bedita/issues/421) Expose the relations' properties in object detail view
* [#430](https://github.com/bedita/bedita/issues/430) fix for restore a version of an object with CKEditor default editor
* [#409](https://github.com/bedita/bedita/issues/409) Multimedia list: adding permission and notes flag
* [#388](https://github.com/bedita/bedita/issues/388) handle labels for object relations
* added a new config for small rich text areas
* replace relationships with relations label

### Frontend changes

* [#376](https://github.com/bedita/bedita/issues/376) befront->metaOg - open graph

### Developer-visible changes

* [#444](https://github.com/bedita/bedita/issues/444) - add try catch block in BeThumb::resample - avoid unrecoverable
error
* [#432](https://github.com/bedita/bedita/issues/432) Filtering objects in index view and new filters
* [#441](https://github.com/bedita/bedita/issues/441) fix config cache load
* [#440](https://github.com/bedita/bedita/issues/440) Add categories to galleries
* [#439](https://github.com/bedita/bedita/issues/439) Extend BEAppModel::findObjects() to use custom methods after objects are filtered
* [#438](https://github.com/bedita/bedita/issues/438) error during plug-in, with plugin module with a custom model and a custom table
* [#437](https://github.com/bedita/bedita/issues/437) Backend permission: access error in publications module
* [#133](https://github.com/bedita/bedita/issues/133) advanced search using strings
* [#423](https://github.com/bedita/bedita/issues/423) User Groups: adding permissions from the group detail view
* add check on addressbook save to avoid errors [#417](https://github.com/bedita/bedita/issues/417)
* [#413](https://github.com/bedita/bedita/issues/413) info tab for other media using the same hash file
* [#436](https://github.com/bedita/bedita/issues/436) Administrator module permissions are modified if user not in administrator group save a group
* [#338](https://github.com/bedita/bedita/issues/338) Implement private objects
* [#424](https://github.com/bedita/bedita/issues/424) Add possibility to create interactive images
* [#408](https://github.com/bedita/bedita/issues/408) Objects listing enhancement
* [#433](https://github.com/bedita/bedita/issues/433) Can not insert a relation after already saved relations delete
* [#429](https://github.com/bedita/bedita/issues/429) Sanitizing string with BeLib::stripData() fails
* [#421](https://github.com/bedita/bedita/issues/421) Expose the relations' properties in object detail view 
* [#312](https://github.com/bedita/bedita/issues/312) admin: object relations configuration in UI
* [#425](https://github.com/bedita/bedita/issues/425) Adding an UNIQUE index key in sql permission table
* [#420](https://github.com/bedita/bedita/issues/420) Add and handle relations properties
* [#422](https://github.com/bedita/bedita/issues/422) Remove obsolete relations
* [#414](https://github.com/bedita/bedita/issues/414) Search index enhancement
* refactor wrong method name isPermissionSetted. Changed to Permission::isPermissionSet()
* drop and remove any references to authors table
* add mail options to bedita.cfg sample


## Version 3.2.1 - Populus

### User-visible changes

* [#390](https://github.com/bedita/bedita/issues/390) Improve admin view of log files
* [#417](https://github.com/bedita/bedita/issues/417) hide specific field from module
* [#409](https://github.com/bedita/bedita/issues/409) ubiquity int - paginatedList
* [#412](https://github.com/bedita/bedita/issues/412) Relation list: change link objects from <input> to <a href>
* [#410](https://github.com/bedita/bedita/issues/410) "Add by url" fails if server responds with HTTP Status Code "302 Found"
* [#406](https://github.com/bedita/bedita/issues/406) Bug. Search field in upper menu point to a new object page
* [#405](https://github.com/bedita/bedita/issues/405) User Group view: add a list of protected objects
* [#393](https://github.com/bedita/bedita/issues/393) Rich Text Editor is not initialized for body and abstract fields in Translations module
* added count annotation in multimedia list
* Display the real database object type name in advanced properties
* automplete off on smtp user/passwd
  avoid login user/passwd autocomplete con Chrome that may be unintentionally saved
* multimedia elements: custom tab title + disable remote url option

### Frontend changes

* [#401](https://github.com/bedita/bedita/issues/401) log session invalid, backend only
* [#404](https://github.com/bedita/bedita/issues/404) Rss frontend function: sanitizer corrupt XML output
* [#392](https://github.com/bedita/bedita/issues/392) Logout doesn't work in frontend apps
* [#389](https://github.com/bedita/bedita/issues/389) http response 401 and 403
* [#376](https://github.com/bedita/bedita/issues/376) befront->metaOg - open graph

### Developer-visible changes

* [#418](https://github.com/bedita/bedita/issues/418) Update Smarty to 3.1.16
* [#397](https://github.com/bedita/bedita/issues/397) security: avoid upload of script files
* [#390](https://github.com/bedita/bedita/issues/390) Improve admin view of log files
* [#351](https://github.com/bedita/bedita/issues/351) cfgOneWayRelation doesn't work [drop it]
* [#409](https://github.com/bedita/bedita/issues/409) Multimedia list: adding permission and notes flag. View, with ubiquity also
* [#399](https://github.com/bedita/bedita/issues/399) groups/modules: error mixing readonly and read-write permissions
* [#413](https://github.com/bedita/bedita/issues/413) button for deleting a media file or reference
* [#403](https://github.com/bedita/bedita/issues/403) Bug. Category name change when changing category label
* [#405](https://github.com/bedita/bedita/issues/405) User Group view: add a list of protected objects
* [#385](https://github.com/bedita/bedita/issues/385) Shell script to build change log
* [#400](https://github.com/bedita/bedita/issues/400) Improve RestClientModel to accept custom request options
* [#394](https://github.com/bedita/bedita/issues/394) - unserialize moved in DateItem::afterFind
* [#395](https://github.com/bedita/bedita/issues/395) Thumbnail on remote images behind proxy
* [#398](https://github.com/bedita/bedita/issues/398) webmarks: url check fails behind proxy
* [#396](https://github.com/bedita/bedita/issues/396) public methods in frontend_controller, denied from url
* [#389](https://github.com/bedita/bedita/issues/389) http response 401 and 403
* [#387](https://github.com/bedita/bedita/issues/387) Update Doxyfile, custom css and custom html for API doc
* [#386](https://github.com/bedita/bedita/issues/386) addressbook relations: can't view details or delete related objects
* smarty array of user agent updated
* update files used by doxygen to work with v1.7.1
  version 1.7.1 is present on server that host api


## Version 3.2 - populus

### User-visible changes

* Publication module - added count of permissions also for sections in list children [#243](https://github.com/bedita/bedita/issues/243)
* Admin module - sort modules as you wish [#295](https://github.com/bedita/bedita/issues/295)
* move to LGPL [#327](https://github.com/bedita/bedita/issues/327)
* newsletter module: added plugin to ckeditor to handle correctly "bedita content block" as in tinyMCE
* installer: fix postgres issues, mod_rewrite check and confi restored [#137](https://github.com/bedita/bedita/issues/137)
* added support to https in vimeo
* allowed to save user and object custom property value equal to "0"
* fix next page in installer - admin user creation - [#137](https://github.com/bedita/bedita/issues/137)
* [#308](https://github.com/bedita/bedita/issues/308) - trigger warning nessage on leave page with changes don't saved.
On change page the form serialized data is comapred with those at the begin. To exclude fields, put "ignore" class in form fields or in a parents of fields
* web installer, remove bedita/media url info on wizard (will be set after), display mod_rewrite message only on error - see [#137](https://github.com/bedita/bedita/issues/137)
* [#334](https://github.com/bedita/bedita/issues/334) - fix bug on "group visible, preview for others" permission set. Replace !empty() with isset() to check if the "authorized" key was defined
* web installer: add link to docs.bedita article on mod_rewrite - see [#137](https://github.com/bedita/bedita/issues/137)
* fix "modified page" alert always triggered in multimedia/view
* bugfix on User::beforeSave() that delete user email
* new dashboard enanchements [#303](https://github.com/bedita/bedita/issues/303)
 * redirect to Publications module clicking on sections
 * add also methods to BeTreeHelper to handle params to build url in tree items
 * don't display tags or comments if related modules are off
 * [#343](https://github.com/bedita/bedita/issues/343) - exclude from dashboard search unwanted object types
* user setting in a indipendent page [#169](https://github.com/bedita/bedita/issues/169)
* Admin module - log plain chronological order [#301](https://github.com/bedita/bedita/issues/301)
* [#169](https://github.com/bedita/bedita/issues/169) removed old user profile tab
* handle profile page [#169](https://github.com/bedita/bedita/issues/169) - fix wrong links and fix redirect on save action
* menuleft and menucommands list html layout + fix plusminus trigger on publications nav
* modules background colors on selected tree sections
* fix "menu visibility" saving new section [#339](https://github.com/bedita/bedita/issues/339)
* Modules: fix loading of groups in tab permissions
* Tree menu: fix plusminus click/toggle in Chrome
* [#343](https://github.com/bedita/bedita/issues/343) - created objectgroup "nodashboard", an object of this type is never displayed in "recent items" (comments, notes)
* Publications module: handle "no items" row on add/remove contents
* handle backend page title [#335](https://github.com/bedita/bedita/issues/335)
* fix categories / tree usability conflict in multimedia
* [#303](https://github.com/bedita/bedita/issues/303) filter object types for comments/notes - show only comments/notes related to user visible object types
* Added textbody field in media type "application"
* user history: add area/publication filter
* allow addon enable if file already in addons/models/enabled
* add support to mp4 files to show them in flowplayer
* introduced CKeditor 4 as default rich text editor
* Publication module - added multimedia attach relation [#321](https://github.com/bedita/bedita/issues/321)
* added support for custom multimedia object relations
* fix enable/disable button of newsletter ckeditor plugin [#348](https://github.com/bedita/bedita/issues/348)
 * disable button if two bedita content blocks are present
 * enable button if less then two content blocks are present
* [#332](https://github.com/bedita/bedita/issues/332)
 * add Vera.ttf font
 * use absolute font path, fix color, add angle support
 * support for external images (thumbs in /cache/ext)
* [#356](https://github.com/bedita/bedita/issues/356) avoid wrong select in multimedia with tags
* bugfix on autosave if tinyMCE isn't used [#348](https://github.com/bedita/bedita/issues/348)
* [#360](https://github.com/bedita/bedita/issues/360) fix inverse priority in object save - preserve current values
* managing removal of users who cretaed objects [#280](https://github.com/bedita/bedita/issues/280)
 * merge from ulmus
 * blocking user userid become "deleted-user-$id"
 * handle UI user detail if he's blocked
* fix links from addressbook to the new users module
* hide publications tree in dashboard if publication module not available
* [#371](https://github.com/bedita/bedita/issues/371) - add Embed Code tab to all media objects
* [#365](https://github.com/bedita/bedita/issues/365) - fix error in clone multimedia object with no file
* class "formula" in text editor
* Embed code for video audio and application media types only. Rich text body for spreadsheet and text.
* added "body" field for media type=formula
* permissions for multimedia object
* js - in modal use $.load callback to hide loader instead of $.ajaxStop that remove loader also if an ajax call not related to modal window is completed. For example checking concurrent users removed modal loader
* [#283](https://github.com/bedita/bedita/issues/283) - export from ui: pass "filename" in options, fix error display
* [#378](https://github.com/bedita/bedita/issues/378)
 * multiple calendar dates in event
 * reorder DateItem array to avoid unwanted date removals
 * fix calendar for new items, fix removal of last item
 * get priority utility
 * calendar view in separate method
* [#380](https://github.com/bedita/bedita/issues/380) - new 'title' field in geotags
* [#381](https://github.com/bedita/bedita/issues/381) - calendar warning for start dates after end dates
* change the way nickname is built on clone [#383](https://github.com/bedita/bedita/issues/383)
* [#117](https://github.com/bedita/bedita/issues/117) - handle file existing in multimedia module
* fix overflow error messages [#300](https://github.com/bedita/bedita/issues/300)

### Frontend changes

* in FrontendController::getPath() added "ObjectProperty" to Section bindings
* added "Tree" to Section bindings when FrontendController::baseLevel is used
* mobile.example.com: view content if section contains only one item
* add default frontend model bindings for BeditaProduct
* add "frontend" modelbinding for base object model
* add ObjectProperty binding to default model "frontend" binding
* Card model - Category in frontend binding
* routing fix - get obj by nickname and current environment object status ($this->status), if available
* AppCache support in frontends [#361](https://github.com/bedita/bedita/issues/361)
* fix 404 loading content located in sections [#370](https://github.com/bedita/bedita/issues/370)
 * add to Tree::getParent() $status array as third argument and call it from FrontendController::content().
 * to consistency add $status to BeTreeComponent::getParents() and FrontendController::getParentsObject()
* add Category to "frontend" binding in Media object
* improved handling of BeditaAjaxExceptions. Add handling of BeditaAjaxExceptions in frontends
* add frontend modelBinding to BeditaAnnotationModel
* [#374](https://github.com/bedita/bedita/issues/374) fix permission error on frontends publications
* allow hash job methods calls/override in controller
* permits object/user-group - frontendAccess + unit test
* permission in loadObj frontend controller
* fix wrong bindings loading relations
* fix [#382](https://github.com/bedita/bedita/issues/382) - rss errors

### Developer-visibile changes

* shell - added Dbadmin::clonePublication() to clone a complete Publication with tree structure too
* fix image_info source: use mediaRoot, not mediaUrl for local files
* locales.php loaded from bedita.ini, override in bedita.cfg if needed [#90](https://github.com/bedita/bedita/issues/90)
* custom properties: object types are immutable [#250](https://github.com/bedita/bedita/issues/250)
* BeMailComponent: moved smtp configuration after EmailComponent::reset() to reload smtp options
* links was not indexed, add searchFields
* Removed useless set view var in elements/form_file_list.tpl. It caused wrong defintion of those variable in other elements
* [#331](https://github.com/bedita/bedita/issues/331) - transformed BeThumb Helper in library and moved in /libs
* BeThumb - use of CakeLog::write() method to write error.log file
Use CakeLog::write() instead of the wrong $this->log(). Return always the $config['imgMissingFile'] in case of error.
* add contributing guide lines
* cleanup utility doesn't remove file named "empty" (used to track empty folder in git)
* shell: update deploy script to use "git pull" or "svn update"
* BeThumb - adding watermark effect for GD libraries
* Add ckeditor "onchange" plugin to improve text changes detection
* add BEDITA.base ($html->url('/')) to meta and BEDITA json object
* Reversed parameters in order to call some NotifyBehavior methods from outside the behavior http://book.cakephp.org/1.3/en/view/1074/Creating-behavior-methods
* NotifyBehavior: replace [BEdita] with projectName and change visibility from private to protected to easily extend class
* remove "author" from object types
* Add $timeout param to BeMailComponent::notify() method to handle notifications that stay in "pending" state too much time
* remove useless pid information [#340](https://github.com/bedita/bedita/issues/340)
* move image_info smarty plugin to cakephp helper, [#344](https://github.com/bedita/bedita/issues/344)
* friendly url string:
 * default don't preserve dots
 * use regexp fragment to create custom rules
* bugfix for BeToolbar helper with plugin modules
* avoid multiple select in nickname choice - use timestamp
* add plugin model behaviors path in module plug (otherwise you get errors installing plugins like "tickets")
* [#341](https://github.com/bedita/bedita/issues/341) added a fix for svg images: skip resample and return the originale image whitout crate thumbnails
* [#341](https://github.com/bedita/bedita/issues/341) - svg imported as "drawing" - change media type check order: first check media type mapping, then try using model name
* [#133](https://github.com/bedita/bedita/issues/133) - added "searchType" config parameter, if "fulltext" current fulltext search, if "like" use SQL-like query with %$text%
* bedita shell - restored check media files not in BEdita + automatic creation of media objects if missing
* bedita shell - checkMedia select max depth level
* [#352](https://github.com/bedita/bedita/issues/352) - new media cache dir
* move getCategoryMediaType in Stream model
* smarty view: add frontend plugins dir (APP/vendors/_smartyPlugins) to _smartyPlugins
* $config["reservedWords"] - add "pages", alpha order
* add a more readable custom properties array, [#346](https://github.com/bedita/bedita/issues/346)
* change cookie name to trace open tabs [#353](https://github.com/bedita/bedita/issues/353). New cookie name is: TABS|module_name/action
* remove inflections.php file it doesn't need in CakePHP 1.3
* Newsletter - fix default css in message [#348](https://github.com/bedita/bedita/issues/348)
* Video model - explicit $useTable to avoid lang inflections conflict
* bugfix BeUrl - use explicit action/controller in getUrl to avoid bad urls
* remove wymeditor and old ckeditor [#348](https://github.com/bedita/bedita/issues/348)
* refactoring of rich text editors folder structure
* add index on objects.nickname field
* avoid double ajax call searching object to relate in modal window
* add $excludeIds array to *getChildren() and *getDescendants() methods
* exclude already related objects in modal [#366](https://github.com/bedita/bedita/issues/366)
add to BuildFilterBehavior the way to build 'NOT IN' conditions.
Example:
$filter['BEObject.id'] = array('NOT' => array(1,2,3));
* shell - fix plugin module schema. Fixed findPluginPath function from the module schema script
* fix notice on BeThumb when image without extension
* [#283](https://github.com/bedita/bedita/issues/283) add bedita shell method importFilter: same filters used in backend (Publications / Tools / Import)
* BEAppObjectModel::hasManyAssoc - in hasManyAssoc data save avoid deleting rows with  "id" set in $data array (those rows are updated and not deleted/re-inserted as before)
* [#311](https://github.com/bedita/bedita/issues/311) - elastic search engine integration
 * generic http request method added
 * small refactoring
 * rest client, decode JSON as array
 * add external index/search engine support in SearchText model and rebuildIndex script
 * rest client: using curl allow preformatted string in URL query part
 * handle objects removal
 * use "searchEngine" config property (don't pass args)
 * search available in backend (modules+dashboard)
 * delete index option (in createIndex), exception on index object error
 * dbadmin shell: id param to rebuil index for single object
 * fix removeObject
* log session in beauth check
* fix addons enabled path
* [#311](https://github.com/bedita/bedita/issues/311) - DbadminShell::rebuildIndex, use [searchEngine] if set or -engineparams
* [#372](https://github.com/bedita/bedita/issues/372) Fixed perms module
* [#283](https://github.com/bedita/bedita/issues/283) bedita importFilter - pass other/filter specific options
* check "request_header" in  RestClientModel, if missing log "Missing Request Header"
* REST client model: custom request params using string or array - specify explicit set HTTP method for post/get (avoid problems doing a post/get after delete or post)
* custom properties - get custom props for object
* stream model bindings: add "RelatedObject" to "default" binding
* [#352](https://github.com/bedita/bedita/issues/352) - checkMedia(): avoid check in "cache" dir
* bulk assoc categories: use new Category::addObjectCategory method
* fix warning AppController::setupAnnotations()
* fix warning - model permission
* db - avoid varchar() in table schemas if not necessary
* streams - use full URI for remote media files in getMimeType()
* [#377](https://github.com/bedita/bedita/issues/377) - local thumb cache also for remote files
* code refactoring: getParents() and updateTree() methods moved from BeTree component to Tree model
* categories: method to get all object type categories
* defaultDateFormat: if config "dateFormatValidation" not set, expect valid SQL date format
* DbadminShell::updateStreamFields() add -id option
* [#379](https://github.com/bedita/bedita/issues/379) - fix groups_users HABTM relation in group delete / new schema definition
* added method updateRelationPriority to model ObjectRelation
* upgrade to cakePHP 1.3.17 [#363](https://github.com/bedita/bedita/issues/363)
* fix contain definition on BEObject [#363](https://github.com/bedita/bedita/issues/363)
* upgrade Smarty to 3.1.15 [#373](https://github.com/bedita/bedita/issues/373)
* updateRelationPriority code correction
* add .travis.yml file for Travis CI
* utility - cleanupCache use absolute BEDITA_CORE_PATH
* fix Tree::getAll() method
* add clone structure to Tree model unit test [#383](https://github.com/bedita/bedita/issues/383)


## Version 3.2.beta2 - populus

### User-visible changes

* new text for INSTALL 
* ui: don't try to open bad id selectors (js)
* [#307](https://github.com/bedita/bedita/issues/307): inverse relations - use left or right object types when necessary
* import / export filter
* longer titles in free relations tab
* added robots.txt and meta robots noindex, nofollow for backend interface
* [#252](https://github.com/bedita/bedita/issues/252) - fixed table compare in plugin install
* Users module bugfix - overload user data loading group details. Refactoring
* [#117](https://github.com/bedita/bedita/issues/117) multimedia: handle existing file/url
* ui: select category filter more generic not only for /index methods
* modules: fix error 500 on "deleteSelected"
* [#301](https://github.com/bedita/bedita/issues/301) admin: view backend and frontends log files
* admin: check plugins presence and modules dir existence
* [#316](https://github.com/bedita/bedita/issues/316) use nicknames in backend view URLs
* concurrent access: remove update from viewObject to avoid fake concurrent alerts
* avoid multiple click events in list objects
* webmark - fix smarty error on json object
* added UI spanish translation
* categories: alphabetic order in object list view - trunk
* [#313](https://github.com/bedita/bedita/issues/313) - admin module: introduced the way to enable/disable all addons (not only for BEdita object type)
* fixed unbalanced brackets in multimedia/inc/menuleft.tpl
* [#319](https://github.com/bedita/bedita/issues/319) 
 * add ckeditor simple style 
 * new json config BEDITA array
 * add $currLang2 var for 2 char lang codes (en, de, it,...)
* [#190](https://github.com/bedita/bedita/issues/190) Drag&drop of multimedia elements inside body textarea.
* ui: dashboard more tabs default open
* fix errors in selection of multimedia already present in the system
* [#295](https://github.com/bedita/bedita/issues/295) - Admin module:
 * impemented the utility functions 'update stream fields', 'rebuild index', 'cleanup cache', 'empty logs', 'clear media cache'
 * Added an Utility model class to handle common operations used both in UI and in shell scripts

### Frontend changes

* FrontendController::loadSectionObjects(): return empty array if section is protected
* FrontendController::rss() - make safe string for display as HTML inside <channel> using Sanitize::html()
* [#315](https://github.com/bedita/bedita/issues/315) ui: BeFront helper metaAll and metaDc not correct for sections
* dummy.example.com/html5.example.com - added form for password recovery
* introduced simple mobile frontend (mobile.example.com)
* fix loadSectionsTree bug using non null $depth
* refactoring of BeFront::menu() in order to increase html flexibility

### Developer-visibile changes:

* update to CakePHP 1.3.15
* update to Smarty 3.1.11
* [#265](https://github.com/bedita/bedita/issues/265) - use SmartyException
* [#312](https://github.com/bedita/bedita/issues/312) object_relations: add utility methods
* soap: add try/catch - on exception function returns "null"
* [#295](https://github.com/bedita/bedita/issues/295)
 * improved handle ajax exceptions adding BEdita html standard message error in json response
 * add system event message on success
* in save operations avoided to delete tree positions of objects if isn't set relative data array (data[destination]). Pass empty data[destination] to delete all tree positions
* [#283](https://github.com/bedita/bedita/issues/283) 
 * new convention for name and supported mime types
 * dynamic import/export form
 * export only selected object/section/content - let filter load other objects
 * shell: bedita export filter -f ... -filter ...  -id ...
 * add validation methods
* [#298](https://github.com/bedita/bedita/issues/298) - import/export with Phar module / check plugin existence
* supported media types: add application/zip
* bugfix - adding new item in multimedia module inserted a row in trees table also when no position was selected
* replace folder->ls with folder->read
* smarty translations fix: check if TrHelper is available
* fix BuildFilter con custom table fields - accept values like 0, or '0' in conditions (i.e. Model.field = 0 should be a valid condition)
* shell: dbadmin massRemove
* add custom_property, date_item and count_relations filter to BuildFilter behavior
* Added locales.php
* introudced the possibility to extend BuildFilter (used to build custom query) through other Behaviors (merged from ulmus)
* fix belongsTo assoication between ObjectType and BEObject models
* [#305](https://github.com/bedita/bedita/issues/305) apidoc corrections/improvements
* AppHelper::getHelper public
* added otf supported mimetype
* bedita.ini: allow reload
* fixed  Call-time pass-by-reference in BeLib::arrayValues() (deprecated)
* moved jquery.tooltip from pages/update_editor.tpl to layouts/default.tpl to avoid multiple loading 
* [#313](https://github.com/bedita/bedita/issues/313) 
 * Refactoring, add Addon model to handle operations on addons
 * added FineDiff vendor library to execute diff between files when an addon enabled doesn't match the related addon available
* [#318](https://github.com/bedita/bedita/issues/318)
 * localization: add support for plugin locales in .po files
 * TrHelper: add domain translation  - see [#318](https://github.com/bedita/bedita/issues/318)


## Version 3.2.beta - populus

### User-visible changes

* [#137](https://github.com/bedita/bedita/issues/137) 
 * installation wizard
 * setup: partially formatted database.php
* [#279](https://github.com/bedita/bedita/issues/279) admin: mail queue and mail check
 * email info page
 * change menu labels
* [#283](https://github.com/bedita/bedita/issues/283) basic XML export/import from publications module
 * import/export filter models
 * xml import, allow import of files already present
* [#268](https://github.com/bedita/bedita/issues/268) admin: configuration page
 * lang selection layout
 * added button "test smtp"
* [#301](https://github.com/bedita/bedita/issues/301) admin: view backend and frontends log files
* [#276](https://github.com/bedita/bedita/issues/276) Sections enhancement
 * GeoTag
* [#303](https://github.com/bedita/bedita/issues/303) new BE home
* [#210](https://github.com/bedita/bedita/issues/210) frontend menu - backend interface
 * trees.menu not nullable, view in publications module
 * icon for hidden sections
 * save trees.menu user selection ("visibility" checkbox)
 * Publication module - list sections: fixed wrong label and icon visibility conditions for hidden sections
 * added "menuhidden" class to left side tree items
 * init data publication/section with menu = 1
* [#233](https://github.com/bedita/bedita/issues/233) highlight objects with permissions
 * wrong protected class set to publications fixed
 * locked icon on .protected elements in .publishingtree
 * fixed double icon in detail doc
* [#307](https://github.com/bedita/bedita/issues/307) relations: handle inverse relations
 * handle inverse relations in obj save and in obj view
 * delete inverse relations before insert
 * fix "inverse" relations delete
 * fix "inverse" relations priority
* [#117](https://github.com/bedita/bedita/issues/117) multimedia: handle existing file/url
* [#295](https://github.com/bedita/bedita/issues/295) admin: add utility functions
 * UI for admin / utility module
 * core modules management (on-off)
* [#296](https://github.com/bedita/bedita/issues/296) Create new Users module to manage users and groups
 * immutable groups + asc desc in user list
 * added search and pagination toolbar in Users module
* [#150](https://github.com/bedita/bedita/issues/150) ui: show ascending/descending order for columns object lists
* [#308](https://github.com/bedita/bedita/issues/308) - view categories in alphabetical order
* other:
 * editor css
 * ckeditor
 * error display / new view
 * bigger pub tree area

### Frontend changes

* [#304](https://github.com/bedita/bedita/issues/304) category 'off' should not be visible in frontend
* frontends - debug.example.com: removed comment
* fixes on dummy.example.com, site.example.com, pages_controller
* add tag_cloud element in dummy.example.com and debug.example.com
* set parentAuthorized = authorized in FrontendController::setCanonicalPath() for publication to avoid warning in FrontendController::section() method (merged from ulmus)
* FrontendController::loadObjectsByCategory changed from public to protected
* FrontendController - add setCanonicalPath in objects selected by tag or category and removed forced baseLevel for bindings
* FrontendController::loadObjectsByTagCategory() - add try catch block to avoid 404 error when get contents on draft branch tree
* BeFront::menu() - comment corrected
* BeFront::chooseTemplate() fix 'Check frontendMap currentContent nickname' when currentContent is populated but no content has been selected
* BeFront::chooseTemplate() fix 'object type template' choose  when currentContent is populated but no content has been selected

### Developer-visibile changes

* [#291](https://github.com/bedita/bedita/issues/291) multimedia: url friendly file names
 * added field 'original_name' to streams table
* [#274](https://github.com/bedita/bedita/issues/274) frontend: generic /category method to load categorized objects
 * added category tpl to examples
* [#305](https://github.com/bedita/bedita/issues/305) apidoc corrections/improvements
* added alpha suffix in changelog
* changed default limit value $dim = 100000 in BEAppModel::findObjects()  to $dim = null (get all objects with no limit)
* add sql_dump.tpl for Smarty to show SQL output
* upload: fix mediaType detection
* texteditor script in a separate element view
* BEObject::beforeValidate() - check that property_type is not empty before check if its value is 'date' to format property_value to avoid warning (merged from ulmus)
* de{literal}ize smarty/javascript code
* removed space in .htaccess
* ui: fix js bug - write open fieldset cookie only if "id" is set
* fixed PagesController::showObject() to get correctely objects in relations tab
* fix on search text save for new sections
* fix search bug: don't use "id" in order by! - trunk
* remove unused model
* New HTML5 dummy fronted (based on http://html5boilerplate.com/)
* Added ignore rules for /tmp in html5.example.com
* added tag {t} in "matching the query" string
* generalized search form action attribute using $view->action
* [#265](https://github.com/bedita/bedita/issues/265) - use and handle SmartyException
* [#248](https://github.com/bedita/bedita/issues/248) - fix translations and multimedia on postgres
* [#287](https://github.com/bedita/bedita/issues/287)
 * postgres search working (quite...) 
 * fix multiple results in postgres search (with AND)
 * fix postgres search like mysql - with OR


## Version 3.2.alpha - populus

### User-visible changes

* [#137](https://github.com/bedita/bedita/issues/137) - installation wizard
 * setup: force cake debug=1
 * installer BEDITA_IGNORE_CFG to avoid bedita.cfg.php load
* [#268](https://github.com/bedita/bedita/issues/268) - Admin module - some configuration can be edit through user interface
 * BEdita url, project name
 * media url and media root
 * default UI language
 * content default language
 * content languages
 * smtpOptions
 * mail support
* [#259](https://github.com/bedita/bedita/issues/259) addressbook: newsletter subscription bug
* multimedia module: introduced GPS info in exif data view
* tags module: fix views for cake 1.3
* [#291](https://github.com/bedita/bedita/issues/291) - url friendly file names
* [#293](https://github.com/bedita/bedita/issues/293) Sample module: updated to cakephp 1.3 plugin conventions
* Events module - fix smarty include wrong path in form.tpl
* Addressbook module - fix smarty include wrong path in form.tpl
* [#239](https://github.com/bedita/bedita/issues/239) - Publication module: removed ajax behavior
* Publication module
 * added filter for object_type_id
 * new section form features
 * sections: "create new section here"
 * added tags and notes
* modules menu with search field / search input removed from toolbar
* new object command in toolbar
* modules menu css + search
* [#296](https://github.com/bedita/bedita/issues/296) - Users module
 * create users module, moved users and groups from admin to users module, update sql initialization nad upgrade
 * random passw for new user
 * css color module / menuLeft on admin
 * fixed wrong redirect path
 * reordered groups and fix module_permission in  bedita_init_data.sql for the introduction of manager group
 * "manager" groups have not permission to create groups with access to "admin" module and to edit "administrator" users
 * dedicated page for view/edit/new group
 * when list groups count number of users belongs to any group
* alternative module list menu
* load publications in every backend page (AppController::beforeFilter()) to have url for frontend site
* [#282](https://github.com/bedita/bedita/issues/282) translations: publication public name / description translatable
* EventLog => allow multiple logs
* Helper [#233](https://github.com/bedita/bedita/issues/233), [#243](https://github.com/bedita/bedita/issues/243) - BeTreeHelper::view() added class="protected" to publications/sections with some permission (on the tree)

### Frontend changes

* sample frontends adjustments for upgrade to CakePHP
* frontend debug.example.com: wrong php tag in default.ctp
* [#263](https://github.com/bedita/bedita/issues/263) added BeFront::stagingToolbar() method to load the staging toolbar in frontend apps
* [#286](https://github.com/bedita/bedita/issues/286) - lang codes for HTML 639-1 => BeFront::lang() method
* [#90](https://github.com/bedita/bedita/issues/90) set locale in $currLocale, reading from config "locales"
* debug.example.com - Replaced $view->_smarty->_tpl_vars (not in Smarty anymore) with $view->viewVars
* FrontendController: removed Set::isEqual() because it has been removed in Cake 1.3
* [#278](https://github.com/bedita/bedita/issues/278) - Improve routing rules in frontend applications
 * security: if first url args is a method of PagesController check that it aren't a ForntendController/AppController method
* FrontendController - change nickname callbacks name:
 * before with this-is-my-nick it called this_is_my_nickBeforeFilter(), ....
 * now with this-is-my-nick it calls thisIsMyNickBeforeFilter(), ....

### Developer-visibile changes

* shell: dbadmin cleanup -days / removes old items from log/job tables
* change bedita cfg files load chain - now bedita.ini requires bedita.cfg
* upgrade cake to 1.3.13
* [#265](https://github.com/bedita/bedita/issues/265) - upgrade to Smarty 3.1.7
* shell: bedita modules fix/update
* small fix in TransactionComponent for upgrade to CakePHP
* read schema tables with options
* shell: check all frontends in checkApp / check frontends existence
* XML::toArray patch http://cakephp.lighthouseapp.com/projects/42648/tickets/1667
* add BEDITA_LOCAL_CFG_PATH constant, local configuration files path
* schema doc update
* [#285](https://github.com/bedita/bedita/issues/285) replaced php_thumb with new php_thumb library
* UI: general modulesmenu in standard html5 NAV element + general HTML5 declaration + CSS html5 elements block definition
* helper: BeToolbarHelper::changeDimSelect() - added keys to $options array params to create tag select with value different from text
* css declaration with var argument (to prevent caching)
* beditaNew renamed to bedita.css
* unit test: add test for BeLib::variableFromName()
* Area and Section model: added Annotation to $modelBindings["default"] to get EditorNote
* add Tag and Annotation to Section and Annotation to Area models. Now Section and Area saves use AppController::saveObject() method
* [#268](https://github.com/bedita/bedita/issues/268), [#137](https://github.com/bedita/bedita/issues/137) - fix config write regexp => ignore $config[] after =
* [#268](https://github.com/bedita/bedita/issues/268) - bedita.cfg.php / only one main config file (bedita.sys.php deprecated)
* config: remove 'config''language', 'multilang' - add 'defaultUILang'
* bedita shell: add cleanphp method to clean php files from leadind and trailing spaces
* [#291](https://github.com/bedita/bedita/issues/291) - Stream::updateStreamFields - add filename modification
* shell: fix dbadmin error
* be_lib: allow start digits in nickname / friendlyUrlString
* shell/core: move BeLib::initConfig() in AppController - allow shell scripts launch with config errors
* shell: fix for cake 1.3 / cleanup automatic in frontends also
* shell: dbadmin clearMediaCache method
* helper AppHelper::getHelper() - changed var name 'themeWeb'  in 'theme' (due to cake 1.3 upgrade)