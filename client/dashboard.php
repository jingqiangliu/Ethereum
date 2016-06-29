<!DOCTYPE html>
<html ng-app="app">
<head>
	<meta charset="utf-8">
	<title>GPU Info</title>
	<link rel="stylesheet" type="text/css" href="css/reset.css">
	<link rel="stylesheet" type="text/css" href="css/design.css">
</head>
<body>
<script src="js/angular.min.js"></script>
<script type="text/javascript">
	var app = angular.module('app', []);
</script>
		<?php
		// C'est crade mais fonctionnel
		$json_file = file_get_contents('config.json');
		$f = json_decode($json_file);

		$dataH = $f->master;
		$s = 0;
		foreach ($dataH as $d) {
			?>
		<header class="header" ng-controller="ctrl_master<?= $s ?>">
			<div class="container" ng-repeat="i in infos">
				<div class="header__serv">
					<h1 class="header__servName"></h1>
					<div class="header__servIp">{{ i.Ip }}</div>
				</div>
				<div class="header__stat">
					<h2 class="header__statName">Gain</h2>
					<div>
						<div>
							<span class="header__statNumber">{{ i.Balance /1000000000000000000 }}</span>
							<span class="header__statUnit"> eth</span>
						</div>
						<div>
							<span class="header__statNumber">{{ i.Euro }}</span>
							<span class="header__statUnit"> &euro;</span>
						</div>
					</div>
				</div>
				<div class="header__stat">
					<h2 class="header__statName">Hash</h2>
					<div>
						<span class="header__statNumber">{{ (i.Hash / 1000000).toFixed(2) }}</span>
						<span class="header__statUnit">Mh/s</span>
					</div>
				</div>
			</div>
			<script type="text/javascript">
			app.controller('ctrl_master<?= $s ?>', function($scope, $http) {
			var master = function gpuInfos() {
			$http.get("http://<?= $d->ip ?>") .then(function (response) {
				$scope.infos = response.data.data;
			});}
			setInterval(master, 1000);
		});
		</script>
		</header>
		
		<?php
			$s++;
		}
		?>


	<main>
		<div class="container">
		<?php

		$data = $f->worker;

		$i = 0;

		foreach ($data as $d) {
			?>
			<section class="serv" ng-controller="gpu<?= $i ?>">
			<header class="serv__head">
				<div>
					<span class="serv__status serv__status--up">
						&#9679;
					</span>
					<h3 class="serv__name" id="serv_name">{{ infos.Name }}</h3>
				</div>
				<div class="serv__ip" id="serv_ip">{{ infos.Ip }}</div>
			</header>
			<article class="serv__gpu" ng-repeat="g in infos.gpu">
				<div class="serv__grid-3">
					<div class="serv__mod">
						<div class="serv__modLabel serv__modLabel--red">
							Load
						</div>
						<div class="serv__modNumber" id="serv_load">
							 {{ g.Load }}
							<label class="serv__modUnit">%</label>
						</div>
					</div>
					<div class="serv__mod">
						<div class="serv__modLabel serv__modLabel--green">
							Heat
						</div>
						<div class="serv__modNumber" id="serv_heat">
							 {{ g.Heat }}
							<label class="serv__modUnit">&deg;C</label>
						</div>
					</div>
					<div class="serv__mod">
						<div class="serv__modLabel serv__modLabel--yellow">
							Fan
						</div>
						<div class="serv__modNumber" id="serv_fan">
							 {{ g.FanSpeed }}
							<label class="serv__modUnit">%</label>
						</div>
					</div>
					<div class="serv__mod">
						<div class="serv__modLabel serv__modLabel--red">
							Clock
							<div class="serv__modSublabel">Max</div>
						</div>
						<div class="serv__modNumber">
							{{ (g.CurrentClock / g.MaxClock * 100).toFixed(2) }}
							<label class="serv__modUnit">%</label>
							<div class="serv__modSubnumbler">
								{{ g.MaxClock }}mHz
							</div>
						</div>
					</div>
					<div class="serv__mod">
						<div class="serv__modLabel serv__modLabel--red">
							Mem
							<div class="serv__modSublabel">Max</div>
						</div>
						<div class="serv__modNumber">
							{{ (g.CurrentMem / g.MaxMem * 100).toFixed(2) }}
							<span id="serv_mem"></span>
							<label class="serv__modUnit">%</label>
							<div class="serv__modSubnumbler">
								<span id="serv_maxmem"></span>{{ g.MaxMem }}mHz
							</div>
						</div>
					</div>
				</div>
			</article>
		
		<script type="text/javascript">

			app.controller("gpu<?= $i ?>", function($scope, $http) {
				var gpu = function gpuInfos() {
				$http.get("http://<?= $d->ip ?>").then(function (response) {
					$scope.infos = response.data.data;
				});}
				setInterval(gpu, 1000);
			});

		</script>
		</section>
		<?php
			$i++;
		}
		?>
		</div>
	</main>
</body>
</html>