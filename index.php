<!DOCTYPE HTML>
<html lang="ru">
<head>
    <!-- META -->
    <title>SkyNet Test</title>
    <meta charset="utf-8">
    <meta name="author" content="Дмитрий Жиляев">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- CSS -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/my.css">
</head>
<body>
<br>
<header class="container">
	<h1>SkyNet Тарифы</h1>
	<hr>
	<?php
	$file = "api/data.json";
	if(!file_exists($file)){
		$json = file_get_contents('http://sknt.ru/job/frontend/data.json');
		file_put_contents($file, $json);
	}
	?>
</header>
<section class="container" id="app" >
		<router-view class="justify-content-end d-flex"></router-view>
</section>
<hr>
<footer class="container">
    <div class="row justify-content-center">
        <i class="material-icons" style="font-size: 100px;">code</i>
        <div>
            <br>
            <h5>© 2017. All rights reserved</h5>
            <iframe align="center" class="justify-content-center" src="https://ghbtns.com/github-btn.html?user=zhilyaev&type=follow&size=large" frameborder="0" scrolling="0" width="177px" height="30px"></iframe>
        </div>
    </div>
</footer>
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<!--
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js"></script>
-->
<script src="https://unpkg.com/vue"></script>
<script src="https://unpkg.com/vue-router/dist/vue-router.js"></script>
<script>
	/* smth like func main */
    let app; // Для управления из консоли
    $.getJSON( "api/data.json", function( json ) {
	    let plans = json.tarifs;
        let htmlMain = "<div class=\"row\" >"; // !undefined
        /* Building html */
        for(let i=0;i<plans.length;i++){
			//console.log(plans[i])
	        if (plans[i].free_options===undefined){
                plans[i].free_options='Просто интернет'
	        }else{
                plans[i].free_options = plans[i].free_options.join("</li><li>")
	        }
	        htmlMain+="<div class=\"card col col-12 col-sm-12 col-md-6 col-lg-4\">" +
                "<h4 class=\"card-header\">Тариф \""+plans[i].title+"\" <span class=\"badge badge-success\">"+plans[i].speed+" Мбит/с</span></h4>" +
                "<div class=\"card-body\">" +
                "<b>350 - 480 Р/мес</b>\n" +
                "<i class=\"material-icons float-right\">play_arrow</i>" +
                "<ul><li>"+plans[i].free_options+"</li></ul>" +
                "<hr>" +
                "<a href='"+plans[i].link+"'>Узнать подробнее на сайте</a>" +
                "</div>" +
                "</div>";


        }
        htmlMain+="</div>";
        //console.log(htmlMain)
	    //document.getElementById("app").innerHTML=htmlMain;

        const index = {
            template : htmlMain
        };

        const router = new VueRouter({
            routes: [
	            { path:"/index", component:index}
            ]
        });

	    app = new Vue({
            router: router,
	        data: {
                plans
            }
        }).$mount('#app')

    });
</script>
</body>
</html>