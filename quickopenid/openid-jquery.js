/*
Based on Simple OpenID Plugin
http://code.google.com/p/openid-selector/

This code is licenced under the New BSD License.
*/

var providers = {
    google: {
        name: 'Google',
        url: 'https://www.google.com/accounts/o8/id'
    },
    yahoo: {
        name: 'Yahoo',      
        url: 'http://yahoo.com/'
    },    
    launchpad: {
        name: 'Launchpad',
        url: 'https://launchpad.net/~{username}',
        label: 'Belépés Launchpad-azonosítóval:'
    },
    openid: {
        name: 'OpenID',     
        label: 'Belépés OpenID használatával:',
        url: null
    }
};

var quickopenid = {

    ajaxHandler: null,
    input_id: "edit-openid-identifier",
    provider_url: null,
    provider_id: null,
    
    init: function() {
        
        var openid_btns = $('.openid-link').parents("ul");
        $('.openid-link').remove();
        
        for (id in providers) {
            openid_btns.append(this.getBoxHTML(providers[id]));
        }
    },
    getBoxHTML: function(provider) {
        var box_id = provider["name"].toLowerCase();
        return '<li><a title="' + provider["name"] + '" href="javascript: quickopenid.signin(\'' + box_id + '\');"' + ' class="openid-provider-' + box_id + '">' + provider["name"] + '</a></li>';    
    
    },
    /* Provider image click */
    signin: function(box_id, onload) {
        $(".selected-openid-provider").removeClass("selected-openid-provider");
        var provider = providers[box_id];
        if (! provider) {
            return;
        }
        
        this.provider_id = box_id;
        this.provider_url = provider['url'];
        
        $(".openid-provider-" + box_id).addClass("selected-openid-provider");
        // prompt user for input?
        if (provider['label']) {
            var $loginElements = $("#edit-name-wrapper, #edit-pass-wrapper, li.openid-link");
            var $openidElements = $("#edit-openid-identifier-wrapper, li.user-link"); 
            $loginElements.hide();
            $openidElements.css("display", "block"); 
            this.useInputBox(provider);
        } else {
            if (this.submit()) {
                this.change();
                $('#user-login-form').submit();
            }
        }
    },
    /* Sign-in button click */
    submit: function() {
        
        return true;
    },
    useInputBox: function (provider) {
        
        var html = '';
        var value = '';
        var label = provider['label'];
        var style = '';
        
        if (label) {
            $('#edit-openid-identifier-wrapper label').empty().append(label);
            $("#edit-openid-identifier").hide();
            if ($("#edit-quickopenid-identifier").length < 1) {
              $('#edit-openid-identifier-wrapper').append('<input type="text" class="form-text" value="" size="13" id="edit-quickopenid-identifier" onchange="quickopenid.change()" />');
            }
            $("#edit-openid-identifier-wrapper .description").hide();
            $("#edit-quickopenid-identifier").show();
        }
        if (provider['url'] == null) {
            $("#edit-openid-identifier").show();
            $("#edit-quickopenid-identifier").hide();
            $("#edit-openid-identifier-wrapper .description").show();
        }
    },
    change: function () {
        var url = quickopenid.provider_url; 
        if (url) {
            url = url.replace('{username}', $("#edit-quickopenid-identifier").val());
            $("#edit-openid-identifier").val(url);
        }
    }
};

$(document).ready(function() {quickopenid.init();});
