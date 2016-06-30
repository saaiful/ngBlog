// App Start
var app = angular.module("ngBlog", ['ngRoute', 'gist', 'gitcard', 'ui.bootstrap', 'ngDisqus']);
// App Route
app.config(function($disqusProvider) {
	$disqusProvider.setShortname('saifulszone');
});
app.config(['$routeProvider',
	function($routeProvider) {
		$routeProvider.when('/', {
			templateUrl: function(attr) {
				return 'home.html';
			}
		}).when('/:tpl', {
			templateUrl: function(attr) {
				return 'single.html';
			}
		}).when('/404', {
			templateUrl: "404.html"
		});
	}
]);
// Filter Start
app.directive('dynamic', function($compile) {
	return {
		restrict: 'A',
		replace: true,
		link: function(scope, ele, attrs) {
			scope.$watch(attrs.dynamic, function(html) {
				ele.html(html);
				$compile(ele.contents())(scope);
			});
		}
	};
});
app.filter('to_trusted', ['$sce', function($sce) {
	return function(text) {
		return $sce.trustAsHtml(text);
	};
}]);
// Filter End
// MainCtrl
app.controller("MainCtrl", function($scope, $http, $location, $compile) {
	$scope.error_404 = "404.html";
	$scope.title = "Blog";
	$scope.menu = {};
	$scope.language = {
		"list": {
			"bn": "English",
			"en": "বাংলা",
		},
		"selected": "bn"
	};
	$scope.getClass = function(path) {
		return ("#" + $location.path() == path) ? 'active' : '';
	}
	$scope.titleMod = function(title) {
		$scope.title = title;
	};
	$scope.getMenu = function() {
		$.getJSON("admin/menu.json", function(data) {
			$('.navbar-nav').html("");
			document.html = '';
			$.each(data, function(index, value) {
				var len = Object.keys(value).length;
				if (len == 3) {
					document.html += '<li ng-class="getClass(\'' + value.link + '\')"><a href="' + value.link + '">' + value.name + '</a></li>';
				}
				if (len > 3) {
					document.html += '<li class="dropdown">';
					document.html += '<a href="javascript:{};" data-target="#" class="dropdown-toggle" data-toggle="dropdown">Apps';
					document.html += '<b class="caret"></b></a>';
					document.html += '<ul class="dropdown-menu">';
					$.each(value.children, function(a, b) {
						document.html += '<li ng-class="getClass(\'' + b.link + '\')"><a href="' + b.link + '">' + b.name + '</a></li>';
					});
					document.html += '</ul>';
					document.html += '</li>';
				}
			});
			var html = $compile(document.html)($scope);
			$('.navbar-nav').html(html);
		});
	};
	$scope.getMenu();
});
// HomeCtrl
app.controller("HomeCtrl", function($scope, $http, $routeParams, $filter, $sce, $compile) {
	$scope.slug = $routeParams.tpl;
	$scope.datas = {};
	$scope.totalItems = 1;
	$scope.itemsPerPage = 10;
	$scope.currentPage = 1;
	$scope.totalPages = 0;
	$scope.pageChanged = function() {};
	$scope.getData = function() {
		$http({
			method: 'GET',
			url: "api/?page=" + $scope.currentPage
		}).then(function successCallback(response) {
			$scope.datas = response.data.data;
			$scope.currentPage = response.data.currentPage;
			$scope.totalItems = response.data.totalItems;
			$scope.totalPages = response.data.totalPages;
		}, function errorCallback(response) {
			cosole.log(response);
		});
	};
	$scope.getData();
});
// PageCtrl
app.controller("PageCtrl", function($scope, $http, $routeParams, $filter, $sce) {
	$scope.slug = $routeParams.tpl;
	$scope.notFound = false;
	$scope.data = {};
	$scope.getData = function() {
		$http({
			cache: true,
			method: 'GET',
			url: "api/display/" + $scope.slug
		}).then(function successCallback(response) {
			$scope.data = response.data;
			$scope.titleMod($scope.data.title);
			$scope.notFound = false;
		}, function errorCallback(response) {
			$scope.notFound = true;
			$scope.titleMod('404');
		});
	};
	$scope.getData();
});
// script js start
function loadGits(name) {
	$.getJSON("api/github/" + name).success(function(data) {
		var list = $('#github');
		$(data).each(function() {
			list.append('<div class="list-group-item"><a href="https://github.com/' + this.full_name + '/archive/master.zip"><div class="row-action-primary"><i class="glyphicon glyphicon-download"></i></div></a><div class="row-content"><div class="least-content">' + this.forks_count + ' Forks</div><h4 class="list-group-item-heading"><a href="' + (this.homepage ? this.homepage : this.html_url) + '">' + this.name + '</a></h4><p class="list-group-item-text">' + this.description + '</p></div></div><div class="list-group-separator"></div>');
		});
	});
}
$(document).ready(function() {
	$.material.init();
	$(".navbar-nav li a").click(function(event) {
		if (!$(this).parent().hasClass("dropdown")) {
			$(".navbar-collapse").collapse('hide');
		}
	});
});
// script js start