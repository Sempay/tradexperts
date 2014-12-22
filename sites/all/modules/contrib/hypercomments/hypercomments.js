/**
 * HC js class
 */
var HyperComments = function(hc_url, site_name, site_url) {
    var app = this;
    var nodes = [];

    this.init = function() {
        jQuery('#edit-hypercommetns-wid').click(function() {
            var callback = function() {
                app.createWidget();
            };
            app.createAuthPopup(600, 450, 'hc_auth', hc_url + '/auth?service=google', callback);
            return false;
        });

        jQuery('#edit-export-button').click(function() {
            jQuery('#hc_exp_box').remove();
            var div_exp = '<div id="hc_exp_box"><b>Export node: </b><span id="hc_exp_progress"></span></div>';
            jQuery('#edit-export').append(div_exp);
            app.startExport();
            return false;
        });
    };

    this.createWidget = function() {
        var date = new Date();
        var time_zone = -date.getTimezoneOffset() / 60;
        jQuery.getJSON(hc_url + '/api/widget_create?jsoncallback=?', {
                site: site_url,
                title: site_name,
                plugins: "comments,rss,login,count_messages,authors,topics,hypercomments,likes",
                hypertext: "*",
                limit: 20,
                template: "index",
                cluster: "c1",
                platform: "drupal",
                time_zone: time_zone,
                enableParams: true,
                notify_url: site_url + '/admin/config/services/hypercomments/notify'
            },
            function(data) {
                if (data.result == 'success') {
                    app.saveWid(data);
                } else {
                    alert(data.description);
                }
            });
    };

    this.saveWid = function(data) {
        data.hc_action = 'save_wid';
        jQuery.get(site_url + '/?q=admin/config/services/hypercomments/save_wid',
            data,
            function(response) {
                if (response.result == 'success') {
                    document.location.reload();
                } else {
                    alert('ERROR saving widget ID');
                }
            },
            'json');
    };

    this.createAuthPopup = function(width, height, name, url, callback) {
        var x = (640 - width) / 2;
        var y = (480 - height) / 2;
        if (screen) {
            y = (screen.availHeight - height) / 2;
            x = (screen.availWidth - width) / 2;
        }
        var w = window.open(url, name, "menubar=0,location=0,toolbar=0,directories=0,scrollbars=0,status=0,resizable=0,width=" + width + ",height=" + height + ',screenX=' + x + ',screenY=' + y + ',top=' + y + ',left=' + x);
        w.focus();

        if (callback)
            var interval = setInterval(function() {
                if (!w || w.closed) {
                    clearInterval(interval);
                    callback();
                }
            }, 500);
    };

    this.startExport = function() {
        jQuery.get(site_url + '/?q=admin/config/services/hypercomments/get_nodes', function(response) {
                if (response.result == 'success') {
                    nodes = response.data.split(',');
                    app.makeExport(nodes);
                } else {
                    alert('ERROR: can\'t find nodes');
                }
            },
            'json');
    };

    this.makeExport = function(nodes) {
        var node = nodes[0];
        nodes.shift();

        get_param = {
            url: site_url + '/?q=admin/config/services/hypercomments/make_export',
            data: 'node=' + node,
            success: function(data) {
                var packet = data;

                for (var i = 0; i < packet.length; i++) {
                    var nt;
                    if (packet[i].result == 'success') {
                        nt = 'start export node ' + node;
                        jQuery('#hc_exp_progress').html(nt);
                        app.sendExport(packet[i], node);
                        if (nodes.length > 0) app.makeExport(nodes);
                    } else if (packet[i].result == 'error' && (packet[i].code == 101 || packet[i].code == 102)) {
                        nt = '<span style="color:red">ERROR: To export the comments folder <i>/sites/default</i> should be writable (777)</span>';
                        jQuery('#hc_exp_progress').html(nt);
                    }
                }
            },
            error: function(data) {
                var nt = 'ERROR: start export node ' + node;
                jQuery('#hc_exp_progress').html(nt);
                if (nodes.length > 0) app.makeExport(nodes);
            }
        };
        jQuery.ajax(get_param);
    };

    this.sendExport = function(obj, node) {
        jQuery.getJSON(hc_url + '/api/import?response_type=callback&callback=?', obj,
            function(data) {
                var nt;
                if (data.result == 'success')
                    nt = '<span style="color:green">SUCCESS</span> export node ' + node;
                else
                    nt = '<span style="color:red">ERROR</span> export node ' + node;

                jQuery('#hc_exp_progress').html(nt);
            });
    };
};

var _hcwp = _hcwp || [];
/**
 * Drupal HyperComments behavior.
 */

Drupal.behaviors.hypercomments = {
    attach: function(context, settings) {
        jQuery('#hc_full_comments').remove();
        jQuery('body').once('hypercomments', function() {
            // Load the HyperComments comments.
            if (settings.hypercomments || false) {
                if (settings.hypercomments.debug)
                    HCDeveloper = 1;
                // Setup the global JavaScript variables for HyperComments.
                var hc_obj = {};
                hc_obj.widget_id = settings.hypercomments.wid;
                hc_obj.widget = 'Stream';
                hc_obj.platform = 'drupal';
                hc_obj.language = settings.hypercomments.lang;
                hc_obj.xid = settings.hypercomments.xid;
                hc_obj.auth = settings.hypercomments.auth || '';
                _hcwp.push(hc_obj);

                jQuery.ajax({
                    type: 'GET',
                    url: document.location.protocol + '//w.hypercomments.com/widget/hc/' + settings.hypercomments.wid + '/' + settings.hypercomments.lang + '/widget.js',
                    dataType: 'script',
                    cache: false
                });
            }
            // load comment counter
            if (settings.hypercomments_count || false) {
                if (settings.hypercomments_count.debug)
                    HCDeveloper = 1;
                var hc_obj = {};
                hc_obj.widget_id = parseInt(settings.hypercomments_count.wid);
                hc_obj.widget = 'Bloggerstream';
                hc_obj.language = settings.hypercomments_count.lang;
                hc_obj.selector = '.hypercomments_count a';
                _hcwp.push(hc_obj);

                jQuery.ajax({
                    type: 'GET',
                    url: document.location.protocol + '//w.hypercomments.com/widget/hc/' + settings.hypercomments_count.wid + '/' + settings.hypercomments_count.lang + '/widget.js',
                    dataType: 'script',
                    cache: false
                });
            }

        });
    }
};