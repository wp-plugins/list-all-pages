	jQuery(document).ready(function()	{
		jQuery('li.toplevel_page_show-all-button').append(jQuery("#tjg-show-all"));
		//jQuery('#adminmenu').prepend(jQuery("#tjg-show-all"));

		var depths = new Array();
		jQuery(".pagelist").find("dd").each(function()	{
			var depthNum = jQuery(this).attr("class").split("-");
			depths.push(depthNum[1]);
		});
		depths.sort();
		depths.reverse();
		var deepest = depths[0];
		if (deepest < 5)	{
			for (i = 1; i <= deepest; i++)	{
				jQuery(".depth-" + i).css('paddingLeft', (i-1)*10);
			}
		}
		else	{
			for (i = 1; i <= 5; i++)	{
				jQuery(".depth-" + i).css('paddingLeft', (i-1)*10);
			}
		}
		
		var topLevels = jQuery(".pagelist").length;

		function clearFloats()	{
			jQuery(".pagelist").css('clear', 'none');
			var areaWidth = jQuery(window).width()-20;
			jQuery("#tjg-show-all").width(areaWidth);
			var listWidths = new Array();
			var currentWidth = 0;
			jQuery(".pagelist").each(function()	{
				var theWidth = jQuery(this).outerWidth(true);
				listWidths.push(theWidth);
			});

			for (i = -1; i <= topLevels; i++)	{
				var nextList = i+1;
				currentWidth += listWidths[nextList];
				if (currentWidth > areaWidth)	{
					jQuery(".pagelist").eq(nextList).css('clear', 'left');
					jQuery("#tjg-show-all").width(areaWidth);
					break;
				}
				else	{
					jQuery("#tjg-show-all").width(currentWidth);
				}
			}
		}
		
		jQuery(".toplevel_page_show-all-button > a").toggle(function(event) {
			if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; }
			jQuery(this).closest("#adminmenu").find("#tjg-show-all").show();
			clearFloats()
		}, function(event) {
			if (event.preventDefault) { event.preventDefault(); } else { event.returnValue = false; }
			jQuery(this).closest("#adminmenu").find("#tjg-show-all").hide();
		});		
		
		jQuery(window).resize(function()	{
			clearFloats();
		});
	});