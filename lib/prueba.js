$('#multiple-datasets').typeahead({
    source: function (query, process) {
    states = [];
    map = {};
 
    var data = [
        {"stateCode": "CA", "stateName": "California"},
        {"stateCode": "AZ", "stateName": "Arizona"},
        {"stateCode": "NY", "stateName": "New York"},
        {"stateCode": "NV", "stateName": "Nevada"},
        {"stateCode": "OH", "stateName": "Ohio"}
    ];
 
    $.each(data, function (i, state) {
        map[state.stateName] = state;
        states.push(state.stateName);
    });
 
    process(states);
},
    updater: function (item) {
        selectedState = map[item].stateCode;
        return item;
    },
    matcher: function (item) {
        if (item.toLowerCase().indexOf(this.query.trim().toLowerCase()) != -1) {
            return true;
        }
    },
    sorter: function (items) {
        return items.sort();
    },
    highlighter: function (item) {
        var regex = new RegExp( '(' + this.query + ')', 'gi' );
        return item.replace( regex, "<strong>$1</strong>" );
    }
});