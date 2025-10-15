/*
...torment kittens here...
*/

window.addEventListener('scroll', onWindowScroll);
window.addEventListener('scroll', showVisible);
window.addEventListener('DOMContentLoaded', onDomReady);
window.addEventListener('mousedown', onWindowClick);

/*
@returns true if provided substring(s) are found in the string
@returns false if not
*/
String.prototype.found = function(){
  for (let i = 0, l = arguments.length; i < l; i++)
    if(this.indexOf(arguments[i]) !== -1)
      return 1;
  return 0;
};

/**
* Catches all clicks
* @param {object} e - Event Object
*/
function onWindowClick(e){
  let target = e.target;
  
  for(let key in handlers){
    if(target.closest('.' + key)){
      handlers[key](target.closest('.' + key), e);
      break;
    }
  }
}

const initBestStudents = () => {
  const lists = document.querySelectorAll('.certificates-list:not(.slick-initialized)');
  const links = document.querySelectorAll('.author-certificates');

  lists.forEach((slider) => {
    if(!slider.children.length) return;

    const $slider = $(slider);
    
    $slider.slick({
      slidesToShow: 1,
      slidesToScroll: 1,
      dots: false,
      arrows: false,
      lazyLoad: 'progressive',
      draggable: false,
      infinite: false
    });
  });

  const onLinkClick = function(){
    const listIndex = this.getAttribute("data-list");
    const list = this.parentNode.querySelectorAll('.certificates-list')?.[listIndex];

    if(!list) return

    const $slider = $(list);
    const $img = $(list.querySelector("img"));

    SlickGallery.showGallery($slider, $img);
  }

  links.forEach((link) => {
    link.addEventListener("click", onLinkClick);
  })
}

const animateNumbers = () => {
  const numbers = document.querySelectorAll('.popup__number-digit');
  let interval;

  setTimeout(() => {
    interval = setInterval(() => {

      numbers.forEach(number => {
        let start = +number.innerHTML;
        let end = +number.dataset.max;
  
        if(start != end) number.innerHTML = ++start;
      })
      
    }, 60);
  }, 600);

  setTimeout(() => {
    clearInterval(interval);
  }, 8000);
}

const initPopup = () => {
  const popup = document.querySelector(".popup");
  const isShown = sessionStorage.getItem("popup") === "shown";
  const popupClose = document.querySelector(".popup__close");

  if(!isShown
    && window.location.href.indexOf("about/specialties/125-bachelor/") === -1
    && window.location.href.indexOf("svitnia-prohrama-informatsijni-systemy-ta-tekhnolohii-kvalifikatsij") === -1
    && window.location.href.indexOf("about/specialties/172-bachelor-2") === -1) popup.classList.add("popup--visible");

  if (!document.hidden) animateNumbers();
  else {
    window.onfocus = () => {
      animateNumbers(); 
      window.onfocus = null;
    }; 
  }

  popupClose.addEventListener('click', () => {
    popup.classList.remove("popup--visible");
    sessionStorage.setItem("popup", "shown");
  })
}

function onDomReady(){
  let URL = window.location.pathname.substr(1);
  
  try {
    if(URL.found('news/')) _get('.menu__item').classList.add('menu__item-active');
    if(URL.found('teaching-staff')) get('#menu-menu-ua > .menu__item')[2].classList.add('menu__item-active');
  } catch (e) {}

  search.init();
  
  setSubmenuActiveIndex();
  initBestStudents();
  initPopup();
}

function setSubmenuActiveIndex(){
  let URL = window.location.pathname.substr(4).slice(0, -1);
  let link = _get('.main__sidebar a[href*="' + URL + '"]');
  if(link) link.classList.add('active');
}

