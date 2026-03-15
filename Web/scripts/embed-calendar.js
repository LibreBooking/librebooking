(function (global) {
  function bookedLoadCalendar(div, baseUrl, queryString) {
    div.innerHTML = 'Loading calendar...';
    var xhttp = new XMLHttpRequest();

    var reloadCalendar = function (event) {
      if (event) {
        event.preventDefault();
      }
      bookedLoadCalendar(div, baseUrl, queryString);
    };

    xhttp.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        div.innerHTML = xhttp.responseText;
        var reloadButton = document.getElementById('booked-calendar-reload');
        if (reloadButton) {
          reloadButton.removeEventListener('click', reloadCalendar);
          reloadButton.addEventListener('click', reloadCalendar);
        }
      }
    };
    xhttp.open('GET', baseUrl + 'export/embedded.php' + queryString, true);
    xhttp.send();

    var thirtyMinutes = 1800000;
    setTimeout(reloadCalendar, thirtyMinutes);
  }

  var scriptTags = document.getElementsByTagName('script');
  var styleTags = document.getElementsByTagName('style');
  var processedScripts = [];

  for (var i = 0; i < scriptTags.length; i++) {
    var scriptTag = scriptTags[i];
    if (scriptTag.src != '' && processedScripts.indexOf(scriptTag) < 0) {
      processedScripts.push(scriptTag);

      var baseUrl = scriptTag.src;
      var queryString = '?';
      var indexOfQueryString = scriptTag.src.indexOf('?');
      if (indexOfQueryString !== -1) {
        queryString = baseUrl.substring(indexOfQueryString, baseUrl.length);
        baseUrl = baseUrl.substring(0, indexOfQueryString);
      }
      baseUrl = baseUrl.replace('/scripts/embed-calendar.js', '/');

      if (styleTags.length === 0) {
        var cssFile = baseUrl + 'css/embed.css';
        var styleTag = document.createElement('link');
        styleTag.rel = 'stylesheet';
        styleTag.type = 'text/css';
        styleTag.href = cssFile;
        styleTag.media = 'all';
        document.getElementsByTagName('head')[0].appendChild(styleTag);
      }

      var div = document.createElement('div');
      div.id = 'booked-calendar-widget';
      div.className = '';
      bookedLoadCalendar(div, baseUrl, queryString);

      scriptTag.parentNode.insertBefore(div, scriptTag);
    }
  }
})(this);
