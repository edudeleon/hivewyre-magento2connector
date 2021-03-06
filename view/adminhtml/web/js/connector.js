     /**
     * Get Dropdown segments (Ajax Call)
     * @return {[type]}
     * @author edudeleon
     * @date   2016-02-09
     */
    function getDropdownSegments() {
        require(['jquery'], function($){
            $.ajax( {
                url: segments_url,
                data: {form_key: window.FORM_KEY},
                type: 'POST',
                showLoader: true
            }).done(function(data) { 
                var $el = $("#hivewyre_magentoconnector_account_registration_segments");
                $el.empty(); // remove old options
                
                //Adding options
                $.each(data, function(value, key) {
                    $el.append($("<option></option>")
                            .attr("value", value).text(key));
                });
            });
        });
    }

    /**
     * Gets the params from the Registration section and makes an Ajax call to register the Merchant
     * @return {[type]}
     * @author edudeleon
     * @date   2016-02-09
     */
    function registerMerchant(){
        require(['jquery'], function($){

             $.ajax( {
                url: registration_url,
                data: {
                    form_key:   window.FORM_KEY,
                    email:      $('#hivewyre_magentoconnector_account_registration_email').val(),
                    website:    $('#hivewyre_magentoconnector_account_registration_website').val(),
                    segment:    $('#hivewyre_magentoconnector_account_registration_segments').val(),
                    password:   $('#hivewyre_magentoconnector_account_registration_password').val()
                },
                type: 'POST',
                showLoader: true
            }).done(function(result) { 
                    
                    if(!result.success){
                        var message = '<ul class = "message">'+result.msg+'</ul>';
                        document.getElementById('hivewyre-api-register-results').innerHTML = message;
     
                    } else {
                        //Set Site ID
                        $('#hivewyre_magentoconnector_account_settings_site_id').val(result.site_id);

                        //Set Website Name
                        $('#hivewyre_magentoconnector_account_settings_connected_website').val($('#hivewyre_magentoconnector_account_registration_website').val());
                        
                        //Submit Form
                        $("#config-edit-form").submit();  
                    }
            });

            return false;
        });
    }

    /**
     * Login merchant and list the available websites
     * @return {[type]}
     * @author edudeleon
     * @date   2016-02-19
     */
    function loginMerchant(){
        require(['jquery'], function($){

             $.ajax({
                url: login_url,
                data: {
                    form_key:    window.FORM_KEY,
                    email:       $('#hivewyre_magentoconnector_account_login_email').val(),
                    password:    $('#hivewyre_magentoconnector_account_login_password').val()
                },
                type: 'POST',
                showLoader: true
            }).done(function(result) {
                    if(!result.success){
                        var message = '<ul class = "message">'+result.msg+'</ul>';
                        document.getElementById('hivewyre-api-login-results').innerHTML = message;
     
                    } else {
                        document.getElementById('hivewyre-api-login-results').innerHTML = "";
                        var accountWebsites = result.sites;

                        //Set token value
                        $('#token').val(result.token);

                        //Populate Websites select
                        var websitesSelect = $("#hivewyre_magentoconnector_account_login_website");
                        websitesSelect.empty(); // remove old options
                    
                        if(accountWebsites.length !== 0){
                            //Adding options
                            $.each(accountWebsites, function(value, key) {
                                
                                var option_text = key;
                                if(!(option_text.search("Magento.com") != -1 || option_text.search("Not Connected") != -1)){
                                    websitesSelect.append($("<option></option>").attr({value: value, disabled:"disabled"}).text(key));
                                } else {
                                    websitesSelect.append($("<option></option>").attr("value", value).text(key));
                                }

                            });
                        } else {
                            var message = '<ul class = "message">'+'There are no domains available for this account.'+'</ul>';
                            document.getElementById('hivewyre-api-login-results').innerHTML = message;
                        }
                    }
            });

            return false;
        });
    }

    /**
     * Connect Magento Store with Hivewyre
     * @return {[type]}
     * @author edudeleon
     * @date   2016-02-19
     */
    function connectMerchant(){
        require(['jquery'], function($){
            //Site ID
            var site_id         = $('#hivewyre_magentoconnector_account_login_website').val();
            var website_name    = $('#hivewyre_magentoconnector_account_login_website option:selected').text();

            if(!site_id || site_id == 0){
                alert("You need to select a website to connect.");
                return false;
            }

            $.ajax({
                url: connect_url,
                data: {
                    form_key:    window.FORM_KEY,
                    domain_id:   site_id,
                    token:       $('#token').val(),
                    rap:         magento_rap,
                },
                type: 'POST',
                showLoader: true
            }).done(function(result) {
                    if(!result.success){
                        var message = '<ul class = "message">'+result.msg+'</ul>';
                        document.getElementById('hivewyre-api-connect-results').innerHTML = message;
     
                    } else {
                        //Set Site ID
                        $('#hivewyre_magentoconnector_account_settings_site_id').val(result.site_id);

                        //Set Website Name
                        website_name = website_name.replace("(Connected to Magento.com)", "");
                        website_name = website_name.replace("(Not Connected)", "");
                        $('#hivewyre_magentoconnector_account_settings_connected_website').val(website_name);
                        
                        //Submit Form
                        $("#config-edit-form").submit();
                    }
            });

            return false;
        });
    }

    /**
     * Init configs
     * @author edudeleon
     * @date   2016-02-25
     */
    function initConfigs(){
        require(['jquery'], function($){

            //Set website name
            var website_name = $("#hivewyre_magentoconnector_account_registration_website").val();            
            if(!website_name){
                $('#hivewyre_magentoconnector_account_registration_website').val(window.location.hostname);
            }

            //Setting fields as readonly
            $("#hivewyre_magentoconnector_account_settings_site_id" ).attr("readonly", "readonly");
            $("#hivewyre_magentoconnector_account_settings_connected_website" ).attr("readonly", "readonly");

        });
    }