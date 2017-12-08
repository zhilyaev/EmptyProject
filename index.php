<!DOCTYPE HTML>
<html lang="ru">
<head>
    <!-- META -->
    <title>SkyNet Test</title>
    <meta charset="utf-8">
    <meta name="author" content="Дмитрий Жиляев">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<link rel="icon" href="favicon/favicon.ico">
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
		$custom = json_decode($json);
		// Custom moneyRange property
		foreach ($custom->tarifs as $plan){
			$min = 5000; // Ну вы звери будете если выше поднимете (цены)
			$max = 0;
			// JSON файл не однородный (Земля
			// поэтому придется искать
			foreach ($plan->tarifs as $v){
				$range = $v->price / $v->pay_period;
				$max = ($range > $max) ? $range : $max;
				$min = ($range < $min) ? $range : $min;
			}
			$plan->moneyRange = $min." - ".$max;
		}
		file_put_contents($file, json_encode($custom));
	}
	?>
</header>
<section class="container" id="app" >
	<router-view></router-view>
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
<script src="https://unpkg.com/vue"></script>
<script src="https://unpkg.com/vue-router/dist/vue-router.js"></script>
<!-- STEP 1 -->
<script type="text/x-template" id="step1">
	<div class="row justify-content-center d-flex">
		<div v-for="(plan, index) in plans" style="width: 100% !important;" class="align-content-stretch d-flex col col-12 col-sm-12 col-md-6 col-lg-4">
			<div class="card">
				<h4 class="card-header">Тариф "{{plan.title}}"
					<span class="badge badge-success">{{plan.speed}} Мбит/с</span>
				</h4>
				<div class="card-body">
					<b>{{ plan.moneyRange }} ₽/мес</b>
					<ul>
						<li v-for="option in plan.free_options">{{option}}</li>
					</ul>
				</div>
				<a :href="'#/'+index" class="btn btn-outline-primary btn-block">
					<i class="material-icons">play_arrow</i>
				</a>
				<div class="card-footer text-center">
					<a :href="plan.link">
						<i style="font-size: 15px;" class="material-icons">help</i>
						Узнать подробнее на сайте
					</a>
				</div>
			</div>
		</div>
	</div>
</script>
<!-- STEP 2 -->
<script type="text/x-template" id="step2">
	<section>
		<h2 class="text-center">{{plans[$route.params.id].title}}</h2>
		<div class="row justify-content-center d-flex">
			<div v-for="(plan, index) in plans[$route.params.id].tarifs" class="col col-12 col-sm-12 col-md-6 col-lg-4">
				<div class="card">
					<h4 class="card-header">{{plan.title}}</h4>
					<ul class="list-group list-group-flush">
						<li class="list-group-item"><b>-{{ plan.price / plan.pay_period }} ₽/мес</b></li>
						<li class="list-group-item">Разовый платеж — <b> {{ plan.price }} ₽</b></li>
						<li class="list-group-item">Скидка —  <b> {{ plan.price }} ₽</b></li>
					</ul>
					<a :href="'#/'+$route.params.id+'/'+index" class="btn btn-outline-primary">
						<i class="material-icons">play_arrow</i>
					</a>
				</div>
			</div>
		</div>
	</section>
</script>
<!-- STEP 3 -->
<script type="text/x-template" id="step3">
	<h1>Шаг3</h1>
</script>
<script>
	/* smth like func main */
    let app; // Для управления из консоли
    $.getJSON( "api/data.json", function( json ) {
	    let plans = json.tarifs;
        const index = {
            template : "#step1",
            props: ['plans']
        };

        const tarif = {
            template : "#step2",
            props: ['plans']
        };

        const pay = {
            template : "#step3"
        };

        const vueRouter = new VueRouter({
            routes: [
                { path:"/", component:index, props:{plans:plans} },
                { path:"/:id", component:tarif, props: {plans:plans}  },
                { path:"/:id/:i", component:pay }
            ]
        });

	    app = new Vue({
		    el:"#app",
            router: vueRouter
        })

    });
</script>
</body>
</html>