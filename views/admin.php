<form action='options.php' method='post'>
    <div class="salesforce-container">
        <div class='col'>
            <div class="col-title">
                <h1>CF7 Salesforce Integration</h1>
            </div>
            <div class="col-description">
                <span>
                    This page will contain all relevant configurations for the API, form integration.<br>
                    <strong>Note: </strong> All leads are pushed onto a queue, which are then pushed via the CronService (requires cron to be running).
                </span>
                <ul>
                    <li><a href="https://help.salesforce.com/articleView?id=remoteaccess_oauth_endpoints.htm&type=5">API OAuth Endpoints</a></li>
                    <li><a href="https://developer.salesforce.com/docs/atlas.en-us.api_rest.meta/api_rest/quickstart_oauth.htm">API OAuth Quickstart</a></li>
                    <li><a href="https://developer.salesforce.com/docs/atlas.en-us.sfFieldRef.meta/sfFieldRef/salesforce_field_reference_Lead.htm">System Lead Fields</a></li>
                </ul>
            </div>
        </div>
        <div class="col">
            <div class="col-sidebar">
                <div class='postbox-sidebar'>
                    <div class="actions">
                        <h3>Form Actions</h3>
                        <div class="inside">
                            <div class="submitbox" id="submitpost">
                                <div id="major-publishing-actions">
                                    <div id="publishing-action">
                                        <button class="button button-primary" type="reset">Reset</button>
                                        <input type="submit" name="submit" id="submit" class="button button-primary"
                                               value="Save Changes">
                                    </div>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="salesforce-container">
        <div class="col">
            <?php use Drewfx\Salesforce\Admin\Settings;

            settings_fields(Settings::PAGE_NAME); ?>
            <div class="form-integration-enabler row tiles">
                <h2>Salesforce Integrated Forms</h2>
                <ul class='forms-list'>
                    <?php Settings::renderFormList(); ?>
                </ul>
            </div>
            <div class='integration-credentials row tiles'>
                <h2>Salesforce API Credentials</h2>
                <table class='api-credentials'>
                    <?php Settings::renderCredentials(); ?>
                </table>
            </div>
        </div>
        <div class="col">
            <div class="form-blacklist row tiles">
                <h2>Salesforce API</h2>
                <?php Settings::renderStatistics() ?>
            </div>
        </div>
    </div>
</form>
