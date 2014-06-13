var Movie = Backbone.Model.extend({

   defaults: {
       			FirstName :'',
     			 LastName :'',
     			 Mobile :'+91---',
     			 Username: '',
     			 Qualification:'', 
     			 Skills :'',
     			 Company :'',
     			 Role :0
   }

});
//$.ajaxPrefilter( function( options, originalOptions, jqXHR ) {
 //       options.url = 'http://203.193.173.121:4000'+ options.url;
       
 //   	});
var Movies = Backbone.Collection.extend({
   url:'/superuser/bbgetall',
   model: Movie,
   initialize: function() {
        this.fetch();
        Backbone.Pagination.enable(this);
    },
   sortAttribute: "FirstName",
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
      "click th": "headerClick",
      "keydown input":"autoSerch",
      "onload td":"defaultRows",
   },
   collection:Movies,
   initialize: function() {
	
      this.template = _.template( $('#movie-table').html() );
      this.listenTo(this.collection, "sort", this.updateTable);
     
       console.log(this.collection['models'].FirstName);
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
   autoSerch:function( e ){
   	var fname=[],lname=[],mob=[],uname=[],qul=[],skills=[],comp=[],role=[];
   	var collection = this.collection.models
            			for(i=0;i<collection.length;i++)
            			{            				
            				fname[i] = collection[i].attributes['FirstName'];
            				lname[i] = collection[i].attributes['LastName'];
            				mob[i] = collection[i].attributes['Mobile'];
            				uname[i] = collection[i].attributes['username'];
            				qul[i] = collection[i].attributes['Qualification'];
            				skills[i] = collection[i].attributes['Skills'];
            				comp[i] = collection[i].attributes['Company'];
            				role[i] = collection[i].attributes['Role'];	
            			}
   				$("#fname").autocomplete({		   
					source: fname
		   		 });
		   		 $("#lname").autocomplete({		   
					source: lname
		   		 });
		   		 $("#mob").autocomplete({		   
					source: mob
		   		 });
		   		 $("#uname").autocomplete({		   
					source: uname
		   		 });
		   		 $("#qul").autocomplete({		   
					source: qul
		   		 });
		   		 $("#skills").autocomplete({		   
					source: skills
		   		 });
		   		 $("#comp").autocomplete({		   
					source: comp
		   		 });
		   		 $("#role").autocomplete({		   
					source: role
		   		 });
		   		 $('.filter').multifilter();
   },
   defaultRows:function(){
	var ti = this.collection.models.length; 
	var ipp = 3;
	for(i=ipp;i<ti;i++)
	{
		$('tbody tr:eq('+ i +')').hide();
	}
	  
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



   var movieList = new Movies();

   var movieView = new MovieTable({ collection: movieList });

   $('.wrapper').html( movieView.render().$el.attr('id', 'movies').attr('class','table table-bordered  table-striped') );
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
         
          function ConvertToCSV(objArray) {
            var array = typeof objArray != 'object' ? JSON.parse(objArray) : objArray;
            //var str = '';
            
var str= new Array();
var j=0;
            for (var i = 0; i < array.length; i++) {
           // str=array[i];
            for (var index in array[i]) {
           
            str[j] = array[i][index];
            j++;
            }
                /*var line = '';
                for (var index in array[i]) {
                    if (line != '') line += ','

                    line += array[i][index];
                    
                }

                str += line + '\r\n';
                str[]=array[i];*/
                
            }
//alert(str)
            return str;
        }

