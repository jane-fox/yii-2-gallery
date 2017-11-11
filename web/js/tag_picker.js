

/*

Parts:
    Clickable toggle buttons
    Display list
    Remove buttons in display list
    Add custom tag (eventually want autocomplete)
    Invisible textarea with actual values (space seperated list)


autocomplete?: http://fatiherikli.github.io/backbone-autocomplete/

    model: selected


    on model add/change/remove


    //create view for hidden input, listen for update events

    //create a view for buttons

    //create model/collection for selected tags



 */


/*


Simpler:

One tag click

if uncheck, remove text from area

if check, add text to text area




 */


var tag_picker = Backbone.View.extend({

    el: "#tag-picker",

    field: "textarea[name='tags']",

    events: {
        "change  .tag > input": "update_tags"
    },

    initialize: function() {
        //console.log("Tag Picker Init");
    },
	
	//Update tag value based on checked boxes
    update_tags: function(event) {

        var self = this;
        var input = event.currentTarget;

        var selected = input.checked;
        var tag = $(input).attr("data-tag");

        if (selected) {
            self.add_tag(tag);
        } else {
            self.remove_tag(tag);
        }

    },

    //Get all selected tags
    get_checked: function() {

        var checked = this.$('input:checked');
        var list = [];

        checked.each(function(check) {
            list.push( $(checked[check]).attr("data-tag"));
        });

        return list;

    },


    //Takes an array of tags and sets them to field value
    set_tags: function(tags) {

        var self = this;

        $(self.field).val(tags.join(" "));

    },


    //Gets all entered tags as an array
    get_all: function() {

        var self = this;

        //Get all tags and split them into an array
        var current_tags = $(self.field).val();
        current_tags = current_tags.toLowerCase();
        current_tags = current_tags.split(" ");

        return current_tags;
    },


    add_tag: function(tag) {

        var self = this;
        var current_tags = self.get_all();
        tag = tag.toLowerCase();

        //Don't add if tag is already there
        if (current_tags.indexOf(tag) == -1) {

            current_tags.push(tag);

            self.set_tags(current_tags);

        }

    },


    remove_tag: function(tag) {

        var self = this;
        var current_tags = self.get_all();
        tag = tag.toLowerCase();

        var index = current_tags.indexOf(tag);

        //Only remove if it's there
        if (index > -1) {

            current_tags.splice(index, 1);

            self.set_tags(current_tags);

        }

    }

});

//Wait until ready
$(function() {

    //Run app.
    new tag_picker();

});