let search = (() => {
  let _config = {
    searchDelay: 250
  };

  let _setInputCursor = () => {
    let input = _get('.search input');
    let inputLength = input.value.length;
    input.focus();
    input.selectionStart = inputLength;
  };

  let _setNewURL = () => {
    let inputValue = _get('.search input').value;
    let newHref = '/search/' + (inputValue ? inputValue + '/' : '');
    history.pushState({'page': newHref}, null, newHref);
  }

  let _displayResult = (html) => {
    _get('.search__result').innerHTML = html;
  };

  let _search = () => {
    let inputValue = _get('.search input').value;
    let data = {
      'value': inputValue
    };
  
    ajax.post('/wp-content/themes/nure/php/functions/getSearchResult.php', 'POST', data, _displayResult);
  }

  let doSearch = () => {
    _setNewURL();

    clearTimeout(_config.timer);

    if(!_get('.search__result .lds'))
      _get('.search__result').innerHTML = `<div class="lds"><div class="lds-ripple"><div></div><div></div></div></div>`;

    _config.timer = setTimeout(function(){
      _search();
    }, _config.searchDelay);
  };

  let init = () => {
    let URL = window.location.pathname.substr(1);
  
    if(!URL.found('search')) return;

    let query = _get('.search input').value;
    _get('.search input').addEventListener('input', doSearch);
    _setInputCursor();

    if(query.length) doSearch(query);
  }

  return {
    query: doSearch,
    init: init
  };
})();

let newDozeOfPosts = 1;
let mobile = $('body').attr('data-mobile');
let postsNumber = +mobile ? 4 : 10;
let offset = postsNumber;
let newsCategory = 0;

let URL = window.location.pathname.substr(1);
  
