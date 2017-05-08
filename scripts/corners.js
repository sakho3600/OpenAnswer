/**
 * -ms-border-radius
 * by David E. Still
 * contributors: Sim Albert (sim6)
 * 
 * simple border-radius implementation designed to provide functional border-radius for IE6, IE7 and IE8
 * 
 * Licensed under the MIT License
 */

function msbr(e) {
	if (typeof(e) == "string") e = document.getElementById(e);
	// we need currentStyle for this to work
	if (typeof(e.currentStyle) == 'undefined') { return; }
	// shortcut function for parseInt -- makes for smaller minified code
	function p(str) {
		return parseInt(str,10);
	}
	// not all elements can be round (thx, DD_roundies (http://www.dillerdesign.com/experiment/DD_roundies/))
	var allowed = {BODY: false, TABLE: false, TR: false, TD: false, SELECT: false, OPTION: false, TEXTAREA: false},
		s = e.style,
		cs = e.currentStyle;
	// only needed for IE
	if (navigator.appVersion.indexOf('MSIE') > 0 // only use with Internet Explorer
			// at least one corner must be defined
			&& (typeof cs['border-radius'] != 'undefined'
				|| typeof cs['border-radius-top-right'] != 'undefined'
				|| typeof cs['border-radius-top-left'] != 'undefined'
				|| typeof cs['border-radius-bottom-right'] != 'undefined'
				|| typeof cs['border-radius-bottom-left'] != 'undefined')
			&& allowed[e.nodeName] !== false // only apply to allowed element types
	) {
		// create necessary VML namespaces
		if (!document.namespaces.v) {
			if (document.documentMode != 8) document.createStyleSheet().addRule('v\\:*', "behavior: url(#default#VML);");
			else document.namespaces.add('v','urn:schemas-microsoft-com:vml','#default#VML');
		}
		if (isNaN(p(cs.borderWidth))) {
			e.style.borderWidth = '0px';
		}

		var br = cs['border-radius'],
			radius = p(br),
			arcSize = Math.min(
				radius / Math.min(e.offsetWidth, e.offsetHeight), 1),
			strokeColor = (cs.borderColor)?cs.borderColor:cs.backgroundColor,
			strokeWeight = (cs.borderWidth)?cs.borderWidth:'0',
			strokeTopWeight = (cs.borderTopWidth)?cs.borderTopWidth:'0',
			strokeBottomWeight = (cs.borderBottomWidth)?cs.borderBottomWidth:'0',
			strokeLeftWeight = (cs.borderLeftWidth)?cs.borderLeftWidth:'0',
			strokeRightWeight = (cs.borderRightWidth)?cs.borderRightWidth:'0',
			stroked = (p(cs.borderWidth))?' strokecolor="'+strokeColor+'" strokeweight="'+strokeWeight+'"':' stroked=false',

			// fill
			fillColor = cs.backgroundColor,
			fillSrc = cs.backgroundImage.replace(/^url\("(.+)"\)$/, '$1'),
			
			// positioning styles
			margin = cs.margin,
			styleFloat = cs.styleFloat,
			clear = cs.clear,
			position = cs.position,
			left = cs.left,
			right = cs.right,
			top = cs.top,
			bottom = cs.bottom,
			width = cs.width,
			height = cs.height,
			widthN = p(e.offsetWidth),
			heightN = p(e.offsetHeight),
			bgX = p(cs.backgroundPositionX),
			bgY = p(cs.backgroundPositionY),
			repeat = (cs.backgroundRepeat == 'no-repeat')?'frame':'tile',
			radii = {
				tl:p(cs['border-radius-top-left']),
				tr:p(cs['border-radius-top-right']),
				br:p(cs['border-radius-bottom-right']),
				bl:p(cs['border-radius-bottom-left'])
			},
			corners = (br+' -1 -1 -1 -1').replace(/\s+/, ' ').split(' ');

		for (var i=0; i < 4; i++) corners[i] = p(corners[i]);
		// determine discrete border radii 
		radii.tl = (radii.tl > -1)?radii.tl:(corners[0] > -1)?corners[0]:radius;
		radii.tr = (radii.tr > -1)?radii.tr:(corners[1] > -1)?corners[1]:radius;
		radii.br = (radii.br > -1)?radii.br:(corners[2] > -1)?corners[2]:(corners[0] > -1)?corners[0]:radius;
		radii.bl = (radii.bl > -1)?radii.bl:(corners[3] > -1)?corners[3]:(corners[1] > -1)?corners[1]:radius;

		// reset element positioning and background styles
		s.border = 'none';
		s.background = 'transparent';
		s.margin = '0';
		s.styleFloat = 'none';
		s.clear = 'none';
		s.position = 'static';
		s.left = '0';
		s.right = '0';
		s.top = '0';
		s.bottom = '0';
		s.width = 'auto';
		s.height = 'auto';

		// VML needs to be built as text for IE8
		var path = 'm '+radii.tl+',0' // start point
						+' qx 0,'+radii.tl
						+' l 0,'+(heightN - radii.bl)
						+' qy '+radii.bl+','+heightN
						+' l '+(widthN - radii.br)+','+heightN
						+' qx '+widthN+','+(heightN - radii.br)
						+' l '+widthN+','+radii.tr
						+' qy '+(widthN - radii.tr)+',0'
						+' x e',
			vml = '<v:group coordorigin="0 0" coordsize="'+widthN+' '+heightN+'" '
					+ 'style="position: relative; display: inline-block'
					+ '; width: ' + ( widthN - p(strokeLeftWeight) - p(strokeRightWeight) ) + 'px'
					+ '; height: ' + ( heightN - p(strokeTopWeight) - p(strokeBottomWeight) ) + 'px'
					+ '; antialias: true'
					+ '">'
					// build a shape for background color
					+ '<v:shape '
						+ stroked
						+ ' style="width: ' + widthN + 'px'
						+ '; height: ' + heightN + 'px'
						+ ';">'
							+ '<v:path pixelwidth="1" pixelheight="1" v="'+path+'" />'
							+ '<v:fill type="solid" color="'+fillColor+'" />'
						+ '</v:shape>'
					// build a shape for background image
					+ '<v:shape '
						+ stroked
						+ ' style="width: ' + ( widthN ) + 'px'
						+ '; height: ' + ( heightN ) + 'px'
						+ ' ">'
							+ '<v:path pixelwidth="1" pixelheight="1" v="'+path+'" />'
							+ '<v:fill src="' + fillSrc + '" type="'+repeat+'" color="'+fillColor+'" position="'+(bgX / widthN - 0.5)+','+(bgY / heightN - 0.5)+'" />'
						+ '</v:shape>'
					+ '</v:group>';
		// build container element; a made-up element still works in IE and is probably *not* going to be affected by any errant styles
		var div = document.createElement('ignore'), ds = div.style;
		div.className = 'rounded';
		ds.background = 'transparent';
		ds.border = 'none';
		ds.padding = 0;
		ds.margin = margin;
		ds['float'] = styleFloat;
		ds.clear = clear;
		ds.position = position;
		ds.left = left;
		ds.right = right;
		ds.top = top;
		ds.bottom = bottom;
		ds.width = (p(width))?p(width) + Math.floor((p(strokeLeftWeight) + p(strokeRightWeight))/2):width;
		ds.height = (p(height))?p(height) + Math.floor((p(strokeTopWeight) + p(strokeBottomWeight))/2):height;
		ds.display = cs.display;
		// insert the new element into the DOM
		e.parentNode.insertBefore(div,e);

		// get image dimensions for non-tiled backgrounds
		if (fillSrc != "none" && p(navigator.appVersion.split('MSIE')[1]) < 8) { // don't size or position for IE8; not yet supported
			var img = new Image(), iLoaded = false;
			vml = vml.split('position=').join('size="1pt,1pt" position=');
			img.onload = function(){
				var x = (bgX / (widthN - (p(img.width)/2)) - 0.5 + (p(img.width)/widthN/2)),
					y = (bgY / (heightN - (p(img.height)/2)) - 0.5 + (p(img.height)/heightN/2));
				div.childNodes[0].childNodes[1].childNodes[1].setAttribute('size', p(img.width)+'px,' + p(img.height)+'px');
				div.childNodes[0].childNodes[1].childNodes[1].setAttribute('position',x+','+y);
			};
			img.src = fillSrc;
		}

		// write the VML
		div.innerHTML = vml;
		// move the old element
		div.childNodes[0].childNodes[1].appendChild(e);
	}
}
