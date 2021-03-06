YUI().use('node', 'cookie', "event-resize", function(Y) {

// removes no-js class and adds yes-js if js
core.setupHtmlClass = function() {
	Y.all('html').removeClass('no-js').addClass('yes-js');
	//document.domain = 'ubuntu.com';
}

core.resizeListener = function() {
	Y.on('windowresize', function(e) {
		core.redrawGlobal();
	});
	core.globalInit();
};

core.globalInit = function() {
	if (document.documentElement.clientWidth < 768) {
		core.globalPrepend = 'footer.global';
		core.setupGlobalNav();
		Y.one('.nav-global-wrapper').insert('<h2>Ubuntu websites</h2>','before');
			Y.one('#nav-global h2').setStyle('cursor', 'pointer').append('<span></span>').on('click',function(e) {
				this.toggleClass('active');
				this.next('div').toggleClass('active');
			});
			Y.one('#nav-global-wrapper-link').on('click',function(e) {
				Y.one('.nav-global-wrapper').addClass('active');
				Y.one('#nav-global h2').addClass('active');
			});
	} else if (document.documentElement.clientWidth >= 768) {
		core.globalPrepend = 'body';
		core.setupGlobalNav();
	}
};

core.redrawGlobal = function() {
	var globalNav = Y.one("#nav-global");
	if (document.documentElement.clientWidth < 768 && core.globalPrepend != 'footer.global') {
		core.globalPrepend = 'footer.global';
		if (globalNav) {
			globalNav.remove();
			core.setupGlobalNav();
			Y.one('.nav-global-wrapper').insert('<h2>Ubuntu websites</h2>','before');
			Y.one('#nav-global h2').setStyle('cursor', 'pointer').append('<span></span>').on('click',function(e) {
				this.toggleClass('active');
				this.next('div').toggleClass('active');
			});
		}
			Y.one('#nav-global-wrapper-link a').on('click',function(e) {
				Y.one('.nav-global-wrapper').addClass('active');
				Y.one('#nav-global h2').addClass('active');
				console.log('yes');
			});
	} else if (document.documentElement.clientWidth >= 768 && core.globalPrepend != 'body') {
		core.globalPrepend = 'body';
		Y.one( "#nav-global-wrapper-link" ).remove();
		if (globalNav) {
			globalNav.remove();
			core.setupGlobalNav();
		}
	}
};

core.cloneSearch = function() {
	var searchNav = Y.one("#box-search");
	searchNav.appendTo('header.global');
};

core.navInit = function() {
	var mainNav = Y.one("#main-navigation");
		if (document.documentElement.clientWidth < 768) {
			mainNav.appendTo('header.global');
			core.mobileNav();
		} else if (document.documentElement.clientWidth >= 768) {
			mainNav.appendTo('.header-inner');
		}
};

core.redrawNav = function() {
	var mainNav = Y.one("#main-navigation");
		if (document.documentElement.clientWidth < 768) {
			Y.one("#main-navigation").appendTo('header.global');
			core.mobileNav();
		} else if (document.documentElement.clientWidth >= 768) {
			mainNav.appendTo('.header-inner');
		}
};

core.cookiePolicy = function() {
	function open() {
		YUI().use('node', function(Y) {
			Y.one('body').prepend('<div class="cookie-policy"><div class="wrapper"><a href="?cp=close" class="link-cta">Close</a><p>We use cookies to improve your experience. By your continued use of this site you accept such use. To change your settings please <a href="/privacy-policy#cookies">see our policy</a>.</p></div></div>');
			Y.one('footer.global .legal').addClass('has-cookie');
			Y.one('.cookie-policy .link-cta').on('click',function(e){
				e.preventDefault();
				close();
			});
		});
	}
	function close() {
		YUI().use('node', function(Y) {
			Y.one('.cookie-policy').setStyle('display','none');
			Y.one('footer.global .legal').removeClass('has-cookie');
			setCookie();
		});
	}
	function setCookie() {
		YUI().use('cookie', function (Y) {
			Y.Cookie.set("_cookies_accepted", "true", {
				path: "/",
				expires: new Date("January 12, 2025")
			});
		});
	}
	if(Y.Cookie.get("_cookies_accepted") != 'true'){
		open();
	}
};

core.setUpNav = function() {
	Y.one( "#main-navigation" ).appendTo( "header.global" );
};

core.mobileNav = function() {
	Y.all('.nav-toggle').on('click', function(e) {
		if(Y.all('#box-search div').hasClass('active')) {
			Y.all('#box-search div, .search-toggle').removeClass('active');
		}
		Y.all('#main-navigation div, .nav-toggle').toggleClass('active');
		e.preventDefault();
		Y.one('#s').blur();
	});

	Y.all('.nav-toggle').on('clickoutside', function(e) {
    Y.all('#main-navigation div, .nav-toggle').removeClass('active');
	});

};

core.mobileSearch = function() {
	Y.all('.search-toggle').on('click', function(e) {
		if(Y.all('#main-navigation div').hasClass('active')) {
			Y.all('#main-navigation div, .nav-toggle').removeClass('active');
		}
		Y.all('#box-search div, .search-toggle').toggleClass('active');
		Y.one('input#s').focus(),9000;
		e.preventDefault();
	});

	Y.all('.search-toggle').on('bluroutside', function(e) {
    Y.all('#box-search div, .search-toggle').removeClass('active');
	});

};

core.svgFallback = function() {
	if (!Modernizr.svg || !Modernizr.backgroundsize) {
	 	Y.all("img[src$='.svg']").each(function(node) {
	 		node.setAttribute("src", node.getAttribute('src').toString().match(/.*\/(.+?)\./)[0]+'png');
	 	});
	}
};

core.downloadAlert = function() {
	if (Y.one(".alert")) {
		Y.one(".alert").append("<a href='#' id='close-alert'>close</a>");
		Y.one('#close-alert').on('click', function(e) {
		  Y.one('.alert').hide(true);
			e.preventDefault();
		});
	}
};

core.externalLinks = function() {
	Y.all('a').filter(function() {
	   return this.hostname && this.hostname !== location.hostname;
	}).addClass( "external" );
	Y.all("a:visited").addClass( "external-visited" );

	Y.all("a:has(img)").filter(function() {
	   return !Y.Lang.trim(Y.one(this).text()).length;
	}).addClass("external-img");
};

core.businessCard = function() {
	var $body = Y.one('body');
	if ($body.hasClass('page-template-page-topics-php') || $body.hasClass('tax-topic')) {
		Y.one(".glossary-box .inner-wrapper").append("<a class='open-message toggle-message' href='#'>show</a><a class='close-message toggle-message' href='#'>close</a>");

		var menu_state = Y.Cookie.get('_business_card');

	  if( menu_state == "visible" ) {
	    Y.one(".glossary-box div div").addClass('active');
			Y.one(".open-message").removeClass('active');
			Y.one(".close-message").addClass('active');

	  } else if( menu_state == "hidden" ) {

			Y.all(".glossary-box div div").removeClass('active');
			Y.one(".close-message").removeClass('active');
			Y.one(".open-message").addClass('active');

	  } else {

		  Y.one('.glossary-box div div').addClass('active');
		  Y.one(".close-message").addClass('active');
	  }

		Y.one('.close-message').on('click', function(e) {
			Y.one(".glossary-box div div").removeClass('active');
			this.removeClass('active');
			Y.one(".open-message").addClass('active');
	    Y.Cookie.set('_business_card', 'hidden', { path: "/", expires: new Date("January 12, 2025") }); // update (or set) the cookie
			e.preventDefault();
		});

		Y.one('.open-message').on('click', function(e) {
			Y.one(".glossary-box div div").addClass('active');
			this.removeClass('active');
			Y.one(".close-message").addClass('active');
			e.preventDefault();
		});
	}
}

core.searchFilter = function() {
	Y.all('.no-filter-wrap .search-filter h2').setStyle('cursor', 'default');
	Y.all('.no-filter-wrap .search-filter h2').setStyle('cursor', 'pointer').append('<span></span>').on('click', function(e) {
		Y.all('.search-filter div.search-inner, .filter-toggle, .search-filter .search-submit, .search-filter h2').toggleClass('active');
		e.preventDefault();
	});
};

core.resizeListener();
core.cookiePolicy();
core.setupHtmlClass();
//core.cloneSearch();
//core.mobileNav();
//core.setUpNav();
//core.mobileSearch();
core.svgFallback();
core.downloadAlert();
core.externalLinks();
//core.imageSquircle();
//core.startCarousel();
//core.businessCard();
core.searchFilter();

});
