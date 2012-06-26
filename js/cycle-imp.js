			jQuery(document).ready(function () {
			
			
				var thumbs = jQuery('.cycleContainer div.slide').find("img").map(function() { return jQuery(this).attr('src'); });

				jQuery('.cycleContainer').after('<ul id="cycle-nav" class="cyclenav scrollable"></ul>');
				
				jQuery('#cycle-nav').before('<div id="cycle-prev" class="cyclenav prev"><a class="prev" href="#"><span class="arrow-w prev"></span></a></div>').after('<div id="cycle-next" class="cyclenav next"><a class="next" href="#"><span class="arrow-e next"></span></a></div></div>');
				
				//Making individual slides linkable via http://jquery.malsup.com/cycle/perma.html
				jQuery(function() {
				
					var index = 0, hash = window.location.hash;
					if (hash) {
					
						index = /\d+/.exec(hash)[0];
						index = (parseInt(index) || 1) - 1;
					
					}
				
				});
			
				jQuery('.cycleContainer').cycle({
					fx: 'fade',
					autostop: false,
					timeout: 7000,
					pause: true,
					slideResize: true,
					containerResize: false,
					width: '100%',
					fit: 1,
					prev: '.prev',
					next: '.next',
					after: function(curr,next,opts){
								window.location.hash = opts.currSlide+1;
							},
					pager: '#cycle-nav',
					pagerEvent: 'mouseover',
					fastOnEvent: true,
					pagerAnchorBuilder: function (index) {
						return '<li class="item"><a href="#"><img src="' + thumbs[index] + '" width="50" height="50" /></a></li>';
						},
						updateActivePagerLink: function(pager, currSlideIndex) {
						
						
							if (currSlideIndex == 0){ 
								currSlideIndex = 0;
							} else {
							
							currSlideIndex = currSlideIndex + 1;
							
							}
							jQuery(pager).find('li').removeClass('active').filter('li:eq('+(currSlideIndex)+')').addClass('active');
						}
						

				});

				jQuery('.cyclenav').wrapAll('<div class="nav-container"></div>');
				jQuery('.item').wrapAll('<div class="items"></div>');
				// jQuery('#cycle-next').after('</div>');
				
	jQuery(function() {
	  // initialize scrollable
	  //This will only take .prev and .next as navigation items, so can't use those above. 
		$(".scrollable").scrollable({circular: true, mousewheel: true}).autoscroll({
			interval: 7000
		});
	});
				
			});
			
			
//function(idx, slide) {
	//				
		//				var img = jQuery(slide).closest('.slider-img').find('img').attr('src');
			//			return '<li><a href="#"><img src="' + img + '" width="50" height="50" /></a></li>';
				//	
					//}