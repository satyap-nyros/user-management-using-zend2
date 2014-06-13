var Movie = Backbone.Model.extend({

   defaults: {
      title: '',
      gross: 0,
      rank: -1,
      year: 1900,
      studio: ''
   }

});

var Movies = Backbone.Collection.extend({

   model: Movie,

   sortAttribute: "rank",
   sortDirection: 1,

   sortMovies: function (attr) {
      this.sortAttribute = attr;
      this.sort();
   },

   comparator: function(a, b) {
      var a = a.get(this.sortAttribute),
          b = b.get(this.sortAttribute);

      if (a == b) return 0;

      if (this.sortDirection == 1) {
         return a > b ? 1 : -1;
      } else {
         return a < b ? 1 : -1;
      }
   }

});

var MovieTable = Backbone.View.extend({

   _movieRowViews: [],

   tagName: 'table',
   template: null,

   sortUpIcon: 'ui-icon-triangle-1-n',
   sortDnIcon: 'ui-icon-triangle-1-s',

   events: {
      "click th":                   "headerClick"
   },

   initialize: function() {

      this.template = _.template( $('#movie-table').html() );
      this.listenTo(this.collection, "sort", this.updateTable);
   },

   render: function() {

      this.$el.html(this.template());

      // Setup the sort indicators
      this.$('th div')
             .append($('<span>'))
             .closest('thead')
             .find('span')
               .addClass('ui-icon icon-none')
               .end()
             .find('[column="'+this.collection.sortAttribute+'"] span')
               .removeClass('icon-none').addClass(this.sortUpIcon);

      this.updateTable();

      return this;
   },

   headerClick: function( e ) {
      var $el = $(e.currentTarget),
          ns = $el.attr('column'),
          cs = this.collection.sortAttribute;

      if (ns == cs) {
         this.collection.sortDirection *= -1;
      } else {
         this.collection.sortDirection = 1;
      }

      $el.closest('thead').find('span').attr('class', 'ui-icon icon-none');

      if (this.collection.sortDirection == 1) {
         $el.find('span').removeClass('icon-none').addClass(this.sortUpIcon);
      } else {
         $el.find('span').removeClass('icon-none').addClass(this.sortDnIcon);
      }

      this.collection.sortMovies(ns);
   },

   updateTable: function () {

      var ref = this.collection,
          $table;

      _.invoke(this._movieRowViews, 'remove');

      $table = this.$('tbody');

      this._movieRowViews = this.collection.map(
            function ( obj ) {
                  var v = new MovieRow({  model: ref.get(obj) });

                  $table.append(v.render().$el);

                  return v;
              });
   }

});

var MovieRow = Backbone.View.extend({

   tagName: 'tr',

   template: null,

   initialize: function() {
      this.template = _.template( $('#movie-row').html() );
   },

   render: function() {

      this.$el.html( this.template( this.model.toJSON()) );

      return this;
   }

});




   var movieList = new Movies(movieData);

   var movieView = new MovieTable({ collection: movieList });

   $('.wrapper').html( movieView.render().$el.attr('id', 'movies') );

