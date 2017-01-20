<?php
/**
 * @package     Arastta eCommerce
 * @copyright   2015-2017 Arastta Association. All rights reserved.
 * @copyright   See CREDITS.txt for credits and other copyright notices.
 * @license     GNU GPL version 3; see LICENSE.txt
 * @link        https://arastta.org
 */

class EventAnalyticsWoopra extends Event
{

    public function preLoadHeader(&$data)
    {
        // Hacked by Cüneyt
        // TO DO: call header controller only 1 time
        static $added;
        if (!empty($added)) {
            return;
        }

        if (!$this->config->get('woopra_analytics_status')) {
            return;
        }

        $domain = $this->url->getDomain();
        $domain = str_replace('http://', '', $domain);
        $domain = str_replace('https://', '', $domain);

        $js = '
<!-- Start of Woopra Code -->
<script>
(function(){
        var t,i,e,n=window,o=document,a=arguments,s="script",r=["config","track","identify","visit","push","call","trackForm","trackClick"],c=function(){var t,i=this;for(i._e=[],t=0;r.length>t;t++)(function(t){i[t]=function(){return i._e.push([t].concat(Array.prototype.slice.call(arguments,0))),i}})(r[t])};for(n._w=n._w||{},t=0;a.length>t;t++)n._w[a[t]]=n[a[t]]=n[a[t]]||new c;i=o.createElement(s),i.async=1,i.src="//static.woopra.com/js/w.js",e=o.getElementsByTagName(s)[0],e.parentNode.insertBefore(i,e)
})("woopra");

woopra.config({
    domain: "'.$domain.'"
});';

        if ($this->config->get('woopra_analytics_customer') && $this->customer->isLogged()) {
            $name = $this->customer->getFirstName() . ' ' . $this->customer->getLastName();
            $email = $this->customer->getEmail();

            $js .= '
woopra.identify({
        email: "'.$name.'",
        name: "'.$email.'"
});';
        }

$js .= '
woopra.track();
</script>
<!-- End of Woopra Code -->';

        $this->document->addScriptDeclaration($js, 'text/javascript', false);

        $added = true;
    }
}
