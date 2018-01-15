;(function($){

	$.isEnabled = function(){
	    var o = this, callee = arguments.callee;
		if(!(o instanceof callee)) return new callee(o,arguments);
		var body  = $('body'),isHide = body.is(':hidden');
		!isHide || body.show();
		for(var i in o) if(typeof o[i] == 'function') callee[i] = o[i]();
		!isHide || body.hide();
	}

	$.isEnabled.prototype.htmlScroll = function(){
		var html = $('html'), top = html.scrollTop();
		var el = $('<div/>').height(10000).prependTo('body');
		html.scrollTop(10000);
		var rs = !!html.scrollTop();
		html.scrollTop(top);
		el.remove();
		return rs;
	}

	$.isEnabled.prototype.positionFixed = function(){
		var el = $('<div/>').add('<div/>').css({height:8,position:'fixed',top:0}).prependTo('body');
		var rs = el.eq(0).offset().top == el.eq(1).offset().top
		el.remove();
		return rs;
	};

	$.isEnabled.prototype.displayInlineBlock = function(){
		var el = $('<div/>').add('<div/>').css({height:8,display:'inline-block'}).prependTo('body');
		var rs = el.eq(0).offset().top == el.eq(1).offset().top
		el.remove();
		return rs;
	};

})(jQuery);
