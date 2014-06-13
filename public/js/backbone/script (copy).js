$.ajaxPrefilter( function( options, originalOptions, jqXHR ) {
        options.url = 'http://203.193.173.121:4000'+ options.url;
       
    	});
    	var Users = Backbone.Collection.extend({
    		
    		url:'/superuser/bbgetall',
    		
    		
    	})
    	var UserList = Backbone.View.extend({
            
            el:'.page',
           /* comparator: function (property) {
        return selectedStrategy.apply(myModel.get(property));
    },
    strategies: {
        FirstName: function (users) { return users.get("FirstName"); }
        
    },
    changeSort: function (sortProperty) {
        this.comparator = this.strategies[sortProperty];
    },
    initialize: function () {
       
        this.changeSort("FirstName");
        console.log(this.comparator);        
    },     */
    /*collection: Users,
     	initialize: function () {
     	//this.listenTo(this.collection, 'reset', this.render);
     	var users = new Users();
     
     	 users.fetch({
  success : function(collection, response) {
 	
  },

  error : function(collection, response) {
    // code here
  }
 
}); 
     	
     	
        
    	}, */
    	               
            render:function(){
            	var users = new Users();
            	var that = this;
            users.fetch({
            		success:function()
            		{      
            			var template = _.template($('#user_list_template').html(),{users:users.models});
            			that.$el.html(template);
            			data = ConvertToCSV(JSON.stringify(users.models));	  					//console.log((users.models[1].attributes["FirstName"]));
            			//console.log((users.models[0].attributes).length);
            			var collection = users.models;
            			
            			var fname=[],lname=[],mob=[],uname=[],qul=[],skills=[],comp=[],role=[];
            			for(i=0;i<collection.length;i++)
            			{            				
            				fname[i] = users.models[i].attributes['FirstName'];
            				lname[i] = users.models[i].attributes['LastName'];
            				mob[i] = users.models[i].attributes['Mobile'];
            				uname[i] = users.models[i].attributes['username'];
            				qul[i] = users.models[i].attributes['Qualification'];
            				skills[i] = users.models[i].attributes['Skills'];
            				comp[i] = users.models[i].attributes['Company'];
            				role[i] = users.models[i].attributes['Role'];
            				
            				
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
				
            		}
            	});
            	
            	
            },
            
          
          
            
         });
         var EditView = Backbone.View.extend({
            
            el:'.page',
            render:function(){
            	this.$el.html('yeah done');
            }           
         });
    	var Router = Backbone.Router.extend({
            routes: {
            '':'user',
            'new':'edituser'
            }
         });
         var UserList = new UserList();
         var EditView = new EditView();
         var router = new Router();
         router.on('route:user',function(){
        	UserList.render();
         });
         router.on('route:edituser',function(){
         
        	EditView.render();
         })
         Backbone.history.start();
         
         
         
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

