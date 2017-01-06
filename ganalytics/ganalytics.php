<?php
if( !defined( "INPROCESS" ) ){
 header("HTTP/1.0 403 Forbidden");
 die();
}

class ganalytics extends plugin {

    static function head(){
		$global = new DB_global;
		$check = new DB_check;
		$analyticscode1 = $global->sqlquery("SELECT * from ddp_ganalytics");
		$analyticscode2 = $analyticscode1->fetch_assoc();
		if (!$check->isLoggedIn()){
        echo $analyticscode2['analytics_code'];
        return true;
		}
    }

    static function amp_bottom(){
				$check = new DB_check;
                $global = new DB_global;
                $analyticscode1 = $global->sqlquery("SELECT * from ddp_ganalytics");
                $analyticscode2 = $analyticscode1->fetch_assoc();
preg_match("/(['])UA-.*?(['])/", $analyticscode2['analytics_code'], $analyticsnumber);
$analyticsnumber1 = str_replace("'", '', $analyticsnumber);
		if (!$check->isLoggedIn()){
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
}
?>
