var modulesPile = {};
var barModules = 0;
var increaseMod = 0;
var categoriesPile = {};
var barCategories = 0;
var increaseCat = 0;
var cleanCategoriesPile = {};
var cleanProductsPile = {};

var featuresPile = {};
var increaseFeature = {};
var barFeature = {};

var attributesPile = {};
var increaseAttribute = 0;
var barAttribute = 0;

var productsPile = {};
var increaseProducts = 0;
var barProducts = 0;

var accessoriesPile = {};
var increaseAccessories = 0;
var barAccessories = 0;

var callModulesStep = function() {
	if (modulesPile.length > 0) {
		$(".installing-proccess .modules").removeClass('hide-step');
		var step = modulesPile.shift();
		$(".installing-proccess .modules .current-module").html(step.name);
		$.ajax({
			url: step.url,
			type: "POST",
			success: function(responseModule) {
				barModules = barModules + increaseMod;
				if (barModules > 100) {barModules = 100;}
				$(".installing-proccess .modules .progress-bar").css("width",barModules+"%");
				$(".installing-proccess .modules .progress-bar").html(Math.ceil(barModules)+"%");
			},
			error: function() {
				barModules = barModules + increaseMod;
				if (barModules > 100) {barModules = 100;}
				$(".installing-proccess .modules .progress-bar").css("width",barModules+"%");
				$(".installing-proccess .modules .progress-bar").html(Math.ceil(barModules)+"%");
			}
		}).then(function() {
			setTimeout(function(){ callModulesStep(); }, 50);
			
		});
	} else {
		$(".installing-proccess .modules .current-module").html("Done");
		callCleanCategoriesStep();
	}
}
var callCleanCategoriesStep = function() {
	if (cleanCategoriesPile.length) {
		var step = cleanCategoriesPile.shift();
		$.ajax({
			url: step.url,
			type: "POST",
			success: function(responseModule) {
				console.log("Cleaning categories");
			},
			error: function() {
				console.log("Error cleaning categories");
			}
		}).then(function() {
			setTimeout(function(){ callCleanCategoriesStep(); }, 50);
		});
	} else {
		callCategoriesStep();
	}
}
var callCategoriesStep = function() {
	if (categoriesPile.length) {
		$(".installing-proccess .categories").removeClass('hide-step');
		var step = categoriesPile.shift();
		$(".installing-proccess .categories .current-category").html(step.sname);
		$.ajax({
			url: step.url,
			type: "POST",
			data: {data:step},
			success: function(responseModule) {
				barCategories = barCategories + increaseCat;
				if (barCategories > 100) {barCategories = 100;}
				$(".installing-proccess .categories .progress-bar").css("width",barCategories+"%");
				$(".installing-proccess .categories .progress-bar").html(Math.ceil(barCategories)+"%");
			},
			error: function() {
				barCategories = barCategories + increaseCat;
				if (barCategories > 100) {barCategories = 100;}
				$(".installing-proccess .categories .progress-bar").css("width",barCategories+"%");
				$(".installing-proccess .categories .progress-bar").html(Math.ceil(barCategories)+"%");
			}
		}).then(function() {
			setTimeout(function(){ callCategoriesStep(); }, 50);
		});
	} else {
		$(".installing-proccess .categories .current-category").html("Done");
		callCleanProductsStep();
	}
}
var callCleanCategoriesStep = function() {
	if (cleanCategoriesPile.length) {
		var step = cleanCategoriesPile.shift();
		$.ajax({
			url: step.url,
			type: "POST",
			success: function(responseModule) {
				console.log("Cleaning categories");
			},
			error: function() {
				console.log("Error cleaning categories");
			}
		}).then(function() {
			setTimeout(function(){ callCleanCategoriesStep(); }, 50);
		});
	} else {
		callCategoriesStep();
	}
}
var callCleanProductsStep = function() {
	if (cleanProductsPile.length) {
		var step = cleanProductsPile.shift();
		$.ajax({
			url: step.url,
			type: "POST",
			success: function(responseModule) {
				console.log("Cleaning categories");
			},
			error: function() {
				console.log("Error cleaning categories");
			}
		}).then(function() {
			setTimeout(function(){ callCleanProductsStep(); }, 50);
		});
	} else {
		callFeaturesStep();
	}
}
var callFeaturesStep = function() {
	if (featuresPile.length) {
		$(".installing-proccess .features").removeClass('hide-step');
		var step = featuresPile.shift();
		$(".installing-proccess .features .current-feature").html(step.sname);
		$.ajax({
			url: step.url,
			type: "POST",
			data: {data:step},
			success: function(responseModule) {
				barFeature = barFeature + increaseFeature;
				if (barFeature > 100) {barFeature = 100;}
				$(".installing-proccess .features .progress-bar").css("width",barFeature+"%");
				$(".installing-proccess .features .progress-bar").html(Math.ceil(barFeature)+"%");
			},
			error: function() {
				barFeature = barFeature + increaseFeature;
				if (barFeature > 100) {barFeature = 100;}
				$(".installing-proccess .features .progress-bar").css("width",barFeature+"%");
				$(".installing-proccess .features .progress-bar").html(Math.ceil(barFeature)+"%");
			}
		}).then(function() {
			setTimeout(function(){ callFeaturesStep(); }, 50);
		});
	} else {
		$(".installing-proccess .features .current-feature").html("Done");
		callAttributeStep();
	}
}
var callAttributeStep = function() {
	if (attributesPile.length) {
		$(".installing-proccess .attributes").removeClass('hide-step');
		var step = attributesPile.shift();
		$(".installing-proccess .attributes .current-attribute").html(step.sname);
		$.ajax({
			url: step.url,
			type: "POST",
			data: {data:step},
			success: function(responseModule) {
				barAttribute = barAttribute + increaseAttribute;
				if (barAttribute > 100) {barAttribute = 100;}
				$(".installing-proccess .attributes .progress-bar").css("width",barAttribute+"%");
				$(".installing-proccess .attributes .progress-bar").html(Math.ceil(barAttribute)+"%");
			},
			error: function() {
				barAttribute = barAttribute + increaseAttribute;
				if (barAttribute > 100) {barAttribute = 100;}
				$(".installing-proccess .attributes .progress-bar").css("width",barAttribute+"%");
				$(".installing-proccess .attributes .progress-bar").html(Math.ceil(barAttribute)+"%");
			}
		}).then(function() {
			setTimeout(function(){ callAttributeStep(); }, 50);
		});
	} else {
		$(".installing-proccess .attributes .current-attribute").html("Done");
		callProductStep();
	}
}
var callProductStep = function() {
	if (productsPile.length) {
		$(".installing-proccess .products").removeClass('hide-step');
		var step = productsPile.shift();
		$(".installing-proccess .products .current-product").html(step.sname);
		$.ajax({
			url: step.url,
			type: "POST",
			data: {data:step},
			success: function(responseModule) {
				barProducts = barProducts + increaseProducts;
				if (barProducts > 100) {barProducts = 100;}
				$(".installing-proccess .products .progress-bar").css("width",barProducts+"%");
				$(".installing-proccess .products .progress-bar").html(Math.ceil(barProducts)+"%");
			},
			error: function() {
				barProducts = barProducts + increaseProducts;
				if (barProducts > 100) {barProducts = 100;}
				$(".installing-proccess .products .progress-bar").css("width",barProducts+"%");
				$(".installing-proccess .products .progress-bar").html(Math.ceil(barProducts)+"%");
			}
		}).then(function() {
			setTimeout(function(){ callProductStep(); }, 50);
		});
	} else {
		$(".installing-proccess .products .current-product").html("Done");
		callAccesoriesStep();
	}
}
var callAccesoriesStep = function() {
	if (accessoriesPile.length) {
		$(".installing-proccess .accessories").removeClass('hide-step');
		var step = accessoriesPile.shift();
		$(".installing-proccess .accessories .current-accessory").html(step.sname);
		$.ajax({
			url: step.url,
			type: "POST",
			data: {data:step},
			success: function(responseModule) {
				barAccessories = barAccessories + increaseAccessories;
				if (barAccessories > 100) {barAccessories = 100;}
				$(".installing-proccess .accessories .progress-bar").css("width",barAccessories+"%");
				$(".installing-proccess .accessories .progress-bar").html(Math.ceil(barAccessories)+"%");
			},
			error: function() {
				barAccessories = barAccessories + increaseAccessories;
				if (barAccessories > 100) {barAccessories = 100;}
				$(".installing-proccess .accessories .progress-bar").css("width",barAccessories+"%");
				$(".installing-proccess .accessories .progress-bar").html(Math.ceil(barAccessories)+"%");
			}
		}).then(function() {
			setTimeout(function(){ callProductStep(); }, 50);
		});
	} else {
		$("#install_form_submit_btn").show();
		$(".loader").hide();
		$(".installing-proccess .modules").addClass('hide-step');
		$(".installing-proccess .categories").addClass('hide-step');
		$(".installing-proccess .features").addClass('hide-step');
		$(".installing-proccess .attributes").addClass('hide-step');
		$(".installing-proccess .products").addClass('hide-step');
		$(".installing-proccess .accessories").addClass('hide-step');
	}
}
$(document).ready(function() {
	$('form.eter_demos').submit(function(e) {
		$('.eterloader').show();
	});

	$("#install_form_submit_btn").click(function() {
		var url = $(this).data('url');
		var theme = $('#package').val();
		$("#install_form_submit_btn").hide();
		$(".loader").show();
		if(confirm('Do you want to continue? this action will delete your current information in modules, products and categories')) {
			$.ajax({
				url: url,
				data: {installTheme:theme},
				success: function(response) {
					//pila de modulos
					$(".installing-proccess .modules").addClass('hide-step');
					$(".installing-proccess .modules .progress-bar").css("width","0%");
					modulesPile = response.modules.steps;
					increaseMod = response.modules.increase;
					barModules = 0;
					//pila de categorias
					cleanCategoriesPile = response.cleancategories.steps;
					cleanProductsPile = response.cleanproducts.steps;
					//pila de categorias
					$(".installing-proccess .categories").addClass('hide-step');
					$(".installing-proccess .categories .progress-bar").css("width","0%");
					categoriesPile = response.categories.steps;
					increaseCat = response.categories.increase;
					barCategories = 0;
					//pila de caracteristicas
					$(".installing-proccess .features").addClass('hide-step');
					$(".installing-proccess .features .progress-bar").css("width","0%");
					featuresPile = response.features.steps;
					increaseFeature = response.features.increase;
					barFeature = 0;
					//pila de caracteristicas
					$(".installing-proccess .attributes").addClass('hide-step');
					$(".installing-proccess .attributes .progress-bar").css("width","0%");
					attributesPile = response.attributes.steps;
					increaseAttribute = response.attributes.increase;
					barAttribute = 0;
					//pila de caracteristicas
					$(".installing-proccess .products").addClass('hide-step');
					$(".installing-proccess .products .progress-bar").css("width","0%");
					productsPile = response.products.steps;
					increaseProducts = response.products.increase;
					barProducts = 0;
					//pila de caracteristicas
					$(".installing-proccess .accessories").addClass('hide-step');
					$(".installing-proccess .accessories .progress-bar").css("width","0%");
					accessoriesPile = response.accessories.steps;
					increaseAccessories = response.accessories.increase;
					barAccessories = 0;
					callModulesStep();
					//callCleanCategoriesStep();
					//callCleanProductsStep();
				}
			});
		}
	})
});

