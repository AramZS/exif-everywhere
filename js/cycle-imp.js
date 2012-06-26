			jQuery(document).ready(function () {
			
			
				var thumbs = jQuery('#cycleContainer div.slide').find("img").map(function() { return jQuery(this).attr('src'); });

				jQuery('#cycleContainer').after('<ul id="cycle-nav" class="cyclenav"></ul>');
				
				jQuery('#cycle-nav').before('<div id="cycle-prev" class="cyclenav prev"><a class="prev" href="#"><span class="arrow-w prev"></span></a></div>').after('<div id="cycle-next" class="cyclenav next"><a class="next" href="#"><span class="arrow-e next"></span></a></div></div>');
				
			
				jQuery('#cycleContainer').cycle({
					fx: 'fade',
					autostop: false,
					delay: 2000,
					timeout: 7000,
					pause: true,
					slideResize: true,
					containerResize: false,
					width: '100%',
					fit: 1,
					prev: '.prev',
					next: '.next',

					pager: '#cycle-nav',
					pagerEvent: 'mouseover',
					fastOnEvent: true,
					pagerAnchorBuilder: function (index) {
						return '<li><a href="#"><img src="' + thumbs[index] + '" width="50" height="50" /></a></li>';
						},
						updateActivePagerLink: function(pager, currSlideIndex) {
							jQuery(pager).find('li').removeClass('active').filter('li:eq('+currSlideIndex+')').addClass('active');
						}
						

				});

				jQuery('.cyclenav').wrapAll('<div class="nav-container"></div>');
				// jQuery('#cycle-next').after('</div>');
				
			});
			
			
//function(idx, slide) {
	//				
		//				var img = jQuery(slide).closest('.slider-img').find('img').attr('src');
			//			return '<li><a href="#"><img src="' + img + '" width="50" height="50" /></a></li>';
				//	
					//}