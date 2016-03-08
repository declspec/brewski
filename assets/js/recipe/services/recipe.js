angular.module('recipe').service('RecipeService', [ '$q', '$httpq',
    function($q, $httpq) {
        var defaultRecipe = {
            ingredients: [ { quantity: "", description: "" } ],
            steps: [ { content: "" } ]
        };
        
        this.getNewRecipe = function() {
            return angular.copy(defaultRecipe, {});  
        };
        
        this.find = function(recipeId) {
            return $q.when({
                name: 'James Squire 150 Lashes replica',
                description: 'JS 150 lashes all grain, bloody excellent after a fortnight bubbling in the fermenter, a week to settle and a month in the keg. Carbonates well, great, persistent head which slides all the way down the glass, same colour as the commercial brew, but more aroma and ' 
                   + ' less fruit salad, a bit more lime? flavour in the finish, maybe a tad bittering  hoppier finish with the Pride of Ringwood than the real deal. Great with a big hunk of smelly, crumbly matured cheddar cheese and a slab of quince jam.',
                notes: 'I would have added just under a kg of wheat grain malt to the mash. Tasted a bottle of that brew again the other day, would probably decrease the aroma hops by a tad as well as the bittering hops, not mellowing as well as I had hoped.',
                
                ingredients: [
                    { quantity: "4.5kg", description: "Barrett Burston Pale malt" },
                    { quantity: "500g", description: "Munich malt" }, 
                    { quantity: "500g", description: "Carapils" },
                    { quantity: "500g", description: "Dry wheat malt extract (Wheat malt grain I had was stale)" },
                    { quantity: "20g", description: "Pride of Ringwood" },
                    { quantity: "10g", description: "Cascade" },
                    { quantity: "10g", description: "Amarillo Gold" },
                    { quantity: "10g", description: "Nelson Sauvin" },
                    { quantity: "5g", description: "Williamette" }
                ],
                
                steps: [
                    { content: "Crack grains medium coarse, put in a clean cotton pillow slip" },
                    { content: "Heat in 18  litres of water to 63 C over 10 min" },
                    { content: "steep and stir the grains 60 min" },
                    { content: "heat to 78 C over 10 min, stir the grains again" },
                    { content: "Drain mash tun into boiler, sparge twice with 78 C water twice to achieve total boil volume of 30 litres" },
                    { content: "Bring the liquid to the boil" },
                    { content: "Add 20g POR hops at start" },
                    { content: "After 40 minutes, add Cascade hops" },
                    { content: "10 minutes later, add both the Amarillo Gold and Nelson Sauvin hops" },
                    { content: "Cool wort to ferment temp. of 24 C, final volume 22 litres" },
                    { content: "Add Williamette hops steeped and leave in fermenter" },
                    { content: "Add SO-5 and M10 Workhorse yeasts" },
                    { content: "Ferment for 14 days at 18-22 C" },
                    { content: "Leave to settle for a week " }
                ]
            });
        };
        
        this.save = function(recipe) {
            var target = recipe.id 
                ? '/api/recipe/' + encodeURIComponent(recipe.id)
                : '/api/recipe';
                
            return $httpq.post(target, recipe).then(function(res) {
                return res.success
                    ? res.data
                    : $q.reject(res.errors);
            });
        };
    }
]);