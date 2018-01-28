
/*
 * jQuery imarcomLoader v1.0
 *
 * Copyright (c) 2012 imarcom
 *
 */
(function($, window, undefined) {
    
    
    $.fn.imarcomLoader = function(options) {
        var default_options = {
            FPS: 5,
            cWidth: 41,
            cHeight: 41,
            cTotalFrames: 17,
            cFrameWidth: 41,
            topMax: 150,
            overlay: true,
            overlayColor: '#ffffff',
            center_by_percent: false,
            content: '',
            overlayOpacity: .6,
            onStart: function(){},
            onEnd: function(){}
        };
        
        
        return this.each(function() {
            var $element = $(this);
            var opts = $.extend(default_options, options);
            var $loader, $content, $overlay, cIndex=0, position, cXpos=0, _continue=true;
            
            if( $element.hasClass('imarcom_loader') ) {
                $element.find('.loader_content').html(opts.content);
                return;
            }
            
            $element.addClass('imarcom_loader');
            position = $element.css('position');
            $element.data('position', position);
            if( jQuery.inArray( position, ['absolute','relative'] )==-1 ) { //not in array
                $element.css('position','relative');
            }
            $loader = $(['<div class="sk-wandering-cubes">',
                        '<div class="sk-cube sk-cube1"></div>',
                        '<div class="sk-cube sk-cube2"></div>',
                '</div>'].join('')).hide().appendTo($element);
            if( opts.overlay ) {
                $overlay = $('<div class="loader_overlay"></div>').hide().appendTo($element);
            }
            
            $content = $('<div class="loader_content">' + opts.content + '</div>').hide().appendTo($element);
            
            $element.bind('loader.destroy', destroyLoad).bind('loader.resize', resize);
            
            startAnimation();
            
            function startAnimation() {
                var h = $element.outerHeight(),
                    w = $element.outerWidth();
                
                $loader.css({
                    position: 'absolute',
                    zIndex: 1100,
                    width: opts.cWidth + 'px',
                    height: opts.cHeight + 'px'
                }).show();
                
                if( opts.overlay ) {
                    $overlay.css({
                        width: 'auto', //more stable with right 0px
                        height: '100%',
                        position: 'absolute',
                        zIndex: 1001,
                        top: '0px',
                        left: '0px',
                        right: '0px', //more stable with width auto
                        backgroundColor: opts.overlayColor,
                        opacity: opts.overlayOpacity
                    }).show();
                }
                
                $content.css({
                    position: 'absolute',
                    width: '100%',
                    left: 0,
                    zIndex : 1100,
                    color: '#666',
                    textAlign: 'center'
                }).show().trigger('loader.resize');
                
                opts.onStart();
            }
            
            function resize() {
                var h = $element.outerHeight();
                var w = $element.outerWidth();
                $loader.css({
                    'left' : opts.center_by_percent ? '47%' : (w - opts.cWidth)/2 + 'px',
                    'top' : Math.min(opts.topMax, (h - opts.cHeight)/2) + 'px'
                });
                
                $content.css({
                    top: Math.min(opts.topMax, (h - opts.cHeight)/2) + opts.cHeight + 5
                });
            }
            
            function destroyLoad() {
                _continue = false;
                $('> .sk-wandering-cubes, > .loader_overlay, > .loader_content', $element).remove();
                $element.
                    removeClass('imarcom_loader').
                    unbind('loader').
                    css('position', '');
                
                opts.onEnd();
            }
            
            this.destroy = destroyLoad;
            
        });
        
    };
    
}(jQuery, window));
