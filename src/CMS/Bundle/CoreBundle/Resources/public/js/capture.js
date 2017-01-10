/**
 * Created by DCA on 07/12/2016.
 */
var page = require('webpage').create();
page.open('http://www.cms2.local/?code=beede74da1d64fc7bf46c6e463377928', function() {
    page.render('screenshot.png');
    phantom.exit();
});