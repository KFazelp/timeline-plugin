var filterFlag = new Map;

function applyFilter (str) {
    document.getElementById('filtersInput').value = str;
    jQuery(function($){
        var filter = $('#filterForm');
        $.ajax({
            url:filter.attr('action'),
            data:filter.serialize(), // form data
            type:filter.attr('method'), // POST
            beforeSend:function(xhr){
                $('#timeline-results').css('visibility', 'hidden');
                $('#spinner').show();
            },
            success:function(data){
                $('#timeline-results').html(data); // insert data
                $('#spinner').hide();
                $('#timeline-results').css('visibility', 'visible');
            }
        });
        return false;
    });
}

const selectFilter = (filterId) => {
    if (filterFlag.get(filterId))
        filterFlag.set(filterId, false);
    else
        filterFlag.set(filterId, true);

    var filtersStr = '';
    filterFlag.forEach((value, id) => {
        if (value) {
            document.getElementById(`filter-chip-${id}`).style.background = '#eeeeee';
            document.getElementById(`filter-chip-${id}`).style.color = '#393939';
            if (filtersStr === '')
                filtersStr += `${id}`;
            else
                filtersStr += `,${id}`;
        } else {
            document.getElementById(`filter-chip-${id}`).style.background = '#393939';
            document.getElementById(`filter-chip-${id}`).style.color = '#eeeeee';
        }
    })
    
    applyFilter(filtersStr);
}

const removeFilters = () => {
    filterFlag.forEach((value, id) => {
        filterFlag.set(id, false);
        document.getElementById(`filter-chip-${id}`).style.background = '#393939';
        document.getElementById(`filter-chip-${id}`).style.color = '#eeeeee';
    });
    
    applyFilter('');
}

let btn = document.getElementById('filterBoxBtn');
const openFilterBox = () => {
    let filterBox = document.getElementById('filterBox');
    if (filterBox.style.maxHeight == '1000px') {
        filterBox.style.maxHeight = '0';
        filterBox.style.transition = 'max-height 0.45s ease-out';
    } else {
        filterBox.style.maxHeight = '1000px';
        filterBox.style.transition = 'max-height 0.55s ease-in';
    }
}