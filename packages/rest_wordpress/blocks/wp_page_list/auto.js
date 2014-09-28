$(function(){
    $('#truncateSummaries').change(function(){
        var disabled = ($(this).prop('checked')) ? false : true;
        $('#ccm-pagelist-truncateChars').prop('disabled', disabled);
    });
});