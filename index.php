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
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"><!-- Мои любимые иконки -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/my.css">
</head>
<body>
<br>
<header class="container">
	<h1><a href="#/"><i class="material-icons">settings_input_antenna</i> SkyNet: Тарифы</a></h1>
	<hr>
	<?php
	/* Да это действительно единственный скрипт ((( */
	$file = "api/data.json";
	if(!file_exists($file)){
		$json = file_get_contents('http://sknt.ru/job/frontend/data.json');
		$custom = json_decode($json);
		// Change the directory to take a min, mix in 1 step
		$tmp = $custom->tarifs[0]->tarifs[0];
		$custom->tarifs[0]->tarifs[0] = $custom->tarifs[0]->tarifs[1];
		$custom->tarifs[0]->tarifs[1] = $tmp;
		unset($tmp);
		// Custom  property : {moneyRange , discount}
		foreach ($custom->tarifs as $plan){
			$max = $plan->tarifs[0]->price;
			$l = sizeof($plan->tarifs)-1;
			$min = $plan->tarifs[$l]->price / $plan->tarifs[$l]->pay_period;
			$plan->moneyRange = $min." - ".$max;
			unset($min,$l);
			foreach ($plan->tarifs as $v){
				$v->discount = ($max - ($v->price / $v->pay_period)) * $v->pay_period;
			}
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
            <h5>© 2017. I am ok, thanks!</h5>
            <iframe align="center" class="justify-content-center" src="https://ghbtns.com/github-btn.html?user=zhilyaev&type=follow&size=large" frameborder="0" scrolling="0" width="177px" height="30px"></iframe>
        </div>
    </div>
</footer>
<!-- следует убрать так как используетья только функция getJSON() -->
<script src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
<!-- Ротуинг -->
<script src="https://unpkg.com/vue/dist/vue.min.js"></script>
<script src="https://unpkg.com/vue-router/dist/vue-router.min.js"></script>
<!-- TEMPLATE STEP 1 -->
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
<!-- TEMPLATE STEP 2 -->
<script type="text/x-template" id="step2">
	<section>
		<h2 class="text-center">
			<button onclick="history.back();" class="btn float-left btn-outline-secondary">
				<i class="material-icons">keyboard_arrow_left</i>
			</button>
			{{plans[$route.params.id].title}}
		</h2>
		<div class="row justify-content-center d-flex">
			<div v-for="(plan, index) in plans[$route.params.id].tarifs" class="col col-12 col-sm-12 col-md-6 col-lg-4">
				<div class="card">
					<h4 class="card-header">{{plan.title}}</h4>
					<ul class="list-group list-group-flush">
						<li class="list-group-item"><b>-{{ plan.price / plan.pay_period }} ₽/мес</b></li>
						<li class="list-group-item">Разовый платеж — <b> {{ plan.price }} ₽</b></li>
						<li v-if="plan.discount>0" class="list-group-item">Скидка —  <b> {{ plan.discount }} ₽</b></li>
					</ul>
					<a :href="'#/'+$route.params.id+'/'+index" class="btn btn-outline-primary">
						<i class="material-icons">play_arrow</i>
					</a>
				</div>
			</div>
		</div>
	</section>
</script>
<!-- TEMPLATE STEP 3 -->
<script type="text/x-template" id="step3">
	<section>
		<h2 class="text-center">
			<button onclick="history.back();" class="btn float-left btn-outline-secondary">
				<i class="material-icons">keyboard_arrow_left</i>
			</button>
			Выбор тарифа
		</h2>
		<div class="row justify-content-center d-flex">
			<div class="col col-12 col-sm-12 col-md-6 col-lg-4">
				<div class="card">
					<h4 class="card-header">{{plan.title}}</h4>
					<ul class="list-group list-group-flush">
						<li class="list-group-item">Период оплаты — <b>{{plan.pay_period}}</b> мес.</li>
						<li class="list-group-item">В среднем — <b>{{ plan.price / plan.pay_period }} ₽/мес</b></li>
						<li class="list-group-item">Разовый платеж — <b> {{ plan.price }} ₽</b></li>
						<li class="list-group-item">Со счета спишется — <b> {{ plan.price }} ₽</b></li>
						<li class="list-group-item">Вступит в силу — <b> сегодня </b></li>
						<li class="list-group-item">Активно до — <b>{{new Date(new Date().setMonth(+(plan.pay_period)+today.getMonth())).ddmmyyyy() }}</b></li>
						<li v-if="plan.discount>0" class="list-group-item">Скидка — <b> {{ plan.discount }} ₽</b></li>
					</ul>
					<a href="#" class="btn btn-outline-primary">
						<i class="material-icons">shopping_cart</i>
					</a>
				</div>
			</div>
		</div>
	</section>
</script>
<script>
    Date.prototype.ddmmyyyy = function (literal) {
        literal = (literal===undefined) ? '.' : literal;
        let mm = this.getMonth() + 1; // getMonth() is zero-based
        let dd = this.getDate();

        return [
            (dd>9 ? '' : '0') + dd,
            (mm>9 ? '' : '0') + mm,
            this.getFullYear()
        ].join(literal);
    };

	/* smth like func main */
    $.getJSON( "api/data.json", function( json ) {
	    let plans = json.tarifs;
        const index = {
            template : "#step1",
            props: ['plans']
        };

        const step2 = {
            template : "#step2",
            props: ['plans']
        };

        const step3 = {
            template : "#step3",
            props: ['plan','today']
        };

        const vueRouter = new VueRouter({
            routes: [
                { path:"/", component:index, props:{plans:plans} },
                { path:"/:id", component:step2, props: {plans:plans}  },
                {
                    path:"/:id/:i",
	                component:step3,
	                props: (route) => ({ plan: plans[route.params.id].tarifs[route.params.i], today: new Date() })
                }
            ]
        });

	    const app = new Vue({
		    el:"#app",
            router: vueRouter
        })

    });
</script>
</body>
</html>