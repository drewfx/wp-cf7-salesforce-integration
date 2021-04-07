<form action='options.php' method='post'>
    <div class="salesforce-container">
        <div class='col'>
            <div class="col-title">
                <h1>CF7 SalesForce Integration</h1>
            </div>
            <div class="col-description">
                <span>

                </span><br>
                <span>This page will contain all relevant configurations for the API, form integration, and blacklisting of emails.</span>
            </div>
        </div>
        <div class="col">
            <div class="col-sidebar">
                <?php use Drewfx\Salesforce\Admin\Settings;

                include_once __DIR__ . '/sidebar.php' ?>
            </div>
        </div>
    </div>
    <div class="salesforce-container">
        <div class="col">
            <?php settings_fields(Settings::PAGE_NAME); ?>
            <div class="form-integration-enabler row tiles">
                <h2>Salesforce Integrated Forms</h2>
                <ul class='forms-list'>
                    <?php Settings::render_form_list(); ?>
                </ul>
            </div>
            <div class='integration-credentials row tiles'>
                <h2>Salesforce API Credentials</h2>
                <table class='api-credentials'>
                    <?php Settings::render_credentials(); ?>
                </table>
            </div>
        </div>
        <div class="col">
            <div class="form-blacklist row tiles">
                <h2>Salesforce API Blacklist</h2>
                <span>Enter a comma separated list of emails here to blacklist for the integration.</span><br>
                <span><strong>Note: </strong> this feature searches for an input with the name of 'email' to match in a form submission.</span><br>
                <?php Settings::render_blacklist(); ?>

                <h2>Mass Import <small>(requires .csv)</small></h2>
                <div class="action-button-group">
                    <input class="mass-upload" id="blacklist-mass-upload" type="file" accept=".csv">
                    <button type="button" class="button button-primary-disabled" id="blacklist-mass-upload-merge"
                            disabled>Merge
                    </button>
                    <button type="button" class="button button-secondary-disabled" id="blacklist-mass-upload-reset"
                            disabled>Revert
                    </button>
                    <button type="submit" class="button button-primary" title="Saves all fields.">Save</button>
                </div>
                <div class="mass-upload-notification" id="mass-upload-notification"></div>
            </div>
        </div>
    </div>
</form>
