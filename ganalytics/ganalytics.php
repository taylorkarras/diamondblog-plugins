<?php
if( !defined( "INPROCESS" ) ){
 header("HTTP/1.0 403 Forbidden");
 die();
}

class ganalytics extends plugin {

    static function head(){
		$global = new DB_global;
		$analyticscode1 = $global->sqlquery("SELECT * from ddp_ganalytics");
		$analyticscode2 = $analyticscode1->fetch_assoc();
        echo $analyticscode2['analytics_code'];
        return true;
    }
	
    static function amp_bottom(){
                $global = new DB_global;
                $analyticscode1 = $global->sqlquery("SELECT * from ddp_ganalytics");
                $analyticscode2 = $analyticscode1->fetch_assoc();
preg_match("/(['])UA-.*?(['])/", $analyticscode2['analytics_code'], $analyticsnumber);
$analyticsnumber1 = str_replace("'", '', $analyticsnumber);
echo '  <amp-analytics type="googleanalytics">
    <script type="application/json">
      {
        "vars": {
          "account": "'.$analyticsnumber1[0].'"
        },
        "triggers": {
          "default pageview": {
            "on": "visible",
            "request": "pageview",
            "vars": {
              "title": "{{title}}"
            }
          }
        }
      }
    </script>
  </amp-analytics>';
}
}
?>
