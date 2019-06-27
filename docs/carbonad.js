;(function(window) {
    window.Carbon = {
      scriptEl: null,
      create: function() {
        return function(hook, vm) {
          hook.ready(function() {
            window.Carbon.injectCarbonStyle();
          })

          hook.doneEach(function () {
            window.Carbon.injectCarbonScript();
          })
        }
      },

      injectCarbonScript() {
        if (window.Carbon.scriptEl) {
          window.Carbon.scriptEl.remove()
        }

        let nav = document.getElementsByClassName('sidebar-nav')

        var script = document.createElement('script')
        script.src = "//cdn.carbonads.com/carbon.js?serve=CK7ICKQI&placement=realrashidgithubio"
        script.id = '_carbonads_js'
        script.async = "async"
        nav[0].insertBefore(script, nav[0].firstChild)
        window.Carbon.scriptEl = script
      },

      injectCarbonStyle() {
        var carbonCss = document.createElement('style');
        // window.Carbon.injectCarbonContainer();
        var css = '#carbonads{font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto,Oxygen-Sans, Ubuntu, Cantarell, "Helvetica Neue", Helvetica, Arial,sans-serif}#carbonads{display: flex;max-width: 330px;background-color: hsl(0, 0%, 98%);box-shadow: 0 1px 4px 1px hsla(0, 0%, 0%, 0.1)}#carbonads a{color: inherit;text-decoration: none}#carbonads a:hover{color: inherit}#carbonads span{position: relative;display: block;overflow: hidden}#carbonads .carbon-wrap{display: flex}.carbon-img{display: block;margin: 0;line-height: 1}.carbon-img img{display: block}.carbon-text{font-size: 13px;padding: 10px;line-height: 1.5;text-align: left}.carbon-poweredby{display: block;padding: 8px 10px;background: repeating-linear-gradient(-45deg,transparent,transparent 5px,hsla(0, 0%, 0%, 0.025) 5px,hsla(0, 0%, 0%, 0.025) 10px)hsla(203, 11%, 95%, 0.4);text-align: center;text-transform: uppercase;letter-spacing: 0.5px;font-weight: 600;font-size: 9px;line-height: 1}';

        carbonCss.type = 'text/css';
        if (carbonCss.styleSheet){
          carbonCss.styleSheet.cssText = css;
        } else {
          carbonCss.appendChild(document.createTextNode(css));
        }
        document.head.appendChild(carbonCss);
      }
    }
  })(window)
