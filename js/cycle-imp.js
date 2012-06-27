			jQuery(document).ready(function () {
			
				var counter = 0;
				var thumbs = jQuery('.cycleContainer div.slide').find("img").map(function() { return jQuery(this).attr('src'); });

				jQuery('.cycleContainer').after('<ul id="cycle-nav" class="cyclenav scrollable"></ul>');
				
				jQuery('#cycle-nav').before('<div class="cyclenav prev"><a class="prev" href="#"><span class="arrow-w prev"></span></a></div>').after('<div class="cyclenav next"><a class="next" href="#"><span class="arrow-e next"></span></a></div></div>');
				
				//Making individual slides linkable via http://jquery.malsup.com/cycle/perma.html
			
				
					var index = 0, hash = window.location.hash;
					if (hash) {
					
						index = /\d+/.exec(hash)[0];
						index = (parseInt(index) || 1) - 1;
					
					}
				
				
			
				jQuery('.cycleContainer').cycle({
					fx: 'fade',
					autostop: false,
					startingSlide: index,
					timeout: 7000,
					pause: true,
					slideResize: true,
					containerResize: false,
					width: '100%',
					fit: 1,
					prev: '.sliderPrev',
					next: '.sliderNext',
					after: function(curr,next,opts){
								window.location.hash = opts.currSlide + 1;
							},
					pager: '#cycle-nav',
					pagerEvent: 'mouseover',
					fastOnEvent: true,
					pagerAnchorBuilder: function (index) {
						return '<li class="item"><a href="#"><img src="' + thumbs[index] + '" width="50" height="50" /></a></li>';
						},
						updateActivePagerLink: function(pager, currSlideIndex) {
						
						//Have to include this if else statement because jQuery Tools is causing a conflict. 
						//The conflict is that it clones the first item and puts it on the end and
						// does the opposite for the last item. 
							var countThumbs = (thumbs.length);
						//A thought - we could use this variable to determine maximum required width
						//And if the nav isn't that width, don't bother executing scrollable.
							
							
							if (currSlideIndex == 0){ 
								currSlideIndex = 0;
							} else {
							
							currSlideIndex = currSlideIndex + 1;
							
							}
							
							if (counter == countThumbs ) {
							
								currSlideIndex = 1;
								counter = 0;
						
							} 
							jQuery(pager).find('li').removeClass('active').filter('li:eq('+(currSlideIndex)+')').addClass('active');
							counter = counter+1;
							
						}
						

				});

				jQuery('.cyclenav').wrapAll('<div class="nav-container"></div>');
				jQuery('.item').wrapAll('<div class="items"></div>');
				// jQuery('#cycle-next').after('</div>');
				
	jQuery(function() {
	  // initialize scrollable
	  //This will only take .prev and .next as navigation items, so can't use those above. 
		jQuery(".scrollable").scrollable({circular: true, mousewheel: true}).autoscroll({
			interval: 6900
		});
	
		//Scrollable API usage at http://www.jquerytools.org/documentation/scrollable/autoscroll.html
		//Method of use http://www.jquerytools.org/documentation/scripting.html
		//May need to implement this - http://www.jquerytools.org/forum/tools/35/98118
	
		var scrollableapi = jQuery(".scrollable").data("scrollable");
		
		//This is a nasty fix for a stupid problem. 
		jQuery(".cloned").addClass('hidden');
		
		
		//We need to do these as buttons so they don't screw up the linkable hash.
		jQuery('#pauseButton').click(function() { 
			jQuery('.cycleContainer').cycle('pause'); 
			scrollableapi.stop();
		});
		
		jQuery('#resumeButton').click(function() { 
			jQuery('.cycleContainer').cycle('resume'); 
			scrollableapi.play();
		});
		
		jQuery('.scrollable').mouseenter(function() {
			jQuery('.cycleContainer').cycle('pause');
		}).mouseleave(function(){
			jQuery('.cyclerContainer').cycle('resume');
		
		});
		
		jQuery('.cycleContainer').mouseenter(function() {
			scrollableapi.pause();

		}).mouseleave(function(){
			scrollableapi.play();
			//This creates additional delay before restarting. Dunno why. Need to fix. 
			
		});
		

		
	});

				
});
			
			
//function(idx, slide) {
	//				
		//				var img = jQuery(slide).closest('.slider-img').find('img').attr('src');
			//			return '<li><a href="#"><img src="' + img + '" width="50" height="50" /></a></li>';
				//	
					//}