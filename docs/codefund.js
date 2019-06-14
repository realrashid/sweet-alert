;(function(window) {
  window.Codefund = {
    scriptEl: null,
    create: function(codefundId) {
      return function(hook, vm) {
        hook.ready(function() {
          window.Codefund.injectCodeFundStyle();
        })

        hook.doneEach(function () {
          window.Codefund.injectCodeFundScript(codefundId);
          window.Codefund.injectCodeFundContainer();
        })
      }
    },

    injectCodeFundContainer() {
      let nav = document.getElementsByClassName('sidebar-nav')
      let codefundContainer = document.createElement('div')
      codefundContainer.id = 'codefund'
      nav[0].insertBefore(codefundContainer, nav[0].firstChild)

      if (document.getElementsByClassName('cf-wrapper').length === 0) {
        window._codefund && window._codefund.serve();
      }
    },

    injectCodeFundScript(codefundId) {
      if (window.Codefund.scriptEl) {
        window.Codefund.scriptEl.remove()
      }

      var script = document.createElement('script')
      script.src = "https://codefund.app/properties/" + codefundId + "/funder.js"
      script.async = "async"
      document.body.appendChild(script)
      window.Codefund.scriptEl = script
    },

    injectCodeFundStyle() {
      var style = document.createElement('style');
      window.Codefund.injectCodeFundContainer();
      var css = '#cf span.cf-wrapper { background-color: transparent!important } #cf a.cf-text { color: #444!important; font-weight: 400!important; font-size: 10px!important }';

      style.type = 'text/css';
      if (style.styleSheet){
        style.styleSheet.cssText = css;
      } else {
        style.appendChild(document.createTextNode(css));
      }
      document.head.appendChild(style);
    }
  }
})(window)
