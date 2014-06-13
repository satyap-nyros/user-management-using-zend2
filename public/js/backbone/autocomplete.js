$.ajaxPrefilter( function( options, originalOptions, jqXHR ) {
        options.url = 'http://203.193.173.121:4000'+ options.url;
       
    	});
    	
var MyCollection = Backbone.Collection.extend({
	
	url:'/superuser/bbgetall',
	
	
});
var MyView = Backbone.View.extend({
render:function(){alert('asdf');
            	var myCollection = new MyCollection();
            	var that = this;
            	myCollection.fetch({
            		success:function()
            		{    
            		console.log(myCollection.models)  
            			//var template = _.template($('#user_list_template').html(),{users:users.models});
            			//that.$el.html(template);
            		}
            	});
            	
            }
  /*initialize: function () {
    this.myCollection = new MyCollection();
    console.log(JSON.stringify(this.myCollection));
    this.collectionFetched = false;
  },
  events: {
    'focus #names': 'getAutocomplete'
    'keydown #names': 'fetchCollection'
  },
  fetchCollection: function() {
    if (this.collectionFetched) return;
    this.myCollection.fetch();
    this.collectionFetched = true;
  },
  getAutocomplete: function () {
    $("#names").autocomplete({
        source: JSON.stringify(this.myCollection)
    });
  }*/
});


var view = new MyView();
