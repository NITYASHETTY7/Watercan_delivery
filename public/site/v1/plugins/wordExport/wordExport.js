(function ($) {
    $.fn.wordExport = function (fileName) {
        fileName = typeof fileName !== 'undefined' ? fileName : 'jQuery-Word-Export';

        var static = {
            mhtml: {
                top: 'Mime-Version: 1.0\nContent-Base: ' + location.href + '\nContent-Type: Multipart/related; boundary="NEXT.ITEM-BOUNDARY";type="text/html"\n\n--NEXT.ITEM-BOUNDARY\nContent-Type: text/html; charset="utf-8"\nContent-Location: ' + location.href + '\n\n<!DOCTYPE html>\n<html>\n_html_</html>',
                head: '<head>\n<meta http-equiv="Content-Type" content="text/html; charset=utf-8">\n<style>\n_styles_\n</style>\n</head>\n',
                body: '<body>_body_</body>',
            },
        };
        var options = {
            maxWidth: 624, // Maximum width of images
        };

        var markup = $(this).clone();
        markup.each(function () {
            var self = $(this);
            if (self.is(':hidden')) self.remove();
        });

        // Add styles for margins
        var styles =  'body { margin: 0px; padding: 0; font-size: .5rem; line-height: 1.5; font-weight: 400; font-family: "Nunito Sans", sans-serif; }' +
            '* { margin: 0; padding: 0 }' +
            '@page { margin: 0; } ' +
            'img { max-width: 100% !important; height: auto !important; }' + // Add this line to adjust images
            '.container  { margin: 0; padding: 0; width:920px !important;}' +
            'div, p, span, h1, h2, h3, h4, h5, h6 { background-color: transparent !important; }' +
            'table { padding: 0; width: 100%; border-collapse: collapse; }' +
            'table .table { padding: 0; margin-top:10px !important; width: 100%; border-collapse: collapse; border: 1px solid black !important; margin-bottom:10px !important; }' +
            'table .table-bordered { padding: 0; width: 100%; border-collapse: collapse; border: 1px solid black; }' +
            '.table thead th { width: 10px !important; }' +
            '.table tbody td { width: 10px !important; }' +
            '.break-word { word-break: break-all !important; }' +
            '.custom-border { border: 1px solid black;padding: 15px; }' +
            '.no-border { border: none; }' +
            '.table th, .table td { border: 1px solid black; padding: 5px; text-align: left; font-size: 13px; }' +
            '.float-container { width: 100%; overflow: hidden; }' +
            '.custom-hr{ border-top: 1px solid #a7abb7; padding: 12px;}' +
            '.float-left { float: left; width: 48%; text-align: left; }' +
            '.px-2 { padding: .5rem !important; }' +
            '.hs-1 { font-size: 25px !important; font-weight: 700 !important; color: #74bc3b !important; }' +
            '.hs-2 { font-size: 25px !important; font-weight: 700 !important; color: #74bc3b !important; }' +
            '.hs-3 { font-size: 25px !important; font-weight: 700 !important; color: #74bc3b !important; }' +
            '.hs-4 { font-size: 25px !important; font-weight: 700 !important; color: #74bc3b !important; }' +
            '.hs-5 { font-size: 25px !important; font-weight: 700 !important; color: #74bc3b !important; }' +
            '.hs-6 { font-size: 25px !important; font-weight: 700 !important; color: #74bc3b !important; }' +
            '.mt-4 { margin-top: 1.5rem !important; }' +
            '.mb-2 { margin-bottom: .5rem !important; }' +
            'img { width: 100%; max-width: 625px; height: auto; }' +
            '.custom-list { list-style: none !important; padding: 0 !important; }' +
            '.custom-list li { list-style: none !important; padding: 0 !important; }' +
            'ul li {font-size: 12px !important; }' +
            '.mb-0 { margin-bottom: 0 !important; }' +
            '.float-right { float: right; width: 48%; text-align: right; }' +
            '.insight-span { font-size: 10px !important; }' +
            'section { margin-top: 20px !important; }' +
            '.row { margin-top: 20px !important; }' +
            '.isActive { font-size: 20px !important; font-weight: 700 !important; }' +
            '.clearfix::after { content: ""; clear: both; display: table; }' +
            '.custom-grade-badge { font-size: 26px !important; }' +
            '.table th { font-size: 16px !important; }' +
            '.text-success { color: #74bc3b !important; }' +
            'p { font-size: 14px; }' +
            '.border { border: 1px solid #dee2e6 !important; }' +
            'strong { font-weight: 500; }';

        var img = markup.find('img');

        function loadImage(url) {
            return new Promise(function (resolve, reject) {
                var image = new Image();
                image.crossOrigin = 'Anonymous'; // Allow cross-origin loading
                image.onload = function () {
                    resolve(image);
                };
                image.onerror = function () {
                    reject();
                };
                image.src = encodeURI(url);
            });
        }

        var loadImagePromises = [];

        for (var i = 0; i < img.length; i++) {
            loadImagePromises.push(loadImage(img[i].src));
        }

        Promise.allSettled(loadImagePromises)
            .then(function (results) {
                var modifiedImages = [];
                for (var i = 0; i < results.length; i++) {
                    if (results[i].status === 'fulfilled') {
                        var image = results[i].value;
                        var maxImageWidth = options.maxWidth; // Maximum width from options
                        var imgWidth = image.width; // Original width
                        var imgHeight = image.height; // Original height
                        // Check if the width exceeds the maximum allowed width
                        if (imgWidth > maxImageWidth) {
                            var ratio = (imgHeight) / (imgWidth / 2.4); // Calculate the aspect ratio
                            imgWidth = maxImageWidth; // Set the new width
                            imgHeight = maxImageWidth * (ratio / 2.2); // Adjust height to maintain aspect ratio
                        }                        
        
                        // Create a canvas for high-quality image rendering
                        const canvas = document.createElement('CANVAS');
                        canvas.width = imgWidth; // Set to new width
                        canvas.height = imgHeight; // Set to new height 
                        const context = canvas.getContext('2d');
        
                        // Draw the image on the canvas
                        context.drawImage(image, 0, 0, imgWidth, imgHeight);
        
                        // Convert canvas to a high-quality data URL
                        var uri = canvas.toDataURL('image/png', 1.0); // Use '1.0' for best quality
                        img[i].src = uri; // Update the image source
                        modifiedImages.push({
                            type: uri.substring(uri.indexOf(':') + 1, uri.indexOf(';')),
                            encoding: uri.substring(uri.indexOf(';') + 1, uri.indexOf(',')),
                            location: img[i].src,
                            data: uri.substring(uri.indexOf(',') + 1),
                        });
                    }
                }

                var mhtmlBottom = '\n';
                for (var i = 0; i < modifiedImages.length; i++) {
                    mhtmlBottom += '--NEXT.ITEM-BOUNDARY\n';
                    mhtmlBottom += 'Content-Location: ' + modifiedImages[i].location + '\n';
                    mhtmlBottom += 'Content-Type: ' + modifiedImages[i].type + '\n';
                    mhtmlBottom += 'Content-Transfer-Encoding: ' + modifiedImages[i].encoding + '\n\n';
                    mhtmlBottom += modifiedImages[i].data + '\n\n';
                }
                mhtmlBottom += '--NEXT.ITEM-BOUNDARY--';

                var fileContent = static.mhtml.top.replace('_html_', static.mhtml.head.replace('_styles_', styles) + static.mhtml.body.replace('_body_', markup.html())) + mhtmlBottom;

                var blob = new Blob([fileContent], {
                    type: 'application/msword;charset=utf-8',
                });
                saveAs(blob, fileName + '.doc');
            })
            .catch(function (error) {
                console.error(error);
            });
    };
})(jQuery);
