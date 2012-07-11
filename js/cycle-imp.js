jQuery(document).ready(function () {
			
					var slideHeight = 0;
					//Figure out the height of the nav and add to total height?
					jQuery('.slide').each(function (i) {
						var checkheight = jQuery(this).height();
						if (checkheight > slideHeight) { slideHeight = checkheight; jQuery('.cycleContainer').height(slideHeight); }
						
					});

			
				var counter = 0;
				var thumbs = jQuery('.cycleContainer div.slide').find("img").map(function() { return jQuery(this).attr('src'); });

				jQuery('.cycleContainer').after('<ul id="cycle-nav" class="cyclenav scrollable"></ul>');
				
				jQuery('#cycle-nav').before('<div class="cyclenav prev"><a class="prev" href="#"><span class="arrow-w prev"></span></a></div>').after('<div class="cyclenav next"><a class="next" href="#"><span class="arrow-e next"></span></a></div></div>');
				
				//Making individual slides linkable via http://jquery.malsup.com/cycle/perma.html
				//Due to jQuery tools if you come in on load, wrong active is set. 
				
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
		
		jQuery('#exifButton').toggle(
		
			function(){
				jQuery('.exif-data').removeClass('disappear'); 
			},
			function() { 
				jQuery('.exif-data').addClass('disappear'); 
			}
			
		);
		
		//When we create the fullscreen, let's pause the thing beneath it. We can resume it later on clicking the full screen close button.
		//Based off of code at http://webdesign.tutsplus.com/tutorials/htmlcss-tutorials/super-simple-lightbox-with-css-and-jquery/
		jQuery('#fullButton').click(function(e) { 
			e.preventDefault();
			if (jQuery('#fullbox').length > 0) {
				jQuery('#contained').html('<div class="overall-full-cycle-container"></div>')
			} else {
			
				var fullbox =
					'<div id="fullbox">' +
						'<div id="contained">' + //insert clicked link's href into img src
							'<center><div class="overall-full-cycle-container"></div></center>' +
						'</div>' +
						'<div id="full-close"><button type="button" class="close-button">Close</button></div>' +
					'</div>';
				 
					//insert lightbox HTML into page
					jQuery('body').append(fullbox);
					

			}
			
			//jQuery('.overall-cycle-contained').clone(true).appendTo('.overall-full-cycle-container')
			var cycleToCopy = jQuery('.overall-cycle-contained');
			var cycleCopy = jQuery.extend(true, {}, cycleToCopy);
			jQuery(cycleCopy).appendTo('.overall-full-cycle-container');
			jQuery('#fullButton').addClass('disappear'); //Can't go full screen from full screen.
			
			/**scrollableapi.pause();
			jQuery('.overall-cycle-container .cycleContainer').cycle('pause');**/
			
			//Also, let's avoid visual conflict disappearing the underneath item to reappear it later. 
			jQuery('.overall-cycle-container').addClass('hidden');
			
			jQuery('.close-button').click(function() {
			
				//I'm not sure why the above extend is not properly cloning instead of moving.
				//Have to look further at documentation.
				//Until then, here's a stupid fix. 
				var cycleToCopy = jQuery('.overall-cycle-contained');
				var cycleCopy = jQuery.extend(true, {}, cycleToCopy);
				jQuery(cycleCopy).appendTo('.overall-cycle-container');
				/** 	A lot of these lightbox scripts just sort of store the container for later.
						However, I don't think it is likely in this case that users are going to go
						and reopen fullscreen after they have closed it. So let's just get rid of the thing.
				**/
				jQuery('#fullbox').remove();
				jQuery('.overall-cycle-container').removeClass('hidden');
				jQuery('#fullButton').removeClass('disappear'); 
				jQuery('.overall-cycle-container .cycleContainer').cycle('resume');

			
			});
			
		});
		
	});
	
	jQuery(function() {					
		
		jQuery('.slide').mouseenter(function() {
		
			jQuery('.slider-social-box').removeClass('disappear');
			
		}).mouseleave(function(){
			
			jQuery('.slider-social-box').addClass('disappear');
			
		});
	
	});
	
	jQuery(function() {
			jQuery('.shareButton').click(function(e) {
				e.preventDefault();
				var cycleToShare = jQuery('.overall-cycle-contained');
				var cycleShare = jQuery.extend(true, {}, cycleToShare);
				//Need to get the javascript external links, add them together with the code and put it into one package.
				//No need to pull any of the php involved. Just the final generated code. 
			});
	});
});
			
			
//function(idx, slide) {
	//				
		//				var img = jQuery(slide).closest('.slider-img').find('img').attr('src');
			//			return '<li><a href="#"><img src="' + img + '" width="50" height="50" /></a></li>';
				//	
					//}