if(URL.found('participation-in-conferences')) newsCategory = 1;
if(URL.found('international-cooperation')) newsCategory = 2;
if(URL.found('workshops-and-meetings')) newsCategory = 3;
if(URL.found('sport')) newsCategory = 4;
if(URL.found('development-of-educational-programs')) newsCategory = 5;
if(URL.found('monographs')) newsCategory = 6;
if(URL.found('educational-and-scientific-achievements')) newsCategory = 7;
if(URL.found('sports-and-artistic-achievements')) newsCategory = 8;
if(URL.found('achievements-of-the-department')) newsCategory = 9;
if(URL.found('about/international-cooperation')) newsCategory = 10;
if(window.location.pathname.match(/international-cooperation\/.+\//gi) != null) newsCategory = 11;
if(URL.found('about-us')) newsCategory = 12;
if(URL.found('exchange-and-sub-diploma-programs')) newsCategory = 13;
if(URL.found('excursions-and-trips')) newsCategory = 14;
if(URL.found('meeting-with-graduates')) newsCategory = 15;
if(URL.found('/communities/')) newsCategory = 16;
if(URL.found('/emc-conferences/')) newsCategory = 17;

$.ajaxSetup({ cache: false });

function onWindowScroll(){
  let ifMobile = +document.body.getAttribute('data-mobile');
  let scrollTop = window.pageYOffset || document.documentElement.scrollTop;

  let scroll = _get('.scrollTop');
  let scrollHeight = Math.max(
    document.body.scrollHeight, document.documentElement.scrollHeight,
    document.body.offsetHeight, document.documentElement.offsetHeight,
    document.body.clientHeight, document.documentElement.clientHeight
  );

  let block = $('.main__news')[0];
  let yOffset       = window.pageYOffset;
  let window_height = window.innerHeight;
  let y             = yOffset + window_height;

  if(!block) return;
  
  if(y >= (scrollHeight - window_height * 2) && newDozeOfPosts){
    newDozeOfPosts = 0;
    let project_id = $('#project_id')[0] ? $('#project_id').text() : 0;
    let community_id = $('#community_id')[0] ? $('#community_id').text() : 0;
    
    block.insertAdjacentHTML('afterEnd', '<div id="loader"><div class="lds-ripple"><div></div><div></div></div></div>');
    
    $.ajax({
      type: 'POST',
      url: "/wp-content/themes/nure/php/functions/getNews.php?_=" + new Date().getTime(),
      data: {
        offset: offset,
        mobile: mobile,
        postsNumber: postsNumber,
        category: newsCategory,
        project_id: project_id,
        community_id: community_id,
        isEnglish: window.location.pathname.indexOf('/en/') !== -1 ? 1 : 0
      }
    }).done(function(html){
      $('#loader').remove();
      let div = document.createElement('div');
      div.innerHTML = html;
      newDozeOfPosts = html.trim() == '' ? 0 : 1;

      if(!newDozeOfPosts) return;

      if(ifMobile){
        $('.main__news')[0].insertAdjacentHTML('beforeEnd', html);
      }else{
        let leftBlock = $(div).find('.news__block:nth-child(1)')[0];
        let rightBlock = $(div).find('.news__block:nth-child(2)')[0];
        let leftHTML = leftBlock.innerHTML;
        let rightHTML = rightBlock.innerHTML;
        
        $('.news__block:nth-child(1)')[0].insertAdjacentHTML('beforeEnd', leftHTML);
        $('.news__block:nth-child(2)')[0].insertAdjacentHTML('beforeEnd', rightHTML);
      }

      offset += postsNumber;
    })
  }

  if(ifMobile) return;

  if(scrollTop >= 100 && !window.isMenuCollapsed){
    scroll.style.display = "block";
    _get('.header__tools').style.display = "none";
    _get('.header > .header__inner').style.display = "none";

    let logo = _get('.header__logo');
    logo.parentNode.removeChild(logo);

    let menuInner = _get('.header__menu > .header__inner');
    menuInner.insertBefore(logo, menuInner.firstChild);

    _get('.header__menu').classList.add('header__inRow');
    window.isMenuCollapsed = 1;
  }else if(scrollTop < 100 && window.isMenuCollapsed){
    scroll.style.display = "";
    _get('.header__tools').style.display = "";
    _get('.header > .header__inner').style.display = "";

    let logo = _get('.header__logo');
    logo.parentNode.removeChild(logo);

    let headerInner = _get('.header .header__left');
    headerInner.insertBefore(logo, headerInner.firstChild);

    _get('.header__menu').classList.remove('header__inRow');
    window.isMenuCollapsed = 0;
  }
}

let menuAnimationTime = 0;
let animationInterval;
let blocked = 0;

const handlers = {
  'js-toggleMenu': () => {
    if(menuAnimationTime) return;
    menuAnimationTime = 800;

    let menu = _get('.header__menu');
    let isMenuOpened = menu.style.display == "block";

    if(isMenuOpened) closeMenu();
    else openMenu();
    setAnimation();

    function openMenu(){
      document.body.style.overflow = "hidden";
      menu.style.display = "block";
      menu.style.padding = "";
      setTimeout(() => {
        menu.style.height = "calc(100% - 78px)";
      }, 55);
      menu.scrollTop = 0;
    }

    function closeMenu(){
      document.body.style.overflow = "";
      menu.style.height = "";
      menu.style.padding = "0";
      setTimeout(() => {
        menu.style.display =  "";
      }, menuAnimationTime);
    }

    function setAnimation(){
      setTimeout(() => {
        menuAnimationTime = 0;
      }, menuAnimationTime);
    }
  },
  'js-darkMode': () => {
    let language = document.documentElement.getAttribute('lang') == 'en' ? 'en' : 'ua';
    document.body.classList.toggle('dark');
    let darkMode = document.body.classList.contains('dark');
    let lightTheme = language == 'ua' ? "Світла тема" : "Light theme";
    let darkTheme = language == 'ua' ? "Нічна тема" : "Dark theme";
    _get('.darkMode').innerHTML = darkMode ? lightTheme : darkTheme;
    setCookie('darkMode', darkMode, 30);

    try{
      if(window.chart) switch_theme(darkMode, window.chart);
      if(window.chart2) switch_theme(darkMode, window.chart2);
    }catch(e){};
  },
  'js-scrollTop': () => {
    window.scrollTo(0,0);
  },
  'js-switch-ua': () => {
    let path = window.location.pathname.substr(4);
    window.location.assign('/ua/' + path);
  },
  'js-switch-en': () => {
    let path = window.location.pathname.substr(4);
    window.location.assign('/en/' + path);
  },
  'js-show-statistics': (elem, e) => {
    if(blocked) return;
    blocked = 1;
    
    animationInterval = setTimeout(function(){
      blocked = 0;
    }, 600);

    e.preventDefault();

    let container = _get('.section--statistics');
    let hiddenText = _get('.js-show-statistics span:first-child');
    let activeText = _get('.js-show-statistics span:last-child');

    container.style.overflow = "hidden";

    if (!container.classList.contains('active')) {
      container.classList.add('active');
      container.style.height = 'auto';

      let height = container.clientHeight + 'px';
      container.style.height = '0px';

      setTimeout(function () {
        container.style.height = height;
        container.addEventListener('transitionend', function () {
          container.style.overflow = "visible";
          container.style.height = 'auto';
          setTimeout(function(){
            container.style.height = container.offsetHeight + 'px';
          }, 50);
        }, {
          once: true
        });
      }, 0);
      
      hiddenText.classList.remove('active');
      activeText.classList.add('active');
    }else{
      container.style.height = '0px';
      container.addEventListener('transitionend', function () {
        container.classList.remove('active');

        hiddenText.classList.add('active');
        activeText.classList.remove('active');
      }, {
        once: true
      });
    }
  },
  'js-collapse': function(elem, e){
    if(e.target.tagName == "A") return;

    let $elem = $(elem);
    let $block = $elem.next();
    let speed = 400;
    $block.slideToggle(speed, "linear");
    $elem.toggleClass('title--opened');
    
    setTimeout(function(){
      showVisible();
      let $slider = $block.find('.slider--certificates:not(.slick-initialized)');

      if($slider.length) $slider.slick({
        slidesToShow: 5,
        slidesToScroll: 5,
        dots: true,
        arrows: false,
        lazyLoad: 'progressive',
        adaptiveHeight: true,
        speed: 2000,
        draggable: false,
        responsive: [
          {
            breakpoint: 768,
            settings: {
              slidesToShow: 1,
              slidesToScroll: 1,
              dots: false,
              speed: 400,
              lazyLoad: 'ondemand',
              draggable: true
            }
          },
        ]
      });
    }, 450);
  },
  'js-click-year': function(target){
    document.querySelector('.years-block__item--active').classList.remove('years-block__item--active');
    document.querySelector('.year-block--active').classList.remove('year-block--active');

    const year = target.getAttribute('data-year');

    document.querySelector(`.years-block__item[data-year="${year}"]`).classList.add('years-block__item--active');
    document.querySelector(`.year-block[data-year="${year}"]`).classList.add('year-block--active');
  }
}

function switch_theme(darkMode, chart){
  if(!chart) return;
  
  if(darkMode){
    if(window.Chart) window.Chart.defaults.global.defaultFontColor = '#ffffff';
    let yAxes = chart.options.scales.yAxes;
    let xAxes = chart.options.scales.xAxes;

    for(let key in yAxes){
      yAxes[key].gridLines.zeroLineColor = 'rgba(255,255,255,.25)';
      yAxes[key].gridLines.color = 'rgba(255,255,255,.1)';
    }

    for(let key in xAxes){
      xAxes[key].gridLines.zeroLineColor = 'rgba(255,255,255,.25)';
      xAxes[key].gridLines.color = 'rgba(255,255,255,.1)';
    }
  }else{
    if(window.Chart) window.Chart.defaults.global.defaultFontColor = '#2F536D';
    let yAxes = chart.options.scales.yAxes;
    let xAxes = chart.options.scales.xAxes;

    for(let key in yAxes){
      yAxes[key].gridLines.zeroLineColor = 'rgba(0,0,0,.25)';
      yAxes[key].gridLines.color = 'rgba(0,0,0,.1)';
    }

    for(let key in xAxes){
      xAxes[key].gridLines.zeroLineColor = 'rgba(0,0,0,.25)';
      xAxes[key].gridLines.color = 'rgba(0,0,0,.1)';
    }
  }

  chart.update();
}

/* == DOM Search Methods == */

/**
* Finds elements by selector in box element
*
* @param  {string} selector - CSS selector of elements to be found
* @param  {object HTMLElement} box - element in which search request is proceed
* @returns {object HTMLCollection} - found HTMLCollection
*/
function get(selector, box){
  box = box ? box : document;

  return box.querySelectorAll(selector);
}

/**
* Finds the first element by selector in box element
*
* @param  {string} selector - CSS selector of element to be found
* @param  {object HTMLElement} box - element in which search request is proceed
* @returns {object HTMLElement} - found HTMLElement
*/
function _get(selector, box){
  return get(selector, box)[0];
}

/* == Cookies == */

function getCookie(name) {
  let matches = document.cookie.match(new RegExp(
    "(?:^|; )" + name.replace(/([\.$?*|{}\(\)\[\]\\\/\+^])/g, '\\$1') + "=([^;]*)"
  ));
  return matches ? decodeURIComponent(matches[1]) : undefined;
}

function setCookie(cname, cvalue, exdays) {
  let date = new Date();
  let ms = exdays * 24 * 60 * 60 * 1000;
  date.setTime(date.getTime() + ms);

  let expires = "expires=" + date.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

/* == AJAX == */

let ajax = {};

ajax.post = (URL, method = "GET", data = null, callback) => {
  let dataString = "";
  let request;

  formDataString();
  formRequest();
  processRequest();

  function formDataString(){
    if(data != null)
      for(let key in data){
        let value = data[key];
        dataString += key + "=" + encodeURIComponent(value) + "&";
      }
  }

  function formRequest(){
    if(window.XMLHttpRequest) request = new XMLHttpRequest();   
    else if(window.ActiveXObject){  
      try{request = new ActiveXObject('Msxml2.XMLHTTP')}catch (e){}                 
      try{request = new ActiveXObject('Microsoft.XMLHTTP')}catch (e){}
    }
    if(!request.onload) if(window.XDomainRequest) request = new XDomainRequest();
  }

  function processRequest(){
    if(request){
      setRequestHandlers();
      sendRequest();
    }
  }

  function setRequestHandlers(){
    request.onreadystatechange = requestOnReady;
  }

  function requestOnReady(){
    let requestIsReady = request.readyState == 4;
    let requestIsOk = request.status == 200;

    if(requestIsReady && requestIsOk && callback)
      processResponse();
  }

  function sendRequest(){
    request.open(method, URL);
    request.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    request.send(dataString);
  }

  function processResponse(){
    let response = request.responseText;
    callback(response);
    setTimeout(showVisible, 0);
  }
}

/* == Images Lazy Load == */

let visibleTimer;
let timeout = 100;

function showVisible() {
  clearTimer();
  setTimer();

  function clearTimer(){
    clearTimeout(visibleTimer);
  }

  function setTimer(){
    visibleTimer = setTimeout(searchVisible, timeout);
  }

  function searchVisible(){
    for (let img of get('img')) {
      let src = img.dataset.src;
      if(!src) continue;

      let shiftX = img.getAttribute('data-shift-x');
      if(shiftX) img.style.marginLeft = shiftX + "%";
  
      if(isVisible(img))
        showImage(img, src);
    }
  }

  function showImage(img, src){
    img.src = src;
    img.dataset.src = '';
  
    img.onload = () => removeClasses(img);
  }

  function removeClasses(img){
    if(img.classList.contains("loader"))
      img.classList.remove("loader");
  }

  function isVisible(elem) {
    let coords = elem.getBoundingClientRect();
    let windowHeight = document.documentElement.clientHeight;
    let topVisible = coords.top > 0 && coords.top < windowHeight;
    let bottomVisible = coords.bottom < windowHeight && coords.bottom > 0;
  
    return topVisible || bottomVisible;
  }
}

showVisible();

$(document).ready(() => {
  $(document).on('click', 'a[href^="#"]', function (e) {
    e.preventDefault();

    let $el = $(this);
    let href = $el.attr('href').substr(1);
    $el = $('a[name="' + href + '"]');
    let top = $el.offset().top;
    let scrollTop = top - $('.header')[0].offsetHeight - 30;

    $('html, body').animate({
      scrollTop: scrollTop
    }, 500);
  });

  $('.slider--main').slick({
    infinite: false,
    slidesToShow: 1,
    slidesToScroll: 1,
    dots: true,
    arrows: false,
    lazyLoad: 'progressive',
    autoplay: true,
    autoplaySpeed: 5000,
    adaptiveHeight: true,
    speed: 2000,
    responsive: [
      {
        breakpoint: 481,
        settings: {
          slidesToShow: 2,
        }
      },
    ]
  });

  $('.main .slider:not(.slider--main)').slick({
    infinite: false,
    slidesToShow: 3,
    slidesToScroll: 3,
    dots: true,
    arrows: false,
    lazyLoad: 'progressive',
    autoplay: true,
    autoplaySpeed: 5000,
    speed: 2000,
    responsive: [
      {
        breakpoint: 481,
        settings: {
          slidesToShow: 2,
        }
      },
    ]
  });

  $('.section .slider').slick({
    infinite: false,
    slidesToShow: 3,
    slidesToScroll: 3,
    dots: true,
    arrows: false,
    lazyLoad: 'progressive',
    autoplay: true,
    autoplaySpeed: 5000,
    speed: 2000,
    responsive: [
      {
        breakpoint: 481,
        settings: {
          slidesToShow: 2,
        }
      },
    ]
  });
});