jQuery(document).ready(function ($) {
	'use strict';

	//Social sharer - taken from, with permission, Swifty bar plugin made by Goran Jakovljevic http://www.itsgoran.com/swiftybar/

	$.fn.socShare = function(opts) {
    	var $this = this;
    	var $win = $(window);

    	opts = $.extend({
    		facebook : false,
    		google_plus : false,
    		twitter : false,
    		linked_in : false,
    	}, opts);

    	for(var opt in opts) {

    		if(opts[opt] === false) {
    			continue;
    		}

    		var url;
			var name;

    		switch (opt) {
    			case 'facebook':
    				url = 'https://www.facebook.com/sharer/sharer.php?u=';
    				name = 'Facebook';
    				_popup(url, name, opts[opt], 400, 640);
    				break;

    			case 'twitter':
                    var posttitle = $('.event_talk-share-tw').data('title');
                    url = 'https://twitter.com/intent/tweet?&text='+posttitle+'&hashtags=event,talk&url=';
    				name = 'Twitter';
    				_popup(url, name, opts[opt], 440, 600);
    				break;

				case 'google_plus':
    				url = 'https://plus.google.com/share?url=';
    				name = 'Google+';
    				_popup(url, name, opts[opt], 600, 600);
    				break;

    			case 'linked_in':
    				url = 'https://www.linkedin.com/shareArticle?mini=true&url=';
    				name = 'LinkedIn';
    				_popup(url, name, opts[opt], 570, 520);
    				break;

				default:
					break;
    		}
    	}

		function isUrl(data) {
            var regexp = new RegExp( '(^(http[s]?:\\/\\/(www\\.)?|ftp:\\/\\/(www\\.)?|(www\\.)?))[\\w-]+(\\.[\\w-]+)+([\\w-.,@?^=%&:/~+#-]*[\\w@?^=%&;/~+#-])?', 'gim' );
            return regexp.test(data);
        }

    	function _popup(url, name, opt, height, width) {
    		if(opt !== false && $this.find(opt).length) {
				$this.on('click', opt, function(e){
					e.preventDefault();

					var top = (screen.height/2) - height/2;
					var left = (screen.width/2) - width/2;
					var share_link = $(this).data('event');

					if(!isUrl(share_link)) {
						share_link = window.location.href;
					}

					window.open(
						url+encodeURIComponent(share_link),
						name,
						'menubar=no,toolbar=no,resizable=yes,scrollbars=yes,height='+height+',width='+width+',top='+top+',left='+left
					);

					return false;
				});
			}
    	}
    	return;
	};

	$('.event_talk_meta_share_icons').socShare({
		facebook : '.event_talk-share-fb',
		twitter : '.event_talk-share-tw',
		google_plus : '.event_talk-share-gplus',
		linked_in : '.event_talk-share-linked',
	});

	//Tab Change
	function tab_change(){
		var $this = $(this);
		var tab_no = $this.data('tab');

		$this.addClass('active');
		$this.siblings().removeClass('active');

 		$('.event_talk_wrapper').find('.tab_content.active').removeClass('active');
 		$('#'+tab_no).addClass('active');


	}

	//Widget Tab Change
	function widget_tab_change(){
		var $this = $(this);
		var tab_no = $this.data('tab');

		$this.addClass('active');
		$this.siblings().removeClass('active');

 		$('.event_talk_widget').find('.tab_content.active').removeClass('active');
 		$('#widget_'+tab_no).addClass('active');


	}

	var active_tab_width = $('.event_talk_tab_container .active').outerWidth();
	$('.magic_line').css('left', active_tab_width/2+'px');

	var window_width = $(window).width();

	$(document).on('click', '.event_talk_wrapper .tab', tab_change)
			   .on('click', '.event_talk_widget .tab', widget_tab_change);

});
