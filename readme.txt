# CF7 Salesforce Integration #

- Version 1.1.0

### Wordpress plugin for adding additional track variables to contact form 7 submissions. ###

### Salesforce Auth Information ###
- https://help.salesforce.com/articleView?id=remoteaccess_oauth_endpoints.htm&type=5
- https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/quickstart_oauth.htm
- https://developer.salesforce.com/docs/atlas.en-us.sfFieldRef.meta/sfFieldRef/salesforce_field_reference_Lead.htm

### How do I get set up? ###
- Copy contents to `wp-content/plugins`, enable the plugin itself then navigate to Settings -> CF7 Salesforce and enable/fulfill all credentials.
- All classes are auto loaded using the psr-4 standard designated in the `composer.json` file.
- If adding any additional folders/files that need to be auto loaded please add them to composer psr-4 and follow psr-4 standards.
- All core sourcecode is contained in `/src/Drewfx`, dependencies in `/vendor` and helper methods in `/functions`.

### Plugin Flow ###
- Hooks are registered in `/src/Drewfx/Plugin.php` file in either the `defineAdminHooks()` or `definePublicHooks` methods respectively.
- These hooks will be fired off in true WP fashion. In concern to this plugin in particular we register our hook in
`definePublicHooks` to grab the post data from Contact Form 7 (dependency) and submit it to the `Hooks` class, `queueLead()` method.
The `queueLead()` will create a row in the database queue using the `QueueService`.  The `CronService` will automatically pick up
any items on the queue and push them out to the Salesforce API.

## Changelog ##

### v.1.1.0 ###
- General Refactoring
- Add PHP Version to Settings page

### v.1.0.0 ###
- Initial app development
