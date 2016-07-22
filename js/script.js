//Namespace
var app = app || {};

//************** Model *****************
app.extension = Backbone.Model.extend({
	defaults: {
		name : "unknown",
		status : "not-registered"
	},
	initialize: function() {
		console.log("Extension " + this.get("name"));		
	}
});

//************** Collection *****************
app.ExtensionCollection = Backbone.Collection.extend({
	model: app.extension,
	url: "pa.php",
    initialize: function() {
    	//console.log("Collection initialized");
    }
}); 

//***************** View **********************
app.singleExtensionView = Backbone.View.extend({
	
	tagName: "article",
	className: "extensionItemList",
	template: _.template($("#extensionElement").html()),
	render: function() {
		var extensionTemplate = this.template(this.model.toJSON());
		this.$el.html(extensionTemplate);
		return this;
	}	
});


app.allExtensionsView = Backbone.View.extend({
	tagName: "section",
	render: function() {
		this.collection.each(this.addExtension, this);
		return this;
	},
	addExtension: function(extension) {
		var extensionView = new app.singleExtensionView({model: extension});
		this.$el.append(extensionView.render().el);
	},
	initialize: function() {
		this.listenTo(this.collection, 'add', _.once(this.render));
	}
});
//***************** Main Application **********************

var extensionGroup = new app.ExtensionCollection();	
extensionGroup.fetch();

var extensionGroupView = new app.allExtensionsView({collection: extensionGroup});
$("#allExtensions").html(extensionGroupView.render().el);
